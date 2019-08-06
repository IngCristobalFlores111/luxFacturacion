<?php
include("Functions.php");
$precios_iva = $_POST['preciosIva'];
$objSQL = F_sqlConn();

$objSQL->executeCommand("UPDATE `user_config` SET `conceptos_iva`=$precios_iva");

?>