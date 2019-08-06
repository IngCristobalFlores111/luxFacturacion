<?php

include("Functions.php");
session_start();
$objSQL = F_sqlConn();
$idUsuario = $_SESSION['idUsuario'];
$idUsuario = $objSQL->filter_input($idUsuario);
$result = $objSQL->executeQuery("SELECT `mensaje_generada` FROM `user_config` WHERE `idusuario`='$idUsuario'");
echo $result[0]['mensaje_generada'];

?>