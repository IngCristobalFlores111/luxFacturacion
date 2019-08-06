<?php
include("../../dompdf/generate.php");
$xml_file = "../Facturacion/facturas/timbradas/xml/52_2017_08_31_406.xml";
$idCliente ="52";
$resp = generatePdf($xml_file,$idCliente);
?>