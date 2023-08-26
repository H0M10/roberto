<?php require('../layout/header.php') ?>
<?php require 'C:/xampp/htdocs/base_de_datos/database.php';?>


<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f7f7;
        margin: 0;
        padding: 0;
    }

    .cart-content {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 20px auto;
        max-width: 800px;
    }

    h2 {
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
    }

    .product-image {
        max-width: 80px;
        height: auto;
    }

    .btn {
        display: inline-block;
        padding: 5px 10px;
        background-color: #333;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
    }

    .btn-remove {
        background-color: #e74c3c;
    }

    .empty-cart {
        text-align: center;
        padding: 40px;
    }

    .empty-cart h2 {
        color: #333;
    }

    .empty-cart p {
        color: #888;
    }


    form {
        margin-top: 20px;
    }
</style>
<?php


if (isset($_SESSION['idusuario'])) {
    $idusuario = $_SESSION['idusuario'];

    $sqlclie = "SELECT IdCliente FROM cliente WHERE IdUsuario = $idusuario";
    $resultclie = $conn->query($sqlclie);

    if ($resultclie && $resultclie->num_rows > 0) {
        $row = $resultclie->fetch_assoc();
        $idcliente = $row['IdCliente'];

        $sql = "SELECT p.NombreProd, p.IdProducto, p.RutaImagen, p.Precio, c.Cantidad, s.NombreSuc, v.IdUsuario, c.IdCarrito, c.IdEstatus
        FROM TProductos p
        INNER JOIN tdetallescarrito i ON p.IdProducto = i.IdProducto
        INNER JOIN ventainventario c ON i.IdInventario = c.IdInventario
        INNER JOIN sucursal s ON i.IdSucursal = s.IdSucursal
        INNER JOIN venta v ON c.IdVenta = v.IdVenta
        WHERE v.IdCliente = $idcliente AND v.IdEstatus = 2;";

        $result = $conn->query($sql);
    }

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
                    echo '<td><img src="assets/imagenes_sweet/' . $product["Imagen"] . '" alt="' . $product["Nombre"] . '" class="product-image"></td>';
                    echo '<td>' . $product["Nombre"] . '</td>';
                    echo '<td>' . $product["Nombresucursal"] . '</td>';
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