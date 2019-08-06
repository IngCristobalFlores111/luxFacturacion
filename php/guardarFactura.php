<?php
include("Functions.php");
$gs_idCliente = $_POST['idCliente'];
$gs_facturaParams = $_POST['factura_params'];
$gj_facturaParams =json_decode($gs_facturaParams);
$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");

$query = "SELECT folio_factura FROM factura_generada ORDER BY idcliente DESC";
$result = $objSQL->executeQuery($query);
$folio1 = 0;$folio2 = 0;
if(is_null($result))
	$folio1 = 1;
else
{$folio1 = (int)$result[0]['folio_factura'];
	$folio1++;
}
$query = "SELECT folio_factura FROM factura_pendiente ORDER BY idcliente DESC";
$result = $objSQL->executeQuery($query);
if(is_null($result))
$folio2 = 1;
else
{$folio2= (int)$result[0]['folio_factura'];
	$folio2++;
}
$folio = 0;
if($folio1>$folio2){$folio = $folio1;}
else{ $folio =$folio2; }
	
	$query ="SELECT SUM(importe) AS subtotal FROM folio_conceptos WHERE folio_factura='-1'";
$result = $objSQL->executeQuery($query);
if(is_null($result)|| $result[0]['subtotal']=='0' || trim($result[0]['subtotal'])==''){echo 0;exit();}
$subtotal = (float)$result[0]['subtotal'];	
$iva = $subtotal*0.16;
$total = ($subtotal) + ($iva);
$objSQL->executeCommand("UPDATE `folio_conceptos` SET `folio_factura`=$folio WHERE folio_factura='-1'");

$query ="INSERT INTO `factura_pendiente` (`folio_factura`, `idcliente`, `fecha`, `total`, `forma_pago`, `metodo_pago`, `descuento`) VALUES ('$folio', '$gs_idCliente', '".date("Y-m-d")."', '$total', '".$gj_facturaParams->forma_pago."', '".$gj_facturaParams->metodo_pago."', '".$gj_facturaParams->descuento."');";
$objSQL->executeCommand($query);
echo $folio;
?>