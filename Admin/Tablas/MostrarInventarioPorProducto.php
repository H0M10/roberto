<?php require('../layout/header.php'); ?>

<?php
require 'C:/xampp/htdocs/base_de_datos/database.php';

$query = "SELECT TInventario.IdInventario, TProductos.NombreProd, TSucursal.NombreSuc, TInventario.Cantidad 
          FROM TInventario 
          JOIN TProductos ON TInventario.IdProducto = TProductos.IdProducto 
          JOIN TSucursal ON TInventario.IdSucursal = TSucursal.IdSucursal";

if (isset($_GET['action']) && $_GET['action'] == "show_all") {
    $result = $conn->query($query);
} elseif (isset($_GET['IdProducto'])) {
    $idProducto = $_GET['IdProducto'];
    $query .= " WHERE TInventario.IdProducto = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idProducto);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Inventario por producto</h1>

            <!-- Tarjeta para el formulario -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="MostrarInventarioPorProducto.php">
                        <label for="IdProducto">Selecciona un producto:</label>
                        <select name="IdProducto">
                            <?php
                            $productos = $conn->query("SELECT IdProducto, NombreProd FROM TProductos");
                            while ($row = $productos->fetch_assoc()) {
                                echo "<option value='{$row['IdProducto']}'>{$row['NombreProd']}</option>";
                            }
                            ?>
                        </select>
                        <input type="submit" value="Cargar inventario">
                    </form>
                    <form method="GET" action="MostrarInventarioPorProducto.php">
                        <input type="hidden" name="action" value="show_all">
                        <input type="submit" value="Mostrar todo">
                    </form>
                </div>
            </div>

            <!-- Tarjeta para la tabla -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Inventario del producto seleccionado
                </div>
                <div class="card-body">
                    <?php if (isset($result) && $result->num_rows > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nombre del producto</th>
                                    <th>Nombre de la sucursal</th>
                                    <th>Cantidad</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>{$row['NombreProd']}</td>";
                                    echo "<td>{$row['NombreSuc']}</td>";
                                    echo "<td>{$row['Cantidad']}</td>";
                                    echo "<td><a href='EditarInventario.php?IdInventario={$row['IdInventario']}'>Editar</a></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No hay registros de inventario para mostrar.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require('../layout/footer.php'); ?>
