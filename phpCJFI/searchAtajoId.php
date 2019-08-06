<?php
include("functions.php");
$gn_idAtajo = $_POST['idAtajo'];
$query = "SELECT * FROM `atajos` WHERE `idatajo`=$gn_idAtajo";

$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");

echo $objSQL->executeQueryJSON($query)





?>