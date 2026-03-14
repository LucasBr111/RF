<?php

class Cuota {
    private $pdo;
    public $id_cuota;
    public $id_venta;
    public $numero_cuota;
    public $monto;
    public $monto_pagado;
    public $fecha_vencimiento;
    public $tipo;

    public function __construct(){
        try{
            $this->pdo = Database::StartUp();     
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function guardar($id_venta, $data) {
        $sql = "INSERT INTO cuotas (id_venta, numero_cuota, monto, fecha_vencimiento, tipo) VALUES (?, ?, ?, ?, ?)";
        $this->pdo->prepare($sql)->execute([
            $id_venta,
            $data->numero, // El número de cuota que viene del array JS
            $data->monto,
            $data->vencimiento,
            $data->tipo // 'normal' o 'refuerzo'
        ]);
        return $this->pdo->lastInsertId();
    }

    public function listarGestion1($filtros = []) {
        try {
            $where = " WHERE cu.estado = 1 ";
            $params = [];
    
            // Filtro por Vehículo/Modelo
            if (!empty($filtros['id_modelo'])) {
                $where .= " AND v.id_modelo = ? ";
                $params[] = $filtros['id_modelo'];
            }
    
            // Filtro por Fechas
            if (!empty($filtros['desde']) && !empty($filtros['hasta'])) {
                $where .= " AND cu.fecha_vencimiento BETWEEN ? AND ? ";
                $params[] = $filtros['desde'];
                $params[] = $filtros['hasta'];
            }
    
            $sql = "SELECT 
                        cu.id_cuota, cu.numero_cuota, cu.monto, cu.monto_pagado, cu.fecha_vencimiento,
                        cl.id_cliente, cl.nombre AS cliente_nombre, cl.telefono,
                        m.nombre AS modelo_nombre, v.anho AS vehiculo_anho,
                        vnt.interes_mora,
                        (cu.monto - cu.monto_pagado) AS saldo,
                        -- Cálculo de meses de atraso exactos
                        PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM CURRENT_DATE), EXTRACT(YEAR_MONTH FROM cu.fecha_vencimiento)) AS meses_atraso,
                        -- Cálculo de Mora
                        CASE 
                            WHEN PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM CURRENT_DATE), EXTRACT(YEAR_MONTH FROM cu.fecha_vencimiento)) >= 2 
                            THEN (cu.monto * (vnt.interes_mora / 100))
                            ELSE 0 
                        END AS mora_calculada
                    FROM cuotas cu
                    INNER JOIN ventas vnt ON cu.id_venta = vnt.id_venta
                    INNER JOIN clientes cl ON vnt.id_cliente = cl.id_cliente
                    INNER JOIN vehiculos v ON vnt.id_vehiculo = v.id_vehiculo
                    INNER JOIN modelos m ON v.id_modelo = m.id_modelo
                    $where
                    ORDER BY cu.fecha_vencimiento ASC";
    
            $stm = $this->pdo->prepare($sql);
            $stm->execute($params);
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function listarGestion($filtros = [])
    {

        $where = " WHERE cu.estado = 1 ";
        $params = [];

        if (!empty($filtros['id_modelo'])) {
            $where .= " AND v.id_modelo = ? ";
            $params[] = $filtros['id_modelo'];
        }

        if (!empty($filtros['desde']) && !empty($filtros['hasta'])) {
            $where .= " AND cu.fecha_vencimiento BETWEEN ? AND ? ";
            $params[] = $filtros['desde'];
            $params[] = $filtros['hasta'];
        }

        $sql = "

        SELECT

        vnt.id_venta,
        cl.id_cliente,
        cl.nombre cliente_nombre,
        cl.telefono,

        m.nombre modelo_nombre,

        COUNT(
            CASE
            WHEN cu.fecha_vencimiento < CURDATE()
            AND (cu.monto - cu.monto_pagado) > 0
            THEN 1 END
        ) cuotas_atrasadas,

        SUM(
            CASE
            WHEN cu.fecha_vencimiento < CURDATE()
            THEN (cu.monto - cu.monto_pagado)
            ELSE 0 END
        ) monto_acumulado,

        MIN(
            CASE
            WHEN (cu.monto - cu.monto_pagado) > 0
            THEN cu.fecha_vencimiento
            END
        ) proximo_vencimiento

        FROM cuotas cu

        INNER JOIN ventas vnt ON cu.id_venta = vnt.id_venta
        INNER JOIN clientes cl ON vnt.id_cliente = cl.id_cliente
        INNER JOIN vehiculos v ON vnt.id_vehiculo = v.id_vehiculo
        INNER JOIN modelos m ON v.id_modelo = m.id_modelo

        $where

        GROUP BY vnt.id_venta

        ORDER BY proximo_vencimiento ASC
        ";

        $stm = $this->pdo->prepare($sql);
        $stm->execute($params);

        $data = $stm->fetchAll(PDO::FETCH_OBJ);

        foreach ($data as $r) {

            $categoria = "AL DIA";

            if ($r->proximo_vencimiento) {

                $venc = strtotime($r->proximo_vencimiento);
                $hoy = strtotime(date("Y-m-d"));

                $meses = ((date('Y', $hoy) - date('Y', $venc)) * 12) + (date('m', $hoy) - date('m', $venc));

                if (date("d", $venc) == date("d"))
                    $categoria = "VENCE HOY";

                elseif ($meses >= 2)
                    $categoria = "ACUMULADO +2 MESES";

                elseif ($venc < $hoy)
                    $categoria = "ATRASADO";

                elseif (date("m", $venc) == date("m"))
                    $categoria = "PENDIENTE";
            }

            $r->categoria = $categoria;
        }

        return $data;
    }


    // ============================================================
    //  AGREGAR AL MODELO: CuotasModel (o como lo llames)
    // ============================================================
     
    /**
     * Devuelve datos generales de la venta (para el header del detalle).
     */
    public function getDatosVenta($id_venta)
    {
        $sql = "
            SELECT
                vnt.id_venta,
                cl.id_cliente,
                cl.nombre  cliente_nombre,
                cl.telefono,
                m.nombre   modelo_nombre,
                SUM(cu.monto) monto_total
            FROM ventas vnt
            INNER JOIN clientes cl ON vnt.id_cliente = cl.id_cliente
            INNER JOIN vehiculos v  ON vnt.id_vehiculo = v.id_vehiculo
            INNER JOIN modelos   m  ON v.id_modelo    = m.id_modelo
            LEFT  JOIN cuotas   cu  ON cu.id_venta    = vnt.id_venta
            WHERE vnt.id_venta = ?
            GROUP BY vnt.id_venta
        ";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id_venta]);
        return $stm->fetch(PDO::FETCH_OBJ);
    }
     
    /**
     * Lista todas las cuotas de una venta con su detalle de pago.
     */
    public function getCuotasDetalle($id_venta)
    {
        $sql = "
            SELECT
                cu.id_cuota,
                cu.numero_cuota,
                cu.fecha_vencimiento,
                cu.monto,
                COALESCE(cu.monto_pagado, 0)  monto_pagado,
                cu.estado
            FROM cuotas cu
            WHERE cu.id_venta = ?
            ORDER BY cu.numero_cuota ASC
        ";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id_venta]);
        return $stm->fetchAll(PDO::FETCH_OBJ);
    }
}
?>