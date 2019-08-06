<?php
include("Functions.php");
session_start();

$gs_lugar =$_POST['lugar'];
$gj_lugar = json_decode($gs_lugar);
$idUsuario = $_SESSION['idUsuario'];


$objSQL = F_sqlConn();
$gj_lugar =$objSQL->filter_json($gj_lugar);
$insertQuery = '';
$campos = '';
foreach($gj_lugar as $key=>$value)
{
	$insertQuery=$insertQuery."'".$value."',";
	$campos =$campos."$key,";
}

$insertQuery = rtrim($insertQuery, ",");
$campos = rtrim($campos, ",");

$query ="INSERT INTO `lugar_expedicion`($campos) VALUES ($insertQuery)";
 //echo $query;

$objSQL->executeCommand($query);

$query="SELECT LAST_INSERT_ID(`idexpedicion`) AS id_last FROM lugar_expedicion ORDER BY id_last DESC LIMIT 1";
$result = $objSQL->executeQuery($query);
$last_inserted = $result[0]['id_last'];

$query ="INSERT INTO `usuarios_expedicion`(`idusuario`, `idexpedicion`) VALUES ('$idUsuario','$last_inserted')";
$objSQL->executeCommand($query);


?>