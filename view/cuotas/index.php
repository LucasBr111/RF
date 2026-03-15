<style>
/* ── Filtro inline ── */
.filtros-inline {
    background: var(--rf-surface);
    border: 1px solid var(--rf-border);
    border-radius: var(--rf-radius-lg);
    padding: .85rem 1rem;
    margin-bottom: 1rem;
    display: flex;
    gap: .6rem;
    align-items: flex-end;
}

.filtros-inline .fi-select {
    flex: 1;
    min-width: 0;
}

.filtros-inline .fi-btn {
    flex-shrink: 0;
    height: 38px;
    width: 42px;
    background: var(--rf-accent);
    border: none;
    border-radius: var(--rf-radius-sm);
    color: #fff;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity var(--rf-transition);
}
.filtros-inline .fi-btn:hover { opacity: .85; }

/* ── Stat grid mobile ── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: .6rem;
    margin-bottom: 1rem;
}

.sc {
    background: var(--rf-surface);
    border: 1px solid var(--rf-border);
    border-radius: var(--rf-radius);
    padding: .65rem .75rem;
    display: flex;
    align-items: center;
    gap: .55rem;
}

.sc-icon {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .95rem;
    flex-shrink: 0;
}

.sc-label {
    font-size: .6rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--rf-muted);
    line-height: 1.2;
}

.sc-value {
    font-family: var(--rf-font-mono);
    font-size: 1.3rem;
    font-weight: 700;
    line-height: 1;
}

.sc.warning  .sc-icon { background: var(--rf-blue-bg);    color: var(--rf-blue); }
.sc.warning  .sc-value { color: var(--rf-blue); }
.sc.atrasado .sc-icon { background: var(--rf-warning-bg); color: var(--rf-warning); }
.sc.atrasado .sc-value { color: var(--rf-warning); }
.sc.danger   .sc-icon { background: var(--rf-danger-bg);  color: var(--rf-danger); }
.sc.danger   .sc-value { color: var(--rf-danger); }

/* ── Pill tabs scroll horizontal en mobile ── */
.pill-tabs {
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    gap: .4rem;
    margin-bottom: .85rem;
    padding-bottom: 2px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.pill-tabs::-webkit-scrollbar { display: none; }

.pill-btn {
    flex-shrink: 0;
    white-space: nowrap;
    padding: .35rem .9rem;
    font-size: .72rem;
}

/* ── TABLE DESKTOP (normal) ── */
.table-card { overflow: hidden; }

#tblGestionCuotas { font-size: .82rem; }

/* ── CARDS MOBILE — reemplaza la tabla ── */
@media (max-width: 767px) {

    /* Ocultar tabla real, mostrar cards */
    .table-card .table-responsive { display: none; }
    .cuotas-cards-mobile { display: flex; flex-direction: column; gap: .55rem; padding: .75rem; }

    .cuota-card {
        background: var(--rf-surface2);
        border: 1px solid var(--rf-border);
        border-radius: 12px;
        padding: .75rem .85rem;
        display: flex;
        flex-direction: column;
        gap: .45rem;
    }

    /* Fila superior: nombre + badge */
    .cuota-card .cc-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: .5rem;
    }

    .cuota-card .cc-nombre {
        font-weight: 700;
        font-size: .88rem;
        color: var(--rf-text);
        text-decoration: none;
        line-height: 1.2;
    }
    .cuota-card .cc-nombre:hover { color: var(--rf-accent); }

    /* Fila info: modelo + vencimiento */
    .cuota-card .cc-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: .72rem;
        color: var(--rf-muted);
    }

    /* Fila deuda */
    .cuota-card .cc-deuda {
        font-family: var(--rf-font-mono);
        font-size: .92rem;
        font-weight: 700;
        color: var(--rf-danger);
    }

    /* Acciones */
    .cuota-card .cc-acciones {
        display: flex;
        gap: .4rem;
        padding-top: .35rem;
        border-top: 1px solid var(--rf-border);
        margin-top: .05rem;
    }

    .cuota-card .cc-acciones .btn-accion {
        flex: 1;
        justify-content: center;
        font-size: .72rem;
        padding: .38rem .5rem;
    }

    /* Stats en 2 columnas en mobile */
    .stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }

    /* La tercera stat ocupa todo el ancho */
    .stats-grid .sc:nth-child(3) {
        grid-column: 1 / -1;
    }
}

