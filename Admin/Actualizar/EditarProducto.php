<?php require('../layout/header.php') ?>
<?php

require 'C:/xampp/htdocs/base_de_datos/database.php';
$selected_product = null;

function loadProduct($conn, $idProducto)
{
    $stmt = $conn->prepare("SELECT * FROM TProductos WHERE IdProducto=?");
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
    return $product;
}

function updateProduct($conn)
{
    $idProducto = $_POST['idProducto'];
    $nombreProd = $_POST['nombreProd'];
    $idCategoria = $_POST['idCategoria'];
    $precio = $_POST['precio'];

    if (empty($nombreProd) || empty($idCategoria) || empty($precio)) {
        return false;
    }

    $stmt = $conn->prepare("UPDATE TProductos SET NombreProd=?, IdCategoria=?, Precio=? WHERE IdProducto=?");
    $stmt->bind_param("sidi", $nombreProd, $idCategoria, $precio, $idProducto);
    $success = $stmt->execute();
    $stmt->close();
    return $success;
}

function getAllProducts($conn)
{
    $query_productos = "SELECT IdProducto, NombreProd FROM TProductos";
    return $conn->query($query_productos);
}

function getAllCategories($conn)
{
    $query_categorias = "SELECT IdCategoria, NombreCat FROM TCategorias";
    return $conn->query($query_categorias);
}

$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['loadProduct'])) {
        $selected_product = loadProduct($conn, $_POST['idProducto']);
    } else {
        $success = updateProduct($conn);
        // Reload the product after updating it
        $selected_product = loadProduct($conn, $_POST['idProducto']);
    }
}

$result_productos = getAllProducts($conn);
$result_categorias = getAllCategories($conn);

$conn->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        form {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 5px;
        }

        label,
        input,
        select {
            margin-bottom: 10px;
        }
    
        /* Additional styles for success message */
        #successMessage {
            position: fixed;  /* Posición fija en la página */
    top: 200px;  /* 200px desde la parte superior */
    right: 20px;  /* 20px desde el lado derecho */
            width: 200px;  /* Width of the message */
            background-color: #4CAF50;  /* Green background */
            color: white;  /* White text */
            padding: 15px;  /* Internal padding */
            border-radius: 4px;  /* Rounded borders */
            display: none;  /* Initially hidden */
        }
    </style>
    

</head>


    <body>

    <div id="successMessage">Producto actualizado con éxito</div>
    

    <form action="EditarProducto.php" method="post">
        <label for="idProducto">Seleccionar Producto:</label>
        <select name="idProducto" required>
            <?php while ($row = $result_productos->fetch_assoc()) : ?>
                <option value="<?php echo $row['IdProducto']; ?>" <?php if ($selected_product && $selected_product['IdProducto'] == $row['IdProducto']) echo 'selected'; ?>><?php echo $row['NombreProd']; ?></option>
            <?php endwhile; ?>
        </select>


        <input type="submit" name="loadProduct" value="Cargar Datos" onclick="removeRequiredForLoad()">
        <script>
            function removeRequiredForLoad() {
                document.querySelector("[name='nombreProd']").removeAttribute("required");
                document.querySelector("[name='precio']").removeAttribute("required");
            }

            function addRequiredForUpdate() {
                document.querySelector("[name='nombreProd']").setAttribute("required", "");
                document.querySelector("[name='precio']").setAttribute("required", "");
            }
        </script>

        <br><br>

        <label for="nombreProd">Nombre del Producto:</label>
        <input type="text" name="nombreProd" value="<?php echo $selected_product['NombreProd'] ?? ''; ?>" required>

        <br><br>

        <label for="idCategoria">Categoría:</label>
        <select name="idCategoria" required>
            <?php while ($row = $result_categorias->fetch_assoc()) : ?>
                <option value="<?php echo $row['IdCategoria']; ?>" <?php if ($selected_product && $selected_product['IdCategoria'] == $row['IdCategoria']) echo 'selected'; ?>><?php echo $row['NombreCat']; ?></option>
            <?php endwhile; ?>
        </select>

        <br><br>

        <label for="precio">Precio:</label>
        <input type="number" step="0.01" name="precio" value="<?php echo $selected_product['Precio'] ?? ''; ?>" required>

        <br><br>

        <input type="submit" value="Actualizar" onclick="addRequiredForUpdate()">
    </form>


    <script>
        <?php if ($success) : ?>
            // Show the success message
            document.getElementById("successMessage").style.display = "block";

            // Hide the success message after 3 seconds
            setTimeout(function() {
                document.getElementById("successMessage").style.display = "none";
            }, 3000);
        <?php endif; ?>
    </script>

    </body>
    

</html>


<?php require('../layout/footer.php') ?>
