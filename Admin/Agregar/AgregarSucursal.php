
<?php require('../layout/header.php') ?>
<?php

require 'C:/xampp/htdocs/base_de_datos/database.php';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreSuc = $_POST['nombreSuc'];
    $telefonoSuc = $_POST['telefonoSuc'];
    $direccionSuc = $_POST['direccionSuc'];
    $emailSuc = $_POST['emailSuc'];
    $idEstatus = $_POST['idEstatus'];

    $stmt = $conn->prepare("INSERT INTO TSucursal (NombreSuc, TelefonoSuc, DireccionSuc, EmailSuc, IdEstatus) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $nombreSuc, $telefonoSuc, $direccionSuc, $emailSuc, $idEstatus);
    $success = $stmt->execute();
    $stmt->close();
}

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

<div id="successMessage">Sucursal agregada con éxito.</div>
<form method="post">
    <label for="nombreSuc">Nombre:</label>
    <input type="text" name="nombreSuc" required>

    <label for="telefonoSuc">Teléfono:</label>
    <input type="text" name="telefonoSuc">

    <label for="direccionSuc">Dirección:</label>
    <input type="text" name="direccionSuc">

    <label for="emailSuc">Email:</label>
    <input type="email" name="emailSuc">

    <label for="idEstatus">Estatus:</label>
    <select name="idEstatus">
        <?php while($row = $result_estatus->fetch_assoc()): ?>
            <option value="<?php echo $row['IdEstatus']; ?>"><?php echo $row['Descripcion']; ?></option>
        <?php endwhile; ?>
    </select>

    <input type="submit" value="Agregar">
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
