<?php require('../layout/header.php') ?>

<?php
require 'C:/xampp/htdocs/base_de_datos/database.php';
$query = "SELECT s.IdSucursal, s.NombreSuc, s.TelefonoSuc, s.DireccionSuc, s.EmailSuc, s.IdEstatus,e.Descripcion AS DescripcionEstatus
FROM TSucursal AS s
INNER JOIN TEstatus AS e ON s.IdEstatus = e.IdEstatus;";
$result = $conn->query($query);
?>
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
                <div class="card-body">
                                <table id="datatablesSimple">
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