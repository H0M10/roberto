<?php require('../layout/header.php') ?>

<?php
require 'C:/xampp/htdocs/base_de_datos/database.php';
$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreCat = $_POST['nombreCat'];
    $descripcionCat = $_POST['descripcionCat'];
    $idEstatus = $_POST['idEstatus'];

    if (empty($nombreCat) || empty($idEstatus)) {
        $mensaje = "Por favor, completa todos los campos obligatorios.";
    } else {
        $stmt = $conn->prepare("INSERT INTO TCategorias (NombreCat, DescripcionCat, IdEstatus) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nombreCat, $descripcionCat, $idEstatus);

        if ($stmt->execute()) {
            $mensaje = "Categoría agregada con éxito!";
        } else {
            $mensaje = "Error al agregar la categoría: " . $stmt->error;
        }

        $stmt->close();
    }
}

$query = "SELECT IdEstatus, Descripcion FROM TEstatus";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        select {
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
</head>

<body>

    <?php if ($mensaje) : ?>
        <p><?php echo $mensaje; ?></p>
    <?php endif; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Agregar Categoria</h1>
                <ol class="breadcrumb mb-4">
                </ol>
                <div class="card mb-4">
                    <div class="card-body">
                        Aqui podras agregar las categorias para tus productos.
                    </div>
                </div>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <label for="nombreCat">Nombre de la Categoría:</label>
                    <input type="text" id="nombreCat" name="nombreCat" required>

                    <label for="descripcionCat">Descripción:</label>
                    <textarea id="descripcionCat" name="descripcionCat" rows="4"></textarea>

                    <label for="idEstatus">Estatus:</label>
                    <select id="idEstatus" name="idEstatus">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['IdEstatus']}'>{$row['Descripcion']}</option>";
                            }
                        }
                        ?>
                    </select>
                    <input type="submit" value="Agregar Categoría">
                </form>
            </div>
        </main>
    </div>
</body>

</html>


<?php require('../layout/footer.php') ?>