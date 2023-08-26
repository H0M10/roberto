
<?php

require('../layout/header.php');
require 'C:/xampp/htdocs/base_de_datos/database.php';

echo '<p>ID del usuario: ' . $_SESSION['idusuario'] . '</p>';

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Direct SQL query to fetch cart details
$sql = "SELECT p.NombreProd, p.IdProducto, p.RutaImagen, p.Precio, dc.Cantidad, s.NombreSuc
        FROM TProductos p
        INNER JOIN TDetallesCarrito dc ON p.IdProducto = dc.IdProducto
        INNER JOIN TSucursal s ON dc.IdSucursal = s.IdSucursal
        INNER JOIN TCarrito c ON dc.IdCarrito = c.IdCarrito
        WHERE c.IdUsuario = " . $_SESSION['idusuario'] . " AND c.IdEstatus = 1";

$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
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

    while ($product = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td><img src="' . $product["RutaImagen"] . '" alt="' . $product["NombreProd"] . '" class="product-image"></td>';
        echo '<td>' . $product["NombreProd"] . '</td>';
        echo '<td>' . $product["NombreSuc"] . '</td>';
        echo '<td>' . $product["Precio"] . '</td>';
        echo '<td>' . $product["Cantidad"] . '</td>';
        echo '<td>$' . $product["Precio"] * $product["Cantidad"] . '</td>';
        echo '</tr>';

        $totalPrice += $product["Precio"] * $product["Cantidad"];
    }

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

mysqli_close($conn);

?>

<?php require('../layout/footer.php'); ?>
