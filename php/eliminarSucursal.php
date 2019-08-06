<?php
include("Functions.php");
session_start();
$objSQL = F_sqlConn();
$idLugar =MysqlLink::m_filterInput($_POST["idLugar"]);
$query ="DELETE FROM `usuarios_expedicion` WHERE `idexpedicion`='$idLugar';";
$query = $query . " DELETE FROM `lugar_expedicion` WHERE `idexpedicion` = '$idLugar';";
$objSQL->executeCommand($query);
?>