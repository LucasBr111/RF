<?php

class Cliente {
    private $pdo;
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

    public function guardar($data) {
        $sql = "INSERT INTO clientes (nombre, ci, telefono, ubicacion, codeudor_nombre, codeudor_ci) VALUES (?, ?, ?, ?, ?, ?)";
        $this->pdo->prepare($sql)->execute([
            $data->nombre, $data->ci, $data->telefono, $data->ubicacion, $data->codeudor_nombre, $data->codeudor_ci
        ]);
        return $this->pdo->lastInsertId();
    }
}
?>