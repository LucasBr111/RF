<?php
class Cliente {
    private $pdo;
    
    public $id_cliente;
    public $nombre;
    public $ci;
    public $telefono;
    public $ubicacion;
    public $codeudor_nombre;
    public $codeudor_ci;

    public function __construct(){
        try{
            $this->pdo = Database::StartUp();     
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function Listar() {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM clientes ORDER BY nombre ASC");
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function Obtener($id) {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
            $stm->execute(array($id));
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function guardar($data) {
        try {
            $sql = "INSERT INTO clientes (nombre, ci, telefono, ubicacion, codeudor_nombre, codeudor_ci) VALUES (?, ?, ?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->nombre, $data->ci, $data->telefono, $data->ubicacion, $data->codeudor_nombre, $data->codeudor_ci
            ]);
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function actualizar($data) {
        try {
            $sql = "UPDATE clientes SET nombre=?, ci=?, telefono=?, ubicacion=?, codeudor_nombre=?, codeudor_ci=? WHERE id_cliente=?";
            $this->pdo->prepare($sql)->execute([
                $data->nombre, $data->ci, $data->telefono, $data->ubicacion, $data->codeudor_nombre, $data->codeudor_ci, $data->id_cliente
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function obtenerVehiculos($id_cliente) {
        try {
            $sql = "SELECT v.id_venta, v.fecha_venta, v.monto_total, 
                           vh.anho, vh.color, vh.detalle, m.nombre as modelo
                    FROM ventas v
                    INNER JOIN vehiculos vh ON v.id_vehiculo = vh.id_vehiculo
                    INNER JOIN modelos m ON vh.id_modelo = m.id_modelo
                    WHERE v.id_cliente = ?
                    ORDER BY v.fecha_venta DESC";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cliente]);
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function obtenerHistorialPagos($id_cliente) {
        try {
            $sql = "SELECT ph.id_pago, ph.monto_entregado, ph.fecha_pago, ph.observacion,
                           c.numero_cuota, c.fecha_vencimiento, c.tipo as tipo_cuota,
                           v.id_venta, m.nombre as modelo_vehiculo
                    FROM pagos_historial ph
                    INNER JOIN cuotas c ON ph.id_cuota = c.id_cuota
                    INNER JOIN ventas v ON c.id_venta = v.id_venta
                    INNER JOIN vehiculos vh ON v.id_vehiculo = vh.id_vehiculo
                    INNER JOIN modelos m ON vh.id_modelo = m.id_modelo
                    WHERE v.id_cliente = ?
                    ORDER BY ph.fecha_pago DESC";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cliente]);
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function obtenerSumatoriaCuotas($id_cliente) {
        try {
            $sql = "SELECT 
                        COUNT(c.id_cuota) as total_cuotas,
                        SUM(c.monto) as total_deuda,
                        SUM(c.monto_pagado) as total_pagado,
                        SUM(c.monto - c.monto_pagado) as deuda_restante,
                        MAX(ph.fecha_pago) as ultima_fecha_pago
                    FROM cuotas c
                    INNER JOIN ventas v ON c.id_venta = v.id_venta
                    LEFT JOIN pagos_historial ph ON c.id_cuota = ph.id_cuota
                    WHERE v.id_cliente = ?";
            $stm = $this->pdo->prepare($sql);
            $stm->execute([$id_cliente]);
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}