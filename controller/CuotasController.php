<?php
require_once 'model/Cuota.php';
require_once 'model/Modelo.php';
require_once 'model/Vehiculo.php';
require_once 'model/Venta.php';
require_once 'model/Cliente.php'; // Asegúrate de incluirlo si no estaba

class CuotasController {
    private $model;
    private $cliente;
    private $modelo_vehiculo;
    private $vehiculo;
    private $venta;

    public function __construct() {
        $this->model = new Cuota();
        $this->cliente = new Cliente();
        $this->modelo_vehiculo = new Modelo();
        $this->vehiculo = new Vehiculo();
        $this->venta = new Venta();
    }

    public function index()
    {
    
        global $pageTitle, $activePage, $breadcrumbs;
    
        $pageTitle = 'Gestión de Cuotas';
        $activePage = 'cuotas';
    
        $breadcrumbs = [
            ['label' => 'Inicio', 'url' => 'index.php'],
            ['label' => 'Cuotas'],
        ];
    
        $filtros = [];
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
            $filtros['id_modelo'] = $_POST['id_modelo'] ?? '';
            $filtros['desde'] = $_POST['desde'] ?? '';
            $filtros['hasta'] = $_POST['hasta'] ?? '';
        }
    
        $modelos_lista = $this->modelo_vehiculo->listar();
    
        $cuotas = $this->model->listarGestion($filtros);
    
        // indicadores
        $stats = [
            'vence_hoy'=>0,
            'atrasados'=>0,
            'muy_atrasados'=>0
        ];
    
        foreach($cuotas as $c){
    
            if($c->categoria=="VENCE HOY")
                $stats['vence_hoy']++;
    
            if($c->categoria=="ATRASADO")
                $stats['atrasados']++;
    
            if($c->categoria=="ACUMULADO +2 MESES")
                $stats['muy_atrasados']++;
    
        }
    
        require_once 'view/layout/header.php';
        require_once 'view/cuotas/index.php';
        require_once 'view/layout/footer.php';
    }

    public function detalle()
    {
        global $pageTitle, $activePage, $breadcrumbs;
     
        $id_venta = intval($_GET['id'] ?? 0);
        if (!$id_venta) {
            header('Location: ?c=cuotas&a=index');
            exit;
        }
     
        // Datos de la venta (cliente, vehículo, monto total)
        $venta = $this->model->getDatosVenta($id_venta);
        if (!$venta) {
            header('Location: ?c=cuotas&a=index');
            exit;
        }
     
        $pageTitle  = 'Cuotas — ' . $venta->cliente_nombre;
        $activePage = 'cuotas';
     
        $breadcrumbs = [
            ['label' => 'Inicio',  'url' => 'index.php'],
            ['label' => 'Cuotas',  'url' => '?c=cuotas&a=index'],
            ['label' => $venta->cliente_nombre],
        ];
     
        // Detalle de cada cuota
        $cuotas_detalle = $this->model->getCuotasDetalle($id_venta);
     
        // Resumen calculado en PHP (igual que la lógica en la vista)
        $resumen = [
            'total_cuotas'    => count($cuotas_detalle),
            'pagadas'         => 0,
            'pendientes'      => 0,
            'cuotas_atrasadas'=> 0,
            'monto_cobrado'   => 0,
            'monto_pendiente' => 0,
            'mora_total'      => 0,
        ];
     
        $hoy = strtotime(date('Y-m-d'));
     
        foreach ($cuotas_detalle as $c) {
            $saldo = $c->monto - $c->monto_pagado;
            $resumen['monto_cobrado'] += $c->monto_pagado;
     
            if ($saldo <= 0) {
                $resumen['pagadas']++;
            } else {
                $resumen['pendientes']++;
                $resumen['monto_pendiente'] += $saldo;
                $venc = strtotime($c->fecha_vencimiento);
                if ($venc < $hoy) {
                    $dias_atraso  = max(0, floor(($hoy - $venc) / 86400));
                    $meses_atraso = ceil($dias_atraso / 30);
                    $resumen['cuotas_atrasadas']++;
                    $resumen['mora_total'] += $saldo * 0.02 * $meses_atraso;
                }
            }
        }
     
        require_once 'view/layout/header.php';
        require_once 'view/cuotas/detalle.php';
        require_once 'view/layout/footer.php';
    }
     
}