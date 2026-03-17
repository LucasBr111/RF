<?php
/**
 * sidebar_inner.php — Contenido compartido del sidebar
 * Usado tanto por el Offcanvas (mobile) como por la sidebar fija (desktop)
 */
$activePage = $activePage ?? '';

// Helper: retorna 'active' si la página coincide
if (!function_exists('isActive')) {
    function isActive(string $page, string $current): string {
        return $page === $current ? 'active' : '';
    }
}

$menus = [
    [
        'section' => null,
        'items' => [
            ['page' => 'inicio',    'label' => 'Inicio',           'icon' => 'bi bi-speedometer2', 'url' => 'index.php'],
        ]
    ],
    [
        'section' => 'Gestión',
        'items' => [
            ['page' => 'cuotas',    'label' => 'Gestión de Cuotas', 'icon' => 'bi bi-wallet2',      'url' => 'index.php?c=cuotas'],
            ['page' => 'pagos',    'label' => 'Cobros del Día',    'icon' => 'bi bi-cash-coin',    'url' => 'index.php?c=pagos'],
        ]
    ],
    [
        'section' => 'Operaciones',
        'items' => [
            ['page' => 'clientes',  'label' => 'Clientes',          'icon' => 'bi bi-people',       'url' => 'index.php?c=clientes'],
            ['page' => 'ventas',    'label' => 'Registrar Venta',   'icon' => 'bi bi-cart-plus',    'url' => 'index.php?c=ventas'],
            ['page' => 'vehiculos', 'label' => 'Vehículos',         'icon' => 'bi bi-car-front',    'url' => 'index.php?c=vehiculos'],
            ['page' => 'modelos',   'label' => 'Modelos de Vehículos', 'icon' => 'bi bi-card-list', 'url' => 'index.php?c=modelos'],
        ]
    ],
  
];
?>

<div class="sidebar-inner">

  <!-- ── Brand / Logo ─────────────────────────────────────── -->
  <a href="index.php" class="sidebar-brand" aria-label="R&F Automotores - Inicio">
    <img
      src="assets/img/logo.png"
      alt="Logo R&F Automotores"
      class="sidebar-brand-logo"
      onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
    >
    <!-- Fallback si no existe logo.png -->
    <div class="sidebar-brand-logo-fallback" style="display:none;" aria-hidden="true">
      <i class="bi bi-car-front-fill"></i>
    </div>
    <div class="sidebar-brand-text">
      <span class="brand-name">R&amp;F Automotores</span>
      <span class="brand-sub">Sistema de Cuotas</span>
    </div>
  </a>

  <!-- ── Navegación ───────────────────────────────────────── -->
  <nav class="sidebar-nav" aria-label="Menú de navegación">
    <?php foreach ($menus as $group): ?>

      <?php if ($group['section']): ?>
        <div class="sidebar-section-label"><?= htmlspecialchars($group['section']) ?></div>
      <?php endif; ?>

      <?php foreach ($group['items'] as $item):
        $activeClass = isActive($item['page'], $activePage);
      ?>
        <div class="nav-item-rf animate-fade-in-left">
          <a
            href="<?= htmlspecialchars($item['url']) ?>"
            class="nav-link-rf <?= $activeClass ?>"
            data-tooltip="<?= htmlspecialchars($item['label']) ?>"
            aria-label="<?= htmlspecialchars($item['label']) ?>"
            aria-current="<?= $activeClass ? 'page' : 'false' ?>"
          >
            <i class="<?= htmlspecialchars($item['icon']) ?> nav-icon" aria-hidden="true"></i>
            <span class="nav-label"><?= htmlspecialchars($item['label']) ?></span>
          </a>
        </div>
      <?php endforeach; ?>

    <?php endforeach; ?>
  </nav>

  <!-- ── Footer del Sidebar (toggle collapse — solo desktop) ── -->
  <div class="sidebar-footer d-none d-lg-block">
    <button id="toggleSidebar" aria-label="Colapsar menú" title="Colapsar / expandir menú">
      <i class="bi bi-chevron-double-left toggle-icon" aria-hidden="true"></i>
      <span class="toggle-label">Colapsar menú</span>
    </button>
  </div>

</div><!-- /.sidebar-inner -->