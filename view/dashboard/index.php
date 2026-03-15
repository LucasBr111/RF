<?php
// view/dashboard/index.php
// Variables disponibles desde el controller:
// $cobros_hoy, $clientes_activos, $cuotas_hoy, $cuotas_vencidas
// $ingresos, $egresos_mes, $cartera
// $ultimos_cobros, $cuotas_urgentes, $ultimos_clientes
// $grafico_labels, $grafico_totales, $grafico_cantidad
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
     FILA 1 — KPI Cards
══════════════════════════════════════════════════════════ -->
<div class="row g-3 mb-4">

    <!-- Cobros del día -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card success">
            <div class="stat-icon green"><i class="bi bi-cash-coin"></i></div>
            <div>
                <div class="stat-label">Cobros hoy</div>
                <div class="stat-value">
                    <?= number_format($cobros_hoy->total, 0, ',', '.') ?>
                </div>
                <div class="stat-sub"><?= $cobros_hoy->cantidad ?> pago(s) registrado(s)</div>
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

    <!-- Cuotas que vencen hoy -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card warning">
            <div class="stat-icon amber"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-label">Vencen hoy</div>
                <div class="stat-value"><?= $cuotas_hoy ?></div>
                <div class="stat-sub">Cuotas a cobrar</div>
            </div>
        </div>
    </div>

    <!-- Cuotas vencidas / mora -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card danger">
            <div class="stat-icon red"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <div class="stat-label">En mora</div>
                <div class="stat-value"><?= $cuotas_vencidas->cantidad ?></div>
                <div class="stat-sub">
                    Gs. <?= number_format($cuotas_vencidas->mora_total, 0, ',', '.') ?>
                </div>
            </div>
        </div>
    </div>

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

<!-- ══════════════════════════════════════════════════════════
     FILA 4 — Últimos cobros (tabla completa)
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
                        <th>Monto pagado</th>
                        <th>Fecha pago</th>
                        <th>Vencía</th>
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
                        <td style="font-family:var(--rf-font-mono); font-size:.8rem; color:var(--rf-muted);">
                            <?= str_pad($c->numero_cuota, 2, '0', STR_PAD_LEFT) ?> / <?= $c->total_cuotas ?>
                        </td>
                        <td style="font-family:var(--rf-font-mono); font-weight:600; color:var(--rf-success);">
                            Gs. <?= number_format($c->monto_cuota, 0, ',', '.') ?>
                        </td>
                        <td style="font-family:var(--rf-font-mono); font-size:.8rem;">
                            <?= date('d/m/Y', strtotime($c->fecha_pago)) ?>
                        </td>
                        <td style="font-family:var(--rf-font-mono); font-size:.8rem; color:var(--rf-muted);">
                            <?= date('d/m/Y', strtotime($c->fecha_vencimiento)) ?>
                        </td>
                        <td>
                            <a href="?c=cuotas&a=detalle&id=<?= $c->id_venta ?>" class="btn-accion btn-ver" title="Ver cuotas">
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

<!-- ══════════════════════════════════════════════════════════
     MODAL WHATSAPP (reutilizado desde cuotas)
══════════════════════════════════════════════════════════ -->
<div class="modal fade" id="modalWA" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span style="color:#25d366;font-size:1.2rem;"><i class="bi bi-whatsapp"></i></span>
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
                <a href="#" id="wa-link" target="_blank" class="btn-wa-send" onclick="$('#modalWA').modal('hide')">
                    <i class="bi bi-whatsapp"></i> Abrir en WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     SCRIPTS
══════════════════════════════════════════════════════════ -->
<script>
$(document).ready(function () {

    // ── DataTable últimos cobros ──
    if ($('#tblCobros tbody tr td').length > 1) {
        $('#tblCobros').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
            pageLength: 10,
            dom: 'lrtip',
            order: [[4, 'desc']]
        });
    }

    // ── Chart.js — cobros por mes ──
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
                        borderColor: 'rgba(108,127,255,0.9)',
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Cantidad de cobros',
                        data: <?= $grafico_cantidad ?>,
                        type: 'line',
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34,197,94,0.1)',
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
                    legend: {
                        labels: { color: '#7b82a0', font: { size: 12 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                if (ctx.datasetIndex === 0) {
                                    return ' Gs. ' + Number(ctx.raw).toLocaleString('es-PY');
                                }
                                return ' ' + ctx.raw + ' cobro(s)';
                            }
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
                            callback: v => 'Gs. ' + (v/1000000).toFixed(1) + 'M'
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

// ── Modal WhatsApp ──
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