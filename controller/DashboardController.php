<?php
// controller/DashboardController.php

require_once 'model/Dashboard.php';

class DashboardController {

    private $model;

    public function __construct() {
        $this->model = new Dashboard();
    }

    public function index() {

        global $pageTitle, $activePage, $breadcrumbs;

        $pageTitle   = 'Dashboard';
        $activePage  = 'inicio';
        $breadcrumbs = [
            ['label' => 'Inicio', 'url' => 'index.php'],
            ['label' => 'Dashboard'],
        ];

        require_once 'model/Cuota.php';
        $cuotaModel = new Cuota();
        $notificaciones = $cuotaModel->getResumenNotificaciones();
       
        require_once 'view/layout/header.php';
        require_once 'view/dashboard/index.php';
        require_once 'view/layout/footer.php';
    }

    public function getNotificaciones() {
        require_once 'model/Cuota.php';
        $cuotaModel = new Cuota();
        echo json_encode($cuotaModel->getResumenNotificaciones());
    }
}