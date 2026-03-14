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
}