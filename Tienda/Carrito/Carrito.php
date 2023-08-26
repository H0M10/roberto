<?php require('../layout/header.php') ?>
<?php require 'C:/xampp/htdocs/base_de_datos/database.php';?>



<?php


if (isset($_SESSION['idusuario'])) {
    $idusuario = $_SESSION['idusuario'];


        $sql = "SELECT p.NombreProd, p.IdProducto, p.RutaImagen, p.Precio, c.Cantidad, s.NombreSuc, v.IdUsuario, c.IdCarrito, c.IdEstatus
        FROM TProductos p
        INNER JOIN tdetallescarrito i ON p.IdProducto = i.IdProducto
        INNER JOIN tsucursal s ON i.IdSucursal = s.IdSucursal
        INNER JOIN tcarrito c ON c.IdCarrito = i.IdCarrito
        WHERE c.IdUsuario = $idusuario AND c.IdEstatus = 3;";

        $result = $conn->query($sql);
    

    if ($result && $result->num_rows > 0) {
?>

        <div class="cart-content">
            <h2>Carrito de Compras</h2>
            <table>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Sucursal</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th></th>

                </tr>
                <?php
                $totalPrice = 0;

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

                    echo '</tr>';



                    $totalPrice += $product["Precio"] * $product["CantidadVenta"];
                }
                echo '<tr>';
                echo '<td colspan="3"><strong>Total</strong></td>';
                echo '<td><strong>$' . $totalPrice . '</strong></td>';
                echo '<td></td>';
                echo '</tr>';
                ?>
            </table>

            <form action="paypall.php" method="post">
                <input type="hidden" name="totalPrice" value="<?php echo $totalPrice; ?>">
                <input type="hidden" name="idcliente" value="<?php echo $product["IdCliente"]; ?>">
                <input type="hidden" name="idventa" value="<?php echo $product["IdVenta"] ?>">
                <input type="hidden" name="products" value="<?php echo htmlspecialchars(json_encode($result->fetch_all(MYSQLI_ASSOC)), ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" class="btn">Finalizar Compra</button>
            </form>


        </div>

<?php
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
$conn->close();
?>


<?php require('../layout/footer.php') ?>