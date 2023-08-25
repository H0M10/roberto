
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
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">ComponentSpace</a>
    </nav>
    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-md-3 p-0">
                <div class="sidebar">
                    <h5>Menú</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><button class="btn btn-primary btn-block">Iniciar Sesión</button></li>
                        <li class="mb-2"><button class="btn btn-primary btn-block">Categorías</button></li>
                        <li class="mb-2"><button class="btn btn-primary btn-block">Carrito</button></li>
                        <li class="mb-2"><button class="btn btn-primary btn-block">Factura</button></li>
                        <li class="mb-2"><button class="btn btn-primary btn-block">Usuario</button></li>
                        <li class="mb-2"><button class="btn btn-primary btn-block">Sucursal</button></li>

                        <li class="mb-2"><button class="btn btn-danger btn-block">Cerrar Sesión</button></li>
                    </ul>
                </div>
                <h5>Categorías</h5>
            </div>
            <div class="col-md-9">
                <?php
                require 'C:/xampp/htdocs/base_de_datos/database.php';
                $query = "SELECT * FROM TProductos";
                $result = $conn->query($query);
                $productos = [];
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $productos[] = $row;
                    }
                }
                ?>
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
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Cantidad</span>
                                            </div>
                                            <input type="number" name="quant[<?php echo $producto['IdProducto']; ?>]" class="form-control" value="1" min="1">
                                        </div>
                                        <button type="button" class="btn btn-primary btn-block">Agregar</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
