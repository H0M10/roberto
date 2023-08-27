<?php require('../layout/header.php'); ?>

<?php
require 'C:/xampp/htdocs/base_de_datos/database.php';

$mensaje = '';
$inventario = null;  // Initialize the variable to avoid undefined variable error

if (isset($_GET['IdInventario'])) {
    $idInventario = $_GET['IdInventario'];

    // Consulta SQL para obtener detalles del inventario seleccionado
    $query = "SELECT TInventario.IdInventario, TProductos.NombreProd, TSucursal.NombreSuc, TInventario.Cantidad 
              FROM TInventario 
              JOIN TProductos ON TInventario.IdProducto = TProductos.IdProducto 
              JOIN TSucursal ON TInventario.IdSucursal = TSucursal.IdSucursal 
              WHERE TInventario.IdInventario = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idInventario);
    $stmt->execute();
    $inventario = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idInventario = $_POST['IdInventario'];
    $cantidad = $_POST['Cantidad'];
    $accion = $_POST['Accion'];

    if ($accion === "sumar") {
        $cantidad++;
    } elseif ($accion === "restar" && $cantidad > 0) {
        $cantidad--;
    }

    $stmt = $conn->prepare("UPDATE TInventario SET Cantidad = ? WHERE IdInventario = ?");
    $stmt->bind_param("ii", $cantidad, $idInventario);

    if ($stmt->execute()) {
        $mensaje = "Inventario actualizado con éxito!";
    } else {
        $mensaje = "Error al actualizar el inventario: " . $stmt->error;
    }

    $stmt->close();
}
?>

<form method="POST" action="EditarInventario.php">
    <label for="NombreProd">Nombre del producto:</label>
    <input type="text" name="NombreProd" value="<?php echo $inventario ? $inventario['NombreProd'] : ''; ?>" readonly>

    <label for="NombreSuc">Nombre de la sucursal:</label>
    <input type="text" name="NombreSuc" value="<?php echo $inventario ? $inventario['NombreSuc'] : ''; ?>" readonly>

    <label for="Cantidad">Cantidad:</label>
    <input type="number" name="Cantidad" value="<?php echo $inventario ? $inventario['Cantidad'] : ''; ?>">

    <input type="hidden" name="IdInventario" value="<?php echo $inventario ? $inventario['IdInventario'] : ''; ?>">
    <input type="submit" name="Accion" value="Actualizar">
</form>

<input type="button" value="Regresar" onclick="window.location.href='./MostrarInventarioPorProducto.php';">

<?php if (!empty($mensaje)): ?>
<?php endif; ?>

<?php require('../layout/footer.php'); ?>
