<?php
// view/dashboard/index.php
// Variables del controller:
// $cobros_hoy       → objeto {cantidad, total}
// $clientes_activos → int
// $cuotas_mes       → int  (pendientes este mes: hoy→fin de mes)
// $mora             → objeto {cantidad, saldo_total, mora_total}
// $cartera          → objeto {total_cuotas, pagadas, pendientes_mes, futuras, atrasadas, monto_total, monto_cobrado, monto_mora}
// $ultimos_cobros   → array
// $cuotas_urgentes  → array
// $ultimos_clientes → array
// $grafico_labels, $grafico_totales, $grafico_cantidad → JSON strings
?>

<!-- Page header -->
<div class="page-header mb-4">
    <h1 class="page-title">Dashboard</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Inicio</li>
        </ol>
    </nav>
</div>

<!-- ══════════════════════════════════════════════════════════
     FILA 1 — KPIs
══════════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4 row-stats-mobile">

    <!-- Cobros hoy -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card success">
            <div class="stat-icon green"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="stat-label">Cobros hoy</div>
                <div class="stat-value">
                    <?= number_format($cobros_hoy->total, 0, ',', '.') ?>
                </div>
                <div class="stat-sub"><?= $cobros_hoy->cantidad ?> pago(s)</div>
            </div>
        </div>
    </div>

    <!-- Clientes activos -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card primary">
            <div class="stat-icon blue"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-label">Clientes activos</div>
                <div class="stat-value"><?= $clientes_activos ?></div>
                <div class="stat-sub">Con financiación vigente</div>
            </div>
        </div>
    </div>

    <!-- Cuotas pendientes este mes -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card warning">
            <div class="stat-icon amber"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-label">Pendientes este mes</div>
                <div class="stat-value"><?= $cuotas_mes ?></div>
                <div class="stat-sub">Vencen antes del <?= date('d/m') ?></div>
            </div>
        </div>
    </div>

    <!-- En mora -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card danger">
            <div class="stat-icon red"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <div class="stat-label">En mora</div>
                <div class="stat-value"><?= $mora->cantidad ?></div>
                <div class="stat-sub">
                    Gs. <?= number_format($mora->mora_total, 0, ',', '.') ?>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════
     FILA 2 — Gráfico + Resumen cartera
══════════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">

    <!-- Gráfico -->
