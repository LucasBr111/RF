<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark m-0">Inventario de Vehículos</h4>
        <button onclick="abrirModalVehiculo()" type="button" class="btn btn-primary px-4" >
            <i class="bi bi-car-front-fill me-2"></i>Registrar Vehículo
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="stat-card total">
                <div class="stat-icon"><i class="bi bi-truck"></i></div>
                <div>
                    <div class="stat-label">Stock de Vehículos</div>
                    <div class="stat-value"><?= $stats['total'] ?> Unidades</div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-card shadow-lg">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tblVehiculos">
                <thead>
                    <tr>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Color</th>
                        <th>Propietario</th>
                        <th>Detalle</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($vehiculos as $v): ?>
                    <tr>
                        <td class="text-bold-dark"><?= htmlspecialchars($v->modelo_nombre) ?></td>
                        <td><?= $v->anho ?></td>
                        <td><span class="badge border text-dark" style="background: #f8fafc;"><?= $v->color ?></span></td>
                        <td>
                            <span class="fw-bold">
                                <?= !empty($v->propietario) ? htmlspecialchars($v->propietario) : 'R&F Automotores' ?>
                            </span>
                        </td>
                        <td><?= $v->detalle ?: '—' ?></td>
                        <td class="text-center">
                            <button onclick='abrirModalVehiculo(<?= json_encode($v) ?>)' class="btn btn-dark btn-sm rounded-pill px-3">
                                <i class="bi bi-pencil-square"></i> Editar
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalVehiculo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="?c=Vehiculos&a=Guardar" method="POST" class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitulo">Registrar Vehículo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id_vehiculo" id="id_vehiculo">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Modelo</label>
                        <select name="id_modelo" id="id_modelo" class="form-select select2" required>
                            <option value="">Seleccione un modelo</option>
                            <?php foreach($modelos_lista as $m): ?>
                                <option value="<?= $m->id_modelo ?>"><?= $m->nombre ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Año</label>
                        <input type="number" name="anho" id="anho" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Color</label>
                        <input type="text" name="color" id="color" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Propietario</label>
                        <input type="text" name="propietario" id="propietario" class="form-control" placeholder="Dejar vacío para R&F Automotores">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Detalles / Observaciones</label>
                        <input type="text" name="detalle" id="detalle" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-dark px-4 fw-bold shadow-sm">Guardar Vehículo</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tblVehiculos').DataTable({
        language: { url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" }
    });
});

function abrirModalVehiculo(data = null) {
    const myModal = new bootstrap.Modal(document.getElementById('modalVehiculo'));
    const form = document.querySelector('#modalVehiculo form');
    form.reset();
    
    if(data) {
        document.getElementById('modalTitulo').innerText = 'Editar Vehículo';
        document.getElementById('id_vehiculo').value = data.id_vehiculo;
        document.getElementById('id_modelo').value = data.id_modelo;
        document.getElementById('anho').value = data.anho;
        document.getElementById('color').value = data.color;
        document.getElementById('detalle').value = data.detalle;
        document.getElementById('propietario').value = data.propietario;
    } else {
        document.getElementById('modalTitulo').innerText = 'Registrar Nuevo Vehículo';
        document.getElementById('id_vehiculo').value = '';
    }
    
    myModal.show();
}
</script>