<?php
// view/vehiculos/index.php
// Variables: $vehiculos (array), $stats['total'], $modelos_lista
?>

<div class="container-fluid py-3">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="page-title m-0">Vehículos</h4>
        <button onclick="abrirModalVehiculo()" type="button"
                class="btn-rf primary btn-header-nuevo">
            <i class="bi bi-car-front-fill"></i>
            <span class="btn-label ms-1">Registrar</span>
        </button>
    </div>

    <!-- Stat única -->
    <div class="row g-3 mb-3 row-stats-mobile">
        <div class="col-12">
            <div class="stat-card primary">
                <div class="stat-icon blue"><i class="bi bi-truck"></i></div>
                <div>
                    <div class="stat-label">Stock de vehículos</div>
                    <div class="stat-value"><?= $stats['total'] ?> <span style="font-size:1rem; color:var(--rf-muted); font-weight:500;">unidades</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ► CARDS MOBILE -->
    <div class="mobile-cards-list" id="vehiculosCardsMobile">

        <?php if (empty($vehiculos)): ?>
        <div style="text-align:center; color:var(--rf-muted); padding:1.5rem; font-size:.85rem;">
            <i class="bi bi-car-front me-2"></i>Sin vehículos registrados.
        </div>
        <?php else: ?>

        <?php foreach ($vehiculos as $v): ?>
        <div class="mc">
            <!-- Modelo + año -->
            <div class="mc-top">
                <span class="mc-title"><?= htmlspecialchars($v->modelo_nombre) ?></span>
                <span class="badge-rf primary" style="font-size:.65rem; flex-shrink:0;">
                    <?= $v->anho ?>
                </span>
            </div>

            <!-- Info secundaria -->
            <div class="mc-info">
                <span>🎨 <?= htmlspecialchars($v->color) ?></span>
                <span>
                    👤 <?= !empty($v->propietario) ? htmlspecialchars($v->propietario) : 'R&F Automotores' ?>
                </span>
            </div>

            <?php if ($v->detalle): ?>
            <div style="font-size:.72rem; color:var(--rf-muted);">
                📝 <?= htmlspecialchars($v->detalle) ?>
            </div>
            <?php endif; ?>

            <!-- Acciones -->
            <div class="mc-foot">
                <button onclick='abrirModalVehiculo(<?= json_encode($v) ?>)'
                        class="btn-accion btn-ver" style="flex:1; justify-content:center;">
                    <i class="bi bi-pencil-square"></i> Editar
                </button>
            </div>
        </div>
        <?php endforeach; ?>

        <?php endif; ?>
    </div>

    <!-- ► TABLA DESKTOP -->
    <div class="table-card has-mobile-cards">
        <div class="table-responsive">
            <table class="table table-hover" id="tblVehiculos">
                <thead>
                    <tr>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Color</th>
                        <th>Propietario</th>
                        <th>Detalle</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehiculos as $v): ?>
                    <tr>
                        <td style="font-weight:600;"><?= htmlspecialchars($v->modelo_nombre) ?></td>
                        <td class="font-mono" style="color:var(--rf-muted);"><?= $v->anho ?></td>
                        <td>
                            <span class="badge-rf info"><?= htmlspecialchars($v->color) ?></span>
                        </td>
                        <td style="font-weight:600; font-size:.85rem;">
                            <?= !empty($v->propietario) ? htmlspecialchars($v->propietario) : 'R&F Automotores' ?>
                        </td>
                        <td style="color:var(--rf-muted); font-size:.82rem;">
                            <?= $v->detalle ?: '—' ?>
                        </td>
                        <td>
                            <div class="acciones-group">
                                <button onclick='abrirModalVehiculo(<?= json_encode($v) ?>)'
                                        class="btn-accion btn-print">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal vehículo -->
<div class="modal fade" id="modalVehiculo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="?c=Vehiculos&a=Guardar" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Registrar Vehículo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id_vehiculo" id="id_vehiculo">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Modelo</label>
                        <select name="id_modelo" id="id_modelo" class="form-select select2" required>
                            <option value="">Seleccione un modelo</option>
                            <?php foreach ($modelos_lista as $m): ?>
                                <option value="<?= $m->id_modelo ?>"><?= htmlspecialchars($m->nombre) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Año</label>
                        <input type="number" name="anho" id="anho" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Color</label>
                        <input type="text" name="color" id="color" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Propietario</label>
                        <input type="text" name="propietario" id="propietario" class="form-control"
                               placeholder="Dejar vacío: R&F Automotores">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Detalle / Observaciones</label>
                        <input type="text" name="detalle" id="detalle" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn-rf primary px-4">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#tblVehiculos').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
        pageLength: 15,
        dom: 'lfrtip'
    });

    if ($.fn.select2) {
        $('.select2').select2({ theme: 'default', width: '100%' });
    }
});

function abrirModalVehiculo(data = null) {
    const form = document.querySelector('#modalVehiculo form');
    form.reset();

    if (data) {
        document.getElementById('modalTitulo').innerText  = 'Editar Vehículo';
        document.getElementById('id_vehiculo').value     = data.id_vehiculo;
        document.getElementById('id_modelo').value       = data.id_modelo;
        document.getElementById('anho').value            = data.anho;
        document.getElementById('color').value           = data.color;
        document.getElementById('detalle').value         = data.detalle ?? '';
        document.getElementById('propietario').value     = data.propietario ?? '';
    } else {
        document.getElementById('modalTitulo').innerText = 'Registrar Nuevo Vehículo';
        document.getElementById('id_vehiculo').value    = '';
    }

    new bootstrap.Modal(document.getElementById('modalVehiculo')).show();
}
</script>