<?php
// view/cobros/index.php
// Variables: $cobrosHoy, $cobrosSemana, $cobrosMes (arrays de objetos)
?>

<div class="container-fluid py-3">

    <!-- ── Nav pills ── -->
    <div class="nav-wrapper mb-3">
        <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#tab-hoy" type="button">
                    <i class="bi bi-calendar-event me-1"></i>Hoy
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-semana" type="button">
                    <i class="bi bi-calendar-range me-1"></i>Semana
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#tab-mes" type="button">
                    <i class="bi bi-calendar-check me-1"></i>Mes
                </button>
            </li>
        </ul>
    </div>

    <!-- ── Tab content ── -->
    <div class="tab-content">

        <div class="tab-pane fade show active" id="tab-hoy">
            <?php renderTabCobros('tblHoy', $cobrosHoy); ?>
        </div>

        <div class="tab-pane fade" id="tab-semana">
            <?php renderTabCobros('tblSemana', $cobrosSemana); ?>
        </div>

        <div class="tab-pane fade" id="tab-mes">
            <?php renderTabCobros('tblMes', $cobrosMes); ?>
        </div>

    </div>
</div>

<?php
/**
 * Renderiza una pestaña de cobros:
 *   - Header con total recaudado
 *   - Cards mobile (PHP puro, siempre renderizadas)
 *   - Tabla DataTable (visible solo en desktop)
 */
function renderTabCobros(string $tableId, array $data): void {
    $total = array_sum(array_column($data, 'monto_entregado'));
?>
<div class="card-rf overflow-hidden">

    <!-- Header con total -->
    <div class="cobros-tab-header">
        <span class="tab-label"><i class="bi bi-cash-stack me-2"></i>Detalle de ingresos</span>
        <span class="tab-total">
            Gs. <?= number_format($total, 0, ',', '.') ?>
        </span>
    </div>

    <!-- ► CARDS MOBILE -->
    <div class="mobile-cards-list" id="cards-<?= $tableId ?>">

        <?php if (empty($data)): ?>
        <div style="text-align:center; color:var(--rf-muted); padding:1.5rem; font-size:.85rem;">
            <i class="bi bi-inbox me-2"></i>Sin cobros registrados.
        </div>
        <?php else: ?>

        <?php foreach ($data as $p): ?>
        <div class="mc mc-cobro">

            <!-- Nombre + hora -->
            <div class="mc-top">
                <span class="mc-title"><?= htmlspecialchars($p->cliente_nombre) ?></span>
                <span class="mc-hora"><?= date('H:i', strtotime($p->created_at)) ?></span>
            </div>

            <!-- Info secundaria -->
            <div class="mc-info">
                <span>
                    Cuota <strong style="color:var(--rf-text);"><?= str_pad($p->numero_cuota, 2, '0', STR_PAD_LEFT) ?></strong>
                    &nbsp;·&nbsp; Venta <strong style="color:var(--rf-text);">#<?= $p->id_venta ?></strong>
                </span>
                <span class="badge-cuota hoy" style="font-size:.62rem; padding:.2rem .55rem;">
                    <?= htmlspecialchars($p->metodo_pago) ?>
                </span>
            </div>

            <!-- Monto + acciones -->
            <div class="mc-foot" style="justify-content:space-between; align-items:center;">
                <span class="mc-amount positive">
                    Gs. <?= number_format($p->monto_entregado, 0, ',', '.') ?>
                </span>
                <a href="?c=cuotas&a=imprimirRecibo&id=<?= $p->id_cuota ?>"
                   target="_blank"
                   class="btn-accion-print"
                   title="Imprimir recibo">
                    <i class="bi bi-printer-fill"></i> Recibo
                </a>
            </div>

        </div>
        <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <!-- ► TABLA DESKTOP -->
    <div class="table-card has-mobile-cards" style="border:none; border-radius:0;">
        <div class="table-responsive p-2">
            <table class="table table-hover" id="<?= $tableId ?>">
                <thead>
                    <tr>
                        <th>Hora / Fecha</th>
                        <th>Cliente</th>
                        <th>Venta #</th>
                        <th>Cuota</th>
                        <th>Método</th>
                        <th>Monto</th>
                        <th class="text-center">Recibo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; color:var(--rf-muted); padding:2rem;">
                            <i class="bi bi-inbox me-2"></i>Sin cobros registrados.
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($data as $p): ?>
                    <tr>
                        <td class="font-mono" style="font-size:.8rem; color:var(--rf-muted);">
                            <?= date('H:i | d/m', strtotime($p->created_at)) ?>
                        </td>
                        <td>
                            <span class="cliente-link" style="font-weight:600;">
                                <?= htmlspecialchars($p->cliente_nombre) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge-rf primary">#<?= $p->id_venta ?></span>
                        </td>
                        <td style="font-family:var(--rf-font-mono); font-size:.82rem; color:var(--rf-muted);">
                            Cuota <?= str_pad($p->numero_cuota, 2, '0', STR_PAD_LEFT) ?>
                        </td>
                        <td>
                            <span class="badge-cuota hoy"><?= htmlspecialchars($p->metodo_pago) ?></span>
                        </td>
                        <td style="font-family:var(--rf-font-mono); font-weight:600; color:var(--rf-success);">
                            Gs. <?= number_format($p->monto_entregado, 0, ',', '.') ?>
                        </td>
                        <td class="text-center">
                            <a href="?c=cuotas&a=imprimirRecibo&id=<?= $p->id_cuota ?>"
                               target="_blank"
                               class="btn-accion-print">
                                <i class="bi bi-printer-fill"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php } ?>

<script>
$(document).ready(function () {

    const dtConfig = {
        language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
        pageLength: 10,
        order: [[0, 'desc']],
        dom: 'lrtip'
    };

    $('#tblHoy').DataTable(dtConfig);
    $('#tblSemana').DataTable(dtConfig);
    $('#tblMes').DataTable(dtConfig);

    // Ajustar columnas al cambiar de pestaña
    $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function () {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });

});
</script>