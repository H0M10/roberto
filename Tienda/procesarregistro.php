<?php require 'C:/xampp/htdocs/base_de_datos/database.php';

// Obtener los datos enviados desde el formulario HTML
$nombre = $_POST['nombre'];
$apellidos = $_POST['apellido'];
$email = $_POST['email'];
$clave = $_POST['password'];
$nickname= $_POST['user'];


// Validar la contraseña utilizando una expresión regular
$pattern = "/^(?=.*[A-Z])(?=.*[0-9]).{8,}$/"; // Al menos una mayúscula, un número y 8 caracteres en total

if (!preg_match($pattern, $clave)) {
    echo "<script>alert('La contraseña debe tener al menos 8 caracteres, incluyendo al menos una letra mayúscula y un número.'); window.location.href = 'register.html';</script>";
    exit;
}

// Encriptar la contraseña
$hashedPassword = password_hash($clave, PASSWORD_DEFAULT);

// Establecer el valor del campo 'tipo' como 'cliente'
$tipo = 3;

// Aquí puedes realizar las operaciones de inserción en la base de datos
// Por ejemplo, puedes construir y ejecutar una consulta SQL para insertar los datos en una tabla
$sql = "INSERT INTO tusuario (NombreUsu, ApellidoPUsu, CorreoUsu, ContrasenaUsu, IdTipo, CuentaUsu) VALUES ('$nombre', '$apellidos', '$email', '$hashedPassword', $tipo, '$nickname')";

if ($conn->query($sql) === TRUE) {
    header('Location: login.html');
    exit;
} else {
    echo "Error en el registro: " . $conn->error;
}

// Cerrar conexión
$conn->close();
?>

