
<?php
require 'C:/xampp/htdocs/base_de_datos/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $email = $_POST['email'];
    $input_password = $_POST['password'];

    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM tusuario WHERE CorreoUsu = '$email'";
    $resultado = $conn->query($sql);

    if (!$resultado) {
        header("Location: login_form.php?message=Error en la consulta SQL.");
    } else if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        // Directly comparing the plaintext password
        if ($input_password == $usuario['ContrasenaUsu']) {
            session_start();
            $_SESSION['nombre_usuario'] = $usuario['NombreUsu'] . " " . $usuario['ApellidoPusu'];
            $_SESSION['idusuario'] = $usuario['IdUsuario'];
            $_SESSION['tipo'] = $usuario['IdTipo'];
            
            if ($usuario['IdTipo'] == 3) {
                header('Location: index.php');
            } else {
                header("Location: login_form.php?message=Usuario autenticado pero no tiene permisos para acceder a index.php");
            }
        } else {
            header("Location: login_form.php?message=Contraseña incorrecta.");
        }
    } else {
        header("Location: login_form.php?message=No se encontró el correo electrónico en la base de datos.");
    }
}
?>
