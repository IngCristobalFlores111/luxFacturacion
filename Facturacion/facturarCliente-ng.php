<?php

 //error_reporting(E_ALL);
 //ini_set('display_errors', 1);

//error_reporting(0); // OPCIONAL DESACTIVA NOTIFICACIONES DE DEBUG
date_default_timezone_set('America/Mexico_City');
include_once "lib/cfdi32_multifacturas_PHP7.php";
//include_once "cfdi3.3/sdk2.php";
include_once "../php/new/functions.php";
$sql = createMysqliConnection();
///////////////////////////////////////7d//////////////////////////////////////////
////////////     CREAR ARCHIVOS .PEM
/////////////////////////////////////////////////////////////////////////////////
$postdata = file_get_contents("php://input");
$request = json_decode($postdata,true);
//print_r($request);
//exit();
$facturaParams = $request['facturaParams'];


$result =$sql->executeQuery("SELECT `archivo_cer`,`archivo_key` FROM usuario_facturacion");
$fileKey = $result[0]['archivo_key'];
$fileCer =$result[0]['archivo_cer'];

$datos['PAC']['usuario'] = 'IIDE660331UU7';
$datos['PAC']['pass'] = 'MULTI6d9180f6da47e579158f09226afeea12!';
$datos['PAC']['produccion'] = 'SI'; //   [SI|NO]
$datos['conf']['cer'] ="pruebas/$fileCer";
$datos['conf']['key'] = "pruebas/$fileKey";
$datos['conf']['pass'] = 'VENCEDOR';
/*
$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]
$datos['conf']['cer'] = 'pruebas/CSD_Pruebas_CFDI_LAN7008173R5.cer';
$datos['conf']['key'] = 'pruebas/CSD_Pruebas_CFDI_LAN7008173R5.key';
$datos['conf']['pass'] = '12345678a';
*/
$query="SELECT `folio_factura` FROM `factura_generada` ORDER BY `folio_factura` DESC LIMIT 1";
$folio = $sql->executeQuery($query);
$folio = (int)$folio[0]['folio_factura'];
$folio++;
$query="SELECT * FROM `monedas` WHERE idMoneda = ?";
$moneda =$sql->get_bind_results($query,array("i",$facturaParams['moneda']));
if(count($moneda)==0){
    $moneda['nombre'] ="MXN";
    $moneda['tipo_cambio'] ="1.00";
}
$moneda = $moneda[0];
$date = date('Y_m_d');
$datos['cfdi']="facturas/timbradas/xml/".$request['idCliente']."_".$date."_".$folio.".xml";  // si se especifica este parametro, se timbrara la factura

$datos['xml_debug']="facturas/sin_timbrar/sin_timbrar_".$request['idCliente']."_".$date."_".$folio.".xml";

$xml_file = $request['idCliente']."_".$date."_".$folio.".xml";

$datos['php_openssl']='SI';

$datos['factura']['serie'] = 'A'; //opcional
$datos['factura']['folio'] = $folio;
$fecha_timb = date('Y-m-d H:i:s',time()-120);
$tmp_fecha = explode(" ",$fecha_timb);
$fecha_timb = implode("T",$tmp_fecha);
$datos['factura']['fecha_expedicion'] = $fecha_timb;// Opcional  "time()-120" para retrasar la hora 2 minutos para evitar falla de error en rango de fecha
$idMetodo = $facturaParams['metodosPago'];
$query="SELECT * FROM `metodos_pago` WHERE `idMetodoPago` = ?";
$metodoPago = $sql->get_bind_results($query,array("i",$idMetodo));
if(count($metodoPago)==0){
    $metodoPago['nombre']="EFECTIVO";
}else{
$metodoPago = $metodoPago[0];
}
$datos['factura']['metodo_pago'] = $metodoPago['nombre']; // EFECTIV0, CHEQUE, TARJETA DE CREDITO, TRANSFERENCIA BANCARIA, NO IDENTIFICADO
$datos['factura']['forma_pago'] = $facturaParams['formasPago']; //PAGO EN UNA SOLA EXHIBICION, CREDITO 7 DIAS, CREDITO 15 DIAS, CREDITO 30 DIAS, ETC
$datos['factura']['tipocomprobante'] = $facturaParams['tipo']; //ingreso, egreso


$datos['factura']['moneda'] = $moneda['nombre']; // MXN USD EUR
$datos['factura']['tipocambio'] = $moneda['tipo_cambio']; // OPCIONAL (MXN = 1.00, OTRAS EJ: USD = 13.45; EUR = 16.86)

