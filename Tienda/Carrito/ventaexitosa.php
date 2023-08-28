<?php require('../layout/header.php'); ?>
<?php
// Consulta para obtener el ID de la última venta del usuario
$usuario = $_SESSION['idusuario'];
$sql = "SELECT idventa
        FROM tventas
        WHERE idusuario = $usuario
        ORDER BY fechaventa DESC
        LIMIT 1";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id_venta = $row['idventa'];
}
?>
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

  <form action="formulariofact.php" method="get">
        <input type="hidden" name="idventa" value="<?php echo $id_venta; ?>">
        <button type="submit" class="btn btn-primary">Generar factura</button>
  <br><br>
  <a href="../index.php" class="btn btn-secondary">Seguir comprando</a>
</div>

</body>
</html>

 

<?php require('../layout/footer.php'); ?>