<?php
include ("../pdfWriter/FacturaPDFGen.php");

$gs_folio = $_POST['folio'];
$gs_idUsuario = $_POST['idUsuario'];
$gs_idCliente =$_POST['idCliente'];

if(!file_exists("facturas/pendientes/pdf/preview$gs_folio.pdf"))
generateFacturaPreview($gs_folio,$gs_idUsuario,$gs_idCliente,"logo.png","facturas/timbradas/xml/cfdi_2_2015_12_13.png","facturas/pendientes/pdf/preview$gs_folio.pdf","correo","tel","mail","tel");



?>