<?php
require_once 'model/cliente.php';

class ClientesController {
    private $model;

    public function __construct() {
        $this->model = new Cliente();
    }

    public function Index() {
        $clientes = $this->model->Listar();
        $stats = [
            'total' => count($clientes),
            'activos' => count($clientes), // Ajustar segun lógica
            'nuevos' => 0 
        ];
        require_once 'view/layout/header.php';
        require_once 'view/clientes/index.php';
        require_once 'view/layout/footer.php';
    }

    public function Guardar() {
        $cliente = new Cliente();
        
        $cliente->id_cliente      = $_POST['id_cliente'];
        $cliente->nombre          = $_POST['nombre'];
        $cliente->ci              = $_POST['ci'];
        $cliente->telefono        = $_POST['telefono'];
        $cliente->ubicacion       = $_POST['ubicacion'];
        $cliente->codeudor_nombre = $_POST['codeudor_nombre'];
        $cliente->codeudor_ci     = $_POST['codeudor_ci'];

        $cliente->id_cliente > 0 
            ? $this->model->actualizar($cliente)
            : $this->model->guardar($cliente);
            
        header('Location: ?c=clientes');
    }

    public function detalle() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if (!$id) {
            header('Location: ?c=clientes');
            exit;
        }

        $cliente = $this->model->Obtener($id);
        if (!$cliente) {
            die("Error: Cliente no encontrado.");
        }

        $vehiculos = $this->model->obtenerVehiculos($id);
        $pagos = $this->model->obtenerHistorialPagos($id);
        $resumen = $this->model->obtenerSumatoriaCuotas($id);

        global $pageTitle, $activePage, $breadcrumbs;
        $pageTitle   = 'Perfil de Cliente';
        $activePage  = 'clientes';
        $breadcrumbs = [
            ['label' => 'Inicio', 'url' => 'index.php'],
            ['label' => 'Clientes', 'url' => '?c=clientes'],
            ['label' => $cliente->nombre],
        ];

        require_once 'view/layout/header.php';
        require_once 'view/clientes/detalle.php';
        require_once 'view/layout/footer.php';
    }
}