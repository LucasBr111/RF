<?php
// view/cuotas/detalle.php
// Variables: $venta, $cuotas_detalle, $resumen
?>
<?php
// view/cuotas/detalle.php
// Variables esperadas: $venta, $cuotas_detalle, $resumen
// $venta: objeto con id_venta, cliente_nombre, telefono, modelo_nombre, monto_total
// $cuotas_detalle: array de cuotas con campos: numero_cuota, fecha_vencimiento, monto, monto_pagado, estado
// $resumen: array con total_cuotas, pagadas, pendientes, mora_total, proxima_cuota
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap');

    :root {
        --rf-bg: #0f1117;
        --rf-surface: #1a1d27;
        --rf-surface2: #22263a;
        --rf-border: rgba(255, 255, 255, 0.07);
        --rf-text: #e8eaf0;
        --rf-muted: #7b82a0;
        --rf-accent: #6c7fff;
        --verde: #22c55e;
        --amarillo: #f59e0b;
        --rojo: #ef4444;
        --azul: #3b82f6;
        --cyan: #06b6d4;
    }

    .detalle-header {
        background: var(--rf-surface);
        border: 1px solid var(--rf-border);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .detalle-header .cliente-info h4 {
        font-weight: 700;
        font-size: 1.25rem;
        margin-bottom: .25rem;
        color: var(--rf-text);
    }

    .detalle-header .cliente-info p {
        font-size: .82rem;
        color: var(--rf-muted);
        margin: 0;
    }

    .detalle-header .cliente-info p span {
        color: var(--rf-text);
        font-weight: 500;
    }

    /* ---- RESUMEN CARDS ---- */
    .resumen-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: .85rem;
        margin-bottom: 1.5rem;
    }

    .res-card {
        background: var(--rf-surface);
        border: 1px solid var(--rf-border);
        border-radius: 14px;
        padding: 1rem 1.2rem;
        display: flex;
        flex-direction: column;
        gap: .3rem;
    }

    .res-card .res-label {
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--rf-muted);
    }

    .res-card .res-val {
        font-family: 'JetBrains Mono', monospace;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
    }

    .res-card .res-sub {
        font-size: .72rem;
        color: var(--rf-muted);
    }

    .res-card.mora .res-val {
        color: var(--rojo);
    }

    .res-card.pagado .res-val {
        color: var(--verde);
    }

    .res-card.total .res-val {
        color: var(--rf-accent);
    }

    .res-card.pend .res-val {
        color: var(--amarillo);
    }

    /* ---- BARRA PROGRESO ---- */
    .prog-wrap {
        background: var(--rf-surface);
        border: 1px solid var(--rf-border);
        border-radius: 14px;
        padding: 1.1rem 1.4rem;
        margin-bottom: 1.5rem;
    }

    .prog-wrap label {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--rf-muted);
        margin-bottom: .6rem;
        display: block;
    }

    .prog-track {
        height: 10px;
        background: var(--rf-surface2);
        border-radius: 99px;
        overflow: hidden;
    }

    .prog-bar {
        height: 100%;
        border-radius: 99px;
        background: linear-gradient(90deg, var(--verde), #16a34a);
        transition: width .6s ease;
    }

    .prog-nums {
        display: flex;
        justify-content: space-between;
        font-size: .75rem;
        color: var(--rf-muted);
        margin-top: .45rem;
    }

</style>
<div class="container-fluid py-3">

    <!-- Volver -->
    <div class="mb-3">
        <a href="?c=cuotas&a=index" class="btn-back">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <!-- ── Header cliente ── -->
    <div class="detalle-header mb-3">
        <div class="cliente-info">
            <h4>
                <i class="bi bi-person-circle me-2" style="color:var(--rf-accent);"></i>
                <?= htmlspecialchars($venta->cliente_nombre) ?>
            </h4>
            <p>
                <span><?= htmlspecialchars($venta->modelo_nombre) ?></span>
                &nbsp;·&nbsp; 📱 +595 <?= $venta->telefono ?>
                &nbsp;·&nbsp; Venta #<?= $venta->id_venta ?>
            </p>
        </div>
        <div style="text-align:right; flex-shrink:0;">
            <div style="font-family:var(--rf-font-mono); font-size:1.1rem; font-weight:700; color:var(--rf-accent);">
                Gs. <?= number_format($venta->monto_total, 0, ',', '.') ?>
            </div>
            <div style="font-size:.7rem; color:var(--rf-muted);">Total financiado</div>
        </div>
    </div>

    <!-- ── Resumen cards ── -->
    <div class="resumen-grid mb-3 row-stats-mobile">

        <div class="res-card total">
            <span class="res-label"><i class="bi bi-collection me-1"></i>Total cuotas</span>
            <span class="res-val"><?= $resumen['total_cuotas'] ?></span>
        </div>

        <div class="res-card pagado">
            <span class="res-label"><i class="bi bi-check2-circle me-1"></i>Pagadas</span>
            <span class="res-val"><?= $resumen['pagadas'] ?></span>
            <span class="res-sub">Gs. <?= number_format($resumen['monto_cobrado'], 0, ',', '.') ?></span>
        </div>

        <div class="res-card pend">
            <span class="res-label"><i class="bi bi-hourglass-split me-1"></i>Pendientes</span>
            <span class="res-val"><?= $resumen['pendientes'] ?></span>
            <span class="res-sub">Gs. <?= number_format($resumen['monto_pendiente'], 0, ',', '.') ?></span>
        </div>

        <div class="res-card mora">
            <span class="res-label"><i class="bi bi-exclamation-octagon me-1"></i>Mora acumulada</span>
            <span class="res-val">Gs. <?= number_format($resumen['mora_total'], 0, ',', '.') ?></span>
            <span class="res-sub"><?= $resumen['cuotas_atrasadas'] ?> cuota(s) atrasada(s)</span>
        </div>

    </div>

    <!-- ── Barra de progreso ── -->
    <?php $pct = $resumen['total_cuotas'] > 0 ? round(($resumen['pagadas'] / $resumen['total_cuotas']) * 100) : 0; ?>
    <div class="prog-wrap mb-3">
        <label>Progreso de pago</label>
        <div class="prog-track">
            <div class="prog-bar" style="width:<?= $pct ?>%"></div>
        </div>
        <div class="prog-nums">
            <span><?= $resumen['pagadas'] ?> de <?= $resumen['total_cuotas'] ?> cuotas pagadas</span>
            <span style="color:var(--rf-success); font-weight:700;"><?= $pct ?>%</span>
        </div>
    </div>

    <!-- ── CARDS MOBILE ── -->
    <div class="mobile-cards-list">
        <?php foreach ($cuotas_detalle as $c):
            $saldo = $c->monto - $c->monto_pagado;
            $venc  = strtotime($c->fecha_vencimiento);
            $hoy   = strtotime(date('Y-m-d'));
            $mora  = 0;
            $badge = 'pendiente';
            $badge_label = '⏳ Pendiente';

            if ($saldo <= 0) {
                $badge = 'pagada'; $badge_label = '✓ Pagada';
            } elseif ($venc == $hoy) {
                $badge = 'hoy'; $badge_label = '🕐 Vence hoy';
            } else {
                $dias = max(0, floor(($hoy - $venc) / 86400));
                if ($dias > 0) {
                    $meses = ceil($dias / 30);
                    $mora  = $saldo * 0.02 * $meses;
                    $badge = $meses >= 2 ? 'muy-atras' : 'atrasada';
                    $badge_label = $meses >= 2 ? '🔴 Muy atrasada' : '⚠ Atrasada';
                }
            }
        ?>
        <div class="mc">

            <!-- Número cuota + badge + vencimiento -->
            <div class="mc-top">
                <span style="font-family:var(--rf-font-mono); font-size:.82rem; font-weight:700; color:var(--rf-text);">
                    Cuota <?= str_pad($c->numero_cuota, 2, '0', STR_PAD_LEFT) ?>
                </span>
                <span class="badge-cuota <?= $badge ?>"><?= $badge_label ?></span>
            </div>

            <!-- Fecha vencimiento -->
            <div class="mc-info">
                <span>📅 <?= date('d/m/Y', strtotime($c->fecha_vencimiento)) ?></span>
                <span style="font-family:var(--rf-font-mono); font-size:.72rem;">
                    Cuota: Gs. <?= number_format($c->monto, 0, ',', '.') ?>
                </span>
            </div>

            <!-- Pagado / Saldo / Mora -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:.35rem; font-size:.72rem;">
                <div>
                    <span style="color:var(--rf-muted); font-size:.62rem; text-transform:uppercase; font-weight:700; letter-spacing:.06em;">Pagado</span><br>
                    <span style="font-family:var(--rf-font-mono); color:var(--rf-success); font-weight:600;">
                        Gs. <?= number_format($c->monto_pagado, 0, ',', '.') ?>
                    </span>
                </div>
                <div>
                    <span style="color:var(--rf-muted); font-size:.62rem; text-transform:uppercase; font-weight:700; letter-spacing:.06em;">Saldo</span><br>
                    <?php if ($saldo > 0): ?>
                    <span style="font-family:var(--rf-font-mono); color:var(--rf-danger); font-weight:600;">
                        Gs. <?= number_format($saldo, 0, ',', '.') ?>
                    </span>
                    <?php else: ?>
                    <span style="color:var(--rf-muted);">—</span>
                    <?php endif; ?>
                </div>
                <?php if ($mora > 0): ?>
                <div style="grid-column:1/-1;">
                    <span style="color:var(--rf-muted); font-size:.62rem; text-transform:uppercase; font-weight:700; letter-spacing:.06em;">Mora</span><br>
                    <span class="mora-chip">+Gs. <?= number_format($mora, 0, ',', '.') ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Acciones -->
            <?php if ($saldo > 0 || $c->monto_pagado > 0): ?>
            <div class="mc-foot">
                <?php if ($saldo > 0): ?>
                <button type="button" class="btn-accion-pago"
                    onclick="abrirModalPago(<?= $c->id_cuota ?>, <?= $c->numero_cuota ?>, <?= $saldo ?>, <?= $mora ?>)">
                    <i class="bi bi-cash-stack"></i> Pagar
                </button>
                <?php endif; ?>
                <?php if ($c->monto_pagado > 0): ?>
                <a href="?c=cuotas&a=imprimirRecibo&id=<?= $c->id_cuota ?>" target="_blank"
                   class="btn-accion-print" style="flex:1; justify-content:center; display:flex; align-items:center; gap:.3rem;">
                    <i class="bi bi-printer"></i> Recibo
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
        <?php endforeach; ?>
    </div>

    <!-- ── TABLA DESKTOP ── -->
    <div class="table-card has-mobile-cards">
        <div class="table-responsive">
            <table class="table" id="tblDetalleCuotas">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Vencimiento</th>
                        <th>Monto cuota</th>
                        <th>Pagado</th>
                        <th>Saldo</th>
                        <th>Mora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cuotas_detalle as $c):
                        $saldo = $c->monto - $c->monto_pagado;
                        $venc  = strtotime($c->fecha_vencimiento);
                        $hoy   = strtotime(date('Y-m-d'));
                        $mora  = 0;
                        $badge = 'pendiente';
                        $badge_label = '⏳ Pendiente';

                        if ($saldo <= 0) {
                            $badge = 'pagada'; $badge_label = '✓ Pagada';
                        } elseif ($venc == $hoy) {
                            $badge = 'hoy'; $badge_label = '🕐 Vence hoy';
                        } else {
                            $dias = max(0, floor(($hoy - $venc) / 86400));
                            if ($dias > 0) {
                                $meses = ceil($dias / 30);
                                $mora  = $saldo * 0.02 * $meses;
                                $badge = $meses >= 2 ? 'muy-atras' : 'atrasada';
                                $badge_label = $meses >= 2 ? '🔴 Muy atrasada' : '⚠ Atrasada';
                            }
                        }
                    ?>
                    <tr>
                        <td><span class="cuota-num"><?= str_pad($c->numero_cuota, 2, '0', STR_PAD_LEFT) ?></span></td>
                        <td class="font-mono" style="font-size:.8rem; color:var(--rf-muted);">
                            <?= date('d/m/Y', strtotime($c->fecha_vencimiento)) ?>
                        </td>
                        <td class="font-mono">Gs. <?= number_format($c->monto, 0, ',', '.') ?></td>
                        <td class="font-mono" style="color:var(--rf-success);">
                            Gs. <?= number_format($c->monto_pagado, 0, ',', '.') ?>
                        </td>
                        <td>
                            <?php if ($saldo > 0): ?>
                            <span class="font-mono" style="color:var(--rf-danger);">Gs. <?= number_format($saldo, 0, ',', '.') ?></span>
                            <?php else: ?>
                            <span style="color:var(--rf-muted);">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($mora > 0): ?>
                            <span class="mora-chip">+Gs. <?= number_format($mora, 0, ',', '.') ?></span>
                            <?php else: ?>
                            <span class="mora-chip cero">—</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge-cuota <?= $badge ?>"><?= $badge_label ?></span></td>
                        <td>
                            <div class="acciones-group">
                                <?php if ($saldo > 0): ?>
                                <button type="button" class="btn-accion-pago"
                                    onclick="abrirModalPago(<?= $c->id_cuota ?>, <?= $c->numero_cuota ?>, <?= $saldo ?>, <?= $mora ?>)">
                                    <i class="bi bi-cash-stack"></i> Pagar
                                </button>
                                <?php endif; ?>
                                <?php if ($c->monto_pagado > 0): ?>
                                <a href="?c=cuotas&a=imprimirRecibo&id=<?= $c->id_cuota ?>"
                                   target="_blank" class="btn-accion-print">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ── Modal Pago ── -->
