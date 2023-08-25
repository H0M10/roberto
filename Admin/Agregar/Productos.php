<?php require('../layout/header.php') ?>

<?php require('../layout/database.php');

$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreProd = $_POST['nombreProd'];
    $idCategoria = $_POST['idCategoria'];
    $precio = $_POST['precio'];

    $directorioImagenes = "C:/xampp/htdocs/roberto/img/";
    $nombreImagen = "";

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $archivos = scandir($directorioImagenes);
        $numeros = [];
        foreach ($archivos as $archivo) {
            $partes = explode('.', $archivo);
            if (is_numeric($partes[0])) {
                $numeros[] = (int)$partes[0];
            }
        }

        $siguienteNumero = 1; // Valor por defecto si no hay imágenes
        if (!empty($numeros)) {  // Comprobamos que el array no esté vacío antes de llamar a max()
            $siguienteNumero = max($numeros) + 1;
        }

        $nombreImagen = $siguienteNumero . ".jpg";
        move_uploaded_file($_FILES['imagen']['tmp_name'], $directorioImagenes . $nombreImagen);
    }


    $stmt = $conn->prepare("INSERT INTO TProductos (NombreProd, IdCategoria, Precio, RutaImagen) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sids", $nombreProd, $idCategoria, $precio, $nombreImagen);

    if ($stmt->execute()) {
        $mensaje = "Producto agregado con éxito!";
    } else {
        $mensaje = "Error al agregar el producto: " . $stmt->error;
    }

    $stmt->close();
}

$query = "SELECT IdCategoria, NombreCat FROM TCategorias";
$result = $conn->query($query);

?>

    <link rel="stylesheet" href="../layout/css/tables.css">

    <?php if ($mensaje) : ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
        <label for="nombreProd">Nombre del Producto:</label>
        <input type="text" id="nombreProd" name="nombreProd" required>

        <label for="idCategoria">Categoría:</label>
        <select id="idCategoria" name="idCategoria">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['IdCategoria']}'>{$row['NombreCat']}</option>";
                }
            }
            ?>
        </select>

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" step="0.01" required>

        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen" accept=".jpg">

        <input type="submit" value="Agregar Producto">
    </form>

<?php require('../layout/footer.php')?>