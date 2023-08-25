<?php require('../layout/header.php') ?>

<?php 
require 'C:/xampp/htdocs/base_de_datos/database.php';
$success = false;
$selected_category = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['loadCategory'])) {
    $idCategoria = $_POST['idCategoria'];
    $stmt = $conn->prepare("SELECT * FROM TCategorias WHERE IdCategoria=?");
    $stmt->bind_param("i", $idCategoria);
    $stmt->execute();
    $result = $stmt->get_result();
    $selected_category = $result->fetch_assoc();
    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateCategory"])) {
    $idCategoria = $_POST['idCategoria'];
    $nombreCat = $_POST['nombreCat'];
    $descripcionCat = $_POST['descripcionCat'];
    $idEstatus = $_POST['idEstatus'];

    if(empty($nombreCat) || empty($idEstatus) || empty($idCategoria)) {
        $mensaje = "Por favor, completa todos los campos obligatorios.";
    } else {
        $stmt = $conn->prepare("UPDATE TCategorias SET NombreCat=?, DescripcionCat=?, IdEstatus=? WHERE IdCategoria=?");
        $stmt->bind_param("ssii", $nombreCat, $descripcionCat, $idEstatus, $idCategoria);
        $success = $stmt->execute();
        if(!$success) {
            $mensaje = "Error al actualizar la categoría: " . $stmt->error;
        }
        $stmt->close();
    }
}

$query_categorias = "SELECT IdCategoria, NombreCat FROM TCategorias";
$result_categorias = $conn->query($query_categorias);

$query_estatus = "SELECT IdEstatus, Descripcion FROM TEstatus";
$result_estatus = $conn->query($query_estatus);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría</title>
    <style>
        #successMessage {
            position: fixed;
            top: 10%;
            right: 10%;
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 5px;
            display: none;
        }
    </style>
</head>
<body>

<div id="successMessage">Categoría actualizada</div>

<form action="EditarCategoria.php" method="post">
    <label for="idCategoria">Seleccionar Categoría:</label>
    <select name="idCategoria" required>
        <?php while($row = $result_categorias->fetch_assoc()): ?>
            <option value="<?php echo $row['IdCategoria']; ?>" <?php if ($selected_category && $selected_category['IdCategoria'] == $row['IdCategoria']) echo 'selected'; ?>><?php echo $row['NombreCat']; ?></option>
        <?php endwhile; ?>
    </select>
    
    <input type="submit" name="loadCategory" value="Cargar Datos" onclick="removeRequired()">
    <script>
        function removeRequired() {
            document.querySelector("[name='nombreCat']").removeAttribute("required");
        }
        function addRequired() {
            document.querySelector("[name='nombreCat']").setAttribute("required", "");
        }
    </script>
    

    <br>

    <label for="nombreCat">Nombre:</label>
    <input type="text" name="nombreCat" value="<?php echo $selected_category['NombreCat'] ?? ''; ?>" required>

    <br>

    <label for="descripcionCat">Descripción:</label>
    <input type="text" name="descripcionCat" value="<?php echo $selected_category['DescripcionCat'] ?? ''; ?>">

    <br>

    <label for="idEstatus">Estatus:</label>
    <select name="idEstatus" required>
        <?php while($row = $result_estatus->fetch_assoc()): ?>
            <option value="<?php echo $row['IdEstatus']; ?>" <?php if ($selected_category && $selected_category['IdEstatus'] == $row['IdEstatus']) echo 'selected'; ?>><?php echo $row['Descripcion']; ?></option>
        <?php endwhile; ?>
    </select>

    <br>

    <input type="submit" name="updateCategory" value="Actualizar" onclick="addRequired()">
</form>

<script>
    <?php if ($success): ?>
        document.getElementById("successMessage").style.display = "block";
        setTimeout(function() {
            document.getElementById("successMessage").style.display = "none";
        }, 3000);
    <?php endif; ?>
</script>


</body>
</html>


<?php require('../layout/footer.php') ?>
