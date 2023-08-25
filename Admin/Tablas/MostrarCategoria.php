
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

    th, td {
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

<h2>Mis Categorías</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Estatus</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['IdCategoria']}</td>";
                echo "<td>{$row['NombreCat']}</td>";
                echo "<td>{$row['DescripcionCat']}</td>";
                echo "<td>{$row['IdEstatus']}</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay categorías registradas.</td></tr>";
        }
        ?>
    </tbody>
</table>


</body>
</html>


<?php require('../layout/footer.php') ?>
