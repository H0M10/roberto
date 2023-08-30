
<?php
session_start(); // Iniciar la sesión

require 'C:/xampp/htdocs/base_de_datos/database.php';
print_r($_POST);

if (isset($_POST['idProducto'])) {
    $idProducto = $_POST['idProducto'];
    // El resto de tu código aquí
} else {
    echo json_encode(["success" => false, "message" => "idProducto no recibido"]);
}


$idusuario = $_SESSION['idusuario'];

$idProducto = $_POST['idProducto'];


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conn->connect_error]));
}

$deleteQuery = "DELETE FROM TDetallesCarrito WHERE IdCarrito = ? AND IdProducto = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("ii", $userId, $idProducto);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "Producto eliminado del carrito con éxito."]);
} else {
    echo json_encode(["success" => false, "message" => "Error al eliminar el producto del carrito."]);
}

$conn->close();
?>
