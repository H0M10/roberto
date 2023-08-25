<?php require('../layout/header.php') ?>

<?php
require 'C:/xampp/htdocs/base_de_datos/database.php';


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
<<<<<<< HEAD

=======
>>>>>>> dfc5e0834effe5ccac14afce79ddea3d6d97da02
        $siguienteNumero = 1; // Valor por defecto si no hay imágenes
        if (!empty($numeros)) {  // Comprobamos que el array no esté vacío antes de llamar a max()
            $siguienteNumero = max($numeros) + 1;
        }

        $nombreImagen = $siguienteNumero . ".jpg";
        move_uploaded_file($_FILES['imagen']['tmp_name'], $directorioImagenes . $nombreImagen);
    }

    // Esta es la ruta relativa que se almacenará en la base de datos
    $rutaRelativa = "/roberto/img/" . $nombreImagen;

    $stmt = $conn->prepare("INSERT INTO TProductos (NombreProd, IdCategoria, Precio, RutaImagen) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sids", $nombreProd, $idCategoria, $precio, $rutaRelativa);

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


<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        padding: 50px;
    }

    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: 0 auto;
    }

    label {
        display: block;
        margin-bottom: 10px;
    }

    input[type="text"],
    textarea,
    select,
    input[type="file"],
    input[type="number"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    input[type="submit"] {
        background-color: #333;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    p {
        background-color: #ffc107;
        color: #333;
        padding: 10px;
        border-radius: 4px;
    }
</style>

<body>

    <?php if ($mensaje) : ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Charts</h1>
                <ol class="breadcrumb mb-4">
                    
                </ol>
                <div class="card mb-4">
                    <div class="card-body">
                    Aqui podras agregar los productos.
                    </div>
                </div>

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
            </div>
        </main>
</body>

</html>


<<<<<<< HEAD
<?php require('../layout/footer.php') ?>
=======
<?php require('../layout/footer.php') ?>
>>>>>>> dfc5e0834effe5ccac14afce79ddea3d6d97da02
