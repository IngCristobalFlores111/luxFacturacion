<?php



date_default_timezone_set('America/Mexico_City');

include_once "lib/cfdi32_multifacturas_PHP7.php";
require_once("../php/Functions.php");

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

error_reporting(0);

session_start();

$res = luxlineWebLogin::getClientAndSystemFilesFolder();
     $response = $res->getResponse();
     if($response['status'] < 0)
        $res->printResponseJson();

$clientAndSystemFilesFolder = $response['data'];


$folio = $_POST['folio'];
//$xmlFile = $_POST['xmlFile'];

$objSQL = F_sqlConn();
$query = "SELECT xml_file FROM factura_generada WHERE folio_factura='$folio'";
$result =  $objSQL->executeQuery($query);
$xmlFile =$result[0]['xml_file'];
$tmp = explode(".",$xmlFile);
$pdfFile = $tmp[0].".pdf";

$result =$objSQL->executeQuery("SELECT `archivo_cer`,`archivo_key`,csdPass,RFC FROM `usuario_facturacion`");
$fileKey = $result[0]['archivo_key'];
$fileCer =$result[0]['archivo_cer'];
$csdPass = $result[0]['csdPass'];
$rfc = $result[0]['RFC'];

$datos['cancelar']='SI';
$datos['cfdi']= '../../'.$clientAndSystemFilesFolder.'Facturacion/facturas/timbradas/xml/'.$xmlFile;

$datos['PAC']['usuario'] = $rfc;
$datos['PAC']['pass'] = 'MULTI6d9180f6da47e579158f09226afeea12!';

$datos['PAC']['produccion'] = 'SI'; //   [SI|NO]
$datos['conf']['cer'] = '../../'.$clientAndSystemFilesFolder.'Facturacion/pruebas/'.$fileCer;
$datos['conf']['key'] = '../../'.$clientAndSystemFilesFolder.'Facturacion/pruebas/'.$fileKey;
$datos['conf']['pass'] = $csdPass;

$res= cfdi_cancelar($datos);
$respuesta = $res['codigo_mf_texto'];

$res = null;


if($res['codigo_mf_numero']==0)
{
    $query = "UPDATE factura_generada SET fecha_cancelada=NOW() WHERE folio_factura=$folio";
    $objSQL->executeCommand($query);
    $res = array("status" => 0, "msg" => $respuesta);
    print_r(json_encode($res,JSON_UNESCAPED_UNICODE));
include ("../pdfWriter/addCanceledLabelPdf.php");
addLabelCanceled("../../".$clientAndSystemFilesFolder."Facturacion/facturas/timbradas/pdf/".$pdfFile);
    return;
}
else
{
    $res = array("status" => -1, "error" => $respuesta, "folder"=>$_SESSION["ui"][0]["folder"]);
    print_r(json_encode($res,JSON_UNESCAPED_UNICODE));
    return;
}
?>