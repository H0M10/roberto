<?php require('../layout/header.php'); ?>


    <!-- Agrega aquí tus propias hojas de estilo personalizadas si lo deseas -->
    
    <!DOCTYPE html>
<html>
<head>
<style>
  .custom-center {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    margin: auto;
    margin-top: 50px;
  }
</style>
</head>
<body>

<div class="custom-center">
  <h1 class="card-title">Gracias por su pago</h1>
  <br><br>
  <a href="formulario_fact.php" class="btn btn-primary">Generar factura</a>
  <br>
  <a href="../index.php" class="btn btn-secondary">Seguir comprando</a>
</div>

</body>
</html>

 

<?php require('../layout/footer.php'); ?>