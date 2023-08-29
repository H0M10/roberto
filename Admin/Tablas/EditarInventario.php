<?php require('../layout/header.php'); ?>

<?php
require 'C:/xampp/htdocs/base_de_datos/database.php';

$mensaje = '';
$inventario = null;  // Initialize the variable to avoid undefined variable error



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idProducto = $_POST['IdProducto'];
    $idSucursal = $_POST['IdSucursal'];
    $cantidad = $_POST['Cantidad'];
    $accion = $_POST['Accion'];

    if ($accion === "sumar") {
        $cantidad++;
    } elseif ($accion === "restar" && $cantidad > 0) {
        $cantidad--;
    }

    // Check if a record exists for the selected product and branch
    $check_query = "SELECT IdInventario FROM TInventario WHERE IdProducto = ? AND IdSucursal = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ii", $idProducto, $idSucursal);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $existing_record = $check_result->fetch_assoc();

    if ($existing_record) {
        // Update the existing record
        
// Determine the IdInventario based on selected product and branch
$idInventario_query = "SELECT IdInventario FROM TInventario WHERE IdProducto = ? AND IdSucursal = ?";
$idInventario_stmt = $conn->prepare($idInventario_query);
$idInventario_stmt->bind_param("ii", $idProducto, $idSucursal);
$idInventario_stmt->execute();
$idInventario_result = $idInventario_stmt->get_result();
$inventario_record = $idInventario_result->fetch_assoc();

if ($inventario_record) {
    $idInventario = $inventario_record['IdInventario'];
} else {
    $idInventario = null;
}
$idInventario_stmt->close();
$stmt = $conn->prepare("UPDATE TInventario SET Cantidad = ? WHERE IdInventario = ?");
        $stmt->bind_param("ii", $cantidad, $existing_record['IdInventario']);
    } else {
        // Insert a new record
        $stmt = $conn->prepare("INSERT INTO TInventario (IdProducto, IdSucursal, Cantidad) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $idProducto, $idSucursal, $cantidad);
    }

    if ($stmt->execute()) {
    $cantidad = $_POST['Cantidad'];
    $accion = $_POST['Accion'];

    if ($accion === "sumar") {
        $cantidad++;
    } elseif ($accion === "restar" && $cantidad > 0) {
        $cantidad--;
    }

        $mensaje = "Inventario actualizado con éxito!";
    } else {
        $mensaje = "Error al actualizar el inventario: " . $stmt->error;
    }

    $stmt->close();
}

    
// Determine the IdInventario based on selected product and branch
$idInventario_query = "SELECT IdInventario FROM TInventario WHERE IdProducto = ? AND IdSucursal = ?";
$idInventario_stmt = $conn->prepare($idInventario_query);
$idInventario_stmt->bind_param("ii", $idProducto, $idSucursal);
$idInventario_stmt->execute();
$idInventario_result = $idInventario_stmt->get_result();
$inventario_record = $idInventario_result->fetch_assoc();

if ($inventario_record) {
    $idInventario = $inventario_record['IdInventario'];
} else {
    $idInventario = null;
}
$idInventario_stmt->close();
$stmt = $conn->prepare("UPDATE TInventario SET Cantidad = ? WHERE IdInventario = ?");
    $stmt->bind_param("ii", $cantidad, $idInventario);

    if ($stmt->execute()) {
        $mensaje = "Inventario actualizado con éxito!";
    } else {
        $mensaje = "Error al actualizar el inventario: " . $stmt->error;
    }

    $stmt->close();

?>

<form method="POST" action="EditarInventario.php">
    
<label for="IdProducto">Selecciona un producto:</label>
<select name="IdProducto">
    <?php
    $productos_query = $conn->query("SELECT IdProducto, NombreProd FROM TProductos");
    while ($producto = $productos_query->fetch_assoc()) {
        $selected = $inventario && $inventario['NombreProd'] == $producto['NombreProd'] ? "selected" : "";
        echo "<option value='{$producto['IdProducto']}' $selected>{$producto['NombreProd']}</option>";
    }
    ?>
</select>


    
<label for="IdSucursal">Selecciona una sucursal:</label>
<select name="IdSucursal">
    <?php
    $sucursales_query = $conn->query("SELECT IdSucursal, NombreSuc FROM TSucursal");
    while ($sucursal = $sucursales_query->fetch_assoc()) {
        $selected = $inventario && $inventario['NombreSuc'] == $sucursal['NombreSuc'] ? "selected" : "";
        echo "<option value='{$sucursal['IdSucursal']}' $selected>{$sucursal['NombreSuc']}</option>";
    }
    ?>
</select>


    <label for="Cantidad">Cantidad:</label>
    <input type="number" name="Cantidad" value="<?php echo $inventario ? $inventario['Cantidad'] : ''; ?>">

    
    <input type="submit" name="Accion" value="Actualizar">
</form>

<input type="button" value="Regresar" onclick="window.location.href='./MostrarInventarioPorProducto.php';">

<?php if (!empty($mensaje)): ?>
<?php endif; ?>

<?php require('../layout/footer.php'); ?>
