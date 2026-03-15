<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark m-0">Catálogo de Modelos</h4>
        <button onclick="abrirModalModelo()" class="btn-nuevo shadow-sm">
            <i class="bi bi-plus-circle-fill me-2"></i>Registrar Nuevo
        </button>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="stat-card total">
                <div class="stat-icon"><i class="bi bi-car-front-fill"></i></div>
                <div>
                    <div class="stat-label">Modelos Registrados</div>
                    <div class="stat-value"><?= count($modelos) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-card shadow-lg">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tblModelos">
                <thead>
                    <tr>
                        <th style="width: 100px;">ID</th>
                        <th>Nombre del Modelo</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($modelos as $m): ?>
                    <tr>
                        <td class="small">#<?= $m->id_modelo ?></td>
                        <td>
                            <span class="fw-bold text"><?= htmlspecialchars($m->nombre) ?></span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary"><?= $m->cantidad ?></span>
                        </td>
                        <td class="text-center">
                            <button onclick='abrirModalModelo(<?= json_encode($m) ?>)' class="btn btn-dark btn-sm rounded-pill px-3">
                                <i class="bi bi-pencil-square me-1"></i> Editar
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalModelo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="?c=Modelos&a=Guardar" method="POST" class="modal-content shadow-lg border-0">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="modalTitulo">Registrar Modelo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id_modelo" id="id_modelo">
                
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Nombre del Modelo</label>
                        <input type="text" name="nombre" id="nombre_modelo" 
                               class="form-control rounded-3" 
                               placeholder="Ej: TOYOTA PREMIO" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-dark px-4 fw-bold shadow-sm">Guardar Modelo</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tblModelos').DataTable({
        language: { url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json" },
        pageLength: 15,
        dom: 'lfrtip'
    });
});

function abrirModalModelo(data = null) {
    const myModal = new bootstrap.Modal(document.getElementById('modalModelo'));
    const form = document.querySelector('#modalModelo form');
    form.reset();
    
    if(data) {
        document.getElementById('modalTitulo').innerText = 'Actualizar Modelo';
        document.getElementById('id_modelo').value = data.id_modelo;
        document.getElementById('nombre_modelo').value = data.nombre;
    } else {
        document.getElementById('modalTitulo').innerText = 'Registrar Nuevo Modelo';
        document.getElementById('id_modelo').value = '';
    }
    
    myModal.show();
}
</script>