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

    // ============================================================
    // SECCIÓN 1 — KPIs principales (tarjetas superiores)
    // ============================================================

    /**
     * Cobros registrados HOY (suma de pagos del día).
     * Se ajustó para consultar la tabla 'pagos_historial'.
     */
    public function getCobrosHoy() {
        $sql = "
            SELECT 
                COUNT(*) AS cantidad,
                COALESCE(SUM(monto_entregado), 0) AS total
            FROM pagos_historial
            WHERE DATE(fecha_pago) = CURDATE()
        ";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Total de clientes activos (con al menos una venta activa).
     */
    public function getTotalClientesActivos() {
        $sql = "
            SELECT COUNT(DISTINCT cl.id_cliente) AS total
            FROM clientes cl
            INNER JOIN ventas v ON v.id_cliente = cl.id_cliente
            WHERE v.estado = 1
        ";
        return (int) $this->pdo->query($sql)->fetchColumn();
    }

    /**
     * Cuotas que vencen HOY y aún no están saldadas.
     */
    public function getCuotasVencenHoy() {
        $sql = "
            SELECT COUNT(*) AS total
            FROM cuotas
            WHERE DATE(fecha_vencimiento) = CURDATE()
              AND estado = 1
              AND (monto - monto_pagado) > 0
        ";
        return (int) $this->pdo->query($sql)->fetchColumn();
    }

    /**
     * Cuotas vencidas (fecha pasada, saldo pendiente).
     */
    public function getCuotasVencidas() {
        $sql = "
            SELECT 
                COUNT(*) AS cantidad,
                COALESCE(SUM(monto - monto_pagado), 0) AS mora_total
            FROM cuotas
            WHERE fecha_vencimiento < CURDATE()
              AND estado = 1
              AND (monto - monto_pagado) > 0
        ";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Ingresos del mes actual vs mes anterior.
     * En tu DB, los ingresos reales están en 'pagos_historial'.
     */
    public function getResumenIngresos() {
        $sql = "
            SELECT 
                COALESCE(SUM(CASE WHEN MONTH(fecha_pago) = MONTH(CURDATE()) AND YEAR(fecha_pago) = YEAR(CURDATE()) THEN monto_entregado ELSE 0 END), 0) AS mes_actual,
                COALESCE(SUM(CASE WHEN MONTH(fecha_pago) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(fecha_pago) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) THEN monto_entregado ELSE 0 END), 0) AS mes_anterior
            FROM pagos_historial
        ";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_OBJ);
    }

  

    // ============================================================
    // SECCIÓN 2 — Últimos cobros (tabla principal)
    // ============================================================

   public function getUltimosCobros($limite = 10) {
    $sql = "
        SELECT 
          COUNT(*) AS total_cuotas,
            ph.id_pago,
            ph.monto_entregado,
            ph.fecha_pago,
            cu.numero_cuota,
            ph.monto_entregado AS monto_cuota,
            cl.nombre AS cliente_nombre,
            m.nombre AS modelo_nombre,
            vnt.id_venta
        FROM pagos_historial ph
        INNER JOIN cuotas cu ON ph.id_cuota = cu.id_cuota
        INNER JOIN ventas vnt ON cu.id_venta = vnt.id_venta
        INNER JOIN clientes cl ON vnt.id_cliente = cl.id_cliente
        INNER JOIN vehiculos v ON vnt.id_vehiculo = v.id_vehiculo
        INNER JOIN modelos m ON v.id_modelo = m.id_modelo
        ORDER BY ph.fecha_pago DESC
        LIMIT :limite
    ";
    
    $stm = $this->pdo->prepare($sql);
    // Vinculamos como entero explícitamente
    $stm->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
    $stm->execute();
    
    return $stm->fetchAll(PDO::FETCH_OBJ);
}
// ============================================================
    // SECCIÓN 3 — Cuotas urgentes (alertas del día)
    // ============================================================

    public function getCuotasUrgentes($limite = 8) {
        $sql = "
            SELECT 
                cl.id_cliente,
                cl.nombre AS cliente_nombre,
                cl.telefono,
                m.nombre AS modelo_nombre,
                vnt.id_venta,
                MIN(cu.fecha_vencimiento) AS fecha_vencimiento,
                SUM(cu.monto - cu.monto_pagado) AS saldo_pendiente,
                DATEDIFF(CURDATE(), MIN(cu.fecha_vencimiento)) AS dias_atraso
            FROM cuotas cu
            INNER JOIN ventas vnt ON cu.id_venta = vnt.id_venta
            INNER JOIN clientes cl ON vnt.id_cliente = cl.id_cliente
            INNER JOIN vehiculos v ON vnt.id_vehiculo = v.id_vehiculo
            INNER JOIN modelos m ON v.id_modelo = m.id_modelo
            WHERE cu.estado = 1 
              AND (cu.monto - cu.monto_pagado) > 0
              AND cu.fecha_vencimiento <= CURDATE()
            GROUP BY vnt.id_venta
            ORDER BY dias_atraso DESC
            LIMIT :limite
        ";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
    }

    // ============================================================
    // SECCIÓN 4 — Gráfico de cobros (últimos 6 meses)
    // ============================================================

    public function getCobrosPorMes() {
        $sql = "
            SELECT 
                DATE_FORMAT(fecha_pago, '%Y-%m') AS mes,
                DATE_FORMAT(fecha_pago, '%b %Y') AS mes_label,
                COUNT(*) AS cantidad,
                COALESCE(SUM(monto_entregado), 0) AS total
            FROM pagos_historial
            WHERE fecha_pago >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(fecha_pago, '%Y-%m')
            ORDER BY mes ASC
        ";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_OBJ);
    }

    // ============================================================
    // SECCIÓN 5 — Resumen de cartera
    // ============================================================

    public function getResumenCartera() {
        $sql = "
            SELECT 
                COUNT(*) AS total_cuotas,
                SUM(CASE WHEN (monto - monto_pagado) <= 0 THEN 1 ELSE 0 END) AS pagadas,
                SUM(CASE WHEN (monto - monto_pagado) > 0 AND fecha_vencimiento >= CURDATE() THEN 1 ELSE 0 END) AS pendientes,
                SUM(CASE WHEN (monto - monto_pagado) > 0 AND fecha_vencimiento < CURDATE() THEN 1 ELSE 0 END) AS atrasadas,
                COALESCE(SUM(monto), 0) AS monto_total,
                COALESCE(SUM(monto_pagado), 0) AS monto_cobrado,
                COALESCE(SUM(CASE WHEN (monto - monto_pagado) > 0 AND fecha_vencimiento < CURDATE() THEN (monto - monto_pagado) ELSE 0 END), 0) AS monto_mora
            FROM cuotas
            WHERE estado = 1
        ";
        return $this->pdo->query($sql)->fetch(PDO::FETCH_OBJ);
    }

    // ============================================================
    // SECCIÓN 6 — Últimos clientes registrados
    // ============================================================

    public function getUltimosClientes($limite = 5) {
        $sql = "
            SELECT 
                cl.id_cliente,
                cl.nombre,
                cl.telefono,
                cl.ubicacion,
                COUNT(v.id_venta) AS total_ventas
            FROM clientes cl
            LEFT JOIN ventas v ON v.id_cliente = cl.id_cliente
            GROUP BY cl.id_cliente
            ORDER BY cl.id_cliente DESC
            LIMIT :limite
        ";
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stm->execute();
        return $stm->fetchAll(PDO::FETCH_OBJ);
    }
}