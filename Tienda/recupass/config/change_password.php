<?php
require_once('config.php');

$id = $_POST['idusuario'];
$pass = $_POST['new_password'];

// Encriptar la contraseña
$hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

$query = "UPDATE usuario SET clave = '$hashedPassword' WHERE IdUsuario = $id";
$conexion->query($query);

header("Location: ../index.php?message=success_password");
?>

