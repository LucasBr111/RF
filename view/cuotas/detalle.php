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

    /* ---- TABLE CUOTAS ---- */
    .cuotas-table-card {
        background: var(--rf-surface);
        border: 1px solid var(--rf-border);
        border-radius: 16px;
        overflow: hidden;
    }

    #tblDetalleCuotas {
        color: var(--rf-text) !important;
        margin: 0 !important;
        font-size: .875rem;
    }

    #tblDetalleCuotas thead tr {
        background: var(--rf-surface2) !important;
        border-bottom: 1px solid var(--rf-border);
    }

    #tblDetalleCuotas thead th {
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: var(--rf-muted) !important;
        padding: .9rem 1rem;
        border: none !important;
        white-space: nowrap;
    }

    #tblDetalleCuotas tbody tr {
        border-bottom: 1px solid var(--rf-border) !important;
        transition: background .15s;
    }

    #tblDetalleCuotas tbody tr:hover {
        background: var(--rf-surface2) !important;
    }

    #tblDetalleCuotas tbody td {
        padding: .8rem 1rem;
        vertical-align: middle;
        border: none !important;
        color: var(--rf-text) !important;
    }

    .cuota-num {
        font-family: 'JetBrains Mono', monospace;
        font-size: .8rem;
        color: var(--rf-muted);
    }

    .mono {
        font-family: 'JetBrains Mono', monospace;
        font-size: .85rem;
    }

    .txt-rojo {
        color: var(--rojo) !important;
    }

    .txt-verde {
        color: var(--verde) !important;
    }

    .txt-muted {
        color: var(--rf-muted) !important;
    }

    .badge-cuota {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .06em;
        padding: .28rem .7rem;
        border-radius: 50px;
        border: 1.5px solid transparent;
        white-space: nowrap;
    }

    .badge-cuota.pagada {
        background: rgba(34, 197, 94, .1);
        color: #22c55e;
        border-color: rgba(34, 197, 94, .25);
    }

    .badge-cuota.pendiente {
        background: rgba(6, 182, 212, .1);
        color: #06b6d4;
        border-color: rgba(6, 182, 212, .25);
    }

    .badge-cuota.atrasada {
        background: rgba(245, 158, 11, .1);
        color: #f59e0b;
        border-color: rgba(245, 158, 11, .25);
    }

    .badge-cuota.muy-atras {
        background: rgba(239, 68, 68, .1);
        color: #ef4444;
        border-color: rgba(239, 68, 68, .25);
    }

    .badge-cuota.hoy {
        background: rgba(59, 130, 246, .1);
        color: #3b82f6;
        border-color: rgba(59, 130, 246, .25);
    }

    /* mora chip */
    .mora-chip {
        display: inline-block;
        background: rgba(239, 68, 68, .12);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, .25);
        border-radius: 6px;
        font-size: .7rem;
        font-family: 'JetBrains Mono', monospace;
        font-weight: 600;
        padding: .15rem .5rem;
    }

    .mora-chip.cero {
        background: transparent;
        color: var(--rf-muted);
        border-color: transparent;
    }

    /* back btn */
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: var(--rf-surface2);
        border: 1.5px solid var(--rf-border);
        border-radius: 10px;
        color: var(--rf-muted);
        font-size: .82rem;
        font-weight: 600;
        padding: .45rem .9rem;
        text-decoration: none;
        transition: all .18s;
    }

    .btn-back:hover {
        color: var(--rf-text);
        border-color: var(--rf-muted);
        text-decoration: none;
    }

    /* DataTable overrides */
    .dataTables_wrapper {
        color: var(--rf-text) !important;
        padding: 1rem;
    }

    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_info {
        color: var(--rf-muted) !important;
        font-size: .8rem;
    }

    .dataTables_wrapper .dataTables_length select {
        background: var(--rf-surface2) !important;
        border: 1px solid var(--rf-border) !important;
        color: var(--rf-text) !important;
        border-radius: 8px;
        padding: .2rem .5rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        background: var(--rf-surface2) !important;
        border: 1px solid var(--rf-border) !important;
        color: var(--rf-muted) !important;
        border-radius: 8px !important;
        margin: 0 2px !important;
        padding: .3rem .7rem !important;
        font-size: .8rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--rf-accent) !important;
        color: #fff !important;
        border-color: var(--rf-accent) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--rf-accent) !important;
        color: #fff !important;
        border-color: var(--rf-accent) !important;
        font-weight: 700;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: .35 !important;
        pointer-events: none;
    }

    /* Botones de acción específicos */
    .btn-accion-pago {
        background: var(--verde);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: .4rem .8rem;
        font-size: .75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        transition: transform .15s, filter .15s;
    }

    .btn-accion-pago:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
    }

    .btn-accion-print {
        background: var(--rf-surface2);
        color: var(--rf-accent);
        border: 1px solid var(--rf-border);
        border-radius: 8px;
        padding: .4rem .6rem;
        font-size: .8rem;
        transition: all .2s;
    }

    .btn-accion-print:hover {
        background: var(--rf-accent);
        color: #fff;
    }

    /* Modal de Pago */
    .modal-pago-dark .modal-content {
        background: var(--rf-surface);
        color: var(--rf-text);
        border: 1px solid var(--rf-border);
        border-radius: 16px;
    }

    .modal-pago-dark .modal-header {
        border-bottom: 1px solid var(--rf-border);
    }

    .modal-pago-dark .form-control {
        background: var(--rf-surface2);
        border: 1px solid var(--rf-border);
        color: var(--rf-text);
    }

    .modal-pago-dark .form-control:focus {
        border-color: var(--rf-accent);
        box-shadow: none;
    }

    .input-group-text {
        background: var(--rf-surface2);
        border: 1px solid var(--rf-border);
        color: var(--rf-muted);
    }