/* Mostrar solo en mobile */
.cuotas-cards-mobile { display: none; }

@media (max-width: 767px) {
    .cuotas-cards-mobile { display: flex; }
}

/* ── DataTable: ocultar controles que no necesitamos ── */
.dataTables_wrapper { padding: .75rem !important; }

/* Fix DataTables: select "Show entries" en mobile */
@media (max-width: 480px) {
    .dataTables_length { display: none; }
}
</style>

<div class="container-fluid py-3">

    <!-- ===== FILTRO ===== -->
    <div class="filtros-inline mb-3">
        <form method="POST" class="d-flex gap-2 w-100 align-items-end">
            <div class="fi-select">
                <label class="form-label mb-1">Filtrar por Modelo</label>
                <select class="form-select select2" name="id_modelo" style="height:38px; padding:.4rem .75rem;">
                    <option value="">Todos los modelos</option>
                    <?php foreach($modelos_lista as $m): ?>
                        <option value="<?= $m->id_modelo ?>"
                            <?= (isset($_POST['id_modelo']) && $_POST['id_modelo'] == $m->id_modelo) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($m->nombre) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="fi-btn" title="Buscar">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <!-- ===== STAT CARDS ===== -->
    <div class="stats-grid mb-3">
        <div class="sc warning">
            <div class="sc-icon"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="sc-label">Vence hoy</div>
                <div class="sc-value"><?= $stats['vence_hoy'] ?></div>
            </div>
        </div>
        <div class="sc atrasado">
            <div class="sc-icon"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <div class="sc-label">Atrasados</div>
                <div class="sc-value"><?= $stats['atrasados'] ?></div>
            </div>
        </div>
        <div class="sc danger">
            <div class="sc-icon"><i class="bi bi-fire"></i></div>
            <div>
                <div class="sc-label">+2 Meses</div>
                <div class="sc-value"><?= $stats['muy_atrasados'] ?></div>
            </div>
        </div>
    </div>

    <!-- ===== PILLS ===== -->
    <div class="pill-tabs" id="cuotasTabs">
        <button class="pill-btn active" data-filter="VENCE HOY">
            <i class="bi bi-clock"></i> Hoy
        </button>
        <button class="pill-btn" data-filter="PENDIENTE">
            <i class="bi bi-hourglass-split"></i> Pendientes
        </button>
        <button class="pill-btn" data-filter="ATRASADO">
            <i class="bi bi-exclamation-circle"></i> Atrasados
        </button>
        <button class="pill-btn" data-filter="ACUMULADO +2 MESES">
            <i class="bi bi-x-octagon"></i> +2 Meses
        </button>
        <button class="pill-btn" data-filter="AL DIA">
            <i class="bi bi-check-circle"></i> Al día
        </button>

        <button class="pill-btn " data-filter="">
            <i class="bi bi-grid-3x3-gap"></i> Todos
        </button>
    </div>

    <!-- ===== TABLA (desktop) + CARDS (mobile) ===== -->

    <!-- ► CARDS MOBILE — generadas desde PHP, no DataTables -->
    <div class="cuotas-cards-mobile" id="cuotasCardsMobile">
        <?php foreach($cuotas as $r):
            $badge_class = 'al-dia';
            if ($r->categoria == 'ACUMULADO +2 MESES') $badge_class = 'muy-atrasado';
            elseif ($r->categoria == 'ATRASADO')        $badge_class = 'atrasado';
            elseif ($r->categoria == 'VENCE HOY')       $badge_class = 'hoy';
            elseif ($r->categoria == 'PENDIENTE')       $badge_class = 'pendiente';

            $badge_icon = '✓';
            if ($r->categoria == 'ACUMULADO +2 MESES') $badge_icon = '🔴';
            elseif ($r->categoria == 'ATRASADO')        $badge_icon = '⚠️';
            elseif ($r->categoria == 'VENCE HOY')       $badge_icon = '🕐';
            elseif ($r->categoria == 'PENDIENTE')       $badge_icon = '⏳';
        ?>
        <div class="cuota-card" data-categoria="<?= htmlspecialchars($r->categoria) ?>">
            <!-- Nombre + badge -->
            <div class="cc-top">
                <a href="?c=clientes&a=detalle&id=<?= $r->id_cliente ?>" class="cc-nombre">
                    <?= htmlspecialchars($r->cliente_nombre) ?>
                </a>
                <span class="badge-estado <?= $badge_class ?>" style="font-size:.62rem; padding:.22rem .6rem; flex-shrink:0;">
                    <?= $badge_icon ?> <?= $r->categoria ?>
                </span>
            </div>
            <!-- Modelo + vencimiento -->
            <div class="cc-info">
                <span><?= htmlspecialchars($r->modelo_nombre) ?></span>
                <span style="font-family:var(--rf-font-mono); font-size:.7rem;">
                    📅 <?= date("d/m/Y", strtotime($r->proximo_vencimiento)) ?>
                </span>
            </div>
            <!-- Deuda -->
            <div class="cc-deuda">
                Gs. <?= number_format($r->monto_acumulado, 0, ",", ".") ?>
            </div>
            <!-- Acciones -->
            <div class="cc-acciones">
                <a href="?c=cuotas&a=detalle&id=<?= $r->id_venta ?>" class="btn-accion btn-ver">
                    <i class="bi bi-list-check"></i> Cuotas
                </a>
                <button type="button" class="btn-accion btn-wa"
                    onclick="abrirModalWA(
                        '<?= addslashes($r->cliente_nombre) ?>',
                        '<?= $r->telefono ?>',
                        'Gs. <?= number_format($r->monto_acumulado, 0, ',', '.') ?>',
                        '<?= date('d/m/Y', strtotime($r->proximo_vencimiento)) ?>'
                    )">
                    <i class="bi bi-whatsapp"></i> WA
                </button>
                <a href="?c=ventas&a=pagare&id=<?= $r->id_venta ?>" target="_blank" class="btn-accion btn-print">
                    <i class="bi bi-printer"></i>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- ► TABLA DESKTOP -->
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover" id="tblGestionCuotas">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Vehículo</th>
                        <th>Próx. vencimiento</th>
                        <th>Deuda acumulada</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($cuotas as $r):
                        $badge_class = 'al-dia';
                        if ($r->categoria == 'ACUMULADO +2 MESES') $badge_class = 'muy-atrasado';
                        elseif ($r->categoria == 'ATRASADO')        $badge_class = 'atrasado';
                        elseif ($r->categoria == 'VENCE HOY')       $badge_class = 'hoy';
                        elseif ($r->categoria == 'PENDIENTE')       $badge_class = 'pendiente';

                        $badge_icon = '✓';
                        if ($r->categoria == 'ACUMULADO +2 MESES') $badge_icon = '🔴';
                        elseif ($r->categoria == 'ATRASADO')        $badge_icon = '⚠️';
                        elseif ($r->categoria == 'VENCE HOY')       $badge_icon = '🕐';
                        elseif ($r->categoria == 'PENDIENTE')       $badge_icon = '⏳';
                    ?>
                    <tr>
                        <td>
                            <a href="?c=clientes&a=detalle&id=<?= $r->id_cliente ?>" class="cliente-link">
                                <?= htmlspecialchars($r->cliente_nombre) ?>
                            </a>
                        </td>
                        <td style="color:var(--rf-muted);"><?= htmlspecialchars($r->modelo_nombre) ?></td>
                        <td class="font-mono" style="font-size:.8rem; color:var(--rf-muted);">
                            <?= date("d/m/Y", strtotime($r->proximo_vencimiento)) ?>
                        </td>
                        <td>
                            <span class="deuda-val">Gs. <?= number_format($r->monto_acumulado, 0, ",", ".") ?></span>
                        </td>
                        <td>
                            <!-- span oculto con texto exacto para búsqueda DataTables -->
                            <span class="dt-cat" style="display:none;"><?= $r->categoria ?></span>
                            <span class="badge-estado <?= $badge_class ?>">
                                <?= $badge_icon ?> <?= $r->categoria ?>
                            </span>
                        </td>
                        <td>
                            <div class="acciones-group">
                                <a href="?c=cuotas&a=detalle&id=<?= $r->id_venta ?>" class="btn-accion btn-ver">
                                    <i class="bi bi-list-check"></i> Cuotas
                                </a>
                                <button type="button" class="btn-accion btn-wa"
                                    onclick="abrirModalWA(
                                        '<?= addslashes($r->cliente_nombre) ?>',
                                        '<?= $r->telefono ?>',
                                        'Gs. <?= number_format($r->monto_acumulado, 0, ',', '.') ?>',
                                        '<?= date('d/m/Y', strtotime($r->proximo_vencimiento)) ?>'
                                    )">
                                    <i class="bi bi-whatsapp"></i> WA
                                </button>
                                <a href="?c=ventas&a=pagare&id=<?= $r->id_venta ?>" target="_blank" class="btn-accion btn-print">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ===== MODAL WHATSAPP ===== -->
