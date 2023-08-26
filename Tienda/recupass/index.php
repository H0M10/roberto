<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="styles/style.css">
  <title>Document</title>
</head>
<body class="text-center">
    
<main class="form-signin w-100 m-auto">
  
    <h1>Sweet Home</h1>
    <h3>Solicitud Exitosa!!!</h3>
<br>
<a href="/T210/sweet/pagina/loginxd.html" class="w-100 btn btn-lg btn-primary">Volver a Inicio</a>

    
    <?php 
    if(isset($_GET['message'])){
     
    ?>
      <div class="alert alert-primary" role="alert">
        <?php 
        switch ($_GET['message']) {
          case 'ok':
            echo 'Por favor, revisa tu correo';
            break;
          case 'success_password':
            echo 'Inicia sesión con tu nueva contraseña';
            break;
            
          default:
            echo 'Algo salió mal, intenta de nuevo';
            break;
        }
        ?>
      </div>
    <?php
    }
    ?>

</main>


    
  </body>
</html>