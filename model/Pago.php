<?php
class Pago {
    private $pdo;

  
    public function __construct(){
        try{
            $this->pdo = Database::StartUp();     
        }catch(Exception $e){
            die($e->getMessage());
        }
    }
    public function procesarCobroCompleto($data) {
        try {
            $this->pdo->beginTransaction();
    
            // 1. Insertar en pagos_historial (según tu captura de pantalla)
            $sqlPago = "INSERT INTO pagos_historial (id_cuota, monto_entregado, fecha_pago, metodo_pago, observacion, created_at) 
                        VALUES (?, ?, ?, ?, ?, ?)";
            $this->pdo->prepare($sqlPago)->execute([
                $data->id_cuota,
                $data->monto_entregado, // El capital cobrado
                $data->fecha_pago,
                $data->metodo_pago,
                $data->observacion,
                $data->fecha_pago
            ]);
    
            // 2. Actualizar la tabla CUOTAS
            // Sumamos el monto entregado al monto_pagado actual y actualizamos el estado
            // Si el nuevo monto_pagado es igual o mayor al monto original, es PAGADA.
            $sqlCuota = "UPDATE cuotas 
                         SET monto_pagado = monto_pagado + ?, 
                             estado = CASE 
                                WHEN (monto_pagado + ?) >= monto THEN 'PAGADA'
                                ELSE 'PENDIENTE'
                             END
                         WHERE id_cuota = ?";
            
            $this->pdo->prepare($sqlCuota)->execute([
                $data->monto_entregado,
                $data->monto_entregado,
                $data->id_cuota
            ]);
    
            $this->pdo->commit();
            return true;
    
        } catch (Exception $e) {
            $this->pdo->rollBack();
            die("Error en el registro: " . $e->getMessage());
        }
    }

    public function listarPorPeriodo($inicio, $fin) {
        $sql = "SELECT ph.*, c.numero_cuota, v.id_venta, cl.nombre as cliente_nombre 
                FROM pagos_historial ph
                JOIN cuotas c ON ph.id_cuota = c.id_cuota
                JOIN ventas v ON c.id_venta = v.id_venta
                JOIN clientes cl ON v.id_cliente = cl.id_cliente
                WHERE ph.fecha_pago BETWEEN ? AND ?
                ORDER BY ph.fecha_pago DESC";
        $stm = $this->pdo->prepare($sql);
        $stm->execute([$inicio, $fin]);
        return $stm->fetchAll(PDO::FETCH_OBJ);
    }
}