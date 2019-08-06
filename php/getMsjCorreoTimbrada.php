<?php
session_start();
include("Functions.php");
$idUsuario =$_SESSION['idUsuario'];

$objSQL = F_sqlConn();

$query ="SELECT `mensaje_timbrada` FROM `user_config` WHERE `idusuario`='$idUsuario'";

$result = $objSQL->executeQuery($query);
echo $result[0]['mensaje_timbrada'];



?>