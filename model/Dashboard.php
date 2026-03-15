<?php
// model/Dashboard.php

class Dashboard {

    private $pdo;

    public function __construct() {
        try {
            $this->pdo = Database::StartUp();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════
    // 1. KPI — Cobros de hoy  (fuente: pagos_historial)
    // ══════════════════════════════════════════════════════════

    public function getCobrosHoy() {
        $sql = "
            SELECT
                COUNT(*)                         AS cantidad,
                COALESCE(SUM(monto_entregado), 0) AS total
            FROM pagos_historial
            WHERE fecha_pago = CURDATE()
        ";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_OBJ);
    }

    // ══════════════════════════════════════════════════════════
    // 2. KPI — Clientes con al menos una venta activa
    // ══════════════════════════════════════════════════════════

    public function getTotalClientesActivos() {
        $sql = "
            SELECT COUNT(DISTINCT id_cliente) AS total
            FROM ventas
            WHERE estado = 1
        ";
        return (int) $this->pdo->query($sql)->fetchColumn();
    }

    // ══════════════════════════════════════════════════════════
    // 3. KPI — Cuotas pendientes ESTE MES
    //    Definición: fecha_vencimiento entre HOY y fin de mes,
    //    saldo > 0, estado activo (1).
    //    Ejemplo: hoy 15/03, cuota vence 25/03 → pendiente.
    // ══════════════════════════════════════════════════════════

    public function getCuotasPendientesMes() {
        $sql = "
            SELECT COUNT(*) AS total
            FROM cuotas
            WHERE estado = 1
              AND (monto - monto_pagado) > 0
              AND fecha_vencimiento >= CURDATE()
              AND fecha_vencimiento <= LAST_DAY(CURDATE())
        ";
        return (int) $this->pdo->query($sql)->fetchColumn();
    }

    // ══════════════════════════════════════════════════════════
    // 4. KPI — Cuotas en mora
    //    Definición: fecha_vencimiento < HOY, saldo > 0, activa.
    //    Usa interes_mora de ventas para calcular el monto total.
    // ══════════════════════════════════════════════════════════

    public function getCuotasEnMora() {
        $sql = "
            SELECT
                COUNT(*)                                    AS cantidad,
                COALESCE(SUM(cu.monto - cu.monto_pagado), 0) AS saldo_total,
                COALESCE(SUM(
                    (cu.monto - cu.monto_pagado)
                    * (vnt.interes_mora / 100)
                    * CEIL(DATEDIFF(CURDATE(), cu.fecha_vencimiento) / 30)
                ), 0) AS mora_total
            FROM cuotas cu
            INNER JOIN ventas vnt ON cu.id_venta = vnt.id_venta
            WHERE cu.estado = 1
              AND (cu.monto - cu.monto_pagado) > 0
              AND cu.fecha_vencimiento < CURDATE()
        ";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_OBJ);
    }

    // ══════════════════════════════════════════════════════════
    // 5. Resumen de cartera completa
    // ══════════════════════════════════════════════════════════

    public function getResumenCartera() {
        $sql = "
            SELECT
                COUNT(*)                                                                          AS total_cuotas,
                SUM(CASE WHEN (monto - monto_pagado) <= 0          THEN 1 ELSE 0 END)            AS pagadas,
                SUM(CASE WHEN (monto - monto_pagado) > 0
                          AND fecha_vencimiento >= CURDATE()
                          AND fecha_vencimiento <= LAST_DAY(CURDATE()) THEN 1 ELSE 0 END)        AS pendientes_mes,
                SUM(CASE WHEN (monto - monto_pagado) > 0
                          AND fecha_vencimiento > LAST_DAY(CURDATE()) THEN 1 ELSE 0 END)         AS futuras,
                SUM(CASE WHEN (monto - monto_pagado) > 0
                          AND fecha_vencimiento < CURDATE()          THEN 1 ELSE 0 END)          AS atrasadas,
                COALESCE(SUM(monto), 0)                                                          AS monto_total,
                COALESCE(SUM(monto_pagado), 0)                                                   AS monto_cobrado,
                COALESCE(SUM(CASE WHEN (monto - monto_pagado) > 0
                                  AND fecha_vencimiento < CURDATE()
                                  THEN (monto - monto_pagado) ELSE 0 END), 0)                    AS monto_mora
            FROM cuotas
            WHERE estado = 1
        ";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_OBJ);
    }

    // ══════════════════════════════════════════════════════════
    // 6. Últimos cobros registrados (para tabla del dashboard)
    // ══════════════════════════════════════════════════════════

    public function getUltimosCobros(int $limite = 10) {
        $sql = "
            SELECT
                ph.id_pago,
                ph.monto_entregado,
                ph.fecha_pago,
                ph.metodo_pago,
                cu.numero_cuota,
                cu.fecha_vencimiento,
                cl.id_cliente,
                cl.nombre          AS cliente_nombre,
                m.nombre           AS modelo_nombre,
                vnt.id_venta,
                (SELECT COUNT(*) FROM cuotas WHERE id_venta = vnt.id_venta) AS total_cuotas
            FROM pagos_historial ph
            INNER JOIN cuotas    cu  ON ph.id_cuota    = cu.id_cuota
            INNER JOIN ventas    vnt ON cu.id_venta    = vnt.id_venta
            INNER JOIN clientes  cl  ON vnt.id_cliente = cl.id_cliente
            INNER JOIN vehiculos v   ON vnt.id_vehiculo = v.id_vehiculo
            INNER JOIN modelos   m   ON v.id_modelo    = m.id_modelo
            ORDER BY ph.fecha_pago DESC, ph.id_pago DESC
            LIMIT :limite
        ";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    // ══════════════════════════════════════════════════════════
    // 7. Cuotas urgentes — vencidas o que vencen hoy,
    //    agrupadas por venta. Ordenadas por días de atraso DESC.
    // ══════════════════════════════════════════════════════════

    public function getCuotasUrgentes(int $limite = 8) {
        $sql = "
            SELECT
                cl.id_cliente,
                cl.nombre                              AS cliente_nombre,
                cl.telefono,
                m.nombre                               AS modelo_nombre,
                vnt.id_venta,
                MIN(cu.fecha_vencimiento)              AS fecha_vencimiento,
                SUM(cu.monto - cu.monto_pagado)        AS saldo_pendiente,
                DATEDIFF(CURDATE(), MIN(cu.fecha_vencimiento)) AS dias_atraso
            FROM cuotas cu
            INNER JOIN ventas    vnt ON cu.id_venta    = vnt.id_venta
            INNER JOIN clientes  cl  ON vnt.id_cliente = cl.id_cliente
            INNER JOIN vehiculos v   ON vnt.id_vehiculo = v.id_vehiculo
            INNER JOIN modelos   m   ON v.id_modelo    = m.id_modelo
            WHERE cu.estado = 1
              AND (cu.monto - cu.monto_pagado) > 0
              AND cu.fecha_vencimiento <= CURDATE()
            GROUP BY vnt.id_venta
            ORDER BY dias_atraso DESC
            LIMIT :limite
        ";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    // ══════════════════════════════════════════════════════════
    // 8. Cobros por mes — últimos 6 meses (para Chart.js)
    // ══════════════════════════════════════════════════════════

    public function getCobrosPorMes() {
        $sql = "
            SELECT
                DATE_FORMAT(fecha_pago, '%Y-%m')  AS mes,
                DATE_FORMAT(fecha_pago, '%b %Y')  AS mes_label,
                COUNT(*)                           AS cantidad,
                COALESCE(SUM(monto_entregado), 0) AS total
            FROM pagos_historial
            WHERE fecha_pago >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(fecha_pago, '%Y-%m')
            ORDER BY mes ASC
        ";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_OBJ);
    }

    // ══════════════════════════════════════════════════════════
    // 9. Últimos clientes registrados
    // ══════════════════════════════════════════════════════════

    public function getUltimosClientes(int $limite = 5) {
        $sql = "
            SELECT
                cl.id_cliente,
                cl.nombre,
                cl.telefono,
                COUNT(v.id_venta) AS total_ventas
            FROM clientes cl
            LEFT JOIN ventas v ON v.id_cliente = cl.id_cliente
            GROUP BY cl.id_cliente
            ORDER BY cl.id_cliente DESC
            LIMIT :limite
        ";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
    }
}