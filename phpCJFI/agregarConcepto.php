<?php
include("functions.php");
$gs_concepto = $_POST['concepto'];
$gs_id_cliente = $_POST['idCliente'];
$gs_folio =$_POST['folio'];
$gs_total_importe = $_POST['total_importe'];
$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");
//$objSQL->executeQuery("");


$gj_concepto = json_decode($gs_concepto);

$gs_descripcion =$objSQL->filter_input($gj_concepto->descripcion);
$gs_unidad = $objSQL->filter_input($gj_concepto->unidad);
$gs_cantidad = $objSQL->filter_input($gj_concepto->cantidad);
$gs_precio = $objSQL->filter_input($gj_concepto->precio_unitario);
$gs_importe =$objSQL-> filter_input($gj_concepto->importe);



$query = "INSERT INTO `dblux_facturacion`.`folio_conceptos` (`folio_factura`, `cantidad`, `descripcion`, `unidad`, `precio_unitario`, `importe`) VALUES ('$gs_folio', '$gs_cantidad', '$gs_descripcion', '$gs_unidad', '$gs_precio', '$gs_importe');";
$objSQL->executeCommand($query);
$query2 = "UPDATE factura_pendiente SET total ='$gs_total_importe' WHERE folio_factura=$gs_folio AND idcliente=$gs_id_cliente";
$objSQL->executeCommand($query2);
echo $query2;






?>