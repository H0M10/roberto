
<?php

require('../layout/header.php');
require 'C:/xampp/htdocs/base_de_datos/database.php';

if (isset($_SESSION['idusuario'])) {
    echo '<p>ID del usuario: ' . $_SESSION['idusuario'] . '</p>';

    $idusuario = $_SESSION['idusuario'];

    $sql = "SELECT p.NombreProd, p.IdProducto, p.RutaImagen, p.Precio, dc.Cantidad, s.NombreSuc
            FROM TProductos p
            INNER JOIN TDetallesCarrito dc ON p.IdProducto = dc.IdProducto
            INNER JOIN TSucursal s ON dc.IdSucursal = s.IdSucursal
            INNER JOIN TCarrito c ON dc.IdCarrito = c.IdCarrito
            WHERE c.IdUsuario = ? AND c.IdEstatus = 3";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idusuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        echo '<div class="cart-content">';
        echo '<h2>Carrito de Compras</h2>';
        echo '<table>';
        echo '<tr>';
        echo '<th>Imagen</th>';
        echo '<th>Producto</th>';
        echo '<th>Sucursal</th>';
        echo '<th>Precio</th>';
        echo '<th>Cantidad</th>';
        echo '<th>Total</th>';
        echo '</tr>';

        $totalPrice = 0;

        while ($product = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td><img src="' . $product["RutaImagen"] . '" alt="' . $product["NombreProd"] . '" class="product-image"></td>';
            echo '<td>' . $product["NombreProd"] . '</td>';
            echo '<td>' . $product["NombreSuc"] . '</td>';
            echo '<td>' . $product["Precio"] . '</td>';
            echo '<td>' . $product["Cantidad"] . '</td>';
            echo '<td>$' . $product["Precio"] * $product["Cantidad"] . '</td>';
            echo '</tr>';

<<<<<<< HEAD
            $totalPrice += $product["Precio"] * $product["Cantidad"];
        }
=======
                foreach ($result->fetch_all(MYSQLI_ASSOC) as $product) {
                    echo '<tr>';
                    echo '<td><img src="assets/imagenes_sweet/' . $product["RutaImagen"] . '" alt="' . $product["NombreProd"] . '" class="product-image"></td>';
                    echo '<td>' . $product["NombreProd"] . '</td>';
                    echo '<td>' . $product["NombreSuc"] . '</td>';
                    echo '<td>' . $product["Precio"] . '</td>';
                    echo '<td>';
                    echo '<form method="post" action="actualizar_cantidad.php">'; // Ajusta el archivo de destino del formulario
                    echo '<input type="hidden" name="idproducto" value="' . $product["IdInventario"] . '">';
                    echo '<input type="number" name="cantidad" value="' . $product["CantidadVenta"] . '" min="1" max="' . $product["Existencias"] . '">';
                    echo '</td>';
                    echo '<td>$' . $product["Precio"] * $product["CantidadVenta"] . '</td>';
                    echo '<td>';
                    echo '<button type="submit" name="guardar" class="btn" value="Guardar">Guardar</button>';
                    echo '</form>';
                    echo '<a href="quitar_producto.php?idproducto=' . $product["IdInventario"] . '&idventa=' . $product["IdVenta"] . '" class="btn btn-remove">Quitar</a>';
                    echo '</td>';
>>>>>>> 2f787beecf70f2dec114a48e5d58363e6bab948f

        echo '<tr>';
        echo '<td colspan="5"><strong>Total</strong></td>';
        echo '<td><strong>$' . $totalPrice . '</strong></td>';
        echo '</tr>';
        echo '</table>';

        echo '<form action="paypall.php" method="post">';
        echo '<input type="hidden" name="totalPrice" value="' . $totalPrice . '">';
        echo '<button type="submit" class="btn">Finalizar Compra</button>';
        echo '</form>';

        echo '</div>';
    } else {
        echo '<div class="cart-content empty-cart">';
        echo '<h2>Carrito de Compras</h2>';
        echo '<p>El carrito está vacío.</p>';
        echo '</div>';
    }
} else {
    echo '<div class="cart-content empty-cart">';
    echo '<h2>Carrito de Compras</h2>';
    echo '<p>Por favor, inicia sesión para ver tu carrito de compras.</p>';
    echo '</div>';
}

$stmt->close();
$conn->close();
?>

<?php require('../layout/footer.php'); ?>
