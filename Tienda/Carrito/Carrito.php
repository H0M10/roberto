<?php

require('../layout/header.php');
require 'C:/xampp/htdocs/base_de_datos/database.php';


if (isset($_SESSION['mensaje_compra'])) {
    echo $_SESSION['mensaje_compra'];
    unset($_SESSION['mensaje_compra']);  // Esto es para eliminar el mensaje después de mostrarlo y evitar que se muestre nuevamente en recargas futuras.
}


// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Direct SQL query to fetch cart details
$sql = "SELECT p.NombreProd, p.IdProducto, dc.IdDetalleCarrito, p.IdProducto, p.RutaImagen, p.Precio, dc.Cantidad, s.NombreSuc
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
    echo '<th>Eliminar</th>';
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

    // Add PayPal button container
    echo '<div id="paypal-button-container"></div>';
    echo '</div>';

} else {
    echo '<div class="cart-content empty-cart">';
    echo '<h2>Carrito de Compras</h2>';
    echo '<p>El carrito está vacío.</p>';
    echo '</div>';
}

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="./css/main.css">
    <script src="https://www.paypal.com/sdk/js?client-id=ASu9s-bv1_wUhqV3jNcTMEgpczcOh7EwQzyBVmazGRvit8j_wGw_s5euFuYLVNSAUwyBXjUvQVbBYBHV&currency=USD"></script>
    <style>
        .wrapper {
            display: flex;
        }
        .checkout-column {
            flex: 1;
            padding: 20px;
        }
        .titulo-principal {
            text-align: center;
        }
    </style>
</head>
<body>
      
<div id="paypal-button-container"></div>
    </div>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../layout/"></script>
    <script src="./js/menu.js"></script>
    <!-- PayPal Integration Script -->
<!-- Include PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=AeuTlbN2NF4to_A1egumMKvzhaGA523q77Ya4pQxAL98B1cO-M06f3mEZhzchOG1zHe7D2yFHf-_Epsz&currency=MXN"></script>

<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        currency_code: 'MXN',
                        value: '<?php echo $totalPrice; ?>'
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            // Aquí es donde se maneja la aprobación de la transacción por parte de PayPal

            // Configuramos el indicador para procesar la venta y enviamos el formulario
            var form = document.createElement('form');
            form.method = 'post';
            form.action = 'agregar.php'; // La página actual

            var hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = 'procesarVenta';
            hiddenField.value = 'true';

            form.appendChild(hiddenField);

            // Enviamos el formulario para procesar la venta en el servidor
            document.body.appendChild(form);
            form.submit();
        });
    }
    }).render('#paypal-button-container'); // Display the PayPal button in the container
</script>

<?php require('../layout/footer.php'); ?>