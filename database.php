<?php
// Datos para la conexión
$servername = "localhost";
$username = "root";
$password = "hanniel";
$dbname = "roberto";

// Creando la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
