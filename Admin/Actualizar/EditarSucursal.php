
<?php require('../layout/header.php') ?>
<?php

require 'C:/xampp/htdocs/base_de_datos/database.php';
$success = false;
$selected_sucursal = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['loadSucursal'])) {
    $idSucursal = $_POST['idSucursal'];
    $stmt = $conn->prepare("SELECT * FROM TSucursal WHERE IdSucursal=?");
    $stmt->bind_param("i", $idSucursal);
    $stmt->execute();
    $result = $stmt->get_result();
    $selected_sucursal = $result->fetch_assoc();
    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateSucursal"])) {
    $idSucursal = $_POST['idSucursal'];
    $nombreSuc = $_POST['nombreSuc'];
    $telefonoSuc = $_POST['telefonoSuc'];
    $direccionSuc = $_POST['direccionSuc'];
    $emailSuc = $_POST['emailSuc'];
    $idEstatus = $_POST['idEstatus'];

    if(empty($nombreSuc) || empty($idEstatus) || empty($idSucursal)) {
        $mensaje = "Por favor, completa todos los campos obligatorios.";
    } else {
        $stmt = $conn->prepare("UPDATE TSucursal SET NombreSuc=?, TelefonoSuc=?, DireccionSuc=?, EmailSuc=?, IdEstatus=? WHERE IdSucursal=?");
        $stmt->bind_param("ssssii", $nombreSuc, $telefonoSuc, $direccionSuc, $emailSuc, $idEstatus, $idSucursal);
        $success = $stmt->execute();
        if(!$success) {
            $mensaje = "Error al actualizar la sucursal: " . $stmt->error;
        }
        $stmt->close();
    }
}

$query_sucursales = "SELECT IdSucursal, NombreSuc FROM TSucursal";
$result_sucursales = $conn->query($query_sucursales);

$query_estatus = "SELECT IdEstatus, Descripcion FROM TEstatus";
$result_estatus = $conn->query($query_estatus);

?>

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

<h2>Editar Sucursal</h2>
<div id="successMessage">Sucursal actualizada con éxito.</div>
<form method="post">
    <label for="idSucursal">Seleccionar Sucursal:</label>
    <select name="idSucursal" required>
        <?php while($row = $result_sucursales->fetch_assoc()): ?>
            <option value="<?php echo $row['IdSucursal']; ?>" <?php if ($selected_sucursal && $selected_sucursal['IdSucursal'] == $row['IdSucursal']) echo 'selected'; ?>><?php echo $row['NombreSuc']; ?></option>
        <?php endwhile; ?>
    </select>

    <input type="submit" name="loadSucursal" value="Cargar Datos" onclick="removeRequired()" onclick="removeRequired()">

    <label for="nombreSuc">Nombre:</label>
    <input type="text" name="nombreSuc" value="<?php echo $selected_sucursal['NombreSuc'] ?? ''; ?>" required>

    <label for="telefonoSuc">Teléfono:</label>
    <input type="tel" name="telefonoSuc" value="<?php echo $selected_sucursal['TelefonoSuc'] ?? ''; ?>">

    <label for="direccionSuc">Dirección:</label>
    <input type="text" name="direccionSuc" value="<?php echo $selected_sucursal['DireccionSuc'] ?? ''; ?>">

    <label for="emailSuc">Correo Electrónico:</label>
    <input type="email" name="emailSuc" value="<?php echo $selected_sucursal['EmailSuc'] ?? ''; ?>">

    <label for="idEstatus">Estatus:</label>
    <select name="idEstatus" required>
        <?php while($row = $result_estatus->fetch_assoc()): ?>
            <option value="<?php echo $row['IdEstatus']; ?>" <?php if ($selected_sucursal && $selected_sucursal['IdEstatus'] == $row['IdEstatus']) echo 'selected'; ?>><?php echo $row['Descripcion']; ?></option>
        <?php endwhile; ?>
    </select>

    <input type="submit" name="updateSucursal" value="Actualizar" onclick="addRequired()" onclick="addRequired()">
</form>



<script>
    function removeRequired() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.hasAttribute('required')) {
                input.removeAttribute('required');
            }
        });
    }

    function addRequired() {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input[required], select[required]');
        inputs.forEach(input => {
            input.setAttribute('required', '');
        });
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.querySelector('#successMessage');
        const success = <?php echo $success ? 'true' : 'false'; ?>;
        
        if (success) {
            successMessage.style.display = 'block';
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        }
    });
</script>

</body>
</html>


<?php require('../layout/footer.php') ?>
