<?php require "./code128.php"; ?>
<?php include("../bd.php"); ?>
<?php
session_start();

// Realiza la conexión a la base de datos aquí

// Verifica si el usuario está autenticado y obtiene el ID de usuario de la sesión

$idusuario = $_SESSION['idusuario'];

// Realiza una consulta para obtener los datos del cliente correspondientes al ID de usuario
$sqlclie = "SELECT * FROM cliente WHERE IdUsuario = $idusuario";
$resultclie = $conn->query($sqlclie);

//$nombre = $resultadoUsu['nombre_usuario'];
//$pass = $resultadoUsu['contraseña_usuario'];
if ($resultclie && $resultclie->num_rows > 0) {
	$row = $resultclie->fetch_assoc();
	$IdCliente = $row['IdCliente'];
	$Nombre = $row['Nombre'];
	$Apellido = $row['ApellidoPc'];



	// Cierra la conexión a la base de datos aquí

	$sql = "SELECT p.Nombre, p.ApellidoPc, p.ApellidoMc, p.RFC, p.Celc, p.Ciudad, p.Callec, p.Coloniac, p.CPc, p.Numeroextc, p.Numerointc, p.EmailF, MAX(v.IdVenta) AS MaxIdVenta, MAX(f.IdFactura) AS MaxIdFactura
	FROM cliente p
	INNER JOIN venta v ON v.IdCliente = p.IdCliente
	INNER JOIN factura f ON f.IdVenta = v.IdVenta
	WHERE v.IdCliente = $IdCliente AND v.IdEstatus = 3 
		  AND DATE_FORMAT(f.FechaFactura, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d')
	GROUP BY p.Nombre, p.ApellidoPc, p.ApellidoMc, p.RFC, p.Celc, p.Ciudad, p.Callec, p.Coloniac, p.CPc, p.Numeroextc, p.Numerointc, p.EmailF";

		  

	$resultado = $conn->query($sql);
	$fila = '';
	if ($resultado) {
		// Si la consulta devolvió resultados, obtener la primera fila directamente
		$fila = $resultado->fetch_assoc();
	}
}
$pdf = new PDF_Code128('P', 'mm', 'Letter');
$pdf->SetMargins(17, 17, 17);
$pdf->AddPage();

# Logo de la empresa formato png #
//$pdf->Image('./img/logo07.png', 165, 12, 35, 35, 'PNG');

# Encabezado y datos de la empresa #
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(32, 100, 210);
$pdf->Cell(150, 10, utf8_decode(strtoupper("SweetHome")), 0, 0, 'L');

$pdf->Ln(9);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(39, 39, 51);
$rfc = $fila['RFC'];
$pdf->Cell(150, 9, utf8_decode("RFC: " . "$rfc"), 0, 0, 'L');

$pdf->Ln(5);

$pdf->Cell(150, 9, utf8_decode($fila['Ciudad'] . " " . $fila['Callec'] . " " . $fila['Coloniac']), 0, 0, 'L');

$pdf->Ln(5);

$pdf->Cell(150, 9, utf8_decode($fila['Celc']), 0, 0, 'L');

$pdf->Ln(5);

$pdf->Cell(150, 9, utf8_decode("Email: " . $fila['EmailF']), 0, 0, 'L');

$pdf->Ln(10);

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(30, 7, utf8_decode("Fecha de emisión:"), 0, 0);
$pdf->SetTextColor(97, 97, 97);
$pdf->Cell(116, 7, utf8_decode(date("d/m/Y", strtotime("15-08-2023")) . " " ), 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(39, 39, 51);
$pdf->Cell(35, 7, utf8_decode(strtoupper("Factura Nro." . $fila['MaxIdFactura'])), 0, 0, 'C');

$pdf->Ln(7);


$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(97, 97, 97);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetTextColor(97, 97, 97);

$pdf->Ln(10);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(39, 39, 51);
$pdf->Cell(13, 7, utf8_decode("Cliente:"), 0, 0);
$pdf->SetTextColor(97, 97, 97);
$pdf->Cell(60, 7, utf8_decode($fila['Nombre']), 0, 0, 'L');
$pdf->SetTextColor(39, 39, 51);
$pdf->Cell(8, 7, utf8_decode("RFC:  "), 0, 0, 'L');
$pdf->SetTextColor(97, 97, 97);
$pdf->Cell(60, 7, utf8_decode($fila['RFC']), 0, 0, 'L');
$pdf->SetTextColor(39, 39, 51);
$pdf->Cell(7, 7, utf8_decode("Tel:"), 0, 0, 'L');
$pdf->SetTextColor(97, 97, 97);
$pdf->Cell(35, 7, utf8_decode($fila['Celc']), 0, 0);
$pdf->SetTextColor(39, 39, 51);

$pdf->Ln(7);

$pdf->SetTextColor(39, 39, 51);
$pdf->Cell(6, 7, utf8_decode("Dir:"), 0, 0);
$pdf->SetTextColor(97, 97, 97);
$pdf->Cell(109, 7, utf8_decode("Fray junipero cerra, Querétaro"), 0, 0);

$pdf->Ln(9);

# Tabla de productos #
$pdf->SetFont('Arial', '', 8);
$pdf->SetFillColor(23, 83, 201);
$pdf->SetDrawColor(23, 83, 201);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(90, 8, utf8_decode("Descripción"), 1, 0, 'C', true);
$pdf->Cell(19, 8, utf8_decode("Sucursal"), 1, 0, 'C', true);
$pdf->Cell(15, 8, utf8_decode("Cant."), 1, 0, 'C', true);
$pdf->Cell(25, 8, utf8_decode("Precio"), 1, 0, 'C', true);

$pdf->Cell(32, 8, utf8_decode("Subtotal"), 1, 0, 'C', true);

$pdf->Ln(8);


$pdf->SetTextColor(39, 39, 51);

$IdFactura = $fila['MaxIdFactura'];

$usuario = $_SESSION['idusuario'];

$consulta ="SELECT p.Nombre as NombreP, p.IdProducto, p.Imagen, p.Precio, c.CantidadVenta, s.Nombre as Nombresucursal, v.IdCliente, i.Existencias, c.IdInventario, c.IdVenta, a.Total, p.Coste, f.IdFactura
FROM producto p
INNER JOIN inventario i ON p.IdProducto = i.IdProducto
INNER JOIN ventainventario c ON i.IdInventario = c.IdInventario
INNER JOIN sucursal s ON i.IdSucursal = s.IdSucursal
INNER JOIN venta v ON c.IdVenta = v.IdVenta
INNER JOIN pago a ON a.IdVenta = v.IdVenta
INNER JOIN factura f ON f.IdVenta = v.IdVenta
WHERE v.IdCliente = $IdCliente AND v.IdEstatus = 3 
AND f.IdFactura =  $IdFactura
AND TIMESTAMPDIFF(MINUTE, f.FechaFactura, a.FechaPago )<= 3";

// Agrega esta condición para seleccionar la factura específica

$resultado = $conn->query($consulta);

/*----------  Detalles de la tabla  ----------*/

if ($resultado === false) {
	echo "Error en la consulta: " . $conn->error;
} else {
	while ($fila = $resultado->fetch_assoc()) {
		$pdf->Cell(90, 7, utf8_decode($fila['NombreP']), 'L', 0, 'C');
		$pdf->Cell(19, 7, utf8_decode($fila['Nombresucursal']), 'L', 0, 'C');
		$pdf->Cell(15, 7, utf8_decode($fila['CantidadVenta']), 'L', 0, 'C');
		$pdf->Cell(25, 7, utf8_decode($fila['Coste']), 'L', 0, 'C');
		
		$pdf->Cell(32, 7, utf8_decode($Subtotal = ($fila['CantidadVenta'] * $fila['Coste']). " MXN"), 'LR', 0, 'C');
		$pdf->Ln(7);
	}
	/*----------  Fin Detalles de la tabla  ----------*/
}
$coste = 0;
$subtotal = 0;
$pdf->SetFont('Arial', 'B', 9);
$consulta ="SELECT p.Nombre as NombreP, p.IdProducto, p.Imagen, p.Precio, c.CantidadVenta, s.Nombre as Nombresucursal, v.IdCliente, i.Existencias, c.IdInventario, c.IdVenta, a.Total, p.Coste, f.IdFactura
FROM producto p
INNER JOIN inventario i ON p.IdProducto = i.IdProducto
INNER JOIN ventainventario c ON i.IdInventario = c.IdInventario
INNER JOIN sucursal s ON i.IdSucursal = s.IdSucursal
INNER JOIN venta v ON c.IdVenta = v.IdVenta
INNER JOIN pago a ON a.IdVenta = v.IdVenta
INNER JOIN factura f ON f.IdVenta = v.IdVenta
WHERE v.IdCliente = $IdCliente AND v.IdEstatus = 3 
AND f.IdFactura =  $IdFactura
AND TIMESTAMPDIFF(MINUTE, f.FechaFactura, a.FechaPago) <= 3"; 

$resultado = $conn->query($consulta);
 // Variable para almacenar la suma de los subtotales

while ($fila = $resultado->fetch_assoc()) {
    $subtotalTotal = 0;
	$coste = $fila['Coste'];
    $cantidadVenta = $fila['CantidadVenta'];
    
    $subtotal = $cantidadVenta * $coste; // Cálculo del subtotal para el producto actual
    $subtotalTotal += $subtotal; // Suma del subtotal al total
    
    // Resto de tu código para mostrar o procesar cada producto y su subtotal
}

// $subtotalTotal ahora contendrá la suma de todos los subtotales


# Impuestos & totales #
$pdf->Cell(100, 7, utf8_decode(''), 'T', 0, 'C');
$pdf->Cell(15, 7, utf8_decode(''), 'T', 0, 'C');
$pdf->Cell(32, 7, utf8_decode("SUBTOTAL"), 'T', 0, 'C');
$pdf->Cell(34, 7, utf8_decode($subtotalTotal . " MXN"), 'T', 0, 'C');

$pdf->Ln(7);
$consulta = "SELECT p.Nombre as NombreP, p.IdProducto, p.Imagen, p.Precio, c.CantidadVenta, s.Nombre as Nombresucursal, v.IdCliente, i.Existencias, c.IdInventario, c.IdVenta, a.Total, p.Coste, f.IdFactura
FROM producto p
INNER JOIN inventario i ON p.IdProducto = i.IdProducto
INNER JOIN ventainventario c ON i.IdInventario = c.IdInventario
INNER JOIN sucursal s ON i.IdSucursal = s.IdSucursal
INNER JOIN venta v ON c.IdVenta = v.IdVenta
INNER JOIN pago a ON a.IdVenta = v.IdVenta
INNER JOIN factura f ON f.IdVenta = v.IdVenta
WHERE v.IdCliente = $IdCliente AND v.IdEstatus = 3 
AND f.IdFactura =  $IdFactura
AND TIMESTAMPDIFF(MINUTE, f.FechaFactura, a.FechaPago) <= 3"; // Asegurarse que los tiempos sean iguales o no más de 3 minutos de diferencia

$resultado = $conn->query($consulta);
$total = '';
$IVATOTAL = 0;
while ($fila = $resultado->fetch_assoc()) {
    $coste = $fila['Coste'];
    $cantidadVenta = $fila['CantidadVenta'];
	$iva= 0.16;
	$ivaproducto = $coste * $iva;
    $IVA = $cantidadVenta * $ivaproducto;
	$IVATOTAL += $IVA;  
  // Suma del subtotal al total
    
    // Resto de tu código para mostrar o procesar cada producto y su subtotal
}
$iva = 0;
$pdf->Cell(100, 7, utf8_decode(''), '', 0, 'C');
$pdf->Cell(15, 7, utf8_decode(''), '', 0, 'C');
$pdf->Cell(32, 7, utf8_decode("IVA (16%)"), '', 0, 'C');
$pdf->Cell(34, 7, utf8_decode($IVATOTAL . " MXN"), '', 0, 'C');

$pdf->Ln(7);

$pdf->Cell(100, 7, utf8_decode(''), '', 0, 'C');
$pdf->Cell(15, 7, utf8_decode(''), '', 0, 'C');


$pdf->Cell(32, 7, utf8_decode("TOTAL A PAGAR"), 'T', 0, 'C');
$pdf->Cell(34, 7, utf8_decode($total = $IVATOTAL + $subtotalTotal . " MXN"), 'T', 0, 'C');

$pdf->Ln(7);

$pdf->Cell(100, 7, utf8_decode(''), '', 0, 'C');
$pdf->Cell(15, 7, utf8_decode(''), '', 0, 'C');
$pdf->Cell(32, 7, utf8_decode("TOTAL PAGADO"), '', 0, 'C');
$pdf->Cell(34, 7, utf8_decode($$total = $IVATOTAL + $subtotalTotal  . " MXN"), '', 0, 'C');

$pdf->Ln(7);

/*$pdf->Cell(100,7,utf8_decode(''),'',0,'C');
	$pdf->Cell(15,7,utf8_decode(''),'',0,'C');
	$pdf->Cell(32,7,utf8_decode("CAMBIO"),'',0,'C');
	$pdf->Cell(34,7,utf8_decode("$30.00 USD"),'',0,'C');*/

$pdf->Ln(7);

/*$pdf->Cell(100,7,utf8_decode(''),'',0,'C');
	$pdf->Cell(15,7,utf8_decode(''),'',0,'C');
	$pdf->Cell(32,7,utf8_decode("USTED AHORRA"),'',0,'C');
	$pdf->Cell(34,7,utf8_decode("$0.00 USD"),'',0,'C');*/

$pdf->Ln(12);

$pdf->SetFont('Arial', '', 9);

$pdf->SetTextColor(39, 39, 51);
$pdf->MultiCell(0, 9, utf8_decode("*** Precios de productos incluyen impuestos. Para poder realizar un reclamo o devolución debe de presentar esta factura ***"), 0, 'C', false);

$pdf->Ln(9);

# Codigo de barras #
$pdf->SetFillColor(39, 39, 51);
$pdf->SetDrawColor(23, 83, 201);
$pdf->Code128(72, $pdf->GetY(), "COD000001V0001", 70, 20);
$pdf->SetXY(12, $pdf->GetY() + 21);
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 5, utf8_decode("COD000001V0001"), 0, 'C', false);

# Nombre del archivo PDF #
$pdf->Output("I", "Factura_Nro_1.pdf", true);
?>

</body>

</html>