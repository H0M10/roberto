
$(document).ready(function() {
    $('#tabla').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json',
        },
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            {
                extend: 'pdfHtml5',
                footer: true  // <-- Esto asegura que el tfoot (pie de tabla) se incluya en el PDF
            }
        ]
    });
});