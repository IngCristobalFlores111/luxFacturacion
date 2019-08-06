<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_GET['accion'])){
    include ("../functions.php");
    $respuesta = array();
    $sql = createMysqliConnection();
 switch($_GET['accion']){
  case "buscarClaveProdServ":
  $q = $_GET['q'];
  $q = $sql->filter_input($q);
$query="SELECT clavesProdActuales.idClaveProd AS id, 
c_ClaveProdServ AS codigo,clavesProdActuales.Descripción AS descripcion 
FROM clavesProdActuales WHERE MATCH(c_ClaveProdServ,Descripción) 
AGAINST('*".$q."*' IN BOOLEAN MODE)";
$productos = $sql->executeQuery($query);
print_r(json_encode($productos, JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE |JSON_HEX_QUOT|JSON_HEX_APOS));
break;
case "unidadesActuales":
$query="SELECT f4_c_claveunidad.id,f4_c_claveunidad.Nombre AS nombre,f4_c_claveunidad.c_ClaveUnidad AS clave FROM `unidades_actuales` INNER JOIN f4_c_claveunidad ON f4_c_claveunidad.id = unidades_actuales.idUnidad";
$unidades = $sql->executeQuery($query);
print_r(json_encode($unidades, JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE |JSON_HEX_QUOT|JSON_HEX_APOS));

break;
case "atajos":
$query="SELECT * FROM atajos";
$atajos = $sql->executeQuery($query);
print_r(json_encode($atajos, JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE |JSON_HEX_QUOT|JSON_HEX_APOS));

break;



case "tipoFacturaActual":

$respuesta = array();
try{

  session_start();
  $idUsr =  $_SESSION['idUsuario'];
  $query="SELECT tipo_factura FROM `user_config` WHERE idusuario = ?";  
  $tipoFactura = $sql->get_bind_results($query,array("s",$idUsr));
  $tipoFactura = $tipoFactura[0]['tipo_factura'];

  $query="SELECT retencion_ISR FROM `user_config` WHERE idusuario = ?";  
  $retencionISR = $sql->get_bind_results($query,array("s",$idUsr));
  $retencionISR = $retencionISR[0]['retencion_ISR'];

  $respuesta['exito'] = true;
  $respuesta['data'] = array("tipoFactura"=>$tipoFactura,"retencionISR"=>$retencionISR);
  
}catch(Exception $ex){
  $respuesta['false'] = true;
  $respuesta['data'] =null; 
  $respuesta['msg']="Ha ocurrido un error fatal";
}
print_r(json_encode($respuesta, JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE |JSON_HEX_QUOT|JSON_HEX_APOS));

break;
 }

}
