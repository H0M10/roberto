
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Recuperar Contraseña</title>
        <link href="../Admin/layout/css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Recuperar Contraseña</h3></div>
                                    <div class="card-body">
                                        <form method="post" action="recuperarpass.php">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputEmail" type="email" name="email" placeholder="name@example.com" />
                                                <label for="inputEmail">Email</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button type="submit" class="btn btn-primary">Enviar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <?php
require 'C:/xampp/htdocs/base_de_datos/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['email'];

    // 1. Genera una contraseña aleatoria sin encriptar
    $new_password = bin2hex(random_bytes(5)); // Genera una contraseña de 10 caracteres

    // 2. Envía la contraseña al correo del usuario
    $to = $correo;
    $subject = "Recuperación de Contraseña";
    $message = "Tu nueva contraseña temporal es: " . $new_password;
    $headers = "From: 2022371026@UTEQ.EDU.MX";
    mail($to, $subject, $message, $headers);

    // 3. Encripta la contraseña
    $encrypted_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 4. Actualiza la contraseña encriptada en la base de datos
    $sql = "UPDATE TUsuario SET ContrasenaUsu = ? WHERE CorreoUsu = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $encrypted_password, $correo);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Nueva contraseña enviada con éxito.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error al actualizar la contraseña.</div>";
    }

    $stmt->close();
    $conn->close();
}



?>

</body>
</html>
