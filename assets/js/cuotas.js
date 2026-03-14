// ...existing code...
$(document).ready(function () {

    // Obtener o crear la instancia de DataTable sin duplicarla
    let table;
    if ($.fn.DataTable.isDataTable('#tblGestionCuotas')) {
        table = $('#tblGestionCuotas').DataTable();
    } else {
        table = $('#tblGestionCuotas').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            pageLength: 15,
            dom: 'lrtip',
            ordering: true
        });
    }

    // Enlazar filtro con las pills (asegurarse de no duplicar handlers)
    $('#cuotasTabs .pill-btn').off('click').on('click', function () {
        $('#cuotasTabs .pill-btn').removeClass('active');
        $(this).addClass('active');

        const filtro = $(this).data('filter') ?? '';

        if (filtro === 'GAY') {
            table.column(4).search('').draw();
        } else {
            table.column(4).search('^' + filtro + '$', true, false).draw();
        }
    });

});
// ...existing code...