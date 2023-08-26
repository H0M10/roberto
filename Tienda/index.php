
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

$querySucursales = "SELECT IdSucursal, NombreSuc FROM TSucursal WHERE IdEstatus = 1"; 
$resultSucursales = $conn->query($querySucursales);
$sucursales = [];
if ($resultSucursales->num_rows > 0) {
    while($row = $resultSucursales->fetch_assoc()) {
        $sucursales[] = $row;
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
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #343a40;
            padding: 0.5rem 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 0;
        }
        .navbar-brand {
            color: #ffffff;
        }
        .sidebar {
            background-color: #343a40;
            padding: 2rem;
            height: 100vh;
            position: sticky;
            top: 0;
            color: #ffffff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar h5 {
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
        .sidebar .btn {
            margin-bottom: 1rem;
            border-radius: 20px;
            transition: transform 0.2s;
        }
        .sidebar .btn:hover {
            transform: scale(1.05);
        }
        .sidebar .btn-danger {
            background-color: #dc3545;
        }
        .sidebar .btn-danger:hover {
            background-color: #c82333;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>

<body>
<div id="mensaje-producto">
    <?php if (isset($_SESSION['mensaje_producto'])) {
        echo $_SESSION['mensaje_producto'];
        unset($_SESSION['mensaje_producto']); // Limpia el mensaje después de mostrarlo
    } ?>
</div>

<div id="mensaje-sucursal" style="color: red;">
    <?php if (isset($_SESSION['mensaje_sucursal'])) {
        echo $_SESSION['mensaje_sucursal'];
        unset($_SESSION['mensaje_sucursal']); // Limpia el mensaje después de mostrarlo
    } ?>
</div>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">ComponentSpace</a>
    <div>
    <form method="post" id="seleccionarSucursalForm">
        <select id="sucursalDropdown" name="sucursal_id">
            <option value="">Selecciona una sucursal</option>
            <?php foreach($sucursales as $sucursal): ?>
                <option value="<?php echo $sucursal['IdSucursal']; ?>">
                    <?php echo $sucursal['NombreSuc']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="seleccionar_sucursal">Seleccionar</button>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['idusuario'])) {
    if (isset($_POST['seleccionar_sucursal'])) {
        $sucursalId = $_POST['sucursal_id'];
        $usuarioId = $_SESSION['idusuario'];

        // Eliminar los detalles del carrito relacionados con la sucursal anterior
        $sqlEliminarDetallesCarrito = "DELETE FROM TDetallesCarrito WHERE IdCarrito IN (SELECT IdCarrito FROM TCarrito WHERE IdUsuario = ?) AND IdSucursal <> ?";
        $stmtEliminarDetallesCarrito = $conn->prepare($sqlEliminarDetallesCarrito);
        $stmtEliminarDetallesCarrito->bind_param("ii", $usuarioId, $sucursalId);
        $stmtEliminarDetallesCarrito->execute();
        $stmtEliminarDetallesCarrito->close();

        // Realiza la consulta de actualización
        $sqlActualizarSucursal = "UPDATE TUsuario SET IdSucursalSeleccionada = ? WHERE IdUsuario = ?";
        $stmtActualizarSucursal = $conn->prepare($sqlActualizarSucursal);
        $stmtActualizarSucursal->bind_param("ii", $sucursalId, $usuarioId);

        if ($stmtActualizarSucursal->execute()) {
            echo '<script>alert("Sucursal seleccionada actualizada exitosamente.");</script>';
        } else {
            echo '<script>alert("Error al actualizar la sucursal seleccionada.");</script>';
        }

        $stmtActualizarSucursal->close();
    }
}
?>

                <!-- Botón desplegable de categorías -->
                <button onclick="toggleDropdown()">Categorías</button>
               
<div id="dropdownMenu" style="display:none;">
                <select onchange="location = this.value;">
    <option value="">Selecciona una categoría</option>
    <?php foreach($categorias as $categoria): ?>
        <option value="?categoria=<?php echo $categoria['IdCategoria']; ?>">
            <?php echo $categoria['NombreCat']; ?>
        </option>
    <?php endforeach; ?>
</select>

                </div>
</nav>

<div class="container-fluid p-0">
    <div class="row m-0">
        <div class="col-md-3 p-0">
            <div class="sidebar">
                <h5>Menú</h5>
                <ul class="list-unstyled">
                    <?php
                    if (!isset($_SESSION['idusuario'])) {
                        echo '<li class="mb-2"><button class="btn btn-primary btn-block" onclick="location.href=\'login.php\'">Iniciar Sesión</button></li>';
                    } else {
                        echo '<li class="mb-2"><button class="btn btn-primary btn-block">Factura</button></li>';
                        echo '<li class="mb-2"><button class="btn btn-primary btn-block">Usuario</button></li>';
                        echo '<li class="mb-2"><button class="btn btn-danger btn-block" onclick="location.href=\'cerrar.php\'">Cerrar Sesión</button></li>';
                     } ?>
                        <li class="mb-2">
                            <button class="btn btn-primary btn-block" onclick="location.href='./Carrito/carrito.php'">Carrito</button>
                        </li>
                    </ul>
                    
                </ul>
         

                <!-- Botón para mostrar todos los productos -->
                <button onclick="location.href='?todos=1'">Todos los productos</button>


            </div>
        </div>

        <div class="col-md-9">
             <div class="container mt-4">
        <div class="row">
            <?php foreach($productos as $producto): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo $producto['RutaImagen']; ?>" class="card-img-top" alt="<?php echo $producto['NombreProd']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $producto['NombreProd']; ?></h5>
                            <p class="card-text">$<?php echo $producto['Precio']; ?></p>
                        </div>
                        <div class="card-footer">

                            <!-- Formulario de Agregación al Carrito -->
                            <form action="agregar.php" method="post">
                                <!-- Campo oculto para el IdProducto -->
                                <input type="hidden" name="producto_id" value="<?php echo $producto['IdProducto']; ?>">

                                <!-- Campo para la cantidad -->
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Cantidad</span>
                                    </div>
                                    <input type="number" name="cantidad" class="form-control" value="1" min="1">
                                </div>

                                <!-- Botón de agregar -->
                                <button type="submit" class="btn btn-primary btn-block">Agregar</button>
                            </form>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</div>




<script>
    function toggleDropdown() {
        var dropdown = document.getElementById('dropdownMenu');
        if (dropdown.style.display === "none") {
            dropdown.style.display = "block";
        } else {
            dropdown.style.display = "none";
        }
    }
</script>

<!-- Scripts de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
document.querySelectorAll('.product-form').forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(form);

        var sucursalDropdown = document.getElementById('sucursalDropdown');
        var mensajeSucursal = document.getElementById('mensaje-sucursal');
        
        if (sucursalDropdown.value === "") {
            mensajeSucursal.innerText = 'Selecciona una sucursal antes de agregar al carrito.';
            return; // Detiene el proceso de envío del formulario
        } else {
            mensajeSucursal.innerText = ''; // Borra el mensaje si una sucursal ha sido seleccionada
        }

        fetch('agregar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito o realizar alguna acción en la interfaz
                alert('Producto agregado al carrito.');
            } else {
                // Mostrar mensaje de error o realizar alguna acción en la interfaz
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Mostrar mensaje de error o realizar alguna acción en la interfaz
            alert('Ocurrió un error al agregar al carrito.');
        });
    });
});
</script>

</body>
</html>
