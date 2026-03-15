<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="page-title m-0">Modelos</h4>
        <button onclick="abrirModalModelo()" type="button" 
                class="btn-rf primary btn-header-nuevo">
            <i class="bi bi-plus-circle-fill"></i>
            <span class="btn-label ms-1">Registrar</span>
        </button>
    </div>

    <div class="row g-3 mb-3 row-stats-mobile">
        <div class="col-12">
            <div class="stat-card primary">
                <div class="stat-icon blue"><i class="bi bi-tag-fill"></i></div>
                <div>
                    <div class="stat-label">Modelos Registrados</div>
                    <div class="stat-value"><?= count($modelos) ?> <span style="font-size:1rem; color:var(--rf-muted); font-weight:500;">categorías</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile-cards-list d-md-none" id="modelosCardsMobile">
        <?php if (empty($modelos)): ?>
            <div style="text-align:center; color:var(--rf-muted); padding:1.5rem; font-size:.85rem;">
                <i class="bi bi-folder-x me-2"></i>Sin modelos registrados.
            </div>
        <?php else: ?>
            <?php foreach ($modelos as $m): ?>
            <div class="mc">
                <div class="mc-top">
                    <span class="mc-title"><?= htmlspecialchars($m->nombre) ?></span>
                    <span class="badge-rf info" style="font-size:.65rem;">
                        #<?= $m->id_modelo ?>
                    </span>
                </div>
                
                <div class="mc-info">
                    <span>📦 Stock disponible: <strong><?= $m->cantidad ?></strong></span>
                </div>

                <div class="mc-foot">
                    <button onclick='abrirModalModelo(<?= json_encode($m) ?>)' 
                            class="btn-accion btn-ver" style="flex:1; justify-content:center;">
                        <i class="bi bi-pencil-square"></i> Editar Modelo
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="table-card has-mobile-cards d-none d-md-block">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tblModelos">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Nombre del Modelo</th>
                        <th class="text-center">Stock Unidades</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($modelos as $m): ?>
                    <tr>
                        <td class="font-mono" style="color:var(--rf-muted);">#<?= $m->id_modelo ?></td>
                        <td style="font-weight:600;"><?= htmlspecialchars($m->nombre) ?></td>
                        <td class="text-center">
                            <span class="badge-rf primary"><?= $m->cantidad ?></span>
                        </td>
                        <td>
                            <div class="acciones-group justify-content-end">
                                <button onclick='abrirModalModelo(<?= json_encode($m) ?>)' 
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

<div class="modal fade" id="modalModelo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="?c=Modelos&a=Guardar" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Registrar Modelo</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id_modelo" id="id_modelo">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Nombre del Modelo</label>
                        <input type="text" name="nombre" id="nombre_modelo" 
                               class="form-control" placeholder="Ej: TOYOTA PREMIO" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn-rf primary px-4">Guardar Modelo</button>
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
    const modalEl = document.getElementById('modalModelo');
    const form = modalEl.querySelector('form');
    form.reset();
    
    if(data) {
        document.getElementById('modalTitulo').innerText = 'Editar Modelo';
        document.getElementById('id_modelo').value = data.id_modelo;
        document.getElementById('nombre_modelo').value = data.nombre;
    } else {
        document.getElementById('modalTitulo').innerText = 'Registrar Nuevo Modelo';
        document.getElementById('id_modelo').value = '';
    }
    
    new bootstrap.Modal(modalEl).show();
}
</script>