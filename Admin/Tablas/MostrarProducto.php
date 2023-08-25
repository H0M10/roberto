<?php require('../layout/header.php') ?>

<?php
require 'C:/xampp/htdocs/base_de_datos/database.php';
$query = "SELECT p.IdProducto, p.NombreProd, c.NombreCat, p.Precio, p.RutaImagen 
          FROM TProductos p 
          JOIN TCategorias c ON p.IdCategoria = c.IdCategoria";
$result = $conn->query($query);
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Productos</h1>

            <div class="card mb-4">
                <div class="card-body">
                    Aquí encontraras toda la información de los productos dados de alta.
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Productos alojados en la base de datos.
                </div>
                <div class="card-body">
              
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Imagen</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Imagen</th>
                                <th>Editar</th>
                            </tr>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>{$row['IdProducto']}</td>";
                                    echo "<td>{$row['NombreProd']}</td>";
                                    echo "<td>{$row['NombreCat']}</td>";
                                    echo "<td>{$row['Precio']}</td>";
                                    echo "<td><img src='{$row['RutaImagen']}' alt='{$row['NombreProd']}' width='50'></td>";
                                    echo "<td><a href='../Actualizar/EditarProducto.php?id={$row['IdProducto']}' class='btn btn-primary'>Editar</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No hay productos registrados.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

</div>

<?php require('../layout/footer.php') ?>