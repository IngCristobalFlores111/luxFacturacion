<?php
$concepto = $_POST['concepto'];
$folio = $_POST['folio'];
$idCliente =$_POST['idCliente'];
$total = $_POST['total_importe'];
include("Functions.php");
$objSQL = F_sqlConn();
$lj_concepto = json_decode($concepto);
$lj_concepto = $objSQL->filter_json($lj_concepto);
$folio = $objSQL->filter_input($folio);
$idCliente = $objSQL->filter_input($idCliente);
$total =$objSQL->filter_input($total);

$cantidad = $lj_concepto->cantidad;
$descripcion = $lj_concepto->descripcion;
$unidad = $lj_concepto->unidad;
$precio_unitario = $lj_concepto->precio_unitario;
$importe = $lj_concepto->importe;
$query ="INSERT INTO `folio_conceptos`(`folio_factura`, `cantidad`, `descripcion`, `unidad`, `precio_unitario`, `importe`) VALUES ('$folio','$cantidad','$descripcion','$unidad','$precio_unitario','$importe')";
$objSQL->executeCommand($query);
$query ="UPDATE `factura_pendiente` SET `total`='$total' WHERE `folio_factura`='$folio' AND `idcliente`='$idCliente'";
$objSQL->executeCommand($query);

$result =  $objSQL->executeQuery("SELECT LAST_INSERT_ID(id_concepto) AS last_id FROM folio_conceptos ORDER BY id_concepto DESC LIMIT 1");
    echo $result[0]['last_id'];

?>