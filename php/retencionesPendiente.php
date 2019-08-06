<?php
include("Functions.php");
session_start();
$folio =$_POST['folio'];
$idUsuario = $_SESSION['idUsuario'];

$objSQL = F_sqlConn();
$retencion_data = $objSQL->executeQuery("SELECT `retencion_IVA`,`retencion_ISR`,`retener_ISR`,`conceptos_iva` FROM `user_config` WHERE idusuario='$idUsuario'");
$tipo = $objSQL->executeQuery("SELECT `tipo` FROM `factura_pendiente` WHERE `folio_factura`='$folio'");
$ret_iva =$retencion_data[0]['retencion_IVA'];
$ret_isr =$retencion_data[0]['retener_ISR'];
$tasa_isr = $retencion_data[0]['retencion_ISR'];
$conceptos_iva = $retencion_data[0]['conceptos_iva'];
$tipo = $tipo[0]['tipo'];
$json_result = '{"retencion_IVA":"'.$ret_iva.'","retencion_ISR":"'.$tasa_isr.'","retener_ISR":"'.$ret_isr.'","conceptos_iva":"'.$conceptos_iva.'","tipo":"'.$tipo.'" }';
echo $json_result;
?>