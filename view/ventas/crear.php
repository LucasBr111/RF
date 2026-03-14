<div class="row g-4">
    <div class="col-12 col-xl-4">
        <div class="card-rf h-100 p-4">
            <h5 class="fw-bold mb-4 text-accent"><i class="bi bi-person-plus me-2"></i>Cliente y Co-Deudor</h5>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-bold">Nombre del Deudor</label>
                    <input type="text" id="cliente_nombre" class="form-control" placeholder="Ej: Juan Pérez">
                </div>
                <div class="col-md-6">
                    <label class="form-label small">CI Deudor</label>
                    <input type="text" id="cliente_ci" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label small">Teléfono</label>
                    <input type="text" id="cliente_tel" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">Nombre del Co-Deudor</label>
                    <input type="text" id="codeudor_nombre" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label small fw-bold">CI del Co-Deudor</label>
                    <input type="text" id="codeudor_ci" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label small">Ubicación / Referencia</label>
                    <textarea id="ubicacion" class="form-control" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-8">
        <div class="row g-4">
<div class="col-md-6">
    <div class="card-rf p-4 h-100">
        <h5 class="fw-bold mb-3 text-accent">
            <i class="bi bi-car-front me-2"></i>Vehículo
        </h5>

        <div class="row g-2">

            <div class="col-12">
                <select id="id_modelo" class="form-select select2">
                    <?php 
                    if (!empty($modelos)) {
                        foreach ($modelos as $modelo): ?>
                            <option value="<?php echo htmlspecialchars($modelo->id_modelo); ?>">
                                <?php echo htmlspecialchars($modelo->nombre); ?>
                            </option>
                        <?php endforeach;
                    } ?>
                    <option value="OTRO">-- REGISTRAR NUEVO --</option>
                </select>
            </div>

            <div id="nuevo_modelo_div" class="col-12 d-none">
                <input type="text" id="nuevo_modelo_nombre" class="form-control border-accent" placeholder="Nombre del nuevo modelo">
            </div>

            <div class="col-6">
                <input type="number" id="anho" class="form-control" placeholder="Año">
            </div>

            <div class="col-6">
                <input type="text" id="color" class="form-control" placeholder="Color">
            </div>
<br><br><br><br>
            <!-- Observaciones -->
            <div class="col-12 mt-2">
                <h5 class="fw-bold mb-3 text-accent">
                    <i class="bi bi-chat-left-text me-1"></i>Observaciones
                </h5>
                <textarea 
                    id="observaciones" 
                    class="form-control" 
                    rows="3" 
                    placeholder="Ej: Detalles del vehículo, estado, accesorios, comentarios del cliente...">
                </textarea>
            </div>

        </div>
    </div>
</div>

            <div class="col-md-6">
                <div class="card-rf p-4">
                    <h5 class="fw-bold mb-3 text-accent"><i class="bi bi-gear-wide-connected me-2"></i>Parámetros</h5>
                    <div class="row g-2">
                        <div class="col-4">
                            <label class="small">Desde</label>
                            <input type="number" id="rango_inicio" class="form-control" value="1">
                        </div>
                        <div class="col-4">
                            <label class="small">Hasta</label>
                            <input type="number" id="rango_fin" class="form-control" value="40">
                        </div>
                        <div class="col-4">
                            <label class="small">Mora %</label>
                            <input type="number" id="interes_mora" class="form-control" value="3">
                        </div>
                        <div class="col-12">
                            <label class="small">Monto Cuota Normal (₲)</label>
                            <input type="text" id="monto_cuota_visual" class="form-control mask-guarani" placeholder="0">
                        </div>
                        <div class="col-12">
                            <label class="small">Primer Vencimiento</label>
                            <input type="date" id="fecha_inicio" class="form-control">
                        </div>

                        <hr class="my-3">
                        <div class="col-12"><label class="small fw-bold text-accent">Configuración de Refuerzos</label></div>

                        <div class="col-12">
                            <label class="small">Monto Refuerzo (₲)</label>
                            <input type="text" id="monto_refuerzo_visual" class="form-control mask-guarani" placeholder="0">
                        </div>
                        <div class="col-6">
                            <label class="small">Mes de Cobro</label>
                            <select id="mes_refuerzo" class="form-select">
                                <option value="12">Diciembre</option>
                                <option value="6">Junio</option>
                                <option value="1">Enero</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="small">Cant. Refuerzos</label>
                            <input type="number" id="cant_refuerzos" class="form-control" value="3" min="0">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card-rf p-4 bg-dark text-white border-accent">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="row text-center text-md-start">
                                <div class="col-md-4">
                                    <small class="text">Total Cuotas</small>
                                    <h5 id="res_total_normal">₲ 0</h5>
                                </div>
                                <div class="col-md-4">
                                    <small class="text">Total Refuerzos</small>
                                    <h5 id="res_total_refuerzos">₲ 0</h5>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-accent fw-bold">TOTAL FINANCIADO</small>
                                    <h4 id="res_total_final" class="text-accent fw-bold">₲ 0</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-primary px-4" onclick="abrirModalPreview()">
                                <i class="bi bi-table me-2"></i>GENERAR TABLA
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCuotas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark border-accent text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-accent">Previsualización de Cuotas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-dark table-hover table-sm">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Vencimiento</th>
                                <th>Monto</th>
                                <th>Tipo</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="cuerpoPreview"></tbody>
                    </table>
                </div>
                <div id="paginationContainer" class="d-flex justify-content-center mt-3 gap-2"></div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary px-4" onclick="guardarTodo()">
                    <i class="bi bi-check-all me-2"></i>CONFIRMAR Y GUARDAR VENTA
                </button>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/ventas.js"></script>

<script>
    $('#id_modelo').on('change', function() {
        if ($(this).val() === 'OTRO') {
            $('#nuevo_modelo_div').removeClass('d-none');
            $('#nuevo_modelo_nombre').focus();
        } else {
            $('#nuevo_modelo_div').addClass('d-none');
            $('#nuevo_modelo_nombre').val('');
        }
    });
</script>