<?php

require('../layout/header.php');
require 'C:/xampp/htdocs/base_de_datos/database.php';

echo '<p>ID del usuario: ' . $_SESSION['idusuario'] . '</p>';

$userId = $_SESSION['idusuario'];

// Obtener la sucursal del usuario
$querySucursal = "SELECT IdSucursalSeleccionada FROM TUsuario WHERE IdUsuario = '$userId'";
$resultSucursal = $conn->query($querySucursal);
if (!$resultSucursal || $resultSucursal->num_rows == 0) {
    die("Error al obtener la sucursal del usuario.");
}
$sucursalSeleccionada = $resultSucursal->fetch_assoc()['IdSucursalSeleccionada'];

// Obtener los detalles del carrito y el precio de cada producto en una sola consulta
$queryDetallesCarrito = "
    SELECT dc.*, p.Precio 
    FROM TDetallesCarrito dc 
    JOIN TProductos p ON dc.IdProducto = p.IdProducto
    WHERE dc.IdCarrito = (SELECT IdCarrito FROM TCarrito WHERE IdUsuario = '$userId')
";

$resultDetallesCarrito = $conn->query($queryDetallesCarrito);

if (!$resultDetallesCarrito) {
    die("Error al obtener los detalles del carrito.");
}

$totalVenta = 0;
$carrito = [];

while ($row = $resultDetallesCarrito->fetch_assoc()) {
    $carrito[] = $row;
    $totalVenta += ($row['Precio'] * $row['Cantidad']);
}

// Verificar disponibilidad de productos en el inventario
foreach ($carrito as $producto) {
    $queryInventario = "SELECT Cantidad FROM TInventario WHERE IdProducto = '".$producto['IdProducto']."' AND IdSucursal = '$sucursalSeleccionada'";
    $resultInventario = $conn->query($queryInventario);
    if ($resultInventario->num_rows > 0) {
        $row = $resultInventario->fetch_assoc();
        if ($row['Cantidad'] < $producto['Cantidad']) {
            echo json_encode(["status" => "error", "message" => "Lo sentimos, el producto " . $producto['IdProducto'] . " no está disponible en la cantidad solicitada."]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "El producto " . $producto['IdProducto'] . " no está disponible en esta sucursal."]);
        exit();
    }
}
$totalVentafinal=$totalVenta/2;
// Inserción en la tabla TVentas
$queryVenta = "INSERT INTO TVentas (IdUsuario, MetodoPago, IdEstatus, Total) VALUES ('$userId', 1, 1, '0')";
$conn->query($queryVenta);
$idVenta = $conn->insert_id;

// Inserción en la tabla TDetallesVenta
foreach ($carrito as $producto) {
    $totalProducto = $producto['Precio'] * $producto['Cantidad'];
    $queryDetalle = "INSERT INTO TDetallesVenta (IdVenta, IdProducto, IdSucursal, Cantidad, TotalProducto) VALUES ('$idVenta', '".$producto['IdProducto']."', '$sucursalSeleccionada', '".$producto['Cantidad']."', '$totalProducto')";
    $conn->query($queryDetalle);
}

// Después de procesar la venta, eliminar todos los productos del carrito del usuario
$deleteAllFromCarrito = "DELETE FROM TDetallesCarrito WHERE IdCarrito = (SELECT IdCarrito FROM TCarrito WHERE IdUsuario = '$userId')";
$stmtDelete = $conn->prepare($deleteAllFromCarrito);
$stmtDelete->execute();


echo json_encode(["status" => "success", "message" => "Venta procesada exitosamente."]);

if ($stmtDelete->affected_rows > 0) {
    echo "Compra exitosa";
    $_SESSION['mensaje_compra'] = "Compra exitosa";

    // Redireccionar a otra página
    header('Location: ventaexitosa.php');
    exit; // Asegurarse de que el script se detenga después de la redirección
}

$conn->close();

?>
