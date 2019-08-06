<?php

include("Functions.php");
session_start();
$idUsuario = $_SESSION['idUsuario'];

$objSQL = F_sqlConn();
$query ="SELECT tipo_factura,`retencion_IVA`,`retencion_ISR`,`retener_ISR` FROM `user_config` WHERE idusuario='$idUsuario';";
//echo $query;
echo $objSQL->executeQueryJSON($query);



?>