<?php require('../layout/header.php'); ?>
<?php require 'C:/xampp/htdocs/base_de_datos/database.php';?>

<?php
$userId = $_SESSION['idusuario'];

// Obtén el IdVenta de la URL (supongamos que el parámetro se llama "idventa")
$idVenta = $_GET['idventa'];

// Realiza la consulta con el IdVenta específico
$query = "
    SELECT 
        DV.IdVenta,
        S.IdSucursal,
        S.NombreSuc,
        DV.FechaDetalle,
        P.IdProducto,
        P.NombreProd,
        P.Precio,
        DV.Cantidad,
        (P.Precio * DV.Cantidad) AS TotalPorProducto,
        V.Total
    FROM TDetallesVenta DV
    INNER JOIN TProductos P ON DV.IdProducto = P.IdProducto
    INNER JOIN TVentas V ON DV.IdVenta = V.IdVenta
    INNER JOIN TSucursal S ON DV.IdSucursal = S.IdSucursal
    WHERE DV.IdVenta = $idVenta AND v.IdUsuario = $userId;
";

$result = $conn->query($query);

// Asegúrate de manejar los posibles errores aquí
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detalles de Venta</title>
    <style>
        .custom-center {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin: auto;
            margin-top: 50px;
        }
        /* Agrega aquí tus estilos CSS adicionales si los tienes */
    </style>
</head>
<body>

<div class="custom-center">
  
    <?php
      $totalVenta = 0;
    if ($result->num_rows > 0) {

        $firstRow = true;
        while ($row = $result->fetch_assoc()) {
            $totalVenta += $row['TotalPorProducto'];
            if ($firstRow) {
                echo '<h1>Detalles de la Venta ' . $row['IdVenta'] . '</h1>';
                echo '<p>Fecha: ' . $row['FechaDetalle'] . '</p>';
                echo '<p>Sucursal: ' . $row['NombreSuc'] . '</p>';
                echo '<table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio Unitario</th>
                            <th>Cantidad</th>
                            <th>Total por Producto</th>
                            <th>Sucursal</th>
                        </tr>
                    </thead>
                    <tbody>';
                $firstRow = false;
            }

            echo '<tr>
                <td>' . $row['NombreProd'] . '</td>
                <td>$' . $row['Precio'] . '</td>
                <td>' . $row['Cantidad'] . '</td>
                <td>$' . $row['TotalPorProducto'] . '</td>
                <td>' . $row['NombreSuc'] . '</td>
            </tr>';
        }
        echo '</tbody></table>';
        echo '<p>Total de la Venta: $' . $totalVenta . '</p>';
    } else {
        echo 'No se encontraron detalles de la venta.';
    }
    ?>
</div>
</body>
</html>
<?php require('../layout/footer.php'); ?>