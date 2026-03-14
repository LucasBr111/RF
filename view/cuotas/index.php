<div class="row g-3 mb-4">
    <div class="col-md-12">
        <div class="card-rf p-3">
            <h5 class="mb-3"><i class="bi bi-filter-left me-2"></i>Filtros de Búsqueda</h5>
            <form class="row g-3" id="filterForm">
                <div class="col-md-3">
                    <label class="form-label">Vehículo</label>
                    <select class="form-select select2" name="vehiculo">
                        <option value="">Todos los modelos</option>
                        <option value="1">Toyota Hilux</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Desde</label>
                    <input type="date" class="form-control" name="fecha_desde">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Hasta</label>
                    <input type="date" class="form-control" name="fecha_hasta">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Mín. Cuotas Atrasadas</label>
                    <input type="number" class="form-control" placeholder="Ej: 3" name="min_atraso">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card-rf">
    <div class="card-header-rf d-flex justify-content-between align-items-center">
        <h2 class="card-title-rf">Listado de Deudores (Cuota más antigua)</h2>
        <div class="btn-group shadow-sm">
            <button class="btn btn-light btn-sm active">Todos</button>
            <button class="btn btn-light btn-sm text-danger">Atrasados</button>
            <button class="btn btn-light btn-sm text-success">Al día</button>
        </div>
    </div>
    <div class="card-body-rf">
        <div class="table-responsive">
            <table class="table datatable" id="tblGestionCuotas">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Cant. Atrasado</th>
                        <th>Vencimiento</th>
                        <th>Monto</th>
                        <th>Vehículo</th>
                        <th>Mora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <a href="index.php?c=clientes&a=detalle&id=1" class="text-accent fw-bold text-decoration-none">
                                Lucas Britez
                            </a>
                        </td>
                        <td>3</td>
                        <td>15/02/2026</td>
                        <td>₲ 1.500.000</td>
                        <td>Hyundai Tucson 2018</td>
                        <td class="text-danger">₲ 45.000</td>
                        <td><span class="badge-rf danger">Bastante Atrasado</span></td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-success" onclick="abrirModalCobro(1)"><i class="bi bi-cash"></i></button>
                                <a href="https://wa.me/595981000000" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-whatsapp"></i></a>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <a href="#" class="text-accent fw-bold text-decoration-none">Ramón Ayala</a>
                        </td>
                        <td>6</td>
                        <td>20/03/2026</td>
                        <td>₲ 1.200.000</td>
                        <td>Kia Sportage 2020</td>
                        <td>₲ 0</td>
                        <td><span class="badge-rf success">Al día</span></td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-success"><i class="bi bi-cash"></i></button>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-whatsapp"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPagos" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Historial de Cuotas y Pagos</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="historialContenido">
                    <p class="text-muted">Cargando detalles del cliente...</p>
                </div>
            </div>
        </div>
    </div>
</div>