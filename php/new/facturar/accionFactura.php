<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if(isset($_GET['accion'])){
    include("../functions.php");
      $sql = createMysqliConnection();

    $res = luxlineWebLogin::getClientAndSystemFilesFolder();
     $response = $res->getResponse();
     if($response['status'] < 0)
        $res->printResponseInErrorPage();

    $clientAndSystemFilesFolder = $response['data'];


switch($_GET['accion']){

case "guardarConceptos":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$_SESSION['conceptos'] = $request->conceptos;
break;
case "enviarCorreos":

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
include ("../../../phpMailer/mailer.php");
$query="SELECT email,mail_pass,nombreEmail FROM `usuario_facturacion`";
$cliente= $sql->executeQuery($query);
$cliente = $cliente[0];
$mail = new PHPMailer;
//$mail->isSMTP();
$mail->IsMAIL();
$mail->IsSMTP();
$mail->SMTPDebug = 0;
//Set the hostname of the mail server
$mail->Host =  "smtp.gmail.com";
// use
// $mail->Host = gethostbyname('smtp.gmail.com');
// if your network does not support SMTP over IPv6
//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
$mail->Port = 587;
//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = $cliente['email'];
//Password to use for SMTP authentication
$mail->Password = $cliente['mail_pass'];
//Set who the message is to be sent from
$mail->setFrom($cliente['email'], $cliente['nombreEmail']);
//Set an alternative reply-to address
$respuesta = array();
$i = 0;

$idUsr=  $_SESSION['idUsuario'];

$query="SELECT mensaje_timbrada,mensaje_cancelada FROM `user_config` WHERE idusuario = ?";
$config = $sql->get_bind_results($query,array("i",$idUsr));
if(count($config)==0){
  $config = array("mensaje_cancelada"=>"Le enviamos su factura cancelada,disculpe los inconvenientes","mensaje_timbrada"=>"Le enviamos su factura por nuestros servicios");
  
}else{
$config = $config[0];
} 
foreach($request->emails as $email){


if(!isset($email->cuerpo) || $email->cuerpo==""){
  if($request->tipo=="timbrado"){
  $email->cuerpo =  $config['mensaje_timbrada'];
  }
  if($request->tipo=="cancelado"){
    $email->cuerpo =  $config['mensaje_cancelada'];
    }

}
if(!isset($email->asunto) ||$email->asunto=="" ){
  $email->asunto ="Factura de nuestros servicios";
}

$mail->addReplyTo($email->email, 'Cliente');
//Set who the message is to be sent to
$mail->addAddress($email->email, 'Cliente');
//Set the subject line
$mail->Subject = $email->asunto;

$mail->msgHTML($email->cuerpo);
//Replace the plain text body with one created manually
$mail->AltBody = 'Envio de Facturas';
//Attach an image file
$mail->addAttachment('../../../../'.$clientAndSystemFilesFolder.'Facturacion/facturas/timbradas/xml/'.$request->xml,$request->xml);
$mail->addAttachment('../../../../'.$clientAndSystemFilesFolder.'Facturacion/facturas/timbradas/pdf/'.$request->pdf,$request->pdf);

//send the message, check for errors
if (!$mail->send()) {
   // echo "Mailer Error: " . $mail->ErrorInfo;
   $respuesta[$i] = array("email"=>$email,"error"=>$mail->ErrorInfo);
}else{
  $respuesta[$i] = array("email"=>$email);
  
}
$i++;
}
print_r(json_encode($respuesta));
break;
case "guardarFactura":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$query="SELECT folio_actual + 1 AS folio FROM `factura_series` WHERE serie = '".$request->serie."'";
$folio = $sql->executeQuery($query);
$folio = $folio[0]['folio'];
$total = 0;

$conceptos = $_SESSION['conceptos'];
$conceptos = json_encode($conceptos);
$conceptos = json_decode($conceptos,true);

foreach($conceptos as $c){
$total += (float)$c['importe'];
}

$query="INSERT INTO `factura_pendiente`(`folio_factura`, 
`idcliente`, `fecha`,
 `forma_pago`, `metodo_pago`, 
 `descuento`, `tipo`,total,serie) VALUES (?,?,NOW(),?,?,?,?,?,?);";
 $sql->execQueryBinders($query,array("iissdids",$folio,$request->idCliente,
 $request->formasPago,$request->metodosPago,$request->descuento,$request->tipo_factura,$total,$request->serie
));
$idFacturaPendiente= $sql->getLastId();



$query="";
$total = 0;
foreach($conceptos as $c){
  $cantidad=$c['cantidad'];
  $descripcion = $c['descripcion'];
  $unidad = $c['unidad'];
  $precio = $c['precio'];
  $importe = $c['importe'];
  $noSerie = $c['noSerie'];
  $claveProd = $c['claveProdServ'];
$claveProdId = $claveProd['id'];
$claveProdCodigo = $claveProd['codigo'];
$claveProdDescripcion= $claveProd['descripcion'];
  
$query.="INSERT INTO `folio_conceptos`(idFacturaPendiente,`folio_factura`,
 `cantidad`, `descripcion`, `unidad`, `precio`,
  `importe`, `noSerie`,`claveProdId`, `claveProdCodigo`, `claveProdDescripcion`) VALUES ('$idFacturaPendiente','$folio','$cantidad','$descripcion',
  '$unidad','$precio','$importe','$noSerie','$claveProdId','$claveProdCodigo','$claveProdDescripcion');";

}
$sql->ejecutarNoQuery($query);

