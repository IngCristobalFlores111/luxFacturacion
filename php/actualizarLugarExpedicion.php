<?php
include("Functions.php");
session_start();
$gs_datos =$_POST['jDatos'];
$gs_idLugar=$_POST['idlugar'];
$objSQL = F_sqlConn();
$idUsuario = $_SESSION['idUsuario'];
$idUsuario = $objSQL->filter_input($idUsuario);
$gs_idLugar = $objSQL->filter_input($gs_idLugar);
$gj_lugarExp = json_decode($gs_datos);
$gj_lugarExp = $objSQL->filter_json($gj_lugarExp);
$out = '';
foreach($gj_lugarExp as $key=>$value)
{


	$out=$out.$key."='".$value."',";
}

$out =rtrim($out, ",");
$query ="UPDATE `lugar_expedicion` SET ".$out." WHERE idexpedicion='$gs_idLugar'";
$objSQL->executeCommand($query);





?>