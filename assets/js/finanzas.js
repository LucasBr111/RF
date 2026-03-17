// === GLOBAL FINANCIAL UTILITIES ===
function redondearAGuaranies(n) {
    if (n === 0) return 0;
    const resto = n % 1000;
    return resto === 500 ? n : Math.ceil(n / 1000) * 1000;
}

function formatearNumero(n) {
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function limpiarNumero(n) {
    return parseFloat(String(n).replace(/\./g, '')) || 0;
}

$(document).ready(function() {

    // === REAL-TIME INPUT FORMAT ===
    $(document).on('input', '.input-precio', function() {
        let valor = $(this).val();
        if (valor !== '') {
            $(this).val(formatearNumero(limpiarNumero(valor)));
        }
    });

    // === CLEAN BEFORE SUBMIT ===
    $(document).on('submit', '.form-financiero', function() {
        $(this).find('.input-precio').each(function() {
            $(this).val(limpiarNumero($(this).val()));
        });
    });
});
