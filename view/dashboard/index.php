<?php
/**
 * Ejemplo de vista — Dashboard (Inicio)
 * Ruta: index.php (o views/inicio.php según tu MVC)
 */

// Variables para el layout
$pageTitle   = 'Dashboard';
$activePage  = 'inicio';
$breadcrumbs = [
    ['label' => 'Inicio', 'url' => 'index.php'],
    ['label' => 'Dashboard'],
];


echo "Renderizando vista: Dashboard (index.php)"; // Debugging

?>

<!-- ── Page Header ───────────────────────────────────────────── -->
<div class="page-header mb-4">
  <h1 class="page-title">Dashboard</h1>
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active">Inicio</li>
    </ol>
  </nav>
</div>

<!-- ── Stat Cards ────────────────────────────────────────────── -->
<div class="row g-3 mb-4 row-stats-mobile">

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card success">
      <div class="stat-icon green"><i class="bi bi-cash-coin"></i></div>
      <div class="stat-info">
        <div class="stat-value">₲ 18.500k</div>
        <div class="stat-label">Cobros del día</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card primary">
      <div class="stat-icon blue"><i class="bi bi-people"></i></div>
      <div class="stat-info">
        <div class="stat-value">142</div>
        <div class="stat-label">Clientes activos</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card warning">
      <div class="stat-icon amber"><i class="bi bi-hourglass-split"></i></div>
      <div class="stat-info">
        <div class="stat-value">23</div>
        <div class="stat-label">Vencen hoy</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="stat-card danger">
      <div class="stat-icon red"><i class="bi bi-exclamation-triangle"></i></div>
      <div class="stat-info">
        <div class="stat-value">7</div>
        <div class="stat-label">Cuotas vencidas</div>
      </div>
    </div>
  </div>

</div>

<!-- ── Tabla de ejemplo ───────────────────────────────────────── -->
<div class="card-rf">
  <div class="card-header-rf">
    <h2 class="card-title-rf"><i class="bi bi-clock-history me-2 text-accent"></i>Últimos Cobros</h2>
    <a href="index.php?c=cobros" class="btn btn-sm" style="background:var(--rf-accent-dim);color:var(--rf-accent);border:1px solid var(--rf-accent);border-radius:8px;font-size:12px;padding:5px 12px;">
      Ver todos
    </a>
  </div>
  <div class="card-body-rf">
    <div class="table-responsive">
      <table class="table datatable" id="tblCobros">
        <thead>
          <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Vehículo</th>
            <th>Cuota N°</th>
            <th>Monto</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th class="no-export">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Juan Pérez</td>
            <td>Toyota Hilux 2022</td>
            <td>3 / 36</td>
            <td>₲ 1.250.000</td>
            <td>13/03/2025</td>
            <td><span class="badge-rf success">Pagado</span></td>
            <td>
              <button class="btn btn-sm" style="background:var(--rf-accent-dim);color:var(--rf-accent);border:none;border-radius:6px;">
                <i class="bi bi-eye"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td>María González</td>
            <td>Honda CR-V 2023</td>
            <td>7 / 48</td>
            <td>₲ 2.100.000</td>
            <td>13/03/2025</td>
            <td><span class="badge-rf warning">Pendiente</span></td>
            <td>
              <button class="btn btn-sm" style="background:var(--rf-accent-dim);color:var(--rf-accent);border:none;border-radius:6px;">
                <i class="bi bi-eye"></i>
              </button>
            </td>
          </tr>
          <tr>
            <td>3</td>
            <td>Carlos López</td>
            <td>Chevrolet S10 2021</td>
            <td>12 / 60</td>
            <td>₲ 980.000</td>
            <td>12/03/2025</td>
            <td><span class="badge-rf danger">Vencido</span></td>
            <td>
              <button class="btn btn-sm" style="background:var(--rf-accent-dim);color:var(--rf-accent);border:none;border-radius:6px;">
                <i class="bi bi-eye"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

