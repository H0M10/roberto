<?php require('../layout/header.php') ?>
<?php require 'C:/xampp/htdocs/base_de_datos/database.php'; ?>

<div class="card">
    <div class="card-header">Filtro</div>
    <div class="card-body">

    </div>
</div>
<br>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Productos</h1>
            <form action="" method="post" class="d-flex">
                <label for="inicio" class="align-self-center me-2">Fecha inicial:</label>
                <input type="date" name="inicio" id="inicio" class="form-control me-2">
                <label for="fin" class="align-self-center me-2">Fecha final:</label>
                <input type="date" name="fin" id="fin" class="form-control me-2">
                <input class="btn btn-primary" type="submit" value="Filtrar">
                &nbsp;|&nbsp;&nbsp;<a class="  btn btn-warning  btn-space" href="reporteventas.php">Mostrar todos</a>
            </form>
        </div>
            <br><br>
            <div class="card mb-4">
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>No. Venta</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Sucursal</th>
                                <th>Total de la venta</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Tu código para la conexión a la base de datos

                            $whereConsulta = "";
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                if (isset($_POST["inicio"]) && isset($_POST["fin"])) {
                                    $inicio = $_POST["inicio"];
                                    $fin = $_POST["fin"];
                                    // Escapa las variables para evitar inyección SQL y aplica el formato correcto para las fechas
                                    $inicio = mysqli_real_escape_string($conexion, $inicio); // Reemplaza $conexion con tu conexión a la base de datos
                                    $fin = mysqli_real_escape_string($conexion, $fin);       // Reemplaza $conexion con tu conexión a la base de datos
                                    $whereConsulta = " WHERE p.FechaPago BETWEEN '$inicio' AND '$fin'"; // Agrega comillas simples alrededor de las fechas
                                }
                            }

                            // Modifica tu consulta para que coincida con los nombres de columna y las tablas correctas
                            $sql = "SELECT v.IdVenta, MIN(p.FechaVenta) AS FechaPago, c.NombreUsu, c.ApellidoPUsu, c.ApellidoMUsu, SUM(p.Total) AS Total, s.NombreSuc 
                    FROM tventas v
                    INNER JOIN tusuario c ON v.IdUsuario = c.IdUsuario
                    INNER JOIN tdetallesventa i ON i.IdVenta = v.IdVenta
                    INNER JOIN inventario q ON i.IdInventario = q.IdInventario
                    INNER JOIN sucursal s ON s.IdSucursal = q.IdSucursal
                    INNER JOIN pago p ON v.IdVenta = p.IdVenta" . $whereConsulta . "
                    AND v.IdEstatus = 3
                    GROUP BY v.IdVenta, c.Nombre, c.ApellidoPc, c.ApellidoMc, s.Nombre";



                            $result = $conn->query($sql);
                            $total = 0.0;

                            if ($result !== false && $result->num_rows > 0) {
                                // Iterar a través de los resultados y mostrarlos en la tabla
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>
                            <td>' . $row["IdVenta"] . '</td>
                            <td>' . $row["FechaPago"] . '</td>
                            <td>' . $row["Nombre"] . ' ' . $row["ApellidoPc"] . ' ' . $row["ApellidoMc"] . '</td>
                            <td>' . $row["NombreSucursal"] . '</td>
                            <td>$' . $row["Total"] . '</td>
                        </tr>';
                                    $total += $row["Total"];
                                }
                            }

                            $conn->close();
                            ?>

                        </tbody>
                        <tfoot>
                            <?php
                            // Mostrar el total de ventas al final de la tabla
                            echo '<tr>
                    <td></td><td></td><td></td>
                    <td><strong>Total de ventas:</strong></td>
                    <td>$' . number_format($total, 2) . '</td>
                </tr>';
                            ?>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
<!-- Tu código para los scripts y enlaces a librerías -->
</body>

</html>
<?php require('../layout/footer.php') ?>