<!--     <div class="col-12 col-lg-7">
        <div class="card-rf h-100">
            <div class="card-header-rf">
                <h2 class="card-title-rf">
                    <i class="bi bi-bar-chart-line me-2 text-accent"></i>Cobros — últimos 6 meses
                </h2>
            </div>
            <div class="card-body-rf">
                <canvas id="chartCobros" height="110"></canvas>
            </div>
        </div>
    </div> -->

    <!-- Cartera -->
    <div class="col-12 col-lg-5">
        <div class="card-rf h-100">
            <div class="card-header-rf">
                <h2 class="card-title-rf">
                    <i class="bi bi-pie-chart me-2 text-accent"></i>Estado de cartera
                </h2>
            </div>
            <div class="card-body-rf">

                <?php
                $pct = $cartera->monto_total > 0
                    ? round(($cartera->monto_cobrado / $cartera->monto_total) * 100)
                    : 0;
                ?>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1" style="font-size:.75rem; color:var(--rf-muted);">
                        <span>Cobrado del total financiado</span>
                        <span style="color:var(--rf-success); font-weight:700;"><?= $pct ?>%</span>
                    </div>
                    <div class="prog-track">
                        <div class="prog-bar" style="width:<?= $pct ?>%"></div>
                    </div>
                </div>

                <div class="row g-2">

                    <div class="col-6">
                        <div style="background:var(--rf-surface2); border-radius:10px; padding:.8rem 1rem;">
                            <div style="font-size:.62rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--rf-muted); margin-bottom:.3rem;">
                                <i class="bi bi-check2-circle me-1" style="color:var(--rf-success);"></i>Pagadas
                            </div>
                            <div style="font-family:var(--rf-font-mono); font-size:1.35rem; font-weight:700; color:var(--rf-success); line-height:1;">
                                <?= $cartera->pagadas ?>
                            </div>
                            <div style="font-size:.68rem; color:var(--rf-muted); margin-top:.25rem;">
                                Gs. <?= number_format($cartera->monto_cobrado, 0, ',', '.') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div style="background:var(--rf-surface2); border-radius:10px; padding:.8rem 1rem;">
                            <div style="font-size:.62rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--rf-muted); margin-bottom:.3rem;">
                                <i class="bi bi-hourglass-split me-1" style="color:var(--rf-warning);"></i>Este mes
                            </div>
                            <div style="font-family:var(--rf-font-mono); font-size:1.35rem; font-weight:700; color:var(--rf-warning); line-height:1;">
                                <?= $cartera->pendientes_mes ?>
                            </div>
                            <div style="font-size:.68rem; color:var(--rf-muted); margin-top:.25rem;">
                                Por vencer
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div style="background:var(--rf-surface2); border-radius:10px; padding:.8rem 1rem;">
                            <div style="font-size:.62rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--rf-muted); margin-bottom:.3rem;">
                                <i class="bi bi-x-octagon me-1" style="color:var(--rf-danger);"></i>En mora
                            </div>
                            <div style="font-family:var(--rf-font-mono); font-size:1.35rem; font-weight:700; color:var(--rf-danger); line-height:1;">
                                <?= $cartera->atrasadas ?>
                            </div>
                            <div style="font-size:.68rem; color:var(--rf-muted); margin-top:.25rem;">
                                Gs. <?= number_format($cartera->monto_mora, 0, ',', '.') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div style="background:var(--rf-surface2); border-radius:10px; padding:.8rem 1rem;">
                            <div style="font-size:.62rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--rf-muted); margin-bottom:.3rem;">
                                <i class="bi bi-collection me-1" style="color:var(--rf-accent);"></i>Total cuotas
                            </div>
                            <div style="font-family:var(--rf-font-mono); font-size:1.35rem; font-weight:700; color:var(--rf-accent); line-height:1;">
                                <?= $cartera->total_cuotas ?>
                            </div>
                            <div style="font-size:.68rem; color:var(--rf-muted); margin-top:.25rem;">
                                Gs. <?= number_format($cartera->monto_total, 0, ',', '.') ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════
     FILA 3 — Alertas urgentes + Últimos clientes
