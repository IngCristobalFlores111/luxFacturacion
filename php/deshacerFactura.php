<?php
include("Functions.php");
$folio =$_POST['folio'];
$objSQL = F_sqlConn();
$folio = $objSQL->filter_input($folio);
$query ="DELETE FROM factura_pendiente WHERE `folio_factura`=$folio;";
$query =$query."DELETE FROM `folio_conceptos` WHERE `folio_factura`=$folio;";
$objSQL->executeCommand($query);


?>