<?php
include ("../pdfWriter/FacturaPDFGen.php");
//include ("../php/functions.php");
//error_reporting(0);
$gs_idCliente =$_POST['idCliente'];

$folio = $_POST['folio'];

generateFacturaGeneradaPDF($folio,$gs_idCliente,"logo.png","facturas/timbradas/xml/preview.png","facturas/pendientes/pdf/preview$folio.pdf","correo","tel","mail","tel");

?>