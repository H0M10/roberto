<?php 

require('../../../database.php');

// Inicializamos una variable de mensaje vacía
$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recoger datos del formulario
    $nombreCat = $_POST['nombreCat'];
    $descripcionCat = $_POST['descripcionCat'];
    $idEstatus = $_POST['idEstatus'];

    // Validación de datos
    if(empty($nombreCat) || empty($idEstatus)) {
        $mensaje = "Por favor, completa todos los campos obligatorios.";
    } else {
        // Insertar en la base de datos
        $stmt = $conn->prepare("INSERT INTO TCategorias (NombreCat, DescripcionCat, IdEstatus) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nombreCat, $descripcionCat, $idEstatus);

        if($stmt->execute()) {
            $mensaje = "Categoría agregada con éxito!";
        } else {
            $mensaje = "Error al agregar la categoría: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Consulta para obtener los estatus disponibles
$query = "SELECT IdEstatus, Descripcion FROM TEstatus";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Categoría</title>
</head>
<body>

<?php if($mensaje): ?>
    <p><?php echo $mensaje; ?></p>
<?php endif; ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <label for="nombreCat">Nombre de la Categoría:</label>
    <input type="text" id="nombreCat" name="nombreCat" required>
    <br>

    <label for="descripcionCat">Descripción:</label>
    <textarea id="descripcionCat" name="descripcionCat" rows="4"></textarea>
    <br>

    <label for="idEstatus">Estatus:</label>
    <select id="idEstatus" name="idEstatus">
        <?php 
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<option value='{$row['IdEstatus']}'>{$row['Descripcion']}</option>";
            }
        }
        ?>
    </select>
    <br>

    <input type="submit" value="Agregar Categoría">
</form>

</body>
</html>