</style>

<div class="container-fluid py-4" style="font-family:'DM Sans',sans-serif; background:var(--rf-bg); color:var(--rf-text);">

    <!-- Back -->
    <div class="mb-3">
        <a href="?c=cuotas&a=index" class="btn-back">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <!-- Header cliente -->
    <div class="detalle-header">
        <div class="cliente-info">
            <h4><i class="bi bi-person-circle me-2" style="color:var(--rf-accent);"></i><?= htmlspecialchars($venta->cliente_nombre) ?></h4>
            <p>
                <span><?= htmlspecialchars($venta->modelo_nombre) ?></span>
                &nbsp;·&nbsp;
                📱 +595 <?= $venta->telefono ?>
                &nbsp;·&nbsp;
                Venta #<?= $venta->id_venta ?>
            </p>
        </div>
        <div>
            <span style="font-family:'JetBrains Mono',monospace; font-size:1.1rem; font-weight:700; color:var(--rf-accent);">
                Gs. <?= number_format($venta->monto_total, 0, ',', '.') ?>
            </span>
            <div style="font-size:.7rem; color:var(--rf-muted); text-align:right;">Total financiado</div>
        </div>
    </div>

    <!-- Resumen numérico -->
    <div class="resumen-grid">
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

    <!-- Barra de progreso -->
    <?php
    $pct = $resumen['total_cuotas'] > 0 ? round(($resumen['pagadas'] / $resumen['total_cuotas']) * 100) : 0;
    ?>
    <div class="prog-wrap">
        <label>Progreso de pago</label>
        <div class="prog-track">
            <div class="prog-bar" style="width:<?= $pct ?>%"></div>
        </div>
        <div class="prog-nums">
            <span><?= $resumen['pagadas'] ?> de <?= $resumen['total_cuotas'] ?> cuotas pagadas</span>
            <span style="color:var(--verde); font-weight:700;"><?= $pct ?>%</span>
        </div>
    </div>

    <!-- Tabla de cuotas -->
    <div class="cuotas-table-card">
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

                        // Mora: 2% mensual sobre saldo
                        $mora = 0;
                        $badge = 'pendiente';
                        $badge_label = 'Pendiente';

                        if ($saldo <= 0) {
                            $badge = 'pagada';
                            $badge_label = '✓ Pagada';
                            $mora = 0;
                        } elseif ($venc == $hoy) {
                            $badge = 'hoy';
                            $badge_label = '🕐 Vence hoy';
                        } else {
                            $dias_atraso = max(0, floor(($hoy - $venc) / 86400));
                            if ($dias_atraso > 0) {
                                $meses_atraso = ceil($dias_atraso / 30);
                                $mora = $saldo * 0.02 * $meses_atraso;
                                if ($meses_atraso >= 2) {
                                    $badge = 'muy-atras';
                                    $badge_label = '🔴 Muy atrasada';
                                } else {
                                    $badge = 'atrasada';
                                    $badge_label = '⚠ Atrasada';
                                }
                            } elseif (date('m', $venc) == date('m')) {
                                $badge = 'pendiente';
                                $badge_label = '⏳ Pendiente';
                            }
                        }
                    ?>
                        <tr>
                            <td><span class="cuota-num"><?= str_pad($c->numero_cuota, 2, '0', STR_PAD_LEFT) ?></span></td>
                            <td>
                                <span class="mono"><?= date('d/m/Y', strtotime($c->fecha_vencimiento)) ?></span>
                            </td>
                            <td>
                                <span class="mono">Gs. <?= number_format($c->monto, 0, ',', '.') ?></span>
                            </td>
                            <td>
                                <span class="mono txt-verde">Gs. <?= number_format($c->monto_pagado, 0, ',', '.') ?></span>
                            </td>
                            <td>
                                <?php if ($saldo > 0): ?>
                                    <span class="mono txt-rojo">Gs. <?= number_format($saldo, 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="mono txt-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($mora > 0): ?>
                                    <span class="mora-chip">+Gs. <?= number_format($mora, 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="mora-chip cero">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge-cuota <?= $badge ?>"><?= $badge_label ?></span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <?php if ($saldo > 0): ?>
                                        <button type="button"
                                            class="btn-accion-pago"
                                            onclick="abrirModalPago(<?= $c->id_cuota ?>, <?= $c->numero_cuota ?>, <?= $saldo ?>, <?= $mora ?>)"
                                            title="Registrar Pago">
                                            <i class="bi bi-cash-stack"></i> Pagar
                                        </button>
                                    <?php endif; ?>

                                    <?php if ($c->monto_pagado > 0): ?>
                                        <a href="?c=cuotas&a=imprimirRecibo&id=<?= $c->id_cuota ?>"
                                            target="_blank"
                                            class="btn-accion-print"
                                            title="Imprimir Recibo">
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


<div class="modal fade modal-pago-dark" id="modalPago" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <form action="?c=pagos&a=RegistrarPago" method="POST" class="modal-content form-financiero">
            <input type="hidden" name="id_venta" value="<?= $venta->id_venta ?>">
            <input type="hidden" name="id_cuota" id="pay_id_cuota">

            <div class="modal-header">
                <h6 class="modal-title fw-bold">
                    <i class="bi bi-wallet2 me-2 text-success"></i>
                    Registrar Cobro - Cuota #<span id="pay_num_cuota">00</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="res-label mb-2 d-block">Monto a Pagar (Saldo)</label>
                    <div class="input-group">
                        <span class="input-group-text">Gs.</span>
                        <input type="text" name="monto_pago" id="pay_monto" class="form-control mono input-precio" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="res-label mb-2 d-block">Mora (Opcional)</label>
                    <div class="input-group">
                        <span class="input-group-text text-danger">+</span>
                        <input type="text" name="mora_pago" id="pay_mora" class="form-control mono input-precio" value="0">
                    </div>
                    <small class="text-muted" style="font-size: .65rem;">Se sumará al monto total del cobro.</small>
                </div>

                <div class="mb-3">
                    <label class="res-label mb-2 d-block">Método de Pago</label>
                    <select name="metodo_pago" class="form-select bg-dark text-white border-secondary" id="metodo_pago" required>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Transferencia">Transferencia</option>
                    </select>
                </div>

                <div class="p-3 rounded-3" style="background: rgba(108,127,255,0.05); border: 1px dashed var(--rf-accent);">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="res-label" style="color: var(--rf-accent);">Total a Cobrar:</span>
                        <span class="mono fw-bold text-white" id="pay_total_visual">0</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 p-4 pt-0">
                <button type="submit" class="btn-aplicar py-2">
                    Confirmar Cobro
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#tblDetalleCuotas').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
            },
            pageLength: 25,
            dom: 'lrtip',
            order: [
                [0, 'asc']
            ]
        });
    });

    function abrirModalPago(id, num, saldo, mora) {
        $('#pay_id_cuota').val(id);
        $('#pay_num_cuota').text(num.toString().padStart(2, '0'));
        $('#pay_monto').val(saldo);
        $('#pay_mora').val(Math.round(mora));

        actualizarTotalCobro();

        const modal = new bootstrap.Modal(document.getElementById('modalPago'));
        modal.show();
    }

    // Función para mostrar el total en tiempo real dentro del modal
    function actualizarTotalCobro() {
        const monto = parseFloat($('#pay_monto').val()) || 0;
        const mora = parseFloat($('#pay_mora').val()) || 0;
        const total = monto + mora;
        $('#pay_total_visual').text('Gs. ' + total.toLocaleString('es-PY'));
    }

    $('#pay_monto, #pay_mora').on('input', actualizarTotalCobro);
</script>