<?php require('../layout/header.php'); ?>

<?php
// Conexión a la base de datos
require 'C:/xampp/htdocs/base_de_datos/database2.php';

// Obtener datos para las gráficas
$query = "SELECT FechaRegistroUsu as fecha, COUNT(IdUsuario) as total_usuarios FROM TUsuario GROUP BY FechaRegistroUsu";
$stmt1 = $pdo->query($query);
$usuarios_por_fecha = $stmt1->fetchAll();

// Ventas por fecha
$query = "SELECT DATE(FechaVenta) as fecha, SUM(Total) as total_ventas FROM TVentas GROUP BY DATE(FechaVenta)";
$stmt2 = $pdo->query($query);
$ventas_por_fecha = $stmt2->fetchAll();

// Cantidad vendida por fecha
$query = "SELECT DATE(FechaDetalle) as fecha, SUM(Cantidad) as total_cantidad FROM TDetallesVenta GROUP BY DATE(FechaDetalle)";
$stmt3 = $pdo->query($query);
$cantidad_por_fecha = $stmt3->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráficas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      
      .container {
    border-radius: 10px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    align-items: center; /* Centra los elementos horizontalmente */
    justify-content: center; /* Centra los elementos verticalmente */
}
.row {
    width: 80%; /* Ajusta este valor según tus preferencias */
    max-width: 1000px; /* Puedes establecer un ancho máximo si lo deseas */
    margin: 0 auto; /* Esto centrará la fila dentro del contenedor */
}

      canvas {
    background-color: #000; /* Color de fondo negro */
    box-shadow: 2px 2px 10px rgba(0,0,0,0.5);
    margin-bottom: 20px;
}

    </style>
</head>
<body>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8 text-center">
            <canvas id="chartUsuarios" width="400" height="200"></canvas>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-8 text-center">
            <canvas id="chartVentas" width="400" height="200"></canvas>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-8 text-center">
            <canvas id="chartCantidad" width="400" height="200"></canvas>
        </div>
    </div>
</div>

<script>
// Gráfica de Usuarios Registrados por Fecha
var ctx1 = document.getElementById('chartUsuarios').getContext('2d');
var chartUsuarios = new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: [<?php echo '"' . implode('","', array_column($usuarios_por_fecha, 'fecha')) . '"'; ?>],
        datasets: [{
            label: 'Usuarios registrados',
            data: [<?php echo implode(',', array_column($usuarios_por_fecha, 'total_usuarios')); ?>],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    }
});

// Gráfica de Ventas por Fecha
var ctx2 = document.getElementById('chartVentas').getContext('2d');
var chartVentas = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: [<?php echo '"' . implode('","', array_column($ventas_por_fecha, 'fecha')) . '"'; ?>],
        datasets: [{
            label: 'Total de ventas',
            data: [<?php echo implode(',', array_column($ventas_por_fecha, 'total_ventas')); ?>],
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    }
});

// Gráfica de Cantidad Vendida por Fecha
var ctx3 = document.getElementById('chartCantidad').getContext('2d');
var chartCantidad = new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: [<?php echo '"' . implode('","', array_column($cantidad_por_fecha, 'fecha')) . '"'; ?>],
        datasets: [{
            label: 'Cantidad vendida',
            data: [<?php echo implode(',', array_column($cantidad_por_fecha, 'total_cantidad')); ?>],
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            borderColor: 'rgba(255, 206, 86, 1)',
            borderWidth: 1
        }]
    }
});
</script>

<?php require('../layout/footer.php'); ?>

</body>
</html>
