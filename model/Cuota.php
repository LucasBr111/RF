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
}
?>