══════════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">

    <!-- Cuotas urgentes -->
    <div class="col-12 col-lg-8">
        <div class="card-rf">
            <div class="card-header-rf">
                <h2 class="card-title-rf">
                    <i class="bi bi-bell me-2" style="color:var(--rf-danger);"></i>Cobros urgentes
                </h2>
                <a href="?c=cuotas&a=index" class="btn-accion btn-ver" style="font-size:.75rem;">
                    Ver todos <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body-rf p-0">
                <div class="table-responsive">
                    <table class="table" style="margin:0;">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Vehículo</th>
                                <th>Vencimiento</th>
                                <th>Saldo</th>
                                <th>Atraso</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($cuotas_urgentes)): ?>
                            <tr>
                                <td colspan="6" style="text-align:center; color:var(--rf-muted); padding:2rem;">
                                    <i class="bi bi-check-circle me-2" style="color:var(--rf-success);"></i>
                                    Sin cobros urgentes
                                </td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($cuotas_urgentes as $u):
                                $dias = (int) $u->dias_atraso;
                                if ($dias == 0)     { $bc = 'hoy';          $bl = '🕐 Hoy'; }
                                elseif ($dias >= 60) { $bc = 'muy-atrasado'; $bl = '🔴 ' . $dias . 'd'; }
                                else                 { $bc = 'atrasado';     $bl = '⚠ ' . $dias . 'd'; }
                            ?>
                            <tr>
                                <td>
                                    <a href="?c=clientes&a=detalle&id=<?= $u->id_cliente ?>" class="cliente-link">
                                        <?= htmlspecialchars($u->cliente_nombre) ?>
                                    </a>
                                </td>
                                <td style="color:var(--rf-muted); font-size:.82rem;"><?= htmlspecialchars($u->modelo_nombre) ?></td>
                                <td class="font-mono" style="font-size:.8rem; color:var(--rf-muted);">
                                    <?= date('d/m/Y', strtotime($u->fecha_vencimiento)) ?>
                                </td>
                                <td class="font-mono" style="font-weight:600; color:var(--rf-danger);">
                                    Gs. <?= number_format($u->saldo_pendiente, 0, ',', '.') ?>
                                </td>
                                <td>
                                    <span class="badge-estado <?= $bc ?>"><?= $bl ?></span>
                                </td>
                                <td>
                                    <div class="acciones-group">
                                        <a href="?c=cuotas&a=detalle&id=<?= $u->id_venta ?>" class="btn-accion btn-ver">
                                            <i class="bi bi-list-check"></i>
                                        </a>
                                        <button type="button" class="btn-accion btn-wa"
                                            onclick="abrirModalWA(
                                                '<?= addslashes($u->cliente_nombre) ?>',
                                                '<?= $u->telefono ?>',
                                                'Gs. <?= number_format($u->saldo_pendiente, 0, ',', '.') ?>',
                                                '<?= date('d/m/Y', strtotime($u->fecha_vencimiento)) ?>'
                                            )">
                                            <i class="bi bi-whatsapp"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Últimos clientes -->
    <div class="col-12 col-lg-4">
        <div class="card-rf h-100">
            <div class="card-header-rf">
                <h2 class="card-title-rf">
                    <i class="bi bi-person-plus me-2 text-accent"></i>Nuevos clientes
                </h2>
                <a href="?c=clientes&a=index" class="btn-accion btn-ver" style="font-size:.75rem;">
                    Ver todos <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body-rf">
                <?php if (empty($ultimos_clientes)): ?>
                    <p style="color:var(--rf-muted); font-size:.85rem; text-align:center; padding:1rem 0;">Sin registros.</p>
                <?php else: ?>
                <div style="display:flex; flex-direction:column; gap:.6rem;">
                    <?php foreach ($ultimos_clientes as $c): ?>
                    <div style="display:flex; align-items:center; gap:.75rem; padding:.6rem .75rem; background:var(--rf-surface2); border-radius:10px; border:1px solid var(--rf-border);">
                        <div style="width:34px;height:34px;border-radius:50%;background:var(--rf-accent-dim);color:var(--rf-accent);display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;flex-shrink:0;">
                            <?= mb_strtoupper(mb_substr($c->nombre, 0, 1)) ?>
                        </div>
                        <div style="min-width:0; flex:1;">
                            <div style="font-weight:600; font-size:.84rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <a href="?c=clientes&a=detalle&id=<?= $c->id_cliente ?>" class="cliente-link">
                                    <?= htmlspecialchars($c->nombre) ?>
                                </a>
                            </div>
                            <div style="font-size:.7rem; color:var(--rf-muted);">
                                📱 <?= $c->telefono ?>
                                <?php if ($c->total_ventas > 0): ?>
                                &nbsp;·&nbsp; <?= $c->total_ventas ?> venta(s)
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- ══════════════════════════════════════════════════════════
     FILA 4 — Últimos cobros
══════════════════════════════════════════════════════════ -->
<div class="card-rf mb-4">
    <div class="card-header-rf">
        <h2 class="card-title-rf">
            <i class="bi bi-clock-history me-2 text-accent"></i>Últimos cobros registrados
        </h2>
        <a href="?c=cobros&a=index" class="btn-accion btn-ver" style="font-size:.75rem;">
            Ver todos <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="card-body-rf p-0">
        <div class="table-responsive">
            <table class="table" id="tblCobros">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Vehículo</th>
                        <th>Cuota</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Fecha pago</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($ultimos_cobros)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center; color:var(--rf-muted); padding:2rem;">
                            Sin cobros registrados.
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($ultimos_cobros as $c): ?>
                    <tr>
                        <td>
                            <a href="?c=clientes&a=detalle&id=<?= $c->id_cliente ?>" class="cliente-link">
                                <?= htmlspecialchars($c->cliente_nombre) ?>
                            </a>
                        </td>
                        <td style="color:var(--rf-muted); font-size:.82rem;"><?= htmlspecialchars($c->modelo_nombre) ?></td>
                        <td class="font-mono" style="font-size:.8rem; color:var(--rf-muted);">
                            <?= str_pad($c->numero_cuota, 2, '0', STR_PAD_LEFT) ?> / <?= $c->total_cuotas ?>
                        </td>
                        <td class="font-mono" style="font-weight:600; color:var(--rf-success);">
                            Gs. <?= number_format($c->monto_entregado, 0, ',', '.') ?>
                        </td>
                        <td>
                            <span class="badge-rf primary" style="font-size:.68rem;"><?= htmlspecialchars($c->metodo_pago) ?></span>
                        </td>
                        <td class="font-mono" style="font-size:.8rem; color:var(--rf-muted);">
                            <?= date('d/m/Y', strtotime($c->fecha_pago)) ?>
                        </td>
                        <td>
                            <a href="?c=cuotas&a=detalle&id=<?= $c->id_venta ?>" class="btn-accion btn-ver">
                                <i class="bi bi-list-check"></i>
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

