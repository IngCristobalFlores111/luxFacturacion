<?php
include("Functions.php");
session_start();
$idUsuario = $_SESSION['idUsuario'];
$objSQL = F_sqlConn();

//$privilegios = $_SESSION['privilegios'];

// revizar que tipo de factura esta dada de alta para este usuario:
$idUsuario = $objSQL->filter_input($idUsuario);

echo $objSQL->executeQueryJSON("SELECT tipo_factura,conceptos_iva,retencion_IVA,retencion_ISR,retener_ISR FROM user_config WHERE idusuario='$idUsuario'");
//echo "SELECT tipo_factura,conceptos_iva,retencion_IVA,retencion_ISR,retener_ISR FROM user_config WHERE idusuario='$idUsuario'";
?>