<?php
class CuotasController {
    // private $model; 

    public function __construct() {
        // $this->model = new Cuota();
    }

    public function index() {
        global $pageTitle, $activePage, $breadcrumbs;
        $pageTitle   = 'Gestión de Cuotas';
        $activePage  = 'cuotas';
        $breadcrumbs = [
            ['label' => 'Inicio', 'url' => 'index.php'],
            ['label' => 'Cuotas'],
        ];

        // Aquí simularíamos la carga de datos filtrados desde el modelo
        // $cuotas = $this->model->listarCuotasPendientes(); 

        require_once 'view/layout/header.php';
        require_once 'view/cuotas/index.php';
        require_once 'view/layout/footer.php';
    }
}