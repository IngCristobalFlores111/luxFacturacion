<?php
include("Functions.php");

$idCliente =$_POST['idCliente'];
$query = "SELECT `folio_factura` FROM factura_generada ORDER BY `folio_factura` DESC LIMIT 1";

$objSQL = F_sqlConn();

$result =  $objSQL->executeQuery($query);
$folio1 =(int)$result[0]['folio_factura'];
/////////
$query = "SELECT `folio_factura` FROM factura_pendiente ORDER BY `folio_factura` DESC LIMIT 1";



$result =  $objSQL->executeQuery($query);
$folio2 =(int)$result[0]['folio_factura'];
if($folio1>$folio2){$folio1++;
	echo $folio1;

	$query = "INSERT INTO `dblux_facturacion`.`factura_pendiente` (`folio_factura`, `idcliente`, `fecha`, `total`) VALUES ('$folio1', '$idCliente', '".date("Y-m-d")."', '0');";
	$objSQL->executeCommand($query);
	}
	else{
    $folio2++;
	echo $folio2;
	$query = "INSERT INTO `dblux_facturacion`.`factura_pendiente` (`folio_factura`, `idcliente`, `fecha`, `total`) VALUES ('$folio2', '$idCliente', '".date("Y-m-d")."', '0');";
	$objSQL->executeCommand($query);
}



?>