<!-- Modal WhatsApp -->
<div class="modal fade" id="modalWA" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-whatsapp" style="color:#25d366; font-size:1.1rem;"></i>
                    Enviar recordatorio
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="modal-info-box mb-3">
                    <strong id="wa-nombre">—</strong><br>
                    <span>📱 +595 <span id="wa-telefono">—</span></span>
                    <span class="ms-3">💰 <span id="wa-deuda">—</span></span>
                </div>
                <label class="form-label">Mensaje</label>
                <textarea class="form-control" id="wa-mensaje" rows="5"></textarea>
                <div style="font-size:.72rem; color:var(--rf-muted); margin-top:.5rem;">
                    Podés editar el mensaje antes de enviar.
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="wa-link" target="_blank" class="btn-wa-send">
                    <i class="bi bi-whatsapp"></i> Abrir en WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {

    // Tabla últimos cobros
    if ($('#tblCobros tbody tr').length && $('#tblCobros tbody tr td').length > 1) {
        $('#tblCobros').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
            pageLength: 10,
            dom: 'lrtip',
            order: [[5, 'desc']]
        });
    }

    // Chart.js — cobros por mes
    const ctx = document.getElementById('chartCobros');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= $grafico_labels ?>,
                datasets: [
                    {
                        label: 'Total cobrado (Gs.)',
                        data: <?= $grafico_totales ?>,
                        backgroundColor: 'rgba(108,127,255,0.25)',
                        borderColor: 'rgba(108,127,255,0.85)',
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Cantidad de cobros',
                        data: <?= $grafico_cantidad ?>,
                        type: 'line',
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34,197,94,0.08)',
                        borderWidth: 2,
                        pointBackgroundColor: '#22c55e',
                        pointRadius: 4,
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y2'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { labels: { color: '#7b82a0', font: { size: 12 } } },
                    tooltip: {
                        callbacks: {
                            label: (c) => c.datasetIndex === 0
                                ? ' Gs. ' + Number(c.raw).toLocaleString('es-PY')
                                : ' ' + c.raw + ' cobro(s)'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#7b82a0', font: { size: 11 } },
                        grid:  { color: 'rgba(255,255,255,0.04)' }
                    },
                    y: {
                        position: 'left',
                        ticks: {
                            color: '#7b82a0', font: { size: 11 },
                            callback: v => 'Gs. ' + (v / 1000000).toFixed(1) + 'M'
                        },
                        grid: { color: 'rgba(255,255,255,0.04)' }
                    },
                    y2: {
                        position: 'right',
                        ticks: { color: '#22c55e', font: { size: 11 } },
                        grid:  { display: false }
                    }
                }
            }
        });
    }
});

function abrirModalWA(nombre, telefono, deuda, vencimiento) {
    $('#wa-nombre').text(nombre);
    $('#wa-telefono').text(telefono);
    $('#wa-deuda').text(deuda);

    const msg = `Estimado/a *${nombre}*, le recordamos que tiene una cuota pendiente de *${deuda}* con vencimiento el *${vencimiento}*. Por favor, comuníquese con nosotros para coordinar el pago. ¡Muchas gracias!`;

    $('#wa-mensaje').val(msg);
    $('#wa-link').attr('href', 'https://wa.me/595' + telefono + '?text=' + encodeURIComponent(msg));

    $('#wa-mensaje').off('input').on('input', function () {
        $('#wa-link').attr('href', 'https://wa.me/595' + telefono + '?text=' + encodeURIComponent($(this).val()));
    });

    new bootstrap.Modal(document.getElementById('modalWA')).show();
}
</script>