<?php
include("Functions.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$idUsuario = $_SESSION['idUsuario'];

$objSQL = F_sqlConn();
$idUsuario = $objSQL->filter_input($idUsuario);
$query = "SELECT lg.idexpedicion, CONCAT(lg.`calle`,' ',lg.`noExt`,' ',lg.`municipio`,',',lg.`estado`) AS domicilio,lg.idexpedicion FROM lugar_expedicion as lg";
echo $objSQL->executeQueryJSON($query);
?>