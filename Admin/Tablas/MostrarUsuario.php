
<?php require('../layout/header.php') ?>

<?php 
require 'C:/xampp/htdocs/base_de_datos/database.php';
$query = "SELECT u.IdUsuario, u.NombreUsu, u.ApellidoPUsu, u.ApellidoMUsu, u.CorreoUsu, u.CuentaUsu, u.DireccionUsu, u.TelefonoUsu, u.GeneroUsu, u.IdEstatus, e.Descripcion AS DescripcionEstatus
FROM TUsuario AS u
INNER JOIN TEstatus AS e ON u.IdEstatus = e.IdEstatus;";
$result = $conn->query($query);

if (!$result) {
    die("SQL Error: " . $conn->error);
}

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
            <h1 class="mt-4">Usuario</h1>

            <div class="card mb-4">
                <div class="card-body">
                    Aquí encontraras toda la información de los usuarios dados de alta.
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                  Usuarios alojados en la base de datos.
                </div>
                <div class="">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido Paterno</th>
                                <th>Apellido Materno</th>
                                <th>Telefono</th>
                                <th>Direccion</th>
                                <th>Email</th>
                                <th>Cuenta</th>
                                <th>Genero</th>
                                <th>Estatus</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Apellido Paterno</th>
                                <th>Apellido Materno</th>
                                <th>Telefono</th>
                                <th>Direccion</th>
                                <th>Email</th>
                                <th>Cuenta</th>
                                <th>Genero</th>
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
                                    echo "<td>{$row['IdUsuario']}</td>";
                                    echo "<td>{$row['NombreUsu']}</td>";
                                    echo "<td>{$row['ApellidoPUsu']}</td>";
                                    echo "<td>{$row['ApellidoMUsu']}</td>";
                                    echo "<td>{$row['TelefonoUsu']}</td>";
                                    echo "<td>{$row['DireccionUsu']}</td>";
                                    echo "<td>{$row['CorreoUsu']}</td>";
                                    echo "<td>{$row['CuentaUsu']}</td>";
                                    echo "<td>{$row['GeneroUsu']}</td>";
                                    echo "<td>{$row['DescripcionEstatus']}</td>";
                                    echo "<td><a href='../Actualizar/EditarUsuario.php?id={$row['IdUsuario']}' class='btn btn-primary'>Editar</a></td>";
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