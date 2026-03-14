<?php

class Venta {
    private $pdo;

    public function __construct(){
        try {
            $this->pdo = Database::StartUp();     
        } catch(Exception $e) {
            die($e->getMessage());
        }
    }

    public function guardar_cabecera($id_cliente, $id_vehiculo, $venta) {
        $sql = "INSERT INTO ventas (
                    id_cliente, 
                    id_vehiculo, 
                    fecha_venta, 
                    monto_total, 
                    monto_cuota, 
                    cant_cuotas, 
                    monto_refuerzo, 
                    cant_refuerzos, 
                    interes_mora,
                    observaciones
                ) VALUES (?, ?, CURDATE(), ?, ?, ?, ?, ?, ?, ?)";
        
        $this->pdo->prepare($sql)->execute([
            $id_cliente,
            $id_vehiculo,
            $venta->monto_total,
            $venta->monto_cuota,
            $venta->cant_cuotas,
            $venta->monto_refuerzo,
            $venta->cant_refuerzos,
            $venta->interes_mora,
            $venta->observaciones
        ]);
        
        return $this->pdo->lastInsertId();
    }

    public function listarPagaresPorVenta($id_venta) {
    try {
        $sql = "SELECT 
                    c.id_cuota, 
                    c.numero_cuota, 
                    c.monto, 
                    c.fecha_vencimiento,
                    v.id_venta, 
                    v.fecha_venta as fecha_emision,
                    v.cant_cuotas,
                    cl.nombre as cliente_nombre, 
                    cl.ci as cliente_ci, 
                    cl.ubicacion as cliente_direccion,
                    cl.telefono as cliente_tel,
                    cl.codeudor_nombre,
                    cl.codeudor_ci
                FROM cuotas c
                INNER JOIN ventas v ON c.id_venta = v.id_venta
                INNER JOIN clientes cl ON v.id_cliente = cl.id_cliente
                WHERE v.id_venta = ? AND c.tipo = 'normal'
                ORDER BY c.numero_cuota ASC";

        $stm = $this->pdo->prepare($sql);
        $stm->execute([$id_venta]);
        return $stm->fetchAll(PDO::FETCH_OBJ);
    } catch (Exception $e) {
        die($e->getMessage());
    }
}
}