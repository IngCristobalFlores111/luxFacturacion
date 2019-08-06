<?php
include("Functions.php");
session_start();

if(!isset($_SESSION['idUsuario']) && !isset($_SESSION['privilegios'])){
    echo "no";
    exit();
}
$idUsuario = $_SESSION['idUsuario'];
$privilegios =$_SESSION['privilegios'];
$grant = 0;
if($privilegios==1|| $privilegios==2){
    $grant=1;
}


$objSQL = F_sqlConn();
$query1 ="SELECT `retencion_IVA`,`retencion_ISR`,`retener_ISR`,`tipo_factura` FROM `user_config` WHERE `idusuario`='$idUsuario'";
$query2 ="SELECT `archivo_cer`,`archivo_key` FROM `usuario_facturacion`";

echo $objSQL->multi_query(array($query1,$query2));
    echo "%&/";
    echo $grant;


?>