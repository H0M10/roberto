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
// Obtener el IdCarrito del usuario
$queryCarritoId = "SELECT IdCarrito FROM TCarrito WHERE IdUsuario = '$userId'";
$resultCarritoId = $conn->query($queryCarritoId);
if (!$resultCarritoId || $resultCarritoId->num_rows == 0) {
    die("Error al obtener el carrito del usuario.");
}
$carritoId = $resultCarritoId->fetch_assoc()['IdCarrito'];

// Consultar los detalles del carrito usando el IdCarrito
$queryDetallesCarrito = "SELECT * FROM TDetallesCarrito WHERE IdCarrito = '$carritoId'";
$resultDetallesCarrito = $conn->query($queryDetallesCarrito);

$carrito = [];
while ($row = $resultDetallesCarrito->fetch_assoc()) {
    $carrito[] = $row;
}

// Enriquecer el carrito con los precios de los productos
foreach ($carrito as &$producto) {
    $queryPrecio = "SELECT Precio FROM TProductos WHERE IdProducto = '".$producto['IdProducto']."'";
    $resultPrecio = $conn->query($queryPrecio);
    if ($resultPrecio->num_rows > 0) {
        $row = $resultPrecio->fetch_assoc();
        $producto['Precio'] = $row['Precio'];
    } else {
        echo json_encode(["status" => "error", "message" => "Error al obtener el precio del producto " . $producto['IdProducto']]);
        exit();
    }
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


// Inserción en la tabla TVentas
$queryVenta = "INSERT INTO TVentas (IdUsuario, MetodoPago, IdEstatus, Total) VALUES ('$userId', 1, 1, 0)";
$conn->query($queryVenta);
$idVenta = $conn->insert_id;

// Inserción en la tabla TDetallesVenta
foreach ($carrito as $producto) {
    $totalProducto = $producto['Precio'] * $producto['Cantidad'];
    $queryDetalle = "INSERT INTO TDetallesVenta (IdVenta, IdProducto, IdSucursal, Cantidad, TotalProducto) VALUES ('$idVenta', '".$producto['IdProducto']."', '$sucursalSeleccionada', '".$producto['Cantidad']."', '$totalProducto')";
    $conn->query($queryDetalle);
}

// Después de procesar la venta, eliminar todos los productos del carrito del usuario
$deleteAllFromCarrito = "DELETE FROM TDetallesCarrito WHERE IdCarrito = ?";
$stmtDelete = $conn->prepare($deleteAllFromCarrito);
$stmtDelete->bind_param("i", $carritoId);  // Aquí usamos el ID del carrito que ya hemos obtenido anteriormente
$stmtDelete->execute();


if ($stmtDelete->affected_rows > 0) {
    echo "Compra exitosa";
    $_SESSION['mensaje_compra'] = "Compra exitosa";

}
?>
