<?php

include("Functions.php");
$objSQL = F_sqlConn();
$id_atajo =$_POST['idAtajo'];
$id_atajo = $objSQL->filter_input($id_atajo);
$id_atajo = trim($id_atajo);

$query ="DELETE FROM `atajos` WHERE `idatajo`='$id_atajo'";

$objSQL->executeCommand($query);
?>