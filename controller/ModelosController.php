<?php
require_once 'model/Modelo.php';

class ModelosController {
    private $model;

    public function __construct() {
        $this->model = new Modelo();
    }

    public function index() {
        global $pageTitle, $activePage;
        $pageTitle = 'Modelos de Vehículos';
        $activePage = 'modelos';
        
        $modelos = $this->model->listar();

        require_once 'view/layout/header.php';
        require_once 'view/modelos/index.php';
        require_once 'view/layout/footer.php';
    }

    public function guardar() {
        if ($_POST) {
            $nombre = $_POST['nombre'];
            $id = $_POST['id_modelo'] ?? null;

            if ($id) {
                $this->model->actualizar($id, $nombre);
            } else {
                $this->model->guardar($nombre);
            }
            header("Location: index.php?c=modelos");
        }
    }

    public function eliminar() {
        $id = $_GET['id'];
        $this->model->eliminar($id);
        header("Location: index.php?c=modelos");
    }
}