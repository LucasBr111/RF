<div class="container-fluid py-4">
    <div class="page-header mb-4">
        <h1 class="page-title">Configuración del Sistema</h1>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card-rf p-4">
                <form action="?c=config&a=guardar" method="POST">
                    <div class="row g-3">
                        <div class="col-12">
                            <h5 class="text-accent mb-3"><i class="bi bi-person-badge me-2"></i>Datos del Negocio</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre del Negocio</label>
                            <input type="text" name="owner_name" class="form-control" value="<?= htmlspecialchars($config['owner_name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono de Contacto</label>
                            <input type="text" name="owner_phone" class="form-control" value="<?= htmlspecialchars($config['owner_phone']) ?>">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Email</label>
                            <input type="email" name="owner_email" class="form-control" value="<?= htmlspecialchars($config['owner_email']) ?>">
                        </div>

                        <div class="col-12 mt-4">
                            <h5 class="text-accent mb-3"><i class="bi bi-palette me-2"></i>Personalización y Parámetros</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Color de Acento del Tema</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="color" name="theme_color" class="form-control form-control-color" value="<?= htmlspecialchars($config['theme_color']) ?>" title="Elegir color">
                                <span class="small text-muted"><?= htmlspecialchars($config['theme_color']) ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Porcentaje de Mora (%)</label>
                            <input type="number" step="0.1" name="mora_percentage" class="form-control" value="<?= htmlspecialchars($config['mora_percentage']) ?>">
                        </div>

                        <div class="col-12 mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-rf primary px-5">
                                <i class="bi bi-save me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card-rf p-4 bg-accent-dim border-accent">
                <h5 class="text-accent"><i class="bi bi-info-circle me-2"></i>Información</h5>
                <p class="small mb-0">
                    Estos ajustes afectan globalmente al sistema. El color de acento se aplicará a botones, íconos y elementos destacados.
                </p>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'success'): ?>
<script>
    window.addEventListener('load', function() {
        Swal.fire({
            title: '¡Guardado!',
            text: 'La configuración se ha actualizado correctamente.',
            icon: 'success',
            confirmButtonColor: '<?= $config['theme_color'] ?>'
        });
    });
</script>
<?php endif; ?>