$errores = $sql->getErrorLog();
if(count($errores)==0){
  $query="UPDATE `factura_series` SET folio_actual = folio_actual + 1 WHERE serie='".$request->serie."'";
  $sql->ejecutarNoQuery($query);
  
}
$respuesta = array("dbErrors"=>$errores);
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


break;
case "agregarClaveProdActual":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$query="INSERT INTO `clavesProdActuales`(idClaveProd,c_ClaveProdServ,Descripción) VALUES (?,?,?);";
$sql->execQueryBinders($query,array("iss",$request->id,$request->codigo,$request->descripcion));
$errors = $sql->getErrorLog();
$respuesta = array();
if(count($errors)>0){
$respuesta['exito']=false;
$respuesta['errors']=$errors;
}else{
  $respuesta['exito']=true;
  
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

break;
case "eliminarClaveProdActual":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$query="DELETE FROM `clavesProdActuales` WHERE `idClaveProd`=?";
$sql->execQueryBinders($query,array("i",$request->id));
$errors = $sql->getErrorLog();
$respuesta = array();
if(count($errors)>0){
$respuesta['exito']=false;
$respuesta['errors']=$errors;
}else{
  $respuesta['exito']=true;
  
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

break;
case "agregarUnidadActual":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$query="INSERT INTO `unidades_actuales`(`idUnidad`) VALUES (?);";
$sql->execQueryBinders($query,array("i",$request->id));
$errors = $sql->getErrorLog();

$respuesta = array();
if(count($errors)>0){
$respuesta['exito']=false;
$respuesta['errors']=$errors;
}else{
  $respuesta['exito']=true;
  
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

break;
case "eliminarUnidadActual":
$query="DELETE FROM `unidades_actuales` WHERE `idUnidad` = ?";
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$sql->execQueryBinders($query,array("i",$request->id));
$errors = $sql->getErrorLog();

$respuesta = array();
if(count($errors)>0){
$respuesta['exito']=false;
$respuesta['errors']=$errors;
}else{
  $respuesta['exito']=true;
  
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

break;
case "altaSerie":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$query="INSERT INTO `factura_series`(`serie`,`folio_actual`) VALUES (?,0);";
$sql->execQueryBinders($query,array("s",$request->serie));
$errors = $sql->getErrorLog();

$respuesta = array();
if(count($errors)>0){
$respuesta['exito']=false;
$respuesta['errors']=$errors;
}else{
  $respuesta['exito']=true;
  $respuesta['id'] = $sql->getLastId();
  
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

break;
case "eliminarSerie":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$query="DELETE FROM `factura_series` WHERE `idSerie`=?";
$sql->execQueryBinders($query,array("i",$request->idSerie));
$errors = $sql->getErrorLog();

$respuesta = array();
if(count($errors)>0){
$respuesta['exito']=false;
$respuesta['errors']=$errors;
}else{
  $respuesta['exito']=true;
  
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


break;
case "modSerie":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$query="UPDATE `factura_series` SET serie = ? WHERE `idSerie` = ?";
$sql->execQueryBinders($query,array("si",$request->serie,$request->idSerie));
$errors = $sql->getErrorLog();

$respuesta = array();
if(count($errors)>0){
$respuesta['exito']=false;
$respuesta['errors']=$errors;
}else{
  $respuesta['exito']=true;
  
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
break;

}



}
