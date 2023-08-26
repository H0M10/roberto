<?php require('../layout/header.php') ?>
<?php require('../layout/database.php') ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Hola</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script defer src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script defer src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <div class="card">
        <div class="card-header">Filtro</div>
        <div class="card-body">
            <form action="" method="post">
                Fecha inicial:
                <input type="date" name="inicio" id="inicio">
                Fecha final:
                <input type="date" name="fin" id="fin">
                <input class="btn btn-primary  btn-space" type="submit" value="Filtrar">
                &nbsp;|&nbsp;&nbsp;<a class="  btn btn-warning  btn-space" href="inventarioventas.php">Mostrar todos</a>
            </form>
        </div>
    </div>
    <br>
    <table id="tabla" class="display " style="width:100%">
        <thead>
            <tr>
                <th>No. Venta</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Total de la venta</th>
                
            </tr>
        </thead>
        <tbody>
            <?php

            $whereConsulta = "";
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST["inicio"]) && isset($_POST["fin"])) {
                    $whereConsulta = " where fecha between '" . $_POST["inicio"] . "' and '" . $_POST["fin"] . "'";
                }
            }

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT v.id_venta,v.total as ventatotal, v.fecha, c.nombre, c.aPaterno, c.aMaterno from TVentas as v inner join TClientes as c on c.id_cliente = v.id_cliente " . $whereConsulta;

            $result = $conn->query($sql);
            $total = 0.0;           

            if ($result->num_rows > 0) {
                // output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>
                            <td>' . $row["id_venta"] . '</td>
                            <td>' . $row["fecha"] . '</td>
                            <td>' . $row["nombre"] . ' ' . $row["aPaterno"] .' '. $row["aMaterno"] . '</td>
                            <td>$' . $row["ventatotal"] . '</td>
                            </tr>';
                            $total += $row["ventatotal"];
                      
                }

                

            }

            $conn->close();
            ?>

        </tbody>
        <tfoot>
            <?php 

echo '<tr>
                <td></td><td></td>
                <td ><strong>Total de ventas:</strong></td>
                <td>$' . number_format($total, 2) . '</td>
            </tr>';
            ?>  
        </tfoot>
    </table>




<?php require('../layout/footer.php') ?>