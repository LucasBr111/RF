<?php
class Vehiculo {
    private $pdo;
    public $id_vehiculo;
    public $id_modelo;
    public $anho;
    public $color;
    public $detalle;
    public $propietario; // Para manejar el nombre del dueño

    public function __construct(){
        try{
            $this->pdo = Database::StartUp();     
        }catch(Exception $e){
            die($e->getMessage());
        }
    }

    public function Listar() {
        try {
            // Unimos con modelos para mostrar el nombre del vehículo
            $sql = "SELECT v.*, m.nombre as modelo_nombre, cl.nombre as propietario
                    FROM vehiculos v 
                    INNER JOIN modelos m ON v.id_modelo = m.id_modelo 
                    inner join ventas vt on v.id_vehiculo = vt.id_vehiculo 
                    inner join clientes cl on vt.id_cliente = cl.id_cliente 
                    ORDER BY v.id_vehiculo DESC";
            $stm = $this->pdo->prepare($sql);
            $stm->execute();
            return $stm->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function Obtener($id) {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM vehiculos WHERE id_vehiculo = ?");
            $stm->execute([$id]);
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function guardar($data) {
        try {
            $sql = "INSERT INTO vehiculos (id_modelo, anho, color, detalle) VALUES (?, ?, ?, ?)";
            $this->pdo->prepare($sql)->execute([
                $data->id_modelo, 
                $data->anho, 
                $data->color, 
                $data->detalle,
              
            ]);
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function actualizar($data) {
        try {
            $sql = "UPDATE vehiculos SET id_modelo=?, anho=?, color=?, detalle=? WHERE id_vehiculo=?";
            $this->pdo->prepare($sql)->execute([
                $data->id_modelo, $data->anho, $data->color, $data->detalle, $data->id_vehiculo
            ]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}