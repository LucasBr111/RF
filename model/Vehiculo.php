<?php

class Vehiculo {
    private $pdo;
    public $id_vehiculo;
    public $id_modelo;
    public $anho;
    public $color;
    public $detalle;

    public function __construct(){
        try{
            $this->pdo = Database::StartUp();     
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function guardar($id_modelo, $anho, $color) {
        $sql = "INSERT INTO vehiculos (id_modelo, anho, color, detalle) VALUES (?, ?, ?, ?)";
        $this->pdo->prepare($sql)->execute([
            $id_modelo, 
            $anho, 
            $color, 
            null
        ]);
        return $this->pdo->lastInsertId();
    }
}
?>