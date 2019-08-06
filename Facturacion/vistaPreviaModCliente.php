<?php
session_start();

include ("../pdfWriter/FacturaPDFGen.php");
include ("../php/Functions.php");
error_reporting(0);
$idUsuario = $_SESSION['idUsuario'];
$objSQL = F_sqlConn();

$idUsuario = $objSQL->filter_input($idUsuario);

//$gs_facturaParams = $_POST['factura_params'];
$gs_idCliente =$_POST['idCliente'];
$gsFolio =$_POST['folio'];

$gs_idCliente = $objSQL->filter_input($gs_idCliente);
$gsFolio = $objSQL->filter_input($gsFolio);

if(!file_exists("facturas/pendientes/pdf/preview$gsFolio")){


    $query ="SELECT telefono,email FROM clientes_facturacion WHERE idcliente='$gs_idCliente'";
    $result =$objSQL->executeQuery($query);
    $query="SELECT 	telefono,email FROM `usuario_facturacion`";
    $result2 = $objSQL->executeQuery($query);
    generatePendientePDF($idUsuario,$gsFolio,$gs_idCliente,"logo/logo.png","facturas/timbradas/xml/preview.png","facturas/pendientes/pdf/preview$gsFolio.pdf",$result2[0]['email'],$result2[0]['telefono'],$result[0]['email'],$result[0]['telefono']);
}


?>