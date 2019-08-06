<?php
include("Functions.php");
$objSQL = F_sqlConn();

session_start();
$idUsuario = $_SESSION['idUsuario'];
$idUsuario = $objSQL->filter_input($idUsuario);
$query ="SELECT `privilegio` FROM `usuarios` WHERE `idusuario`='$idUsuario'";
$result = $objSQL->executeQuery($query);
$priv = $result[0]['privilegio'];
$_SESSION['privilegios'] = $priv;
?>