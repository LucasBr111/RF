<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark m-0">Directorio de Clientes</h4>
        <button onclick="abrirModalCliente()" class="btn-nuevo shadow-sm">
            <i class="bi bi-person-plus-fill me-2"></i>Registrar Nuevo
        </button>
    </div>

    <div class="row g-3 mb-4 row-stats-mobile">
        <div class="col-md-6">
            <div class="stat-card primary">
                <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
                <div class="stat-info">
                    <div class="stat-label">Total Clientes</div>
                    <div class="stat-value"><?= $stats['total'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card success">
                <div class="stat-icon green"><i class="bi bi-check-all"></i></div>
                <div class="stat-info">
                    <div class="stat-label">Clientes Activos</div>
                    <div class="stat-value"><?= $stats['activos'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-card shadow-lg">
        <div class="table-responsive table-responsive-mobile">
            <table class="table table-hover align-middle" id="tblClientes">
                <thead>
                    <tr>
                        <th>Nombre y Apellido</th>
                        <th>C.I. / RUC</th>
                        <th>Teléfono</th>
                        <th>Ubicación</th>
                        <th>Codeudor</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($clientes as $c): ?>
                    <tr>
                        <td data-label="Nombre">
                            <a href="?c=Clientes&a=detalle&id=<?=$c->id_cliente?>" class="cliente-link">
                                <?= htmlspecialchars($c->nombre) ?>
                            </a>
                        </td>
                        <td data-label="C.I. / RUC" class="text-bold-dark"><?= $c->ci ?></td>
                        <td data-label="Teléfono"><i class="bi bi-telephone me-1 text-primary"></i> <?= $c->telefono ?></td>
                        <td data-label="Ubicación"><?= htmlspecialchars($c->ubicacion) ?></td>
                        <td data-label="Codeudor">
                            <span class="badge bg-light text-dark border fw-normal">
                                <?= $c->codeudor_nombre ?: 'N/A' ?>
                            </span>
                        </td>
                        <td data-label="Acciones" class="text-center">
                            <div class="acciones-group">
                                <button onclick='abrirModalCliente(<?= json_encode($c) ?>)' class="btn btn-dark btn-sm rounded-pill px-3">
                                    <i class="bi bi-pencil-square me-1"></i> Editar
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

<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="?c=Clientes&a=Guardar" method="POST" class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitulo">Registrar Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id_cliente" id="id_cliente">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nombre Completo</label>
                        <input type="text" name="nombre" id="nombre" class="form-control rounded-3" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Documento (CI/RUC)</label>
                        <input type="text" name="ci" id="ci" class="form-control rounded-3" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control rounded-3">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Ubicación</label>
                        <input type="text" name="ubicacion" id="ubicacion" class="form-control rounded-3">
                    </div>
                    
                    <div class="col-12 mt-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-check text-primary"></i>
                            <h6 class="m-0 fw-bold text-dark text-uppercase" style="font-size: 0.75rem;">Información del Garantía / Codeudor</h6>
                        </div>
                        <hr class="text-secondary opacity-25">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nombre Codeudor</label>
                        <input type="text" name="codeudor_nombre" id="codeudor_nombre" class="form-control rounded-3">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">CI Codeudor</label>
                        <input type="text" name="codeudor_ci" id="codeudor_ci" class="form-control rounded-3">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-dark px-4 fw-bold shadow-sm">Guardar Cliente</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tblClientes').DataTable({
        language: { url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" },
        pageLength: 10,
        dom: 'lfrtip' // Aquí si dejamos el buscador porque es gestión de lista
    });
});

function abrirModalCliente(data = null) {
    const myModal = new bootstrap.Modal(document.getElementById('modalCliente'));
    const form = document.querySelector('#modalCliente form');
    form.reset();
    
    if(data) {
        document.getElementById('modalTitulo').innerText = 'Actualizar Datos: ' + data.nombre;
        document.getElementById('id_cliente').value = data.id_cliente;
        document.getElementById('nombre').value = data.nombre;
        document.getElementById('ci').value = data.ci;
        document.getElementById('telefono').value = data.telefono;
        document.getElementById('ubicacion').value = data.ubicacion;
        document.getElementById('codeudor_nombre').value = data.codeudor_nombre;
        document.getElementById('codeudor_ci').value = data.codeudor_ci;
    } else {
        document.getElementById('modalTitulo').innerText = 'Registrar Nuevo Cliente';
        document.getElementById('id_cliente').value = '';
    }
    
    myModal.show();
}
</script>