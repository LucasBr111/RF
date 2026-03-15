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

        // ── KPIs ──────────────────────────────────────────────
        $cobros_hoy       = $this->model->getCobrosHoy();
        $clientes_activos = $this->model->getTotalClientesActivos();
        $cuotas_mes       = $this->model->getCuotasPendientesMes();
        $mora             = $this->model->getCuotasEnMora();

        // ── Cartera y listas ──────────────────────────────────
        $cartera          = $this->model->getResumenCartera();
        $ultimos_cobros   = $this->model->getUltimosCobros(10);
        $cuotas_urgentes  = $this->model->getCuotasUrgentes(8);
        $ultimos_clientes = $this->model->getUltimosClientes(5);

        // ── Gráfico (JSON para Chart.js) ──────────────────────
        $grafico_raw      = $this->model->getCobrosPorMes();
        $grafico_labels   = json_encode(array_column($grafico_raw, 'mes_label'));
        $grafico_totales  = json_encode(array_column($grafico_raw, 'total'));
        $grafico_cantidad = json_encode(array_column($grafico_raw, 'cantidad'));

        require_once 'view/layout/header.php';
        require_once 'view/dashboard/index.php';
        require_once 'view/layout/footer.php';
    }
}