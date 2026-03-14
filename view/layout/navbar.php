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
      R&amp;<span>F</span> Automotores
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

    <!-- Notificaciones (placeholder para implementación futura) -->
    <a href="#" class="action-btn" aria-label="Notificaciones" title="Notificaciones"
       data-bs-toggle="tooltip" data-bs-placement="bottom">
      <i class="bi bi-bell" aria-hidden="true"></i>
      <span class="badge-dot" aria-hidden="true"></span>
    </a>

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