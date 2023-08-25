<?php require('../layout/header.php') ?>

<?php
require 'C:/xampp/htdocs/base_de_datos/database.php';
$query = "SELECT s.IdSucursal, s.NombreSuc, s.TelefonoSuc, s.DireccionSuc, s.EmailSuc, s.IdEstatus,e.Descripcion AS DescripcionEstatus
FROM TSucursal AS s
INNER JOIN TEstatus AS e ON s.IdEstatus = e.IdEstatus;";
$result = $conn->query($query);
?>
<<<<<<< HEAD

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
=======
>>>>>>> e71384a561ed0835e6fec7e2b9ac8a1538634f3d
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Sucursales</h1>

            <div class="card mb-4">
                <div class="card-body">
                    Aquí encontraras toda la información de las sucursales dadas de alta.
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Sucursales alojadas en la base de datos.
                </div>
<<<<<<< HEAD
                <div class="">
                    <table>
=======
                <div class="card-body">
                    <table id="datatablesSimple">
>>>>>>> e71384a561ed0835e6fec7e2b9ac8a1538634f3d
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Telefono</th>
                                <th>Direccion</th>
                                <th>Email</th>
                                <th>Estatus</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Telefono</th>
                                <th>Direccion</th>
                                <th>Email</th>
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
                                    echo "<td>{$row['IdSucursal']}</td>";
                                    echo "<td>{$row['NombreSuc']}</td>";
                                    echo "<td>{$row['TelefonoSuc']}</td>";
                                    echo "<td>{$row['DireccionSuc']}</td>";
                                    echo "<td>{$row['EmailSuc']}</td>";
                                    echo "<td>{$row['DescripcionEstatus']}</td>";
                                    echo "<td><a href='../Actualizar/EditarSucursal.php?id={$row['IdSucursal']}' class='btn btn-primary'>Editar</a></td>";
                                    echo "</tr>";
<<<<<<< HEAD
                                    
=======
>>>>>>> e71384a561ed0835e6fec7e2b9ac8a1538634f3d
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