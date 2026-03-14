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

        // ── KPIs tarjetas superiores ──────────────────────────
        $cobros_hoy        = $this->model->getCobrosHoy();
        $clientes_activos  = $this->model->getTotalClientesActivos();
        $cuotas_hoy        = $this->model->getCuotasVencenHoy();
        $cuotas_vencidas   = $this->model->getCuotasVencidas();
        $ingresos          = $this->model->getResumenIngresos();
     

        // ── Tablas y listas ───────────────────────────────────
        $ultimos_cobros    = $this->model->getUltimosCobros(10);
        $cuotas_urgentes   = $this->model->getCuotasUrgentes(8);
        $ultimos_clientes  = $this->model->getUltimosClientes(5);

        // ── Resumen cartera ───────────────────────────────────
        $cartera           = $this->model->getResumenCartera();

        // ── Datos para gráfico (JSON para Chart.js) ───────────
        $cobros_grafico    = $this->model->getCobrosPorMes();
        $grafico_labels    = json_encode(array_column($cobros_grafico, 'mes_label'));
        $grafico_totales   = json_encode(array_column($cobros_grafico, 'total'));
        $grafico_cantidad  = json_encode(array_column($cobros_grafico, 'cantidad'));

        require_once 'view/layout/header.php';
        require_once 'view/dashboard/index.php';
        require_once 'view/layout/footer.php';
    }
}