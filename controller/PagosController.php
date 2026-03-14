<?php
require_once 'model/pago.php';

class PagosController {
    private $model;

    public function __construct() {
        $this->model = new Pago();
    }

    public function Index() {
        global $pageTitle, $activePage, $breadcrumbs;
        $pageTitle = 'Dashboard de Cobranzas';
        $activePage = 'cobros';
        $breadcrumbs = [
            ['label' => 'Inicio', 'url' => 'index.php'],
            ['label' => 'Cobros Realizados']
        ];
    
        $hoy = date('Y-m-d');
        $inicioSemana = date('Y-m-d', strtotime('monday this week'));
        $finSemana = date('Y-m-d', strtotime('sunday this week'));
        $inicioMes = date('Y-m-01');
        $finMes = date('Y-m-t');
    
        // Recuperamos los datos usando el modelo Pago
        $cobrosHoy = $this->model->listarPorPeriodo($hoy . " 00:00:00", $hoy . " 23:59:59");
        $cobrosSemana = $this->model->listarPorPeriodo($inicioSemana . " 00:00:00", $finSemana . " 23:59:59");
        $cobrosMes = $this->model->listarPorPeriodo($inicioMes . " 00:00:00", $finMes . " 23:59:59");
    
        require_once 'view/layout/header.php';
        require_once 'view/pagos/index.php';
        require_once 'view/layout/footer.php';
    }

    public function RegistrarPago() {
        if ($_POST) {
            $data = new stdClass();
            $data->id_venta        = $_POST['id_venta'];
            $data->id_cuota        = $_POST['id_cuota'];
            if(isset($_POST['mora_pago'])) {
                $data->monto_entregado = $_POST['monto_pago'] + $_POST['mora_pago'];
                $data->observacion     = "Cobro de cuota + MORA: " . $_POST['mora_pago'] . ".";
            } else {
                $data->monto_entregado = $_POST['monto_pago'];
                $data->observacion     = "Cobro registrado desde el panel de cuotas.";
            }
          
            $data->mora_pago       = $_POST['mora_pago'] ?? 0;
            $data->metodo_pago     = $_POST['metodo_pago'];
            $data->fecha_pago      = date('Y-m-d H:i:s');
           
    
            // Validar que el monto sea mayor a 0
            if ($data->monto_entregado <= 0) {
                header("Location: ?c=cuotas&a=detalle&id=" . $data->id_venta . "&msg=monto_invalido");
                return;
            }
    
            // Llamar al modelo para procesar la transacción
            $this->model->procesarCobroCompleto($data);
    
            header("Location: ?c=cuotas&a=detalle&id=" . $data->id_venta . "&msg=pago_exitoso");
        }
    }
}