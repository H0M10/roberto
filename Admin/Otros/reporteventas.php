<?php require('../layout/header.php') ?>
<?php require 'C:/xampp/htdocs/base_de_datos/database.php'; ?>


<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Productos</h1>


            <form action="" method="post" class="form-inline">
                <div class="form-group">
                    <label for="inicio" class="mr-2">Fecha inicial:</label>
                    <input type="date" name="inicio" id="inicio" class="form-control mr-2">
                </div>
                <div class="form-group">
                    <label for="fin" class="mr-2">Fecha final:</label>
                    <input type="date" name="fin" id="fin" class="form-control mr-2">
                </div>
                <button class="btn btn-primary" type="submit">Filtrar</button>
                <a class="btn btn-warning ml-2" href="reporteventas.php">Mostrar todos</a>
            </form>


        </div>
        <br><br>
        <div class="card mb-4">
            <div class="card-body">
                <table id="">
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
                                $inicio = mysqli_real_escape_string($conn, $inicio); // Reemplaza $conexion con tu conexión a la base de datos
                                $fin = mysqli_real_escape_string($conn, $fin);       // Reemplaza $conexion con tu conexión a la base de datos
                                $whereConsulta = " WHERE p.FechaPago BETWEEN '$inicio' AND '$fin'"; // Agrega comillas simples alrededor de las fechas
                            }
                        }

                        // Modifica tu consulta para que coincida con los nombres de columna y las tablas correctas
                        $sql = "SELECT v.IdVenta, MIN(v.FechaVenta) AS FechaPago, c.NombreUsu, c.ApellidoPUsu, c.ApellidoMUsu, v.Total AS Total, s.NombreSuc 
                        FROM tventas v
                        INNER JOIN tusuario c ON v.IdUsuario = c.IdUsuario
                        INNER JOIN tdetallesventa i ON i.IdVenta = v.IdVenta
                     
                        INNER JOIN tsucursal s ON s.IdSucursal = i.IdSucursal " . $whereConsulta . "
                        AND v.IdEstatus = 1
                        GROUP BY v.IdVenta, c.NombreUsu, c.ApellidoPUsu, c.ApellidoMUsu, s.NombreSuc";



                        $result = $conn->query($sql);
                        $total = 0.0;

                        if ($result !== false && $result->num_rows > 0) {
                            // Iterar a través de los resultados y mostrarlos en la tabla
                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>
                            <td>' . $row["IdVenta"] . '</td>
                            <td>' . $row["FechaPago"] . '</td>
                            <td>' . $row["NombreUsu"] . ' ' . $row["ApellidoPUsu"] . ' ' . $row["ApellidoMUsu"] . '</td>
                            <td>' . $row["NombreSuc"] . '</td>
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