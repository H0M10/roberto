
<?php require('../layout/header.php') ?>
<?php 
require 'C:/xampp/htdocs/base_de_datos/database.php';

$success = false;
$selected_usuario = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['loadUsuario'])) {
    $idUsuario = $_POST['idUsuario'];
    $stmt = $conn->prepare("SELECT IdUsuario, IdTipo, NombreUsu, ApellidoPUsu, ApellidoMUsu, CorreoUsu, CuentaUsu, ContrasenaUsu, DireccionUsu, TelefonoUsu, GeneroUsu, IdEstatus, FechaRegistroUsu, IdSucursalSeleccionada FROM TUsuario WHERE IdUsuario=?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $selected_usuario = $result->fetch_assoc();
    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateUsuario"])) {
    $idUsuario = $_POST['idUsuario'];
    $nombreUsu = $_POST['nombreUsu'];
    $apellidoPUsu = $_POST['apellidoPUsu'];
    $apellidoMUsu = $_POST['apellidoMUsu'];
    $correoUsu = $_POST['correoUsu'];
    $cuentaUsu = $_POST['cuentaUsu'];
    $contrasenaUsu = $_POST['contrasenaUsu'];
    $direccionUsu = $_POST['direccionUsu'];
    $telefonoUsu = $_POST['telefonoUsu'];
    $generoUsu = $_POST['generoUsu'];
    $idEstatus = $_POST['idEstatus'];

    if(empty($nombreUsu) || empty($correoUsu) || empty($cuentaUsu) || empty($idEstatus) || empty($idUsuario)) {
        $mensaje = "Por favor, completa todos los campos obligatorios.";
    } else {
        $stmt = $conn->prepare("UPDATE TUsuario SET NombreUsu=?, ApellidoPUsu=?, ApellidoMUsu=?, CorreoUsu=?, CuentaUsu=?, ContrasenaUsu=?, DireccionUsu=?, TelefonoUsu=?, GeneroUsu=?, IdEstatus=? WHERE IdUsuario=?");
        $stmt->bind_param("ssssssssisi", $nombreUsu, $apellidoPUsu, $apellidoMUsu, $correoUsu, $cuentaUsu, $contrasenaUsu, $direccionUsu, $telefonoUsu, $generoUsu, $idEstatus, $idUsuario);
        $success = $stmt->execute();
        if(!$success) {
            $mensaje = "Error al actualizar el usuario: " . $stmt->error;
        }
        $stmt->close();
    }
}

$query_usuarios = "SELECT IdUsuario, NombreUsu FROM TUsuario";
$result_usuarios = $conn->query($query_usuarios);

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

<h2>Editar Usuario</h2>
<div id="successMessage">Usuario actualizado con éxito.</div>
<form method="post">
    
<form method="post">
    <label for="idUsuario">Seleccionar Usuario:</label>
    <select name="idUsuario" required>
        <?php while($row = $result_usuarios->fetch_assoc()): ?>
            <option value="<?php echo $row['IdUsuario']; ?>" <?php if ($selected_usuario && $selected_usuario['IdUsuario'] == $row['IdUsuario']) echo 'selected'; ?>><?php echo $row['NombreUsu']; ?></option>
        <?php endwhile; ?>
    </select>

    <input type="submit" name="loadUsuario" value="Cargar Datos" onclick="removeRequired()">

    <label for="nombreUsu">Nombre:</label>
    <input type="text" name="nombreUsu" value="<?php echo $selected_usuario['NombreUsu'] ?? ''; ?>">
<label>Apellido Paterno:</label>
<input type="text" name="apellidoPUsu" value="<?php echo $selected_usuario['ApellidoPUsu'] ?? ''; ?>">
<label>Apellido Materno:</label>
<input type="text" name="apellidoMUsu" value="<?php echo $selected_usuario['ApellidoMUsu'] ?? ''; ?>">

    <label for="correoUsu">Correo Electrónico:</label>
    <input type="email" name="correoUsu" value="<?php echo $selected_usuario['CorreoUsu'] ?? ''; ?>" required>

    <label for="cuentaUsu">Cuenta:</label>
    <input type="text" name="cuentaUsu" value="<?php echo $selected_usuario['CuentaUsu'] ?? ''; ?>" required>

    <label for="contrasenaUsu">Contraseña:</label>
    <input type="password" name="contrasenaUsu" value="<?php echo $selected_usuario['ContrasenaUsu'] ?? ''; ?>" required>

    <label for="direccionUsu">Dirección:</label>
    <input type="text" name="direccionUsu" value="<?php echo $selected_usuario['DireccionUsu'] ?? ''; ?>">

    <label for="telefonoUsu">Teléfono:</label>
    <input type="tel" name="telefonoUsu" value="<?php echo $selected_usuario['TelefonoUsu'] ?? ''; ?>">

    <label for="generoUsu">Género:</label>
    <select name="generoUsu" required>
        <option value="M" <?php if ($selected_usuario && $selected_usuario['GeneroUsu'] == 'M') echo 'selected'; ?>>Masculino</option>
        <option value="F" <?php if ($selected_usuario && $selected_usuario['GeneroUsu'] == 'F') echo 'selected'; ?>>Femenino</option>
    </select>

    <label for="idEstatus">Estatus:</label>
    <select name="idEstatus" required>
        <?php while($row = $result_estatus->fetch_assoc()): ?>
            <option value="<?php echo $row['IdEstatus']; ?>" <?php if ($selected_usuario && $selected_usuario['IdEstatus'] == $row['IdEstatus']) echo 'selected'; ?>><?php echo $row['Descripcion']; ?></option>
        <?php endwhile; ?>
    </select>

    <input type="submit" name="updateUsuario" value="Actualizar" onclick="addRequired()">
</form>

    <!-- ... -->
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
