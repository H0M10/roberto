<?php

require('../layout/header.php');
require 'C:/xampp/htdocs/base_de_datos/database.php';

$usuario = $_SESSION['idusuario'];

$sqlclie = "SELECT * FROM tusuario WHERE IdUsuario = $usuario";
$resultclie = $conn->query($sqlclie);

if (isset($_GET['idventa'])) {
    $idVenta = $_GET['idventa'];

    // Realiza las operaciones necesarias con el ID de la venta aquí
    // Por ejemplo, consulta la base de datos u otras acciones

    // Luego, puedes imprimir el ID de la venta o hacer lo que necesites con él
   
} 

//$nombre = $resultadoUsu['nombre_usuario'];
//$pass = $resultadoUsu['contraseña_usuario'];
if ($resultclie && $resultclie->num_rows > 0) {
    $row = $resultclie->fetch_assoc();
    $IdUsuario = $row['IdUsuario'];
    $Nombre = $row['NombreUsu'];
    $Apellido = $row['ApellidoPUsu'];
    $Apellidos = $row['ApellidoMUsu'];
    $RFC = $row['RFC'];
    $Direccion = $row['DireccionUsu'];
    $Correo = $row['CorreoUsu'];
    $Telefono = $row['TelefonoUsu'];


    // Cierra la conexión a la base de datos aquí
?>


    <style>
        .custom-center {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin: auto;
            margin-top: 50px;
        }
    </style>

    <div class="custom-center">
        <p class="">Complete los siguientes campos para actualizar sus datos:</p>
        <form action="formulariofact.php" method="POST">
            <div class="mb-3">
                <input type="text" id="nom_fact" class="form-control" placeholder="Nombre" name="nombre" value="<?php echo $Nombre; ?>" required>
            </div>
            <div class="mb-3">
                <input type="text" id="apellido_pat" class="form-control" placeholder="Apellido Paterno" name="apellido_pat" value="<?php echo $Apellido; ?>" required>
            </div>
            <div class="mb-3">
                <input type="text" id="apellido_mat" class="form-control" placeholder="Apellido Materno" name="apellido_mat" value="<?php echo $Apellidos; ?>" required>
            </div>

            <div class="mb-3">
                <input type="text" id="rfc_fact" class="form-control" placeholder="RFC" name="rfc" value="<?php echo $RFC; ?>" required>
            </div>
            <div class="mb-3">
                <input type="email" id="email_fact" class="form-control" placeholder="Email" name="email" value="<?php echo $Correo; ?>" required>
            </div>
            <div class="mb-3">
                <input type="number" id="telefono_fact" class="form-control" placeholder="Teléfono" name="tel" value="<?php echo $Telefono; ?>" required>
            </div>
            <div class="mb-3">
                <input type="text" id="ciudad_fact" class="form-control" placeholder="Calle, Numero, Colonia, CP ..." name="direccion" value="<?php echo $Direccion; ?>" required>
            </div>
            <input type="hidden" name="idventa" value="<?php echo $idVenta;?>">
            <button type="submit" name="guardar" id="guardar" class="btn btn-primary">Guardar</button>
        </form>
    </div>
<?php }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'C:/xampp/htdocs/base_de_datos/database.php';
    // Obtener los valores del formulario
    $nombre = $_POST['nombre'];
    $apellidoMaterno = $_POST['apellido_mat'];
    $apellidoPaterno = $_POST['apellido_pat'];
    $rfc = $_POST['rfc'];
    $Email = $_POST['email'];
    $telefono = $_POST['tel'];
    $ciudad = $_POST['direccion'];
    $idVenta = $_POST['idventa'];
    // Insertar los valores en la base de datos
    $sql = "UPDATE tusuario 
        SET NombreUsu = '$nombre', 
            ApellidoPUsu = '$apellidoPaterno', 
            ApellidoMUsu = '$apellidoMaterno', 
            RFC = '$rfc', 
            TelefonoUsu = '$telefono', 
            DireccionUsu= '$ciudad', 
            CorreoUsu = '$Email' 
        WHERE IdUsuario = $usuario";

    if ($conn->query($sql) === TRUE) {
        echo "";
    } else {
        echo "Error en la actualización: " . $conn->error;
    }

    if ($conn->query($sql) === TRUE) {
        echo '<div class="custom-center">';
        echo "Información guardada correctamente";
        echo '<form action="./INVOICE-main/mi_factura.php" method="post" target="_blank">
    <input type="hidden" name="id_venta" value="' . $idVenta . '">
    <button type="submit" class="btn btn-success">Descargar Factura</button>
</form>';
    } else {
        echo "Error al guardar la información: " . $conn->error;
    }
    $conn->close();
}
echo '<!-- Enlace al archivo JavaScript de Bootstrap 5 (opcional) -->';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
echo '</body>';
echo '

</html>';
?>