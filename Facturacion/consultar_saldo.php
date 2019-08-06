<?php

date_default_timezone_set('America/Mexico_City');

require_once 'cfdi3.3/sdk2.php';
include_once "../php/new/functions.php";
try{
$sql = createMysqliConnection();

$RFC =$sql->executeQuery("SELECT RFC FROM `usuario_facturacion`");
$RFC = $RFC[0]['RFC'];
// cuando se pase a produccion cambiar estos parametros por los del cliente actual
$usuario=$RFC;
$clave='MULTI6d9180f6da47e579158f09226afeea12!';
ini_set('display_errors', 'Off');
error_reporting(0); // OPCIONAL DESACTIVA NOTIFICACIONES DE DEBUG
$res= saldo_mf($usuario,$clave);
echo "Mi Saldo actual:".$res['saldo']." Folios, estado:".$res['codigo_mf_texto'];
}catch(Exception $ex){
    echo"Error al obtener saldo,consultalo mas tarde";
}





?>