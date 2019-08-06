<?php



date_default_timezone_set('America/Mexico_City');

include_once "lib/cfdi32_multifacturas_PHP7.php";
require_once("../php/Functions.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);
$datos = array();
$datos['cancelar']='SI';
$datos['cfdi']='facturas/timbradas/xml/'.$_GET['xml'];

$datos['PAC']['usuario'] = 'IIDE660331UU7';
$datos['PAC']['pass'] = 'MULTI6d9180f6da47e579158f09226afeea12!';

$objSQL = F_sqlConn();

$result =$objSQL->executeQuery("SELECT `archivo_cer`,`archivo_key` FROM `usuario_facturacion`");
$fileKey = $result[0]['archivo_key'];
$fileCer =$result[0]['archivo_cer'];

$datos['PAC']['produccion'] = 'SI'; //   [SI|NO]
$datos['conf']['cer'] = 'pruebas/'.$fileCer;
$datos['conf']['key'] = 'pruebas/'.$fileKey;
$datos['conf']['pass'] = 'VENCEDOR';

$res= cfdi_cancelar($datos);
print_r($res);
?>