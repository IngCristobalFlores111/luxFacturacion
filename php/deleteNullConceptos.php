<?php
include("Functions.php");
$gs_folio =$_POST['folio'];
$gs_idCliente =$_POST['idCliente'];
$query ="DELETE FROM `folio_conceptos` WHERE folio_factura='$gs_folio'";
$objSQL = F_sqlConn();
$objSQL->executeCommand($query);
$query ="DELETE FROM `factura_pendiente` WHERE folio_factura='$gs_folio' AND idcliente='$gs_idCliente'";
$objSQL->executeCommand($query);


?>