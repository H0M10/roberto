
<?php 
session_start(); 
require 'C:/xampp/htdocs/base_de_datos/database.php';

// Si se seleccionó una categoría específica, se filtra por esa categoría. De lo contrario, se muestran todos los productos.
if (isset($_GET['categoria'])) {
    $queryProductos = "SELECT * FROM TProductos WHERE IdCategoria = " . $_GET['categoria'];
} else {
    $queryProductos = "SELECT * FROM TProductos";
}

$resultProductos = $conn->query($queryProductos);
if (!$resultProductos) {
    die("Error en la consulta: " . $conn->error);
}
$productos = [];
if ($resultProductos->num_rows > 0) {
    while($row = $resultProductos->fetch_assoc()) {
        $productos[] = $row;
    }
}

// Consulta para obtener las categorías
$queryCategorias = "SELECT * FROM TCategorias";
$resultCategorias = $conn->query($queryCategorias);
$categorias = [];
if ($resultCategorias->num_rows > 0) {
    while($row = $resultCategorias->fetch_assoc()) {
        $categorias[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComponentSpace</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <link href="../layout/css/styles.css" rel="stylesheet" />
    
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="../../Tienda/index.php">Pet Milky Way</a>
</nav>

<div class="container-fluid p-0">
    <div class="row m-0">
        <div class="col-md-3 p-0">
            <div class="sidebar">
                <h5>Menú</h5>
                <ul class="list-unstyled">
                    <?php
                     echo '<li class="mb-2"><button class="btn btn-primary btn-block" onclick="location.href=\'../../Tienda/index.php\'">Inicio</button></li>';
                    if (!isset($_SESSION['idusuario'])) {
                        echo '<li class="mb-2"><button class="btn btn-primary btn-block" onclick="location.href=\'login.php\'">Iniciar Sesión</button></li>';
                    } else {
                        echo '<li class="mb-2"><button class="btn btn-primary btn-block" onclick="location.href=\'./carrito.php\'">Carrito</button></li>';
                        echo '<li class="mb-2"><button class="btn btn-primary btn-block" onclick="location.href=\'../Carrito/TablaFacturas.php.\'">Factura</button></li>';
                        echo '<li class="mb-2"><button class="btn btn-primary btn-block">Usuario</button></li>';
                        echo '<li class="mb-2"><button class="btn btn-danger btn-block" onclick="location.href=\'cerrar.php\'">Cerrar Sesión</button></li>';
                    }
                    ?>
                </ul>

                <!-- Botón para mostrar todos los productos -->
                <button onclick="location.href='?todos=1'">Todos los productos</button>

                <!-- Botón desplegable de categorías -->
                <button onclick="toggleDropdown()">Categorías</button>
                <div id="dropdownMenu" style="display:none;">
                    <?php foreach($categorias as $categoria): ?>
                        <div>
                            <a href="?categoria=<?php echo $categoria['IdCategoria']; ?>"><?php echo $categoria['NombreCat']; ?></a>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>