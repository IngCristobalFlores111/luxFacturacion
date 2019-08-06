<?php
error_reporting(0); // OPCIONAL DESACTIVA NOTIFICACIONES DE DEBUG
date_default_timezone_set('America/Mexico_City');
include_once "lib/cfdi32_multifacturas.php";

$datos['RESPUESTA_UTF8'] = "SI";

$datos['PAC']['usuario'] = "DEMO700101XXX";
$datos['PAC']['pass'] = "DEMO700101XXX";
$datos['PAC']['produccion'] = "NO";

$datos['SDK']['ruta'] = "C:\\multifacturas_sdk";

$datos['modulo'] = 'cancelacion';

$datos['cer'] = 'pruebas/sohm7509289ma.cer';
$datos['key'] = 'pruebas/sohm7509289ma.key';
$datos['pass'] = 'sohm2895';
$datos['rfc'] = 'SOHM7509289MA';
$datos['xml'] = 'VENTA-A-PUBLICO-EN-GENERAL-.xml';

$res = cargar_modulo_multifacturas($datos);
var_dump($res);
