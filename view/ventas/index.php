<div class="modal fade" id="modalRegistroVenta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="bi bi-cart-plus me-2"></i>Nueva Operación de Venta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRegistroVenta">
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">
                        
                        <div class="col-md-6">
                            <div class="card-rf p-3 h-100">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-person-badge me-2"></i>Información del Cliente</h6>
                                <div class="row g-2">
                                    <div class="col-md-12">
                                        <input type="text" name="cliente_nombre" class="form-control" placeholder="Nombre Completo del Deudor" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="cliente_ci" class="form-control" placeholder="CI Deudor" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="cliente_tel" class="form-control" placeholder="Teléfono/WhatsApp" required>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" name="co_deudor_nombre" class="form-control" placeholder="Nombre Co-Deudor (Opcional)">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="co_deudor_ci" class="form-control" placeholder="CI Co-Deudor">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="ubicacion" class="form-control" placeholder="Ubicación/Dirección">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card-rf p-3 h-100">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-car-front me-2"></i>Detalles del Vehículo</h6>
                                <div class="row g-2">
                                    <div class="col-md-12">
                                        <label class="form-label small">Modelo</label>
                                        <select name="id_modelo" id="selectModelo" class="form-select select2" required style="width: 100%;">
                                            <option value="">Seleccionar Modelo...</option>
                                            <option value="OTRO">-- OTRO (Registrar nuevo) --</option>
                                            </select>
                                    </div>
                                    <div id="divNuevoModelo" class="col-md-12 d-none">
                                        <input type="text" name="nuevo_modelo_nombre" class="form-control border-warning" placeholder="Escribe el nombre del nuevo modelo">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number" name="vehiculo_anho" class="form-control" placeholder="Año">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="vehiculo_color" class="form-control" placeholder="Color">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card-rf p-3">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-calculator me-2"></i>Plan de Pagos</h6>
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <label class="small">Rango: Desde</label>
                                        <input type="number" name="rango_inicio" class="form-control" value="1" min="1" max="40">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="small">Hasta</label>
                                        <input type="number" name="rango_fin" class="form-control" value="12" min="1" max="40">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small">Monto por Cuota (₲)</label>
                                        <input type="text" name="monto_cuota" class="form-control mask-guarani" placeholder="1.000.000">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="small">% Mora Diario</label>
                                        <input type="number" name="interes_mora" class="form-control" step="0.01" value="0.10">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small">Fecha primer vencimiento</label>
                                        <input type="date" name="fecha_inicio" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card-rf p-3" style="border-left: 5px solid var(--rf-accent);">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-plus-circle me-2"></i>Refuerzos (Opcional)</h6>
                                <div class="row g-2 align-items-end" id="containerRefuerzos">
                                    <div class="col-md-3">
                                        <label class="small">Monto Refuerzo (₲)</label>
                                        <input type="text" name="refuerzo_monto[]" class="form-control mask-guarani" placeholder="0">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small">Mes (N° de cuota)</label>
                                        <input type="number" name="refuerzo_mes[]" class="form-control" placeholder="Ej: 6">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="agregarFilaRefuerzo()">
                                            <i class="bi bi-plus-lg"></i> Añadir otro
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-5">Guardar Venta y Generar Cuotas</button>
                </div>
            </form>
        </div>
    </div>
</div>