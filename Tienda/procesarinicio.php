<?php require 'C:/xampp/htdocs/base_de_datos/database.php';
// Establecer la conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Obtener los datos enviados desde el formulario HTML
$email = $_POST['email'];
$password = $_POST['password'];

// Verificar si hay errores en la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

$sql = "SELECT * FROM tusuario WHERE CorreoUsu = '$email'";
$resultado = $conn->query($sql);

// Verificar si se encontró algún registro coincidente
if ($resultado->num_rows > 0) {
    // Obtener los datos del usuario
    $usuario = $resultado->fetch_assoc();

    // Verificar la contraseña encriptada utilizando password_verify
    if (password_verify($password, $usuario['ContrasenaUsu'])) {
        // Contraseña válida
        // Obtener el nombre y apellidos del usuario desde la tabla "tusuarios"
        $nombreUsuario = $usuario['NombreUsu'] . " " . $usuario['ApellidoPusu'];

        // Iniciar sesión (si aún no está iniciada)
        session_start();

        // Almacenar el nombre completo del usuario en una variable de sesión
        $_SESSION['nombre_usuario'] = $nombreUsuario;

        // Almacenar el idusuario en una variable de sesión
        $_SESSION['idusuario'] = $usuario['IdUsuario'];
        $_SESSION['tipo'] = $usuario['IdTipo'];


        // Verificar el tipo de usuario y redirigir
        if ($usuario['IdTipo'] == 1) {
            // Usuario es administrador, redireccionar a la página de administrador
            header('Location: http://localhost/T210/sweet/admin/index.php');
            exit;
        } elseif ($usuario['IdTipo'] == 3) {
            // Usuario es usuario normal, redireccionar a la página de usuario
            header('Location: http://localhost/T210/sweet/pagina/index.php');
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
        // Contraseña incorrecta, mostrar mensaje de error y redirigir al formulario de inicio de sesión
        $error_message = "Error de inicio de sesión. Por favor, verifica tus credenciales.";
        header("Location: loginxd.html?message=" . urlencode($error_message));
        exit;
    }
} else {
    // Datos de inicio de sesión incorrectos, mostrar mensaje de error y redirigir al formulario de inicio de sesión
    $error_message = "Error de inicio de sesión. Por favor, verifica tus credenciales.";
    header("Location: loginxd.html?message=" . urlencode($error_message));
    exit;
}
?>

