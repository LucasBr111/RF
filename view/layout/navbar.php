<?php
/**
 * navbar.php — R&F Automotores
 * Navbar superior con hamburguesa, título y acciones
 */
$pageTitle   = $pageTitle   ?? 'Dashboard';
$breadcrumbs = $breadcrumbs ?? []; // Array: [['label'=>'Inicio','url'=>'index.php'], ['label'=>'Cuotas']]
?>

<header class="top-navbar animate-fade-in-down" role="banner">

  <!-- ── Hamburguesa MOBILE (abre Offcanvas) ───────────────── -->
  <button
    class="btn-hamburger btn-hamburger-mobile"
    type="button"
    data-bs-toggle="offcanvas"
    data-bs-target="#sidebarOffcanvas"
    aria-controls="sidebarOffcanvas"
    aria-label="Abrir menú"
    aria-expanded="false"
  >
    <i class="bi bi-list" aria-hidden="true"></i>
  </button>

  <!-- ── Hamburguesa DESKTOP (colapsa sidebar fija) ────────── -->
  <button
    class="btn-hamburger btn-hamburger-desktop"
    type="button"
    id="toggleSidebarDesktop"
    aria-label="Colapsar menú lateral"
    aria-expanded="true"
    aria-controls="sidebar"
  >
    <i class="bi bi-list" aria-hidden="true"></i>
  </button>

  <!-- ── Título / Breadcrumb ───────────────────────────────── -->
  <div class="flex-grow-1 d-flex flex-column justify-content-center" style="min-width:0;">
    <span class="navbar-brand-text text-truncate">
      R<span>&amp</span>F<span> Automotores</span>
    </span>
    <?php if (!empty($breadcrumbs)): ?>
      <nav aria-label="breadcrumb" class="d-none d-md-block">
        <ol class="breadcrumb mb-0">
          <?php foreach ($breadcrumbs as $i => $crumb):
            $isLast = ($i === count($breadcrumbs) - 1);
          ?>
            <li class="breadcrumb-item <?= $isLast ? 'active' : '' ?>">
              <?php if (!$isLast && isset($crumb['url'])): ?>
                <a href="<?= htmlspecialchars($crumb['url']) ?>" style="color:inherit;text-decoration:none;">
                  <?= htmlspecialchars($crumb['label']) ?>
                </a>
              <?php else: ?>
                <?= htmlspecialchars($crumb['label']) ?>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ol>
      </nav>
    <?php endif; ?>
  </div>

  <!-- ── Acciones ─────────────────────────────────────────── -->
  <div class="navbar-actions">

    <!-- Notificaciones -->
    <button type="button" class="action-btn" aria-label="Notificaciones" title="Notificaciones"
       data-bs-toggle="modal" data-bs-target="#modalNotifications">
      <i class="bi bi-bell" aria-hidden="true"></i>
      <span class="badge-dot" aria-hidden="true"></span>
    </button>

    <div class="navbar-divider" aria-hidden="true"></div>

    <!-- Configuración -->
    <a href="index.php?c=config" class="action-btn" aria-label="Configuración" title="Configuración"
       data-bs-toggle="tooltip" data-bs-placement="bottom">
      <i class="bi bi-gear" aria-hidden="true"></i>
    </a>

    <!-- Pantalla completa -->
    <button class="action-btn" id="btnFullscreen" aria-label="Pantalla completa" title="Pantalla completa"
            data-bs-toggle="tooltip" data-bs-placement="bottom" style="border:none;">
      <i class="bi bi-fullscreen" aria-hidden="true"></i>
    </button>

  </div>

</header>

<!-- Modal Notificaciones -->
<div class="modal fade" id="modalNotifications" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-accent"><i class="bi bi-bell me-2"></i>Resumen del Día</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="notif-loading" class="p-4 text-center">
                    <div class="spinner-border text-accent" role="status"></div>
                </div>
                <div id="notif-content" class="d-none">
                    <!-- Resumen rápido -->
                    <div class="p-3 border-bottom bg-accent-dim">
                        <div class="row g-2 text-center">
                            <div class="col-6">
                                <div class="small    text-uppercase fw-bold" style="font-size:10px;">Vencen Hoy</div>
                                <div class="h5 mb-0 text-accent fw-bold" id="notif-vence-hoy">-</div>
                            </div>
                            <div class="col-6">
                                <div class="small    text-uppercase fw-bold" style="font-size:10px;">Cobro Estimado</div>
                                <div class="h5 mb-0 text-success fw-bold" id="notif-estimado">-</div>
                            </div>
                        </div>
                    </div>
                    <!-- Lista de atrasados -->
                    <div class="p-3">
                        <h6 class="small    text-uppercase fw-bold mb-3" style="font-size:10px;">Clientes Atrasados (Top 10)</h6>
                        <div id="notif-atrasados-list" class="list-group list-group-flush">
                            <!-- Items dinámicos -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="?c=cuotas" class="btn btn-rf ghost btn-sm w-100">Ver Gestión de Cuotas</a>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('modalNotifications').addEventListener('show.bs.modal', function () {
    const loading = document.getElementById('notif-loading');
    const content = document.getElementById('notif-content');

    loading.classList.remove('d-none');
    content.classList.add('d-none');

    fetch('?c=dashboard&a=getNotificaciones')
        .then(response => response.json())
        .then(data => {
            document.getElementById('notif-vence-hoy').textContent = data.vence_hoy_count;
            document.getElementById('notif-estimado').textContent = '₲ ' + new Intl.NumberFormat('de-DE').format(data.total_estimado);

            const list = document.getElementById('notif-atrasados-list');
            list.innerHTML = '';

            if (data.atrasados.length === 0) {
                list.innerHTML = '<div class="text-center    small py-3">No hay clientes atrasados</div>';
            } else {
                data.atrasados.forEach(c => {
                    const item = document.createElement('div');
                    item.className = 'list-group-item bg-transparent border-rf p-2 d-flex justify-content-between align-items-center';
                    item.innerHTML = `
                        <div style="min-width: 0;">
                            <div style="font-size: 11px; color:white;" class="fw-bold small">${c.nombre}</div>
                            <div class="  " style="font-size: 11px; color:white">${c.telefono }</div>
                        </div>
                        <div class="text-danger fw-bold small">₲ ${new Intl.NumberFormat('de-DE').format(c.deuda_total)}</div>
                    `;
                    list.appendChild(item);
                });
            }

            loading.classList.add('d-none');
            content.classList.remove('d-none');
        });
});
</script>