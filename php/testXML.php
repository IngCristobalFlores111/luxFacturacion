<?php
include("../pdfWriter/FacturaPDFGen.php");
include ("Functions.php");
$objSQL = F_sqlConn();

$folio =$_GET['folio'];
$folio = $objSQL->filter_input($folio);
$query ="SELECT `xml_file` FROM `factura_generada` WHERE `folio_factura`='$folio'";
$result =$objSQL->executeQuery($query);
$xml = "../Facturacion/facturas/timbradas/xml/".$result[0]['xml_file'];
$factura = OpenCompleteXMLFile($xml,false);
print_r($factura);
echo "<br>";

$subtotal =(float) $factura['Subtotal'];


$total =(float) $factura['Total'];


$retencion_isr =(float)$factura['RetencionesISR'];
$retencion_iva = (float)$factura['RetencionesIVA'];

echo "subtotal: $subtotal <br>";
echo "total:$total <br> ";
echo "retencion_isr : $retencion_isr <br>";
echo "retencion_iva: $retencion_iva";
?>