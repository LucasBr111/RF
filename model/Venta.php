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
                    interes_mora
                ) VALUES (?, ?, CURDATE(), ?, ?, ?, ?, ?, ?)";
        
        $this->pdo->prepare($sql)->execute([
            $id_cliente,
            $id_vehiculo,
            $venta->monto_total,
            $venta->monto_cuota,
            $venta->cant_cuotas,
            $venta->monto_refuerzo,
            $venta->cant_refuerzos,
            $venta->interes_mora
        ]);
        
        return $this->pdo->lastInsertId();
    }
}