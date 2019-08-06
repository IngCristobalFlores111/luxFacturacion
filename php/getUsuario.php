<?php
include("Functions.php");


session_start();
//$_SESSION['idUsuario'];
if(!isset($_SESSION['idUsuario']) && !isset($_SESSION['privilegios'])){
    echo "no";
    exit();
}
$priv = $_SESSION['privilegios'];

$objSQL = F_sqlConn();

if($priv=='0'|| $priv=='1'){
    echo "denied";
}
if($priv=='2'){
    echo $objSQL->executeQueryJSON("SELECT `nombre`,`RFC`,`telefono`,`celular`,`email`,`calle` ,`colonia`,`CodigoPostal`,`noExterior`,`noInterior`,`localidad`,`municipio`,`estado` FROM `usuario_facturacion`");

}




?>