<div class="modal fade" id="modalWA" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-whatsapp" style="color:#25d366;font-size:1.1rem;"></i>
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

    // ══ DataTable (solo desktop) ══════════════════════════
    const table = $('#tblGestionCuotas').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
        pageLength: 15,
        dom: 'lrtip',
        columnDefs: [{
            targets: 4,
            // Buscar en el span oculto .dt-cat
            render: function(data, type, row, meta) {
                if (type === 'filter') {
                    const tmp = document.createElement('div');
                    tmp.innerHTML = data;
                    const span = tmp.querySelector('.dt-cat');
                    return span ? span.textContent.trim() : data;
                }
                return data;
            }
        }]
    });

    // ══ Pills — filtran DataTable (desktop) y cards (mobile) ══
    $('#cuotasTabs .pill-btn').on('click', function () {
        $('#cuotasTabs .pill-btn').removeClass('active');
        $(this).addClass('active');
        const filtro = $(this).data('filter');

        // Desktop: filtrar DataTable
        if (filtro === '') {
            table.column(4).search('').draw();
        } else {
            table.column(4).search('^' + filtro + '$', true, false).draw();
        }

        // Mobile: mostrar/ocultar cards
        filtrarCardsMobile(filtro);
    });

    // ══ Filtro mobile (cards) ══════════════════════════════
    function filtrarCardsMobile(filtro) {
        $('#cuotasCardsMobile .cuota-card').each(function() {
            const cat = $(this).data('categoria');
            if (!filtro || cat === filtro) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        // Si no hay resultados visibles, mostrar mensaje
        const visibles = $('#cuotasCardsMobile .cuota-card:visible').length;
        $('#noResultsMobile').remove();
        if (visibles === 0) {
            $('#cuotasCardsMobile').append(
                '<div id="noResultsMobile" style="text-align:center; color:var(--rf-muted); padding:1.5rem; font-size:.85rem;">' +
                '<i class="bi bi-search me-2"></i>Sin resultados para este filtro.</div>'
            );
        }
    }

    // ══ Select2 ═══════════════════════════════════════════
    if ($.fn.select2) {
        $('.select2').select2({ theme: 'default', dropdownAutoWidth: true, width: '100%' });
    }

});

// ══ Modal WhatsApp ════════════════════════════════════════
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