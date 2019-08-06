<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once "functions.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
  }
  
$res = luxlineWebLogin::getClientAndSystemFilesFolder();
$response = $res->getResponse();
$clientAndSystemFolder  = $response['data'];

$postdata = file_get_contents("php://input");
$request = json_decode($postdata,true);
$tmp_files = $request['xml_files'];
$pdf_files = array();
$xml_files = array();
$zip = new ZipArchive;
$hoy = date("Y-m-d");
$zipName = "export_".$hoy;


$pathTimbrados = "../../../".$clientAndSystemFolder."/Facturacion/facturas/timbradas/";
$zipPath = "../../../".$clientAndSystemFolder."/".$zipName;
$respuesta =array();
if ($zip->open($zipPath, ZipArchive::OVERWRITE |ZipArchive::CREATE ) === TRUE){
foreach($tmp_files as $file){
    $tmp = explode(".",$file);
    $pdf_name = $tmp[0].".pdf";
    $xml_name = $tmp[0].".xml";
    $pdf = $pathTimbrados."/pdf/".$pdf_name;
    $xml =$pathTimbrados."/xml/".$xml_name;
    if(file_exists($pdf)){
   $zip->addFile($pdf,$pdf_name);
    }
    if(file_exists($xml)){
   $zip->addFile($xml,$xml_name);
    }
}
$zip->close();
if(file_exists($zipPath)){
    $respuesta['exito']=false;
    $respuesta['msg']="no se pudo guardar el archivo";
}else{
    $respuesta['exito']=true;
    $respuesta['zip']=$zipPath;
}
}else{
    $respuesta['exito']=false;
    $respuesta['msg']="Error en crear zip...";
}
print_r(json_encode($response,JSON_UNESCAPED_UNICODE));


?>