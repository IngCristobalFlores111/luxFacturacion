<?php

require_once("php/Functions.php");   

$res = luxlineWebLogin::getClientAndSystemFilesFolder();
$response = $res->getResponse();

     if((int)$response['status'] < 0)
        $res->printResponseInErrorPage();



    $clientAndSystemFilesFolder = $response['data'];

$file = $_GET['pdfFile'];
$type = $_GET['type'];
$new_filename = $file;
$path ='';
if($type=='1'){
	$path =  "../".$clientAndSystemFilesFolder."Facturacion/facturas/timbradas/pdf/$file";
}
if($type=='0'){
	$path = "../".$clientAndSystemFilesFolder."Facturacion/facturas/pendientes/pdf/$file";

}
if($type=='3'){
	$path = "../".$clientAndSystemFilesFolder."Facturacion/facturas/timbradas/xml/$file";

}
$filename = $path;
if($type=='3'){
    header('Content-type: application/xml');
}else{
    header('Content-type: application/pdf');


}
  header('Content-Disposition: inline; filename="' . $new_filename . '"');
  header('Content-Transfer-Encoding: binary');
  header('Accept-Ranges: bytes');
  //header('Content-Disposition: attachment; filename="' . $new_filename . '"');
  echo "<title>CFDI PDF</title>";

@readfile($path);


?>