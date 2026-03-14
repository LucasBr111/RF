<?php
require_once 'model/vehiculo.php';
require_once 'model/modelo.php'; // Asumo que tienes este para el select

class VehiculosController {
    private $model;
    private $modelModelo;

    public function __construct() {
        $this->model = new Vehiculo();
        $this->modelModelo = new Modelo();
    }

    public function Index() {
        // --- Configuración de Layout ---
        global $pageTitle, $activePage, $breadcrumbs;
        $pageTitle = 'Inventario de Vehículos';
        $activePage = 'vehiculos';
        $breadcrumbs = [
            ['label' => 'Inicio', 'url' => 'index.php'],
            ['label' => 'Vehículos'],
        ];
        $vehiculos = $this->model->Listar();
        $modelos_lista = $this->modelModelo->Listar(); // Para el select del modal
        
        $stats = ['total' => count($vehiculos)];
        require_once 'view/layout/header.php';
        require_once 'view/vehiculos/index.php';
        require_once 'view/layout/footer.php';
    }

    public function Guardar() {
        $v = new stdClass();
        $v->id_vehiculo = $_POST['id_vehiculo'];
        $v->id_modelo   = $_POST['id_modelo'];
        $v->anho        = $_POST['anho'];
        $v->color       = $_POST['color'];
        $v->detalle     = $_POST['detalle'];
        $v->propietario = $_POST['propietario'];

        $v->id_vehiculo > 0 
            ? $this->model->actualizar($v)
            : $this->model->guardar($v);
            
        header('Location: ?c=Vehiculos');
    }
}