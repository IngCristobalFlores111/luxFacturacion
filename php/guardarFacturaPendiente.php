<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../pdfWriter/FacturaPDFGen.php");
session_start();
include("asignNewFolio.php");

$factura_params = $_POST['factura_params'];
$lj_facturaParams = json_decode($factura_params);
$conceptos = $_SESSION['conceptos'];
$query_concepto = '';
$lf_subtotal = 0;

foreach($conceptos as $c)
{
    $cantidad = $c['cantidad'];
    $descripcion = $c['descripcion'];
    $unidad = $c['unidad'];
    $precio_unitario = $c['precio_unitario'];
    $importe = $c['importe'];
    $noSerie = $c['noSerie'];
    $lf_subtotal +=(float)$importe;
    $query_concepto= $query_concepto."INSERT INTO `folio_conceptos`(noSerie,`folio_factura`, `cantidad`, `descripcion`, `unidad`, `precio_unitario`, `importe`) VALUES ('$noSerie','$folio','$cantidad','$descripcion','$unidad','$precio_unitario','$importe');";
}

$objSQL = F_sqlConn();
$objSQL->executeCommand($query_concepto);

$porc = $lj_facturaParams->descuento;
$lf_descuento = (float)$lj_facturaParams->descuento;
$lf_descuento = $lf_subtotal*($lf_descuento/100);
$lf_iva = ($lf_subtotal-$lf_descuento)*0.16;
$lf_total = ($lf_subtotal-$lf_descuento) + $lf_iva;
$metodoPago = $lj_facturaParams->metodo_pago;
$formaPago = $lj_facturaParams->forma_pago;

$fecha = date("Y-m-d");
$query ="INSERT INTO `factura_pendiente`(tipo,`folio_factura`, `idcliente`, `fecha`, `total`, `forma_pago`, `metodo_pago`, `descuento`) VALUES ('$tipo_factura','$folio','$idCliente','$fecha','$lf_total','$formaPago','$metodoPago','$porc');";
$objSQL->executeCommand($query);

//GENERAR PDF
generateFacturaPreview($factura_params,$idCliente,"../Facturacion/logo/logo.png","../Facturacion/facturas/pendientes/2DProvisional/provisional.png","../Facturacion/facturas/pendientes/pdf/".$idCliente."_".$folio.".pdf","correo","tel","mail","tel");
echo $folio;

unset($_SESSION['conceptos']);
?>