$query="SELECT * FROM `lugar_expedicion` WHERE `idexpedicion` = ?";
$lugar = $sql->get_bind_results($query,array("i", $request['lugarExpedicion']));
$lugar = $lugar[0];
$datos['factura']['LugarExpedicion'] =$lugar['municipio'].",".$lugar['estado'];
if($facturaParams['numCuenta']!=""){
    $datos['factura']['NumCtaPago'] =$facturaParams['numCuenta'];
}
//$datos['factura']['NumCtaPago'] = '0234'; //opcional; 4 DIGITOS pero obligatorio en transferencias y cheques

$emisor =$sql->executeQuery("SELECT * FROM `usuario_facturacion` LIMIT 1");
$emisor = $emisor[0];

$datos['factura']['RegimenFiscal'] = $emisor['regimen'];
$datos['emisor']['rfc'] =  $emisor['RFC'];
//$datos['emisor']['rfc'] = 'LAN7008173R5'; //RFC DE PRUEBA  
$datos['emisor']['nombre'] = $emisor['nombre']; // EMPRESA DE PRUEBA
$datos['emisor']['DomicilioFiscal']['calle'] = $emisor['calle'];
$datos['emisor']['DomicilioFiscal']['noExterior'] =  $emisor['noExterior'];
$datos['emisor']['DomicilioFiscal']['noInterior'] =  $emisor['noInterior'];
$datos['emisor']['DomicilioFiscal']['colonia'] = $emisor['colonia'];
$datos['emisor']['DomicilioFiscal']['localidad'] =  $emisor['localidad'];
$datos['emisor']['DomicilioFiscal']['municipio'] = $emisor['municipio'];
$datos['emisor']['DomicilioFiscal']['estado'] = $emisor['estado'];
$datos['emisor']['DomicilioFiscal']['pais'] =$emisor['pais'];
$datos['emisor']['DomicilioFiscal']['CodigoPostal'] = $emisor['CodigoPostal'];

//SI EX EXPEDIDO EN SUCURSAL CAMBIA EL DOMICILIO
//SI ES EN EL MISMO DOMICILIO REPETIR INFORMACION
$datos['emisor']['ExpedidoEn']['calle'] = $lugar['calle'];
$datos['emisor']['ExpedidoEn']['noExterior'] = $lugar['noExt'];
$datos['emisor']['ExpedidoEn']['noInterior'] =  $lugar['noInt'];
$datos['emisor']['ExpedidoEn']['colonia'] =  $lugar['colonia'];
$datos['emisor']['ExpedidoEn']['localidad'] =  $lugar['localidad'];
$datos['emisor']['ExpedidoEn']['municipio'] =  $lugar['municipio'];
$datos['emisor']['ExpedidoEn']['estado'] =  $lugar['estado'];
$datos['emisor']['ExpedidoEn']['pais'] =  $lugar['pais'];
$datos['emisor']['ExpedidoEn']['CodigoPostal'] =  $lugar['codigoPostal'];

$cliente = $sql->get_bind_results("SELECT * FROM `clientes_facturacion` WHERE `idcliente` = ?",array("i",$request['idCliente']));
$cliente = $cliente[0];
// IMPORTANTE PROBAR CON NOMBRE Y RFC REAL O GENERARA ERROR DE XML MAL FORMADO
$datos['receptor']['rfc'] = $cliente['RFC'];
$datos['receptor']['nombre'] = $cliente['nombre'];
//opcional
$datos['receptor']['Domicilio']['calle'] = $cliente['calle'];
$datos['receptor']['Domicilio']['noExterior'] = $cliente['noExterior'];
$datos['receptor']['Domicilio']['noInterior'] = $cliente['noInterior'];
$datos['receptor']['Domicilio']['colonia'] = $cliente['colonia'];
$datos['receptor']['Domicilio']['localidad'] = $cliente['localidad'];
$datos['receptor']['Domicilio']['municipio'] = $cliente['municipio'];
$datos['receptor']['Domicilio']['estado'] = $cliente['estado'];
$datos['receptor']['Domicilio']['pais'] = $cliente['pais'];
$datos['receptor']['Domicilio']['CodigoPostal'] = $cliente['CodigoPostal'];

