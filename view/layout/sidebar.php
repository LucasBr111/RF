<?php
/**
 * sidebar.php — R&F Automotores
 * Sidebar con doble comportamiento:
 *   - Mobile  (< 992px): Offcanvas de Bootstrap
 *   - Desktop (≥ 992px): Sidebar fija colapsable
 */

// Detectar la página activa (el controlador debe setear $activePage)
$activePage = $activePage ?? 'Dashboard';
?>

<!-- ══════════════════════════════════════════════════════════
     MOBILE — Offcanvas (Bootstrap nativo)
     Disparado por btn-hamburger-mobile en navbar.php
══════════════════════════════════════════════════════════════ -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
  <div class="offcanvas-header" style="padding: 0; border: none;">
    <!-- El contenido lo renderiza sidebar-inner -->
  </div>
  <div class="offcanvas-body p-0">
    <?php include __DIR__ . '/sidebar_inner.php'; ?>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     DESKTOP — Sidebar fija
══════════════════════════════════════════════════════════════ -->
<nav id="sidebar" aria-label="Menú principal">
  <?php include __DIR__ . '/sidebar_inner.php'; ?>
</nav>