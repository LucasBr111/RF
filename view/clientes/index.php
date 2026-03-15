<?php
// view/clientes/index.php
// Variables: $clientes (array de objetos), $stats['total'], $stats['activos']
?>

<div class="container-fluid py-3">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="page-title m-0">Clientes</h4>
        <button onclick="abrirModalCliente()" type="button"
                class="btn-rf primary btn-header-nuevo">
            <i class="bi bi-person-plus-fill"></i>
            <span class="btn-label ms-1">Registrar</span>
        </button>
    </div>

    <!-- Stats -->
    <div class="row g-3 mb-3 row-stats-mobile">
        <div class="col-6">
            <div class="stat-card primary">
                <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="stat-label">Total</div>
                    <div class="stat-value"><?= $stats['total'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="stat-card success">
                <div class="stat-icon green"><i class="bi bi-check-all"></i></div>
                <div>
                    <div class="stat-label">Activos</div>
                    <div class="stat-value"><?= $stats['activos'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ► CARDS MOBILE -->
    <div class="mobile-cards-list" id="clientesCardsMobile">

        <?php if (empty($clientes)): ?>
        <div style="text-align:center; color:var(--rf-muted); padding:1.5rem; font-size:.85rem;">
            <i class="bi bi-person-x me-2"></i>Sin clientes registrados.
        </div>
        <?php else: ?>

        <?php foreach ($clientes as $c): ?>
        <div class="mc">
            <!-- Nombre -->
            <div class="mc-top">
                <a href="?c=Clientes&a=detalle&id=<?= $c->id_cliente ?>" class="mc-title">
                    <?= htmlspecialchars($c->nombre) ?>
                </a>
                <?php if ($c->codeudor_nombre): ?>
                <span class="badge-rf info" style="font-size:.6rem; flex-shrink:0;">
                    <i class="bi bi-shield-check me-1"></i><?= htmlspecialchars($c->codeudor_nombre) ?>
                </span>
                <?php endif; ?>
            </div>

            <!-- Info secundaria -->
            <div class="mc-info">
                <span>📄 <?= $c->ci ?></span>
                <span>📱 <?= $c->telefono ?></span>
            </div>
            <?php if ($c->ubicacion): ?>
            <div style="font-size:.72rem; color:var(--rf-muted);">
                📍 <?= htmlspecialchars($c->ubicacion) ?>
            </div>
            <?php endif; ?>

            <!-- Acciones -->
            <div class="mc-foot">
                <a href="?c=Clientes&a=detalle&id=<?= $c->id_cliente ?>" class="btn-accion btn-ver">
                    <i class="bi bi-eye"></i> Ver detalle
                </a>
                <button onclick='abrirModalCliente(<?= json_encode($c) ?>)' class="btn-accion btn-print">
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
            <table class="table table-hover" id="tblClientes">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>C.I. / RUC</th>
                        <th>Teléfono</th>
                        <th>Ubicación</th>
                        <th>Codeudor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $c): ?>
                    <tr>
                        <td>
                            <a href="?c=Clientes&a=detalle&id=<?= $c->id_cliente ?>" class="cliente-link">
                                <?= htmlspecialchars($c->nombre) ?>
                            </a>
                        </td>
                        <td class="font-mono" style="font-size:.82rem; color:var(--rf-muted);"><?= $c->ci ?></td>
                        <td style="color:var(--rf-muted);">
                            <i class="bi bi-telephone me-1" style="color:var(--rf-accent);"></i><?= $c->telefono ?>
                        </td>
                        <td style="color:var(--rf-muted); font-size:.82rem;"><?= htmlspecialchars($c->ubicacion) ?></td>
                        <td>
                            <?php if ($c->codeudor_nombre): ?>
                            <span class="badge-rf info"><?= htmlspecialchars($c->codeudor_nombre) ?></span>
                            <?php else: ?>
                            <span style="color:var(--rf-muted); font-size:.78rem;">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="acciones-group">
                                <button onclick='abrirModalCliente(<?= json_encode($c) ?>)'
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

<!-- Modal cliente (sin cambios funcionales, solo estilos) -->
<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="?c=Clientes&a=Guardar" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Registrar Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" name="id_cliente" id="id_cliente">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre Completo</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Documento (CI/RUC)</label>
                        <input type="text" name="ci" id="ci" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" id="telefono" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ubicación</label>
                        <input type="text" name="ubicacion" id="ubicacion" class="form-control">
                    </div>

                    <div class="col-12 mt-2">
                        <div style="display:flex; align-items:center; gap:.5rem; margin-bottom:.5rem;">
                            <i class="bi bi-shield-check" style="color:var(--rf-accent);"></i>
                            <span style="font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--rf-muted);">
                                Garantía / Codeudor
                            </span>
                        </div>
                        <hr style="border-color:var(--rf-border); margin:.25rem 0 .75rem;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nombre Codeudor</label>
                        <input type="text" name="codeudor_nombre" id="codeudor_nombre" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">CI Codeudor</label>
                        <input type="text" name="codeudor_ci" id="codeudor_ci" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancelar" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn-rf primary px-4">Guardar Cliente</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function () {
    $('#tblClientes').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
        pageLength: 15,
        dom: 'lfrtip'
    });
});

function abrirModalCliente(data = null) {
    const form = document.querySelector('#modalCliente form');
    form.reset();

    if (data) {
        document.getElementById('modalTitulo').innerText = 'Editar: ' + data.nombre;
        document.getElementById('id_cliente').value     = data.id_cliente;
        document.getElementById('nombre').value         = data.nombre;
        document.getElementById('ci').value             = data.ci;
        document.getElementById('telefono').value       = data.telefono;
        document.getElementById('ubicacion').value      = data.ubicacion;
        document.getElementById('codeudor_nombre').value = data.codeudor_nombre ?? '';
        document.getElementById('codeudor_ci').value    = data.codeudor_ci ?? '';
    } else {
        document.getElementById('modalTitulo').innerText = 'Registrar Nuevo Cliente';
        document.getElementById('id_cliente').value = '';
    }

    new bootstrap.Modal(document.getElementById('modalCliente')).show();
}
</script>