//AGREGAR 10 CONCEPTOS DE PRUEBA
session_start();
$conceptos = $_SESSION['conceptos'];
$tmpJConcpetos = json_encode($conceptos);
$conceptos = json_decode($tmpJConcpetos,true);
$subtotal = 0;
$isPredial = false;
if(isset($facturaParams['predial'])){
if($facturaParams['predial']!=""){
    $isPredial = true;
    
}

}
foreach($conceptos as $c){
    unset($concepto);
    $concepto = array();
    $concepto['cantidad'] = $c['cantidad'];
    $concepto['unidad'] = $c['unidad'];
    $concepto['ID'] = $c['noSerie'];
//    $concepto['descripcion'] = "PRODUCTO PRUEBA > '$i'";
    $concepto['descripcion'] =$c['descripcion'];
    $concepto['valorunitario'] = $c['precio'];
    $concepto['importe'] = $c['importe'];
    if($isPredial){
        $concepto['numero'] =$facturaParams['predial'];
    }
    if(isset($c['pedimento'])){
        $pedimento =$c['pedimento'];
        $concepto['numero']=$pedimento['numero'];
        $concepto['fecha']=$pedimento['fecha'];
        $concepto['aduana'] =$pedimento['aduana'];
    }
$subtotal +=(float)$concepto['importe'];
    $datos['conceptos'][] = $concepto;
}

$datos['factura']['subtotal'] = round($subtotal,3); // sin impuestos
$datos['factura']['descuento'] = round($facturaParams['descuento'],3); // descuento sin impuestos

$iva = ($subtotal - $facturaParams['descuento'])*0.16;
$total = ($subtotal - $facturaParams['descuento']) + $iva;
$datos['factura']['total'] = $total;



$translado1['impuesto'] = 'IVA';
$translado1['tasa'] = '16';
$translado1['importe'] = $iva;
$datos['impuestos']['translados'][0] = $translado1;

    if($facturaParams['retencion_IVA']=="true"){
        $iva_retenido =  (float)$iva*(2/3);
        $retenido['impuesto'] = 'IVA';
        $retenido['importe'] = $iva_retenido; // iva de los productos facturados
        $datos['impuestos']['retenidos'][] = $retenido;
        $total =$datos['factura']['total'] ; // total incluyendo impuestos
        $total =(float)($total - $iva_retenido);
        $datos['factura']['total']  = $total;
    }
    if($facturaParams['retener_ISR']=="true"){
        $retenido['impuesto'] = 'ISR';
        $tasa_isr = (float)$facturaParams['retencion_ISR'];
        $importe_isr = (float)($subtotal - $descuento)*($tasa_isr/100);
        $retenido['importe'] = $importe_isr; // iva de los productos facturados
        $datos['impuestos']['retenidos'][] = $retenido;
        $total =$datos['factura']['total'] ; // total incluyendo impuestos
        $total = (float)($total - $importe_isr);
        $datos['factura']['total']  = $total;
    }



$res= cfdi_generar_xml($datos);
$respuesta['datos'] = $datos;
//print_r($res);
$respuesta = array();
if($res['codigo_mf_numero']==0){
    $respuesta['timbrado']=true;
    $idUsr=  $_SESSION['idUsuario'];
    $query="SELECT * FROM `user_config` WHERE `idusuario` = ?";
    $config = $sql->get_bind_results($query,array("i",$idUsr));
    $tipo =$config[0]['tipo_factura'];

$query="INSERT INTO `factura_generada`
(`folio_factura`, `folio_impreso`, `idcliente`, `enviado`,
 `xml_file`, `montoTotal`, `uuid`, `fecha_timbrado`, `tipo`, `json_conceptos`) VALUES ('$folio','$folio','".$request['idCliente']."',
  '0','$xml_file','$total','".$res['uuid']."',NOW(),'$tipo','$tmpJConcpetos')";
$sql->ejecutarNoQuery($query);
  $errors = $sql->getErrorLog();

include("../wkWorks/generatePdf.php");
$tmp = explode(".",$xml_file);
$filePdf = $tmp[0].".pdf";
$absolutePdf = "facturas/timbradas/pdf/".$filePdf;
$qr = $tmp[0].".png";
$EmitterPhone = $emisor['telefono'];
generatePdf("https://www.luxline.com.mx/joseO/LuxFacturacion/Facturacion/facturas/timbradas/xml/".$xml_file,"goldman.badger@gmail.com",$EmitterPhone,"https://www.luxline.com.mx/joseO/LuxFacturacion/Facturacion/facturas/timbradas/xml/".$qr,$absolutePdf);
$respuesta['total'] = $total;
unset($_SESSION['conceptos']);
if(count($errors)>0){
    $respuesta['dbErrors']=$errors;
}else{
    $respuesta['dbErrors']=null;
    
}

}else{
    $respuesta['timbrado']=false;
    
}
$respuesta['res']=$res;
function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}
echo json_encode(utf8ize($respuesta),JSON_UNESCAPED_UNICODE);//echo "esta madre se caga";


?>