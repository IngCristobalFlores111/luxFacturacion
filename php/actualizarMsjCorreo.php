<?php
include("Functions.php");
$gs_timbr =$_POST['timbr'];
$gs_canc = $_POST['canc'];
$gs_pend =$_POST['pend'];
$objSQL = F_sqlConn();
$gs_timbr = $objSQL->filter_input($gs_timbr);
$gs_canc = $objSQL->filter_input($gs_canc);
$gs_pend = $objSQL->filter_input($gs_pend);
session_start();
$idUsuario =$_SESSION['idUsuario'];
$idUsuario  = $objSQL->filter_input($idUsuario);

$query ="UPDATE `user_config` SET  mensaje_timbrada='$gs_timbr',mensaje_cancelada='$gs_canc',mensaje_generada='$gs_pend' WHERE idusuario='$idUsuario'";

$objSQL->executeCommand($query);
echo $query;
?>