
<?php require('../layout/header.php') ?>

<?php 
require 'C:/xampp/htdocs/base_de_datos/database.php';
$query = "SELECT IdCategoria, NombreCat, DescripcionCat, IdEstatus FROM TCategorias";
$result = $conn->query($query);
?>

<style>
    /* Adjusted body padding to accommodate the header */
    body {
        padding-top: 150px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    th {
        background-color: #333;
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Categoria</h1>
            
            <div class="card mb-4">
                <div class="card-body">
                   Aquí encontraras toda la información de las categorias dadas de alta.
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Categorias alojados en la base de datos.
                </div>
                <div class="">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estatus</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                            <tr>
                            <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Estatus</th>
                                <th>Editar</th>
                            </tr>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>{$row['IdCategoria']}</td>";
                                    echo "<td>{$row['NombreCat']}</td>";
                                    echo "<td>{$row['DescripcionCat']}</td>";
                                    echo "<td>{$row['IdEstatus']}</td>";
                                    echo "<td><a href='../Actualizar/EditarCategoria.php?id={$row['IdCategoria']}' class='btn btn-primary'>Editar</a></td>";
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