<div class="modal fade modal-pago-dark" id="modalPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <form action="?c=pagos&a=RegistrarPago" method="POST" class="modal-content">
            <input type="hidden" name="id_venta" value="<?= $venta->id_venta ?>">
            <input type="hidden" name="id_cuota" id="pay_id_cuota">

            <div class="modal-header">
                <h6 class="modal-title">
                    <i class="bi bi-wallet2 me-2" style="color:var(--rf-success);"></i>
                    Cobro — Cuota #<span id="pay_num_cuota">00</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                <div class="mb-3">
                    <label class="form-label">Monto a pagar (saldo)</label>
                    <div class="input-group">
                        <span class="input-group-text">Gs.</span>
                        <input type="number" name="monto_pago" id="pay_monto" class="form-control font-mono" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mora (opcional)</label>
                    <div class="input-group">
                        <span class="input-group-text" style="color:var(--rf-danger);">+</span>
                        <input type="number" name="mora_pago" id="pay_mora" class="form-control font-mono" value="0">
                    </div>
                    <div style="font-size:.65rem; color:var(--rf-muted); margin-top:.3rem;">Se sumará al monto total del cobro.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Método de pago</label>
                    <select name="metodo_pago" id="metodo_pago" class="form-select" required>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Transferencia">Transferencia</option>
                    </select>
                </div>

                <div class="pago-total-box">
                    <span class="pago-total-label">Total a cobrar</span>
                    <span class="pago-total-val font-mono" id="pay_total_visual">Gs. 0</span>
                </div>

            </div>

            <div class="modal-footer" style="border:none; padding:1rem 1.5rem;">
                <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn-rf primary px-4">Confirmar cobro</button>
            </div>

        </form>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#tblDetalleCuotas').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
        pageLength: 25,
        dom: 'lrtip',
        order: [[0, 'asc']]
    });
});

function abrirModalPago(id, num, saldo, mora) {
    $('#pay_id_cuota').val(id);
    $('#pay_num_cuota').text(num.toString().padStart(2, '0'));
    $('#pay_monto').val(saldo);
    $('#pay_mora').val(Math.round(mora));
    actualizarTotal();
    new bootstrap.Modal(document.getElementById('modalPago')).show();
}

function actualizarTotal() {
    const total = (parseFloat($('#pay_monto').val()) || 0) + (parseFloat($('#pay_mora').val()) || 0);
    $('#pay_total_visual').text('Gs. ' + total.toLocaleString('es-PY'));
}

$('#pay_monto, #pay_mora').on('input', actualizarTotal);
</script>