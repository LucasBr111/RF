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
        $sql = "SELECT 
                    m.id_modelo,
                    m.nombre,
                    COUNT(v.id_modelo) AS cantidad
                FROM modelos m
                LEFT JOIN vehiculos v 
                    ON m.id_modelo = v.id_modelo
                GROUP BY m.id_modelo, m.nombre
                ORDER BY m.nombre;";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_OBJ);
    }



    public function obtener($id)
    {
        try {
            $stm = $this->pdo->prepare("SELECT * FROM modelos WHERE id_modelo = ?");
            $stm->execute([$id]);
            return $stm->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function actualizar($id, $nombre)
    {
        try {
            $sql = "UPDATE modelos SET nombre = ? WHERE id_modelo = ?";
            $this->pdo->prepare($sql)->execute([$nombre, $id]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function eliminar($id)
    {
        try {
            $stm = $this->pdo->prepare("DELETE FROM modelos WHERE id_modelo = ?");
            $stm->execute([$id]);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}
