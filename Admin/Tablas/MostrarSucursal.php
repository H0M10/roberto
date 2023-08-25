
<?php require('../layout/header.php') ?>

<?php 
require 'C:/xampp/htdocs/base_de_datos/database.php';
$query = "SELECT IdSucursal, NombreSuc, TelefonoSuc, DireccionSuc, EmailSuc, IdEstatus FROM TSucursal";
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
        border-bottom: 1px solid #eee;
        text-align: left;
    }

    th {
        background-color: #333;
        color: #fff;
    }

    tr:hover {
        background-color: #f5f5f5;
    }
</style>

<h2>Mostrar Sucursales</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Email</th>
            <th>Estatus</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['IdSucursal']; ?></td>
                <td><?php echo $row['NombreSuc']; ?></td>
                <td><?php echo $row['TelefonoSuc']; ?></td>
                <td><?php echo $row['DireccionSuc']; ?></td>
                <td><?php echo $row['EmailSuc']; ?></td>
                <td><?php echo $row['IdEstatus']; ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php require('../layout/footer.php') ?>
