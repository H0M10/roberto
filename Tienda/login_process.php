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
        
        $stored_password = $usuario['ContrasenaUsu']; // Obtén la contraseña almacenada
        
        // Verifica la contraseña utilizando password_verify
        if (password_verify($input_password, $stored_password)) {
            session_start();
            $_SESSION['nombre_usuario'] = $usuario['NombreUsu'] . " " . $usuario['ApellidoPUsu'];
            $_SESSION['idusuario'] = $usuario['IdUsuario'];
            $_SESSION['tipo'] = $usuario['IdTipo'];
            
            if ($usuario['IdTipo'] == 1) {
                // Usuario es administrador, redireccionar a la página de administrador
                header('Location: http://localhost/roberto/Admin/Otros/index.php');
                exit;
            } elseif ($usuario['IdTipo'] == 3) {
                // Usuario es usuario normal, redireccionar a la página de usuario
                header('Location: http://localhost/roberto/Tienda/index.php');
                exit;
            } elseif ($usuario['IdTipo'] == 2) {
                // Usuario es empleado, redireccionar a la página de empleado
                header('Location: /sweet/admin/VistaEmpP.php');
                exit;
            } else {
                // Tipo de usuario desconocido, redireccionar a la página de inicio
                header('Location: index.php');
                exit;
            }
        } else {
            header("Location: login_form.php?message=Contraseña incorrecta.");
        }
    } else {
        header("Location: login_form.php?message=No se encontró el correo electrónico en la base de datos.");
    }
}
?>
