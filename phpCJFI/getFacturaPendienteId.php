<?php
include("functions.php");

$idCliente =$_POST['idCliente'];
$query = "SELECT `folio_factura` FROM factura_generada ORDER BY `folio_factura` DESC LIMIT 1";

$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");

$result =  $objSQL->executeQuery($query);
$folio1 =(int)$result[0]['folio_factura'];
/////////
$query = "SELECT `folio_factura` FROM factura_pendiente ORDER BY `folio_factura` DESC LIMIT 1";

$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");

$result =  $objSQL->executeQuery($query);
$folio2 =(int)$result[0]['folio_factura'];
if($folio1>$folio2){$folio1++;
	echo $folio1;
	}
	else{
    $folio2++;
	echo $folio2;
}

$query = "INSERT INTO `dblux_facturacion`.`factura_pendiente` (`folio_factura`, `idcliente`, `fecha`, `total`) VALUES ('$folio', '$idCliente', '".date("Y-m-d")."', '0');";
$objSQL->executeCommand($query);

?>