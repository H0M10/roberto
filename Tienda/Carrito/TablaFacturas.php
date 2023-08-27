<?php

require('../layout/header.php');
require 'C:/xampp/htdocs/base_de_datos/database.php';

echo '<p>ID del usuario: ' . $_SESSION['idusuario'] . '</p>';
if (isset($_SESSION['mensaje_compra'])) {
    echo $_SESSION['mensaje_compra'];
    unset($_SESSION['mensaje_compra']);  // Esto es para eliminar el mensaje después de mostrarlo y evitar que se muestre nuevamente en recargas futuras.
}

$usuario = $_SESSION['idusuario'];

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Direct SQL query to fetch cart details
$sql = "SELECT v.IdVenta, MIN(v.FechaVenta) AS FechaPago, c.NombreUsu, c.ApellidoPUsu, c.ApellidoMUsu, SUM(v.Total) AS Total, s.NombreSuc 
FROM tventas v
INNER JOIN tusuario c ON v.IdUsuario = c.IdUsuario
INNER JOIN tdetallesventa i ON i.IdVenta = v.IdVenta
INNER JOIN tsucursal s ON s.IdSucursal = i.IdSucursal
WHERE v.IdUsuario = $usuario AND c.IdEstatus = 1
GROUP BY v.IdVenta, c.NombreUsu, c.ApellidoPUsu, c.ApellidoMUsu, s.NombreSuc";

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo '<div class="cart-content">';
    echo '<h2>Registro de Compras</h2>';
    echo '<br><br>';
    echo '<table>';
    echo '<tr>';
    echo '<th>No. Venta</th>';
    echo '<th>Fecha</th>';
    echo '<th>Usuario</th>';
    echo '<th>Sucursal</th>';
    echo '<th>Total</th>';
    echo '<th>Ver</th>';
    echo '<th>Facturar</th>';
    echo '</tr>';

    $totalPrice = 0;

    while ($product = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $product["IdVenta"] . '</td>';
        echo '<td>' . $product["FechaPago"] . '</td>';
        echo '<td>' . $product["NombreUsu"] . '</td>';
        echo '<td>' . $product["NombreSuc"] . '</td>';
        echo '<td>$' . $product["Total"] . '</td>';
        echo '<td><a href="vercompra.php?idventa=' . $product["IdVenta"] . '" class="btn btn-primary">Ver</a></td>';
        echo '<td><a href="vercompra.php?idventa=' . $product["IdVenta"] . '" class="btn btn-warning ml-2">Facturar</a></td>';
        echo '</tr>';

    }

} else {
    echo '<div class="cart-content empty-cart">';
    echo '<h2>Registro de Compras</h2>';
    echo '<p>No tienes compras</p>';
    echo '</div>';
}

mysqli_close($conn);
?>




    <?php require('../layout/footer.php'); ?>