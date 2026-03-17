<?php
/**
 * footer.php — R&F Automotores
 * Incluir al final de cada vista: include "view/layout/footer.php";
 */
$currentYear = date('Y');
?>

    </div><!-- /#main-content -->

    <!-- ══════════════════════════════════════════════════════
         FOOTER
    ══════════════════════════════════════════════════════════ -->
    <footer id="footer" role="contentinfo">
      <div>
        <span class="footer-brand">R&amp;F Automotores</span>
        <span class="ms-2">— Sistema de Gestión de Cuotas</span>
      </div>
      <div>
        <i class="bi bi-c-circle me-1" aria-hidden="true"></i>
        <?= $currentYear ?> Todos los derechos reservados
      </div>
    </footer>

  </div><!-- /#content -->
</div><!-- /#wrapper -->


<!-- ══════════════════════════════════════════════════════════
     JS — Orden de carga:
     1. jQuery (DataTables lo requiere)
     2. Bootstrap 5.3
     3. DataTables + extensiones
     4. SweetAlert2
     5. Scripts propios
══════════════════════════════════════════════════════════════ -->



<!-- 2. Bootstrap 5.3 Bundle (incluye Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<!-- 3a. DataTables core -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<!-- 3b. DataTables Responsive -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- 3c. DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>

<!-- 3d. Export: Excel, PDF, Print -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<!-- 4. SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- ══════════════════════════════════════════════════════════
     SCRIPTS PROPIOS — Sidebar, Navbar, DataTables Global
══════════════════════════════════════════════════════════════ -->
<script>
$(function () {

  /* ──────────────────────────────────────────────────────────
     1. SIDEBAR — Toggle collapse (desktop)
  ────────────────────────────────────────────────────────── */
  const sidebar         = document.getElementById('sidebar');
  const COLLAPSED_KEY   = 'rf_sidebar_collapsed';

  // Restaurar estado guardado en localStorage
  if (sidebar && localStorage.getItem(COLLAPSED_KEY) === '1') {
    sidebar.classList.add('collapsed');
    const content = document.getElementById('content');
    if (content) content.classList.add('sidebar-collapsed');
  }

  function toggleSidebar() {
    if (!sidebar) return;
    const isCollapsed = sidebar.classList.toggle('collapsed');
    localStorage.setItem(COLLAPSED_KEY, isCollapsed ? '1' : '0');

    // Toggle clase en content para ajustar margin
    const content = document.getElementById('content');
    if (content) content.classList.toggle('sidebar-collapsed', isCollapsed);

    // Actualizar aria
    const btn = document.getElementById('toggleSidebarDesktop');
    if (btn) btn.setAttribute('aria-expanded', isCollapsed ? 'false' : 'true');
  }

  // Botón en footer del sidebar
  const toggleBtn = document.getElementById('toggleSidebar');
  if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);

  // Botón hamburguesa desktop en navbar
  const toggleBtnDesktop = document.getElementById('toggleSidebarDesktop');
  if (toggleBtnDesktop) toggleBtnDesktop.addEventListener('click', toggleSidebar);

  /* ──────────────────────────────────────────────────────────
     2. FULLSCREEN
  ────────────────────────────────────────────────────────── */
  const btnFs = document.getElementById('btnFullscreen');
  if (btnFs) {
    btnFs.addEventListener('click', function () {
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(() => {});
        this.querySelector('i').className = 'bi bi-fullscreen-exit';
        this.setAttribute('title', 'Salir de pantalla completa');
      } else {
        document.exitFullscreen();
        this.querySelector('i').className = 'bi bi-fullscreen';
        this.setAttribute('title', 'Pantalla completa');
      }
    });
    document.addEventListener('fullscreenchange', function () {
      if (!document.fullscreenElement && btnFs) {
        btnFs.querySelector('i').className = 'bi bi-fullscreen';
      }
    });
  }

  /* ──────────────────────────────────────────────────────────
     3. TOOLTIPS DE BOOTSTRAP
  ────────────────────────────────────────────────────────── */
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
    new bootstrap.Tooltip(el, { trigger: 'hover' });
  });

  /* ──────────────────────────────────────────────────────────
     4. DATATABLES — Inicialización global automática
        Aplica a cualquier <table class="datatable">
  ────────────────────────────────────────────────────────── */

  // Idioma español
  const dtLang = {
    decimal:        ',',
    thousands:      '.',
    emptyTable:     'No hay datos disponibles',
    info:           'Mostrando _START_ a _END_ de _TOTAL_ registros',
    infoEmpty:      'Mostrando 0 a 0 de 0 registros',
    infoFiltered:   '(filtrado de _MAX_ registros totales)',
    infoPostFix:    '',
    lengthMenu:     'Mostrar _MENU_ registros',
    loadingRecords: 'Cargando...',
    processing:     'Procesando...',
    search:         'Buscar:',
    searchPlaceholder: 'Buscar...',
    zeroRecords:    'No se encontraron coincidencias',
    paginate: {
      first:    '<i class="bi bi-chevron-double-left"></i>',
      last:     '<i class="bi bi-chevron-double-right"></i>',
      next:     '<i class="bi bi-chevron-right"></i>',
      previous: '<i class="bi bi-chevron-left"></i>'
    },
    aria: {
      orderable:        'Ordenar por esta columna',
      orderableReverse: 'Invertir orden de esta columna'
    },
    buttons: {
      excel:  '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
      pdf:    '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
      print:  '<i class="bi bi-printer me-1"></i> Imprimir',
      copy:   '<i class="bi bi-clipboard me-1"></i> Copiar',
      csv:    '<i class="bi bi-file-earmark-csv me-1"></i> CSV'
    }
  };

  // Botones de exportación
  const dtButtons = [
    {
      extend:    'excelHtml5',
      text:      '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
      className: 'btn btn-sm',
      title:     'RF_Automotores_' + new Date().toLocaleDateString('es-PY').replaceAll('/', '-'),
      exportOptions: { columns: ':not(.no-export)' }
    },
    {
      extend:    'pdfHtml5',
      text:      '<i class="bi bi-file-earmark-pdf me-1"></i> PDF',
      className: 'btn btn-sm',
      title:     'RF Automotores',
      orientation: 'landscape',
      pageSize:  'A4',
      exportOptions: { columns: ':not(.no-export)' }
    },
    {
      extend:    'print',
      text:      '<i class="bi bi-printer me-1"></i> Imprimir',
      className: 'btn btn-sm',
      title:     'RF Automotores',
      exportOptions: { columns: ':not(.no-export)' }
    }
  ];

  // Opciones por defecto compartidas
  const dtDefaults = {
    language:    dtLang,
    responsive:  true,
    pageLength:  15,
    lengthMenu:  [[10, 15, 25, 50, 100, -1], [10, 15, 25, 50, 100, 'Todos']],
    dom:
      "<'row align-items-center mb-3'" +
        "<'col-12 col-md-4 mb-2 mb-md-0'l>" +
        "<'col-12 col-md-4 mb-2 mb-md-0 text-md-center'B>" +
        "<'col-12 col-md-4'f>" +
      ">" +
      "<'row'<'col-12'tr>>" +
      "<'row align-items-center mt-3'" +
        "<'col-12 col-md-5'i>" +
        "<'col-12 col-md-7 d-flex justify-content-md-end'p>" +
      ">",
    buttons:     dtButtons,
    autoWidth:   false,
    stateSave:   false,
    processing:  true,
    order:       [[0, 'desc']]
  };

  // Inicializar cada tabla con clase .datatable
  $('table.datatable').each(function () {
    // Combinar opciones base con data-attributes opcionales
    const extraOpts = $(this).data('dt-options') || {};
    $(this).DataTable($.extend(true, {}, dtDefaults, extraOpts));
  });

  /* ──────────────────────────────────────────────────────────
     5. SWEETALERT2 — Defaults globales (modo oscuro)
  ────────────────────────────────────────────────────────── */
  const SwalDark = Swal.mixin({
    background:         '#1a1d24',
    color:              '#f0f4f8',
    confirmButtonColor: '#22c55e',
    cancelButtonColor:  'rgba(255,255,255,.08)',
    iconColor:          '#22c55e',
    customClass: {
      popup:         'swal2-popup',
      confirmButton: 'btn px-4',
      cancelButton:  'btn px-4 ms-2',
    },
    buttonsStyling: false
  });

  // Exponer globalmente
  window.SwalDark = SwalDark;

  /* ──────────────────────────────────────────────────────────
     6. HELPER GLOBAL: confirmar acción destructiva
  ────────────────────────────────────────────────────────── */
  window.rfConfirm = function (opts) {
    const defaults = {
      title:             '¿Estás seguro?',
      text:              'Esta acción no se puede deshacer.',
      icon:              'warning',
      showCancelButton:  true,
      confirmButtonText: 'Sí, continuar',
      cancelButtonText:  'Cancelar',
    };
    return SwalDark.fire(Object.assign(defaults, opts));
  };

}); // END document.ready
</script>

<script src="assets/js/finanzas.js"></script>

<!-- JS adicional por vista (opcional) -->
<?php if (isset($extraJs)) echo $extraJs; ?>

</body>
</html>