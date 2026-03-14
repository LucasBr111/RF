<?php

class Modelo
{
    private $pdo;
    public $nombre;
    public function __construct()
    {
        try {
            $this->pdo = Database::StartUp();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function guardar($nombre)
    {
        $sql = "INSERT INTO modelos (nombre) VALUES (?)";

        // 1. Preparamos la sentencia
        $stmt = $this->pdo->prepare($sql);

        // 2. Ejecutamos pasándole los datos
        $stmt->execute([$nombre]);

        // 3. Retornamos el ID desde el objeto de conexión original
        return $this->pdo->lastInsertId();
    }

    public function listar()
    {
        $sql = "SELECT id_modelo, nombre FROM modelos ORDER BY nombre";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_OBJ);
    }
}
