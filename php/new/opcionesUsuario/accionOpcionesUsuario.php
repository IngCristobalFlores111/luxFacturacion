<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);



if(isset($_GET['accion'])){
    include "../functions.php";
    $sql= createMysqliConnection();
    switch($_GET['accion']){
    case "modificarTipoFactura":	
        session_start();
        $idUsr=  $_SESSION['idUsuario'];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $query="UPDATE `user_config` SET tipo_factura=?,
        regimen = ? WHERE idusuario = ?";
        $sql->execQueryBinders($query,array("iis",$request->tipo_factura,$request->regimen,$idUsr));
        $errores= $sql->getErrorLog();
        $respuesta = array();
        if(count($errores)!=0){
            $respuesta['exito'] = false;
            $respuesta['errores'] = $errores;
        }else{
            $respuesta['exito']= true;
        }
        print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


   
    break;

    }
}
else if(isset($_POST['accion'])){
    include "../functions.php";
    $mySql= createMysqliConnection();
    switch($_POST['accion']){
        case 'modificarPassCSD':{
            $respuesta['exito'] = true;
            $respuesta['errores'] = $_POST['PASS'];
            $respuesta['datos'] = null;

            $query = 'UPDATE usuario_facturacion SET csdPass=?;';
            $mySql->execQueryBinders($query,array("s",$_POST['PASS']));
            $errores = $mySql->getErrorLog();
            if(count($errores) > 0){
                $respuesta['exito']= false;
                $respuesta['errores'] = $errores;
                $respuesta['datos'] = null;
            }
            else{
                $respuesta['exito']= true;
                $respuesta['errores'] = null;
                $respuesta['datos'] = null;
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
        }
        break;

        
    }
}

?>