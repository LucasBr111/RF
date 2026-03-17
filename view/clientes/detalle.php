<div class="container-fluid py-4">
    <!-- Breadcrumbs -->
    <div class="mb-3">
        <a href="?c=clientes" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Volver a Clientes
        </a>
    </div>

    <!-- Client Header -->
    <div class="card shadow-lg border-0 rounded-4 mb-4" style="background: linear-gradient(135deg, #1f2937, #111827); color: white;">
        <div class="card-body p-4 p-md-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="fw-bold text-white mb-2"><i class="bi bi-person-bounding-box me-3 text-primary"></i><?= htmlspecialchars($cliente->nombre) ?></h2>
                    <div class="d-flex flex-wrap gap-3 mt-3">
                        <span class="badge bg-dark border border-secondary p-2"><i class="bi bi-card-text me-2"></i><?= htmlspecialchars($cliente->ci) ?></span>
                        <span class="badge bg-dark border border-secondary p-2"><i class="bi bi-geo-alt me-2"></i><?= htmlspecialchars($cliente->ubicacion ?: 'Sin Ubicación') ?></span>
                        <span class="badge bg-dark border border-secondary p-2"><i class="bi bi-people me-2"></i>Codeudor: <?= htmlspecialchars($cliente->codeudor_nombre ?: 'Ninguno') ?></span>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                    <?php if ($cliente->telefono): ?>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $cliente->telefono) ?>" target="_blank" class="btn btn-success rounded-pill px-4 py-2 shadow-sm fs-5">
                            <i class="bi bi-whatsapp me-2"></i> <?= htmlspecialchars($cliente->telefono) ?>
                        </a>
                    <?php else: ?>
                        <button class="btn btn-secondary rounded-pill px-4 py-2 disabled"><i class="bi bi-telephone-x me-2"></i> Sin Teléfono</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Financial Summary -->
        <div class="col-xl-12">
            <h5 class="fw-bold mb-3"><i class="bi bi-wallet2 me-2"></i>Resumen Financiero</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                        <div class="card-body">
                            <p class="text-muted small fw-bold text-uppercase mb-1">Total Financiado</p>
                            <h4 class="fw-bold text-secondary m-0">Gs. <?= number_format($resumen->total_deuda ?? 0, 0, ',', '.') ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 h-100" style="background-color: #f0fdf4;">
                        <div class="card-body">
                            <p class="text-success small fw-bold text-uppercase mb-1">Total Pagado</p>
                            <h4 class="fw-bold text-success m-0">Gs. <?= number_format($resumen->total_pagado ?? 0, 0, ',', '.') ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 h-100" style="background-color: #fef2f2;">
                        <div class="card-body">
                            <p class="text-danger small fw-bold text-uppercase mb-1">Deuda Restante / Saldo</p>
                            <h4 class="fw-bold text-danger m-0">Gs. <?= number_format($resumen->deuda_restante ?? 0, 0, ',', '.') ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                        <div class="card-body">
                            <p class="text-muted small fw-bold text-uppercase mb-1">Último Pago</p>
                            <h4 class="fw-bold text-dark m-0">
                                <?= $resumen->ultima_fecha_pago ? date('d/m/Y', strtotime($resumen->ultima_fecha_pago)) : '---' ?>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Vehicles -->
        <div class="col-xl-4 col-lg-5">
            <h5 class="fw-bold mb-3"><i class="bi bi-car-front me-2"></i>Vehículos Comprados</h5>
            <?php if (empty($vehiculos)): ?>
                <div class="alert alert-secondary border-0"><i class="bi bi-info-circle me-2"></i> Este cliente no tiene ventas registradas.</div>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($vehiculos as $v): ?>
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-body p-0">
                                <div class="bg-light p-3 border-bottom d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 fw-bold text-dark"><?= htmlspecialchars($v->modelo) ?></h6>
                                    <span class="badge bg-primary rounded-pill">Venta #<?= $v->id_venta ?></span>
                                </div>
                                <div class="p-4">
                                    <p class="mb-2"><i class="bi bi-calendar me-2 text-muted"></i> <strong>Año:</strong> <?= htmlspecialchars($v->anho) ?></p>
                                    <p class="mb-2"><i class="bi bi-palette me-2 text-muted"></i> <strong>Color:</strong> <?= htmlspecialchars($v->color) ?></p>
                                    <p class="mb-2"><i class="bi bi-calendar2-check me-2 text-muted"></i> <strong>Fecha:</strong> <?= date('d/m/Y', strtotime($v->fecha_venta)) ?></p>
                                    <p class="m-0"><i class="bi bi-cash-stack me-2 text-muted"></i> <strong>Monto Venta:</strong> Gs. <?= number_format($v->monto_total, 0, ',', '.') ?></p>
                                    
                                    <div class="mt-3 text-end">
                                        <a href="?c=cuotas&a=detalle&id=<?= $v->id_venta ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            Plan de Cuotas <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Payment History -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0"><i class="bi bi-clock-history me-2 text-primary"></i>Historial Detallado de Pagos</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle datatable w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha de Pago</th>
                                    <th>Venta / Vehículo</th>
                                    <th>Cuota</th>
                                    <th>Monto Pagado</th>
                                    <th>Estado / Puntualidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pagos as $p): 
                                    $fechaPago = strtotime($p->fecha_pago);
                                    $fechaVenc = strtotime($p->fecha_vencimiento);
                                    
                                    $puntualidad = '';
                                    $badgeClass = '';
                                    if ($fechaPago <= $fechaVenc) {
                                        $puntualidad = 'A Tiempo';
                                        $badgeClass = 'bg-success';
                                    } else {
                                        $diasAtraso = floor(($fechaPago - $fechaVenc) / 86400);
                                        $puntualidad = 'Atrasado (' . $diasAtraso . ' días)';
                                        $badgeClass = 'bg-danger';
                                    }
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="bg-light rounded p-2 text-center" style="width: 45px; height: 45px; line-height: 1;">
                                                    <span class="d-block fw-bold text-dark fs-5"><?= date('d', $fechaPago) ?></span>
                                                    <span class="d-block text-muted" style="font-size: 0.65rem;"><?= strtoupper(date('M', $fechaPago)) ?></span>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-bold"><?= date('Y', $fechaPago) ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark d-block">Venta #<?= $p->id_venta ?></span>
                                            <span class="text-muted small"><?= htmlspecialchars($p->modelo_vehiculo) ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                N° <?= str_pad($p->numero_cuota, 2, '0', STR_PAD_LEFT) ?> 
                                                <?= $p->tipo_cuota === 'refuerzo' ? '(R)' : '' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success fs-6">Gs. <?= number_format($p->monto_entregado, 0, ',', '.') ?></span>
                                        </td>
                                        <td>
                                            <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2"><?= $puntualidad ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
