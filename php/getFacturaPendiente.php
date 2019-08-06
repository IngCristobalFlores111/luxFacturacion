<?php
include("Functions.php");
$folio = $_POST['folio'];
$idCliente = $_POST['idCliente'];
$objSQL = F_sqlConn();
$query1 = "SELECT * FROM `folio_conceptos` WHERE folio_factura='$folio';";
$query2 = "SELECT * FROM `factura_pendiente` WHERE folio_factura='$folio' AND idcliente='$idCliente';";
echo $objSQL->multi_query(array($query1,$query2));

?>