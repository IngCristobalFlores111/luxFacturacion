<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_GET['accion'])){
    include ("../functions.php");
    $sql = createMysqliConnection();
 switch($_GET['accion']){
   case"agregarAtajo":
   $respuesta = array();
   
   $postdata = file_get_contents("php://input");
   $request = json_decode($postdata);
   try{
   $query="INSERT INTO `atajos`(`descripcion`, `medida`, 
   `precio`, `atajo`, `noSerie`,
    `claveProdServ`, `nombreProdServ`) VALUES (?,?,?,?,?,?,?);";
    $sql->execQueryBinders($query,array("ssdssss",
    $request->descripcion,
    $request->medida,
    $request->precio,
    $request->atajo,
    $request->noSerie,
    $request->claveProdServ,
    $request->nombreProdServ
    ));
    $errores = $sql->getErrorLog();
    $respuesta = array();
    if(count($errores)>0){
        $respuesta['exito'] = false;
        $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
    }else{
        $respuesta['exito'] = true;
    }
}catch(Exception $e){
    $respuesta['exito'] = false;
    $respuesta['errores'] =$e->getMessage();
    
}
    print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


   break;
   case "modificarAtajo":
    $respuesta = array();
    
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    try{
    $query="UPDATE `atajos` SET `descripcion`= ? ,`medida`=?,
    `precio`=?,`atajo`= ? ,`noSerie`= ? 
    ,`claveProdServ`=?,`nombreProdServ`=? 
    WHERE idatajo = ?";
    $sql->execQueryBinders($query,array("ssdssssi",
    $request->descripcion,
    $request->medida,
    $request->precio,
    $request->atajo,
    $request->noSerie,
    $request->claveProdServ,
    $request->nombreProdServ,
    $request->idatajo
    ));
    $errores = $sql->getErrorLog();
    $respuesta = array();
    if(count($errores)>0){
        $respuesta['exito'] = false;
        $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
    }else{
        $respuesta['exito'] = true;
    }
}catch(Exception $e){
    $respuesta['exito'] = false;
    $respuesta['errores'] =$e->getMessage();
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

    break;
case "actualizarTipoFactura":
$respuesta = array();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
try{
    session_start();
    $idUsr =  $_SESSION['idUsuario'];
$query="UPDATE `user_config` SET tipo_factura = ? WHERE idusuario = ?";
$sql->execQueryBinders($query,array("is",$request->tipoFactura,$idUsr));
$errores = $sql->getErrorLog();
if(count($errores)>0){
    $respuesta['exito'] = false;
    $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
}else{
    $respuesta['exito'] = true;
}
}
catch(Exception $ex){
    $respuesta['exito'] = false;
    $respuesta['errores'] =$e->getMessage();
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


break;


case "actualizarISR":
$respuesta = array();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
try{
session_start();
$idUsr =  $_SESSION['idUsuario'];
$query="UPDATE `user_config` SET retencion_ISR = ? WHERE idusuario = ?";
$sql->execQueryBinders($query,array("ss",$request->ISR,$idUsr));
$errores = $sql->getErrorLog();
if(count($errores)>0){
    $respuesta['exito'] = false;
    $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
}else{
    $respuesta['exito'] = true;
}

}catch(Exception $ex){
    $respuesta['exito'] = false;
    $respuesta['errores'] =$e->getMessage();
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


break;


    case "eliminarAtajo":
    $respuesta = array();
    
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $query="DELETE FROM `atajos` WHERE idatajo = ?";
   
   try{
    $sql->execQueryBinders($query,array("i",$request->idAtajo));
    $errores = $sql->getErrorLog();
    $respuesta = array();
    if(count($errores)>0){
        $respuesta['exito'] = false;
        $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
    }else{
        $respuesta['exito'] = true;
    }
   }catch(Exception $e){
    $respuesta['exito'] = false;
    $respuesta['errores'] =$e->getMessage();
   }
   print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
   
    break;


 }
}
    ?>