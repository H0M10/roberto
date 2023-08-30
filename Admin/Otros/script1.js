$(document).ready(function() {
    $('#tabla').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'print',
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                title: 'Reporte de Ventas',
                customize: function(doc) {
                    // Agrega el total de productos vendidos al final del PDF
                    var totalProductosValue = document.getElementById('totalProductosHidden').value;
                    var totalProductos = 'Total de Productos Vendidos: ' + totalProductosValue;
                    doc.content.push({ text: totalProductos, margin: [0, 20, 0, 0], fontSize: 14, bold: true });
                }
            }
        ]
    });
});
