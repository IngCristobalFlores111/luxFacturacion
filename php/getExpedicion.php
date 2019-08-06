<?php
include("Functions.php");
session_start();
if(!isset($_SESSION['idUsuario']))
{
    echo "0";
    exit();
}
$idUsuario = $_SESSION['idUsuario'];
$objSQL = F_sqlConn();
$idUsuario = $objSQL->filter_input($idUsuario);
$query = "SELECT CONCAT(lg.`calle`,' ',lg.`noExt`,' ',lg.`noInt`,' ',lg.`colonia`,' ',lg.`codigoPostal`,' ',lg.`municipio`,',',lg.`estado`) AS domicilio, ue.`idexpedicion` FROM usuarios_expedicion ue INNER JOIN lugar_expedicion lg ON lg.`idexpedicion`=ue.`idexpedicion` WHERE ue.`idusuario`='$idUsuario'";
//echo $query;
echo $objSQL->executeQueryJSON($query);

?>