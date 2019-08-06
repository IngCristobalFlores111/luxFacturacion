<?php

//include("functions.php");
$idCliente =$_POST['idCliente'];
$objSQL = F_sqlConn();

$query ="SELECT `folio_factura` FROM `factura_pendiente` ORDER BY `folio_factura` DESC LIMIT 1";
$result = $objSQL->executeQuery($query);
$folio_pendiente = 0;
if(is_null($result)){ $folio_pendiente =1;}
else{
	$folio_pendiente = $result[0]['folio_factura'];
}
$folio_pendiente =(int)$folio_pendiente;

$query= "SELECT `folio_factura` FROM `factura_generada` ORDER BY `folio_factura` DESC LIMIT 1";

$result = $objSQL->executeQuery($query);
$folio_generado = 0;
if(is_null($result)){$folio_generado =1;} else{$folio_generado = $result[0]['folio_factura'];}

$folio_generado = (int)$folio_generado;
$folio = 0;
if($folio_generado>$folio_pendiente){ $folio = $folio_generado; }else{ $folio = $folio_pendiente;}

$folio++;
$date = date("Y-m-d");

$query ="SELECT `tipo_factura` FROM `user_config`";
$result = $objSQL->executeQuery($query);
$tipo_factura = $result[0]['tipo_factura'];

$query ="SELECT `folio_impreso`+1 AS folio_factura_impreso FROM `factura_generada` WHERE `tipo`='$tipo_factura' ORDER BY `folio_impreso` DESC LIMIT 1";
$result = $objSQL->executeQuery($query);
$folio_impreso = null;
if($result === null)
    $folio_impreso = 1;
else
    $folio_impreso = $result[0]['folio_factura_impreso'];

?>