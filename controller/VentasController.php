<?php
require_once 'model/Cliente.php';
require_once 'model/Modelo.php';
require_once 'model/Vehiculo.php';
require_once 'model/Venta.php';
require_once 'model/Cuota.php';


class VentasController {
    private $model;
    private $cliente;
    private $modelo_vehiculo;
    private $vehiculo;
    private $cuota;

    public function __construct() {
        $this->model = new Venta();
        $this->cliente = new Cliente();
        $this->modelo_vehiculo = new Modelo();
        $this->vehiculo = new Vehiculo();
        $this->cuota = new Cuota();

    }

    public function index() {
      /*   $ventas = $this->model->listar(); */

        global $pageTitle, $activePage, $breadcrumbs, $modelos;
        $pageTitle   = 'Gestión de ventas';
        $activePage  = 'ventas';
        $breadcrumbs = [
            ['label' => 'Inicio', 'url' => 'index.php'],
            ['label' => 'Ventas'],
        ];
        
        // Pasar los modelos a la vista
        $modelos = $this->modelo_vehiculo->listar();

        require_once 'view/layout/header.php';
        require_once 'view/ventas/crear.php';
        require_once 'view/layout/footer.php';
    }


 public function guardar() {
    if ($_POST) {
        $data = json_decode($_POST['data']);
        
        // Iniciamos la transacción desde el objeto PDO principal para asegurar atomicidad
        $db = Database::StartUp();
        $db->beginTransaction();

        try {


        // --- LÓGICA DE PROCESAMIENTO DE CUOTAS ---
            $cant_normales = 0;
            $monto_normal = 0;
            $cant_refuerzos = 0;
            $monto_refuerzo = 0;

            foreach ($data->venta->cuotas as $cuota) {
                if ($cuota->tipo === 'normal') {
                    $cant_normales++;
                    $monto_normal = $cuota->monto; // Se asume que las normales son iguales
                } else if ($cuota->tipo === 'refuerzo') {
                    $cant_refuerzos++;
                    $monto_refuerzo = $cuota->monto; // Se asume que los refuerzos son iguales
                }
            }

            // Inyectamos estos valores calculados al objeto venta para el modelo
            $data->venta->cant_cuotas = $cant_normales;
            $data->venta->monto_cuota = $monto_normal;
            $data->venta->cant_refuerzos = $cant_refuerzos;
            $data->venta->monto_refuerzo = $monto_refuerzo;

            // 1. Manejar Cliente
            $id_cliente = $this->cliente->guardar($data->cliente);

            // 2. Manejar Modelo de Vehículo
            $id_modelo = $data->vehiculo->id_modelo;
            if ($id_modelo === 'OTRO') {
                $id_modelo = $this->modelo_vehiculo->guardar($data->vehiculo->nuevo_nombre);
            }

            
            //Crear objeto con las propiedades necesarias para guardar el vehículo
            $vehiculoObjeto = new Vehiculo();
            $vehiculoObjeto->id_modelo = $id_modelo;
            $vehiculoObjeto->anho = $data->vehiculo->anho;
            $vehiculoObjeto->color = $data->vehiculo->color;
            $vehiculoObjeto->detalle = $data->vehiculo->detalle;
            // 3. Manejar Vehículo
            $id_vehiculo = $this->vehiculo->guardar($vehiculoObjeto);

            // 4. Guardar Venta
            $id_venta = $this->model->guardar_cabecera($id_cliente, $id_vehiculo, $data->venta);

            // 5. Guardar Cuotas (Modularizado)
            foreach ($data->venta->cuotas as $item) {
                $cuotaObjeto = new stdClass();
                
                $cuotaObjeto->numero = $item->numero;
                $cuotaObjeto->monto  = $item->monto;
                $cuotaObjeto->vencimiento  = $item->fecha; 
                $cuotaObjeto->tipo   = $item->tipo;
            
                // Ahora enviamos el objeto ya cargado al modelo
                $this->cuota->guardar(
                    $id_venta,
                    $cuotaObjeto
                );
            }

            $db->commit();
            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

    // public function actualizar() {
    //     if ($_POST) {
    //         $data = (object)$_POST;
    //         // $res = $this->model->actualizar($data);
    //         echo json_encode(['success' => $res]);
    //     }
    // }

    // public function anular() {
    //     $id = $_GET['id'] ?? null;
    //     if ($id) {
    //         $res = $this->model->anular($id);
    //         // Redireccionar o devolver JSON según prefieras
    //         header("Location: index.php?c=venta&a=index");
    //     }
        
    // }
    public function pagare() {
    $id_venta = $_GET['id'];
    require_once "./config/app.php"; // 👈 importante
    // Obtenemos los datos del modelo
    $pagares = $this->model->listarPagaresPorVenta($id_venta);
    
    if(empty($pagares)) {
        die("No hay cuotas registradas para esta venta.");
    }

    // Cargamos la vista del PDF
    require_once 'view/informes/pagarepdf.php';
}
}