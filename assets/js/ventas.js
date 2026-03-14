let cuotasGeneradas = [];
let paginaActual = 1;
const cuotasPorPagina = 4;

// Helpers
const nPuro = (val) => {
    let num = val.toString()
        .replace(/\./g, '')        // Quita puntos (separador de miles)
        .replace(/,/g, '.');       // Reemplaza coma por punto (separador decimal)
    return parseFloat(num) || 0;
};
const fMoneda = (val) => new Intl.NumberFormat('de-DE').format(val);

// --- 1. LÓGICA DE DETECCIÓN DE REFUERZOS ---
function obtenerPlanCuotas() {
    const inicio = parseInt($('#rango_inicio').val());
    const fin = parseInt($('#rango_fin').val());
    const fechaBaseStr = $('#fecha_inicio').val();
    
    if (!fechaBaseStr) return [];

    const montoNormal = nPuro($('#monto_cuota_visual').val());
    const montoRefuerzo = nPuro($('#monto_refuerzo_visual').val());
    const mesObjetivo = parseInt($('#mes_refuerzo').val()); // Ej: 12
    const maxRefuerzos = parseInt($('#cant_refuerzos').val());
    
    let plan = [];
    let refuerzosContados = 0;

    for (let i = inicio; i <= fin; i++) {
        // Calcular fecha de este vencimiento
        let fechaVenc = new Date(fechaBaseStr + "T00:00:00");
        fechaVenc.setMonth(fechaVenc.getMonth() + (i - inicio));
        
        // Verificar si es mes de refuerzo (los meses en JS son 0-11)
        // Sumamos 1 para comparar con el select (1-12)
        const mesActual = fechaVenc.getMonth() + 1;
        
        let esRefuerzo = false;
        if (mesActual === mesObjetivo && refuerzosContados < maxRefuerzos) {
            esRefuerzo = true;
            refuerzosContados++;
        }

        plan.push({
            numero: i,
            fecha: fechaVenc.toISOString().split('T')[0],
            monto: esRefuerzo ? montoRefuerzo : montoNormal,
            tipo: esRefuerzo ? 'refuerzo' : 'normal'
        });
    }
    return plan;
}

// Actualizar resumen visual
$('#rango_inicio, #rango_fin, #monto_cuota_visual, #monto_refuerzo_visual, #mes_refuerzo, #cant_refuerzos, #fecha_inicio').on('input change', function() {
    cuotasGeneradas = obtenerPlanCuotas();
    
    const sumNormal = cuotasGeneradas.filter(c => c.tipo === 'normal').reduce((s, c) => s + c.monto, 0);
    const sumRefuerzo = cuotasGeneradas.filter(c => c.tipo === 'refuerzo').reduce((s, c) => s + c.monto, 0);

    $('#res_total_normal').text(`₲ ${fMoneda(sumNormal)}`);
    $('#res_total_refuerzos').text(`₲ ${fMoneda(sumRefuerzo)}`);
    $('#res_total_final').text(`₲ ${fMoneda(sumNormal + sumRefuerzo)}`);
});

// --- 2. MODAL Y TABLA ---
function abrirModalPreview() {
    if (cuotasGeneradas.length === 0) {
        return Swal.fire('Error', 'Defina el monto y la fecha de inicio', 'error');
    }
    paginaActual = 1;
    renderizarTabla();
    $('#modalCuotas').modal('show');
}

function renderizarTabla() {
    const inicioIdx = (paginaActual - 1) * cuotasPorPagina;
    const paginadas = cuotasGeneradas.slice(inicioIdx, inicioIdx + cuotasPorPagina);

    let html = paginadas.map((c, index) => {
        const globalIdx = inicioIdx + index;
        return `
            <tr>
                <td>${c.numero}</td>
                <td><input type="date" class="form-control form-control-sm bg-dark text-white border-secondary" 
                    value="${c.fecha}" onchange="cuotasGeneradas[${globalIdx}].fecha = this.value"></td>
                <td><input type="number" class="form-control form-control-sm bg-dark text-white border-secondary" 
                    value="${c.monto}" onchange="cuotasGeneradas[${globalIdx}].monto = parseFloat(this.value)"></td>
                <td><span class="badge ${c.tipo === 'refuerzo' ? 'bg-warning text-dark' : 'bg-info'}">${c.tipo.toUpperCase()}</span></td>
                <td><button class="btn btn-sm btn-outline-danger" onclick="eliminarCuota(${globalIdx})"><i class="bi bi-trash"></i></button></td>
            </tr>`;
    }).join('');

    $('#cuerpoPreview').html(html);
    renderizarPaginacion();
}

