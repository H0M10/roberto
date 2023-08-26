<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

require_once('config.php');
$email = $_POST['email'];
$query = "SELECT * FROM usuario where Email = '$email'";
$result = $conexion->query($query);
$row = $result->fetch_assoc(); 
// Generar un token único
$token = bin2hex(random_bytes(32)); // Genera un token hexadecimal de 64 caracteres

// Guardar el token y la información en la base de datos
$query = "INSERT INTO tokens (idusuario, token, expiration_time) VALUES ('{$row['IdUsuario']}', '$token', NOW() + INTERVAL 1 HOUR)";
$conexion->query($query);

//
if ($result->num_rows > 0) {
  $mail = new PHPMailer(true);
 
  try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'sh7183890@gmail.com';                     //SMTP username
    $mail->Password   = 'hkjsdurivyyuxgsl';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;

    $mail->setFrom('sh7183890@gmail.com', 'ADMINISTRADOR');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Recuperar contraseña';
    $mail->Body = 'Hola, este es un correo generado para solicitar tu recuperación de contraseña, por favor, visita la página de <a href="http://localhost/T210/sweet/pagina/recupass/change_password.php?token=' . $token . '">Recuperación de contraseña</a>';

    $mail->send();
    header("Location: ../index.php?message=ok");
  } catch (Exception $e) {
    header("Location: ../index.php?message=error");
  }
} else {
  header("Location: ../index.php?message=not_found");
}
