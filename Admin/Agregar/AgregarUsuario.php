<?php require('../layout/header.php') ?>
<?php

require 'C:/xampp/htdocs/base_de_datos/database.php';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idTipo = $_POST['idTipo'];
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



    $stmt = $conn->prepare("INSERT INTO TUsuario (IdTipo, NombreUsu, ApellidoPUsu, ApellidoMUsu, CorreoUsu, CuentaUsu, ContrasenaUsu, DireccionUsu, TelefonoUsu, GeneroUsu, IdEstatus, FechaRegistroUsu, IdSucursalSeleccionada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE(), NULL)");
    $stmt->bind_param("isssssssiss", $idTipo, $nombreUsu, $apellidoPUsu, $apellidoMUsu, $correoUsu, $cuentaUsu, $contrasenaUsu, $direccionUsu, $telefonoUsu, $generoUsu, $idEstatus);;
    $success = $stmt->execute();

    $success = $stmt->execute();
    $stmt->close();
}

$query_tipos = "SELECT IdTipo, Descripcion FROM TTipoUsuario";
$result_tipos = $conn->query($query_tipos);

$query_estatus = "SELECT IdEstatus, Descripcion FROM TEstatus";
$result_estatus = $conn->query($query_estatus);

$query_sucursales = "SELECT IdSucursal, NombreSuc FROM TSucursal";
$result_sucursales = $conn->query($query_sucursales);

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

<div id="successMessage">Usuario agregado con éxito.</div>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Agregar Usuarios</h1>
            <ol class="breadcrumb mb-4">
            </ol>
            <div class="card mb-4">
                <div class="card-body">
                    Aqui podras agregar los usuarios.
                </div>
            </div>
            <form method="post">
                <label for="idTipo">Tipo de Usuario:</label>
                <select name="idTipo" required>
                    <?php while ($row = $result_tipos->fetch_assoc()) : ?>
                        <option value="<?php echo $row['IdTipo']; ?>"><?php echo $row['Descripcion']; ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="nombreUsu">Nombre:</label>
                <input type="text" name="nombreUsu" required>
                <label>Apellido Paterno:</label>
                <input type="text" name="apellidoPUsu" required>
                <label>Apellido Materno:</label>
                <input type="text" name="apellidoMUsu" required>

                <label for="correoUsu">Correo:</label>
                <input type="email" name="correoUsu" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>

                <label for="cuentaUsu">Nombre de Cuenta:</label>
                <input type="text" name="cuentaUsu" required>

                <label for="contrasenaUsu">Contraseña:</label>
                <input type="password" name="contrasenaUsu" required>

                <label for="direccionUsu">Dirección:</label>
                <input type="text" name="direccionUsu">

                <label for="telefonoUsu">Teléfono:</label>
                <input type="tel" name="telefonoUsu" pattern="\d{10}" title="Debe contener 10 dígitos.">

                <label for="generoUsu">Género:</label>
                <select name="generoUsu">
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                </select>

                <label for="idEstatus">Estatus:</label>
                <select name="idEstatus" required>
                    <?php while ($row = $result_estatus->fetch_assoc()) : ?>
                        <option value="<?php echo $row['IdEstatus']; ?>"><?php echo $row['Descripcion']; ?></option>
                    <?php endwhile; ?>
                </select>





                <input type="submit" value="Agregar">
            </form>

            <script>
                <?php if ($success) : ?>
                    document.getElementById("successMessage").style.display = "block";
                    setTimeout(function() {
                        document.getElementById("successMessage").style.display = "none";
                    }, 3000);
                <?php endif; ?>
            </script>
        </div>
    </main>
</div>
</body>

</html>


<?php require('../layout/footer.php') ?>