// --- 3. PAGINACIÓN (La función que faltaba) ---
function renderizarPaginacion() {
    const totalPaginas = Math.ceil(cuotasGeneradas.length / cuotasPorPagina);
    let html = '';

    if (totalPaginas > 1) {
        for (let i = 1; i <= totalPaginas; i++) {
            html += `
                <button type="button" 
                    class="btn btn-sm ${i === paginaActual ? 'btn-primary' : 'btn-outline-light'}" 
                    onclick="cambiarPagina(${i})">
                    ${i}
                </button>`;
        }
    }
    $('#paginationContainer').html(html);
}

function cambiarPagina(p) {
    paginaActual = p;
    renderizarTabla();
}

function eliminarCuota(idx) {
    cuotasGeneradas.splice(idx, 1);
    renderizarTabla();
}

// --- LÓGICA DE GUARDADO SOLICITADA ---
function guardarTodo() {
    // Validar que se haya generado la previsualización
    if (cuotasGeneradas.length === 0) {
        return Swal.fire('Error', 'Debe generar la previsualización de cuotas antes de guardar', 'error');
    }

    // Validar que todos los montos sean válidos
    for (let cuota of cuotasGeneradas) {
        if (typeof cuota.monto !== 'number' || isNaN(cuota.monto) || cuota.monto <= 0) {
            return Swal.fire('Error', `Cuota #${cuota.numero} tiene monto inválido. Revise los montos en los campos de Parámetros.`, 'error');
        }
        if (!cuota.fecha) {
            return Swal.fire('Error', `Cuota #${cuota.numero} no tiene fecha de vencimiento.`, 'error');
        }
    }

    const ventaData = {
        cliente: {
            nombre: $('#cliente_nombre').val(),
            ci: $('#cliente_ci').val(),
            tel: $('#cliente_tel').val(),
            codeudor: $('#codeudor_nombre').val(),
            codeudor_ci: $('#codeudor_ci').val(),
            ubicacion: $('#ubicacion').val()
        },
        vehiculo: {
            id_modelo: $('#id_modelo').val(),
            nuevo_nombre: $('#nuevo_modelo_nombre').val(),
            anho: $('#anho').val(),
            color: $('#color').val()
        },
        venta: {
       
            monto_total: cuotasGeneradas.reduce((sum, c) => sum + parseFloat(c.monto), 0),
            interes_mora: $('#interes_mora').val() || 0,
            cuotas: cuotasGeneradas 
        }
    };

    Swal.fire({
        title: '¿Confirmar Operación?',
        text: `Se registrará la venta por un total de ₲ ${ventaData.venta.monto_total.toLocaleString('es-PY')}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Confirmar Registro'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('index.php?c=ventas&a=guardar', { data: JSON.stringify(ventaData) }, function(res) {
                try {
                    const response = (typeof res === 'string') ? JSON.parse(res) : res;
                    if(response.success) {
                        Swal.fire('Éxito', 'Venta y cuotas registradas correctamente', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.message || 'No se pudo guardar', 'error');
                    }
                } catch(e) {
                    Swal.fire('Error', 'Error al procesar respuesta del servidor: ' + e.message, 'error');
                    console.error('Respuesta:', res);
                }
            }, 'json').fail(function(xhr, status, error) {
                Swal.fire('Error de Red', 'No se pudo contactar al servidor: ' + error, 'error');
                console.error('XHR:', xhr.responseText);
            });
        }
    });
}