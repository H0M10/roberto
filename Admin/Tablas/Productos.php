
<?php 

require('../../../database.php');

$query = "SELECT p.IdProducto, p.NombreProd, c.NombreCat, p.Precio, p.RutaImagen 
          FROM TProductos p 
          JOIN TCategorias c ON p.IdCategoria = c.IdCategoria";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 50px;
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
</head>
<body>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Categoría</th>
            <th>Precio</th>
            <th>Imagen</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['IdProducto']}</td>";
                echo "<td>{$row['NombreProd']}</td>";
                echo "<td>{$row['NombreCat']}</td>";
                echo "<td>{$row['Precio']}</td>";
                echo "<td><img src='../../img/{$row['RutaImagen']}' alt='{$row['NombreProd']}' width='50'></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No hay productos registrados.</td></tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>
