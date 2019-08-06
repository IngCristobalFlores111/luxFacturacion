<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Toronto');

require_once("Functions.php");
require_once("../phpMailer/mailer.php");

$res = luxlineWebLogin::getClientAndSystemFilesFolder();
     $response = $res->getResponse();
     if($response['status'] < 0)
        $res->printResponseJson();

$clientAndSystemFilesFolder = $response['data'];

$ga_correos = $_POST['jCorreos'];
$gs_folio = MysqlLink::m_filterInput($_POST['folio']);
$gn_UI = MysqlLink::m_filterInput($_POST["UI"]);
$json = json_decode($ga_correos);

$gs_idcliente = MysqlLink::m_filterInput($_POST['idCliente']);

$objSQLClient = F_sqlConn();

$result = $objSQLClient->executeQuery("SELECT mail_pass,nombre,email FROM usuario_facturacion");
$gs_correoUsuarioFrom = $result[0]['email'];
$gs_nombreUsuario = $result[0]['nombre'];
$gs_mailPass = $result[0]['mail_pass'];


$mensaje_body = "";
$Subject = "";

switch($gn_UI) //Factura generada:0  factura timbrada:1 factura cancelada:2
{
    case 0:
    $query = "SELECT mensaje_pendiente FROM mensajes_correo;";
    $mensaje_body =  $objSQLClient->executeQuery($query)[0]["mensaje_pendiente"];
    $Subject = "Esta representación impresa ha sido enviada para su revisión, la cual no ha sido timbrada";
    break;

    case 1:
    $query = "SELECT mensaje_timbrado FROM mensajes_correo;";
    $mensaje_body =  $objSQLClient->executeQuery($query)[0]["mensaje_timbrado"];
    $Subject = "Le enviamos su comprobante fiscal";
    break;

    case 2:
    $query = "SELECT mensaje_cancelado FROM mensajes_correo;";
    $mensaje_body =  $objSQLClient->executeQuery($query)[0]["mensaje_cancelado"];
    $Subject = "Esta factura ha sido cancelada";
    break;

    default:
    break;
}

$query = "SELECT xml_file FROM factura_generada WHERE folio_factura='$gs_folio'";
$result =  $objSQLClient->executeQuery($query);
$tmp_array = explode(".xml",$result[0]['xml_file']);
$gs_file = $tmp_array[0];

$Sender = $gs_correoUsuarioFrom;
$SenderPass = $gs_mailPass;
$SenderName = $gs_nombreUsuario;
$correos = $json;
$correosQty = sizeof($correos);
$PDFPath = "../../".$clientAndSystemFilesFolder."Facturacion/facturas/timbradas/pdf/$gs_file.pdf";
$XMLPath = "../../".$clientAndSystemFilesFolder."Facturacion/facturas/timbradas/xml/$gs_file.xml";
$Subject = "Le enviamos su comprobante fiscal";
$folio = $gs_folio;
$clienteId = $gs_idcliente;

$i = 0;
for(;$i < $correosQty; $i++)
{

    $mailer = new phpMailerWithGmail($Sender,$SenderPass,$SenderName);
    $Receiver = $correos[$i]->mail;

    if($correos[$i]->asunto != ""){
        $Subject = $correos[$i]->asunto;
    }

    if($correos[$i]->cuerpo != "")
        $MessageHTML = "<label>".$correos[$i]->cuerpo."</label>";
    else{
        $MessageHTML = $mensaje_body;
    }

    $res = $mailer->sendMail($Receiver,'Estimado cliente',$Subject,$MessageHTML,'',array(0=>$PDFPath,1=>$XMLPath),0);
    
    $mailer->clearAttachments();
    if($res['status'] < 0)
        $correos[$i]->status = "-1";
    else
        $correos[$i]->status = "0";

    unset($correos[$i]->asunto);
    unset($correos[$i]->cuerpo);
}


print_r(json_encode($correos,JSON_UNESCAPED_UNICODE));
?>

