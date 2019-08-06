<?php
include("Functions.php");
$gs_concepto = $_POST['concepto'];
$gs_id_cliente = $_POST['idCliente'];
$gs_folio =$_POST['folio'];
$gs_total_importe = $_POST['total_importe'];
$objSQL = F_sqlConn();

$gj_concepto = json_decode($gs_concepto);
$gj_concepto = $objSQL->filter_json($gj_concepto);
$gs_cantidad = $gj_concepto->cantidad;
$gs_descripcion = $gj_concepto->descripcion;
$gs_unidad = $gj_concepto->unidad;
$gs_precio = $gj_concepto->precio_unitario;
$gs_importe =$gj_concepto->importe;
$query = "INSERT INTO `folio_conceptos` (`folio_factura`, `cantidad`, `descripcion`, `unidad`, `precio_unitario`, `importe`) VALUES ('$gs_folio', '$gs_cantidad', '$gs_descripcion', '$gs_unidad', '$gs_precio', '$gs_importe');";
$objSQL->executeCommand($query);
$query = "SELECT SUM(importe) AS subtotal FROM `folio_conceptos` WHERE folio_factura='$gs_folio'";
$result = $objSQL->executeQuery($query);
$subtotal =(float)$result[0]['subtotal'];

$query ="SELECT `descuento` FROM `factura_pendiente` WHERE `folio_factura`=$gs_folio";
$result = $objSQL->executeQuery($query);
$porcentaje_descuento  = (float)$result[0]['descuento'];

$descuento = $porcentaje_descuento*$subtotal;
$iva = ($subtotal-$descuento)*0.16;
$total = ($subtotal-$descuento) + $iva;


$query ="UPDATE `factura_pendiente` SET `total`='$total' WHERE `folio_factura`='$gs_folio'";
echo $query;
$objSQL->executeCommand($query);






?>