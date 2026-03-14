<?php
// view/cuotas/index.php
// Asegurarse que $stats y $cuotas y $modelos_lista vienen del controller
?>



<div class="container-fluid py-4">

    <!-- ===== FILTROS ===== -->
    <div class="filtros-card mb-4">
        <h5><i class="bi bi-sliders me-2"></i>Filtros</h5>
        <form class="row g-3" method="POST">
            <div class="col-md-4">
                <label class="form-label">Modelo</label>
                <select class="form-select select2" name="id_modelo">
                    <option value="">Todos los modelos</option>
                    <?php foreach($modelos_lista as $m): ?>
                        <option value="<?= $m->id_modelo ?>"><?= htmlspecialchars($m->nombre) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
           <!--  <div class="col-md-3">
                <label class="form-label">Vencimiento desde</label>
                <input type="date" class="form-control" name="desde" value="<?= $_POST['desde'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Hasta</label>
                <input type="date" class="form-control" name="hasta" value="<?= $_POST['hasta'] ?? '' ?>">
            </div> -->
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn-aplicar">
                    <i class="bi bi-search me-1"></i> Aplicar
                </button>
            </div>
        </form>
    </div>

    <!-- ===== STAT CARDS ===== -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card hoy">
                <div class="stat-icon"><i class="bi bi-clock-history"></i></div>
                <div>
                    <div class="stat-label">Vence hoy</div>
                    <div class="stat-value"><?= $stats['vence_hoy'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card atrasado">
                <div class="stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
                <div>
                    <div class="stat-label">Atrasados</div>
                    <div class="stat-value"><?= $stats['atrasados'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card muy-atrasado">
                <div class="stat-icon"><i class="bi bi-fire"></i></div>
                <div>
                    <div class="stat-label">ACUMULADO +2 MESESs</div>
                    <div class="stat-value"><?= $stats['muy_atrasados'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== TABS FILTRO ===== -->
    <div class="pill-tabs" id="cuotasTabs">
    <button class="pill-btn active" data-filter="">
        <i class="bi bi-grid-3x3-gap me-1"></i>Todos
    </button>

    <button class="pill-btn" data-filter="VENCE HOY">
        <i class="bi bi-clock me-1"></i>Vence hoy
    </button>

    <button class="pill-btn" data-filter="PENDIENTE">
        <i class="bi bi-hourglass-split me-1"></i>Pendientes
    </button>

    <button class="pill-btn" data-filter="ATRASADO">
        <i class="bi bi-exclamation-circle me-1"></i>Atrasados
    </button>

    <button class="pill-btn" data-filter="ACUMULADO +2 MESES">
        <i class="bi bi-x-octagon me-1"></i>ACUMULADO +2 MESESs
    </button>

    <button class="pill-btn" data-filter="AL DIA">
        <i class="bi bi-check-circle me-1"></i>Al día
    </button>
    </div>

    <!-- ===== TABLA ===== -->
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
                        $cat_lower = strtolower(str_replace(' ', '-', $r->categoria));
                        // Normalizar clase badge
                        $badge_class = 'al-dia';
                        if ($r->categoria == 'ACUMULADO +2 MESES')  $badge_class = 'muy-atrasado';
                        elseif ($r->categoria == 'ATRASADO')  $badge_class = 'atrasado';
                        elseif ($r->categoria == 'VENCE HOY') $badge_class = 'hoy';
                        elseif ($r->categoria == 'PENDIENTE') $badge_class = 'pendiente';

                        $badge_icon = '✓';
                        if ($r->categoria == 'ACUMULADO +2 MESES')  $badge_icon = '🔴';
                        elseif ($r->categoria == 'ATRASADO')  $badge_icon = '⚠️';
                        elseif ($r->categoria == 'VENCE HOY') $badge_icon = '🕐';
                        elseif ($r->categoria == 'PENDIENTE') $badge_icon = '⏳';
                        elseif ($r->categoria == 'AL DIA')    $badge_icon = '✓';
                    ?>
                    <tr>
                        <td>
                            <a href="?c=clientes&a=detalle&id=<?= $r->id_cliente ?>" class="cliente-link">
                                <?= htmlspecialchars($r->cliente_nombre) ?>
                            </a>
                        </td>
                        <td style="color:var(--rf-muted);"><?= htmlspecialchars($r->modelo_nombre) ?></td>
                        <td style="font-family:'JetBrains Mono',monospace; font-size:.8rem; color:var(--rf-muted);">
                            <?= date("d/m/Y", strtotime($r->proximo_vencimiento)) ?>
                        </td>
                        <td>
                            <span class="deuda-val">
                                Gs. <?= number_format($r->monto_acumulado, 0, ",", ".") ?>
                            </span>
                        </td>
                        <td>
                            <!-- valor para filtro DataTables — oculto, badge visible -->
                            <span style="display: none;"?= $r->categoria ?></span>
                            <span class="badge-estado <?= $badge_class ?>">
                                <?= $badge_icon ?> <?= $r->categoria ?>
                            </span>
                        </td>
                        <td>
                            <div class="acciones-group">
                                <!-- Ver cuotas -->
                                <a href="?c=cuotas&a=detalle&id=<?= $r->id_venta ?>"
                                   class="btn-accion btn-ver" title="Ver cuotas">
                                    <i class="bi bi-list-check"></i> Cuotas
                                </a>
                                <!-- WhatsApp -->
                                <button type="button"
                                    class="btn-accion btn-wa"
                                    title="Enviar WhatsApp"
                                    onclick="abrirModalWA(
                                        '<?= addslashes($r->cliente_nombre) ?>',
                                        '<?= $r->telefono ?>',
                                        'Gs. <?= number_format($r->monto_acumulado, 0, ',', '.') ?>',
                                        '<?= date('d/m/Y', strtotime($r->proximo_vencimiento)) ?>'
                                    )">
                                    <i class="bi bi-whatsapp"></i> WA
                                </button>
                                <!-- Pagaré -->
                                <a href="?c=ventas&a=pagare&id=<?= $r->id_venta ?>" target="_blank"
                                   class="btn-accion btn-print" title="Imprimir pagaré">
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
    <div class="modal-dialog modal-dialog-centered modal-wa" style="max-width:480px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span style="color:#25d366;font-size:1.2rem;"><i class="bi bi-whatsapp"></i></span>
                    Enviar recordatorio
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="info-cliente mb-3">
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

<script>
$(document).ready(function () {

    // ---- DataTable ----
    const table = $('#tblGestionCuotas').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
        pageLength: 15,
        dom: 'lrtip',
        columnDefs: [
            // Columna 4 (Estado): usar el texto del span oculto para búsqueda
            {
                targets: 4,
                searchable: true
            }
        ]
    });

    // ---- Filtro por pills ----
    $('#cuotasTabs .pill-btn').on('click', function () {
        $('#cuotasTabs .pill-btn').removeClass('active');
        $(this).addClass('active');

        const filtro = $(this).data('filter');
        // Busca en col 4 con exact match (regex)
        if (filtro === '') {
            table.search('').draw();
        } else {
            table.search(filtro).draw();
        }
    });

    // ---- Select2 dark ----
    if ($.fn.select2) {
        $('.select2').select2({ theme: 'default', dropdownAutoWidth: true });
    }
});

// ---- Modal WhatsApp ----
function abrirModalWA(nombre, telefono, deuda, vencimiento) {
    $('#wa-nombre').text(nombre);
    $('#wa-telefono').text(telefono);
    $('#wa-deuda').text(deuda);

    const msg = `Estimado/a *${nombre}*, le recordamos que tiene una deuda pendiente de *${deuda}* con vencimiento el *${vencimiento}*. Por favor, comuníquese con nosotros para coordinar el pago. ¡Muchas gracias!`;

    $('#wa-mensaje').val(msg);

    const linkBase = 'https://wa.me/595' + telefono + '?text=' + encodeURIComponent(msg);
    $('#wa-link').attr('href', linkBase);

    // Actualizar link al editar el mensaje
    $('#wa-mensaje').off('input').on('input', function () {
        const textoActual = $(this).val();
        $('#wa-link').attr('href', 'https://wa.me/595' + telefono + '?text=' + encodeURIComponent(textoActual));
    });

    const modal = new bootstrap.Modal(document.getElementById('modalWA'));
    modal.show();
}
</script>