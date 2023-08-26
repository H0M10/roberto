<?php
session_start();
require 'C:/xampp/htdocs/base_de_datos/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['idusuario'])) {
    $productoId = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $usuarioId = $_SESSION['idusuario'];

    // Consulta para verificar si el usuario tiene una sucursal seleccionada
    $sqlVerificarSucursal = "SELECT IdSucursalSeleccionada FROM TUsuario WHERE IdUsuario = ?";
    $stmtVerificarSucursal = $conn->prepare($sqlVerificarSucursal);
    $stmtVerificarSucursal->bind_param("i", $usuarioId);
    $stmtVerificarSucursal->execute();
    $stmtVerificarSucursal->bind_result($idSucursalSeleccionada);
    $stmtVerificarSucursal->fetch();
    $stmtVerificarSucursal->close();

    if ($idSucursalSeleccionada === null) {
        $_SESSION['mensaje_sucursal'] = 'Primero selecciona una sucursal.';
    } else {
        // Consulta para verificar la cantidad de inventario
        $sqlInventario = "SELECT Cantidad FROM TInventario WHERE IdProducto = ? AND IdSucursal = ?";
        $stmtInventario = $conn->prepare($sqlInventario);
        $stmtInventario->bind_param("ii", $productoId, $idSucursalSeleccionada);
        $stmtInventario->execute();
        $stmtInventario->bind_result($cantidadInventario);
        $stmtInventario->fetch();
        $stmtInventario->close();

        if ($cantidad <= $cantidadInventario) {
            // Verificar si ya existe un carrito para el usuario
            $sqlVerificarCarrito = "SELECT IdCarrito FROM TCarrito WHERE IdUsuario = ?";
            $stmtVerificarCarrito = $conn->prepare($sqlVerificarCarrito);
            $stmtVerificarCarrito->bind_param("i", $usuarioId);
            $stmtVerificarCarrito->execute();
            $stmtVerificarCarrito->bind_result($idCarrito);
            $stmtVerificarCarrito->fetch();
            $stmtVerificarCarrito->close();

            // Si no existe un carrito, lo creamos
            if ($idCarrito === null) {
                $sqlCrearCarrito = "INSERT INTO TCarrito (IdUsuario,IdEstatus) VALUES (?,1)";
                $stmtCrearCarrito = $conn->prepare($sqlCrearCarrito);
                $stmtCrearCarrito->bind_param("i", $usuarioId);
                if ($stmtCrearCarrito->execute()) {
                    $idCarrito = $stmtCrearCarrito->insert_id;
                }
                $stmtCrearCarrito->close();
            }

            // Verificar la cantidad actual en el carrito para este producto y sucursal
            $sqlVerificarCantidadCarrito = "SELECT SUM(Cantidad) FROM TDetallesCarrito WHERE IdCarrito = ? AND IdProducto = ? AND IdSucursal = ?";
            $stmtVerificarCantidadCarrito = $conn->prepare($sqlVerificarCantidadCarrito);
            $stmtVerificarCantidadCarrito->bind_param("iii", $idCarrito, $productoId, $idSucursalSeleccionada);
            $stmtVerificarCantidadCarrito->execute();
            $stmtVerificarCantidadCarrito->bind_result($cantidadEnCarrito);
            $stmtVerificarCantidadCarrito->fetch();
            $stmtVerificarCantidadCarrito->close();

            $cantidadDisponibleEnInventario = $cantidadInventario - $cantidadEnCarrito;

            if ($cantidad <= $cantidadDisponibleEnInventario) {
                // Verificar si el producto ya existe en el carrito
                $sqlVerificarProductoEnCarrito = "SELECT IdDetalleCarrito FROM TDetallesCarrito WHERE IdCarrito = ? AND IdProducto = ? AND IdSucursal = ?";
                $stmtVerificarProductoEnCarrito = $conn->prepare($sqlVerificarProductoEnCarrito);
                $stmtVerificarProductoEnCarrito->bind_param("iii", $idCarrito, $productoId, $idSucursalSeleccionada);
                $stmtVerificarProductoEnCarrito->execute();
                $stmtVerificarProductoEnCarrito->store_result();

                if ($stmtVerificarProductoEnCarrito->num_rows > 0) {
                    // Actualizar la cantidad en el carrito
                    $sqlActualizarCantidad = "UPDATE TDetallesCarrito SET Cantidad = Cantidad + ? WHERE IdCarrito = ? AND IdProducto = ? AND IdSucursal = ?";
                    $stmtActualizarCantidad = $conn->prepare($sqlActualizarCantidad);
                    $stmtActualizarCantidad->bind_param("iiii", $cantidad, $idCarrito, $productoId, $idSucursalSeleccionada);
                    $stmtActualizarCantidad->execute();
                    $stmtActualizarCantidad->close();
                } else {
                    // Agregar el producto al carrito
                    $sqlInsertDetalle = "INSERT INTO TDetallesCarrito (IdCarrito, IdProducto, IdSucursal, Cantidad) VALUES (?, ?, ?, ?)";
                    $stmtInsertDetalle = $conn->prepare($sqlInsertDetalle);
                    $stmtInsertDetalle->bind_param("iiii", $idCarrito, $productoId, $idSucursalSeleccionada, $cantidad);
                    if ($stmtInsertDetalle->execute()) {
                        $_SESSION['mensaje_producto'] = 'Producto agregado al carrito exitosamente.';
                    } else {
                        $_SESSION['mensaje_producto'] = 'Error al agregar el producto al carrito.';
                    }
                    $stmtInsertDetalle->close();
                }

                $stmtVerificarProductoEnCarrito->close();
            } else {
                $_SESSION['mensaje_producto'] = 'Cantidad excede el inventario disponible en esta sucursal.';
            }
        } else {
            $_SESSION['mensaje_producto'] = 'Cantidad excede el inventario disponible.';
        }
    }
}

header("Location: index.php");
exit();
?>
