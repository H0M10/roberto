<?php 
$host = "localhost";
$user = "root";
$password = "MichaelX 71099";
$db = "sw";

$conexion = new mysqli($host, $user, $password, $db);

if($conexion->connect_errno){
  echo "Falló la conexión a la base de datos " . $conexion->connect_error;
}
?>