<?php 

/* require_once 'model/cuota.php';
require_once 'model/cliente.php';
require_once 'model/ingreso.php';
require_once 'model/egreso.php'; */

class DashboardController{

    private $cuota;
    private $cliente;
    private $ingreso;
    private $egreso;
    public function __construct(){
        // Inicializar los constructores de los modelos 
        // $this->cuota = new cuota();
        // $this->cliente = new cliente();
        // $this->ingreso = new ingreso();
        // $this->egreso = new egreso();
    }

    public function index(){
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