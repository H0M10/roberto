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

$totalVenta = array_sum(array_map(function($producto) {
    return $producto['Precio'] * $producto['Cantidad'];
}, $carrito));

// Inserción en la tabla TVentas
$queryVenta = "INSERT INTO TVentas (IdUsuario, MetodoPago, IdEstatus, Total) VALUES ('$userId', 1, 1, '$totalVenta')";
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
    ?>
    <!DOCTYPE html>
    <html lang="es">
        
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="./css/main.css">
        <title>Compra Exitosa</title>
        <style>
            body {
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: flex-start; /* Cambio aquí */
    height: 100vh;
    background-color: #4b33a8; /* Color de fondo morado */
    padding-top: 50px; /* Espacio desde el inicio de la página */
}

            .success-message {
                background-color: #4b33a8; /* Color de fondo morado */
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
                color: white;
                font-size: 24px;
            }
        </style>
    </head>
    <body>
        <div class="success-message">
            Compra exitosa, ¡felicidades!
        </div>
        <nav>
            <ul>
                <li>
                <a class="boton-menu boton-volver" href="./index.php">
                        <i class="bi bi-arrow-return-left"></i> Seguir comprando
                    </a>
                </li>
                <li>
                    <a class="boton-menu boton-carrito active" href="./carrito.php">
                        <i class="bi bi-cart-fill"></i> Carrito
                    </a>
                </li>
            </ul>
        </nav>
    </body>
    </html>
    <?php
    exit();
} else {
    echo json_encode(["status" => "error", "message" => "Error al eliminar productos del carrito."]);
}

// ... (resto del código PHP sin cambios) ...

$conn->close();

echo json_encode(["status" => "success", "message" => "Venta procesada exitosamente."]);
?>
