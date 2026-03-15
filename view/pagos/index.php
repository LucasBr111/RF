<div class="container-fluid py-4">
    <div class="nav-wrapper mb-4">
        <ul class="nav nav-pills nav-fill p-1 bg-surface border shadow-sm rounded-pill" id="pills-tab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active rounded-pill fw-bold" data-bs-toggle="pill" data-bs-target="#tab-hoy">
                    <i class="bi bi-calendar-event me-2"></i>Cobros de Hoy
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link rounded-pill fw-bold" data-bs-toggle="pill" data-bs-target="#tab-semana">
                    <i class="bi bi-calendar-range me-2"></i>Esta Semana
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link rounded-pill fw-bold" data-bs-toggle="pill" data-bs-target="#tab-mes">
                    <i class="bi bi-calendar-check me-2"></i>Resumen Mensual
                </button>
            </li>
        </ul>
    </div>

    <div class="tab-content border-0">
        <div class="tab-pane fade show active" id="tab-hoy">
            <?php renderTablaCobros('tblHoy', $cobrosHoy); ?>
        </div>

        <div class="tab-pane fade" id="tab-semana">
            <?php renderTablaCobros('tblSemana', $cobrosSemana); ?>
        </div>

        <div class="tab-pane fade" id="tab-mes">
            <?php renderTablaCobros('tblMes', $cobrosMes); ?>
        </div>
    </div>
</div>

<?php 
/** * Función para renderizar las tablas de forma consistente
 */
function renderTablaCobros($id, $data) { 
    $totalRecaudado = array_sum(array_column($data, 'monto_entregado'));
?>
    <div class="card bg-surface border shadow-lg rounded-16 overflow-hidden">
        <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center p-3">
            <span class="res-label m-0 text-accent">Detalle de ingresos</span>
            <div class="h5 m-0 fw-bold text-success">
                Total: <span class="mono">Gs. <?= number_format($totalRecaudado, 0, ',', '.') ?></span>
            </div>
        </div>
        <div class="table-responsive table-responsive-mobile p-3">
            <table class="table table-hover align-middle display nowrap w-100 datatable-custom" id="<?= $id ?>">
                <thead>
                    <tr>
                        <th>Hora/Fecha</th>
                        <th>Cliente</th>
                        <th>Venta #</th>
                        <th>Cuota</th>
                        <th>Método</th>
                        <th>Monto Entregado</th>
                        <th class="text-center">Recibo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data as $p): ?>
                    <tr>
                        <td data-label="Hora/Fecha" class="mono"><?= date('H:i | d/m', strtotime($p->created_at)) ?></td>
                        <td data-label="Cliente" class="fw-bold"><?= htmlspecialchars($p->cliente_nombre) ?></td>
                        <td data-label="Venta #"><span class="badge bg-dark border text-muted">#<?= $p->id_venta ?></span></td>
                        <td data-label="Cuota">Cuota <?= str_pad($p->numero_cuota, 2, '0', STR_PAD_LEFT) ?></td>
                        <td data-label="Método"><span class="badge-cuota hoy"><?= $p->metodo_pago ?></span></td>
                        <td data-label="Monto" class="text-success fw-bold mono">Gs. <?= number_format($p->monto_entregado, 0, ',', '.') ?></td>
                        <td data-label="Recibo" class="text-center">
                            <a href="?c=cuotas&a=imprimirRecibo&id=<?= $p->id_cuota ?>" target="_blank" class="btn-accion-print">
                                <i class="bi bi-printer-fill"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>

<script>
    $(document).ready(function() {
    const dtConfig = {
        language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
        pageLength: 10,
        order: [[0, 'desc']], // Los más recientes primero
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf'], // Opcional por si quieres exportar reportes
        responsive: true
    };

    // Inicializamos las 3 tablas
    const tableHoy = $('#tblHoy').DataTable(dtConfig);
    const tableSemana = $('#tblSemana').DataTable(dtConfig);
    const tableMes = $('#tblMes').DataTable(dtConfig);

    // CRITICO: Ajustar columnas al cambiar de pestaña
    $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function (event) {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    });
});
</script>