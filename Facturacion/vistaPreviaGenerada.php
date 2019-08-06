<?php
session_start();

include ("../pdfWriter/FacturaPDFGen.php");
include ("../php/Functions.php");
error_reporting(0);
//$gs_facturaParams = $_POST['factura_params'];
$gs_idCliente =$_POST['idCliente'];
//$tmp_folio = rand(10,100);
$idUsuario=$_SESSION['idUsuario'];
$folio =$_POST['folio'];

generatePendientePDF($idUsuario,$folio,$gs_idCliente,"logo.png","facturas/timbradas/xml/preview.png","facturas/pendientes/pdf/preview$folio.pdf","correo","tel","mail","tel");

//echo $tmp_folio;
?>