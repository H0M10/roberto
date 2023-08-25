
<?php
require 'C:/xampp/htdocs/base_de_datos/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            // Iniciar sesión (si aún no está iniciada)
            session_start();

            // Almacenar el nombre completo del usuario en una variable de sesión
            $_SESSION['nombre_usuario'] = $usuario['NombreUsu'] . " " . $usuario['ApellidoPusu'];

            // Almacenar el idusuario en una variable de sesión
            $_SESSION['idusuario'] = $usuario['IdUsuario'];
            $_SESSION['tipo'] = $usuario['IdTipo'];

            // Redirigir al index.php
            header('Location: index.php');
            exit;
        } else {
            // Contraseña incorrecta, mostrar mensaje de error y redirigir al formulario de inicio de sesión
            $error_message = "Error de inicio de sesión. Por favor, verifica tus credenciales.";
            header("Location: login.php?message=" . urlencode($error_message));
            exit;
        }
    } else {
        // Datos de inicio de sesión incorrectos, mostrar mensaje de error y redirigir al formulario de inicio de sesión
        $error_message = "Error de inicio de sesión. Por favor, verifica tus credenciales.";
        header("Location: login.php?message=" . urlencode($error_message));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Inicio de Sesion</title>
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
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Ingresar</h3></div>
                                    <div class="card-body">
                                        <form method="post" action="procesarinicio.php">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputEmail" type="email" name="email" placeholder="name@example.com" />
                                                <label for="inputEmail">Email</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="inputPassword" type="password" name="password" placeholder="Password" />
                                                <label for="inputPassword">Contraseña</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="password.html">Olvidaste tu contraseña?</a>
                                                <button type="submit" class="btn btn-primary">Ingresar</button>
                                            </div>
                                        </form>
                                        
                                    </div>
                                    <div class="card-footer text-center py-3">
                                        <div class="small"><a href="register.html">Aun no tienes cuenta? Crea una ahora!</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; PMW 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
