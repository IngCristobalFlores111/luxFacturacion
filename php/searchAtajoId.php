<?php
include("Functions.php");
$gn_idAtajo = $_POST['idAtajo'];
$query = "SELECT * FROM `atajos` WHERE `idatajo`=$gn_idAtajo";

$objSQL = F_sqlConn();

echo $objSQL->executeQueryJSON($query)





?>