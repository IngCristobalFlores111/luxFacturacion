<?php
$desc = $_POST['desc'];
$cantidad = $_POST['cantidad'];
$folio =$_POST['folio'];
include("Functions.php");
$objSQL = F_sqlConn();
$desc = $objSQL->filter_input($desc);
$cantidad =$objSQL->filter_input($cantidad);
$folio =$objSQL->filter_input($folio);
$query ="DELETE FROM `folio_conceptos` WHERE `descripcion`='$desc' AND `cantidad`='$cantidad' AND folio_factura='$folio';";
$objSQL->executeCommand($query);
?>