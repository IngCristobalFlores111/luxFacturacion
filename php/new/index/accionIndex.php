<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(isset($_GET['accion'])){
    include "../functions.php";
    $sql= createMysqliConnection();
    switch($_GET['accion']){
     case "importFacturacion":
     include("../helper/uploadFiles.php");
    $respuesta = array();
    $tipo = $_POST['tipo'];
     $archivo = $_FILES['file'];
     $nombreArchivo = $archivo['name'];
     if(strpos($nombreArchivo,"xlsx")===false){
     $respuesta['exito'] = false;
     $respuesta['msg']="tipo de archivo incorrecto";
     print_r(json_encode($respuesta));
     exit();
     }
     if($archivo['size']>2097152){
        $respuesta['exito'] = false;
        $respuesta['msg']="Archivo demasiado grande,tiene que ser menor de 2mb";
        print_r(json_encode($respuesta));
        exit(); 
    }
    $inputFileName = basename($archivo['tmp_name']);
    $inputFileName = substr_replace($inputFileName , 'xlsx', strrpos($inputFileName , '.') +1);
    $inputFileName = dirname($archivo['tmp_name']).$inputFileName;
    rename($archivo['tmp_name'],$inputFileName);
include("../helper/importCliente.php");
 $res = importExcelFacturacion($sql,$inputFileName,"clientes");
 print_r(json_encode($res));

 
     break;


    }

}