<?php
session_start();

 //error_reporting(E_ALL);
 //ini_set('display_errors', 1);

error_reporting(0); // OPCIONAL DESACTIVA NOTIFICACIONES DE DEBUG
date_default_timezone_set('America/Mexico_City');

// Se incluye el SDK
require_once 'cfdi3.3/sdk2.php';

// Se especifica la version de CFDi 3.3
$datos['version_cfdi'] = '3.3';

include_once "../Login/php/Functions.php";
$sql = createMysqliConnection();
/////////////////////////////////////////////////////////////////////////////////
////////////     CREAR ARCHIVOS .PEM
/////////////////////////////////////////////////////////////////////////////////
$postdata = file_get_contents("php://input");
$request = json_decode($postdata,true);
//print_r($request);
//exit();
$facturaParams = $request['facturaParams'];


//print_r($request['facturaParams']);

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
$folio = $facturaParams['folio'];
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

$datos['factura']['serie'] = $facturaParams['serie'];
$datos['factura']['folio'] = $folio;
$hoy = date('Y-m-d H:i:s',time()-120);
$tmp = explode(" ",$hoy);
$hoy = $tmp[0]."T".$tmp[1];
$datos['factura']['fecha_expedicion'] = $hoy;// Opcional  "time()-120" para retrasar la hora 2 minutos para evitar falla de error en rango de fecha


$datos['factura']['metodo_pago'] =$facturaParams['metodosPago']; // EFECTIV0, CHEQUE, TARJETA DE CREDITO, TRANSFERENCIA BANCARIA, NO IDENTIFICADO
$formaPago =(int) $facturaParams['formasPago'];
if($formaPago<10){
    $formaPago = "0".$formaPago;
}
$datos['factura']['forma_pago'] = $formaPago ; //PAGO EN UNA SOLA EXHIBICION, CREDITO 7 DIAS, CREDITO 15 DIAS, CREDITO 30 DIAS, ETC
$datos['factura']['tipocomprobante'] = $facturaParams['tipo']; //ingreso, egreso
//paramas 3.3
if(!isset($facturaParams['condicionesPago'])){
    $facturaParams['condicionesPago'] ="Sin condiciones";
}
$datos['factura']['condicionesDePago']= $facturaParams['condicionesPago'];

$datos['factura']['moneda'] = $moneda['nombre']; // MXN USD EUR
$datos['factura']['tipocambio'] = $moneda['tipo_cambio']; // OPCIONAL (MXN = 1.00, OTRAS EJ: USD = 13.45; EUR = 16.86)

$query="SELECT * FROM `lugar_expedicion` WHERE `idexpedicion` = ?";
$lugar = $sql->get_bind_results($query,array("i", $request['lugarExpedicion']));
$lugar = $lugar[0];
//$datos['factura']['LugarExpedicion'] =$lugar['municipio'].",".$lugar['estado'];
$datos['factura']['LugarExpedicion'] =$lugar['codigoPostal'];
if(isset($facturaParams['numCuenta'])){
if($facturaParams['numCuenta']!=""){
    $datos['factura']['NumCtaPago'] =$facturaParams['numCuenta'];
}
}

//$datos['factura']['NumCtaPago'] = '0234'; //opcional; 4 DIGITOS pero obligatorio en transferencias y cheques

$emisor =$sql->executeQuery("SELECT * FROM `usuario_facturacion` LIMIT 1");
$emisor = $emisor[0];
$idUsr=  $_SESSION['idUsuario'];
$query="SELECT user_config.tipo_factura,f4_c_regimenfiscal.c_RegimenFiscal AS regimen FROM `user_config` INNER JOIN f4_c_regimenfiscal ON f4_c_regimenfiscal.id = user_config.regimen
WHERE user_config.idusuario	 = ?";
$config = $sql->get_bind_results($query,array("i",$idUsr));
$tipo =$config[0]['tipo_factura'];
$regimenFiscalEmisor = $config[0]['regimen'];

$datos['factura']['RegimenFiscal'] = $regimenFiscalEmisor;
//$datos['factura']['RegimenFiscal']='601';
$datos['emisor']['rfc'] =  $emisor['RFC'];
$datos['receptor']['UsoCFDI'] = $facturaParams['usoCFDI'];
//$datos['emisor']['rfc']  = "LAN7008173R5";
$datos['receptor']['UsoCFDI'] = $facturaParams['usoCFDI'];
$datos['emisor']['nombre'] = $emisor['nombre']; // EMPRESA DE PRUEBA
$datos['emisor']['RegimenFiscal'] =  $regimenFiscalEmisor;
/*
//$datos['emisor']['rfc'] = 'LAN7008173R5'; //RFC DE PRUEBA  
$datos['emisor']['DomicilioFiscal']['calle'] = $emisor['calle'];
$datos['emisor']['DomicilioFiscal']['noExterior'] =  $emisor['noExterior'];
$datos['emisor']['DomicilioFiscal']['noInterior'] =  $emisor['noInterior'];
$datos['emisor']['DomicilioFiscal']['colonia'] = $emisor['colonia'];
$datos['emisor']['DomicilioFiscal']['localidad'] =  $emisor['localidad'];
$datos['emisor']['DomicilioFiscal']['municipio'] = $emisor['municipio'];
$datos['emisor']['DomicilioFiscal']['estado'] = $emisor['estado'];
$datos['emisor']['DomicilioFiscal']['pais'] =$emisor['pais'];
$datos['emisor']['DomicilioFiscal']['CodigoPostal'] = $emisor['CodigoPostal'];
*/
//SI EX EXPEDIDO EN SUCURSAL CAMBIA EL DOMICILIO
//SI ES EN EL MISMO DOMICILIO REPETIR INFORMACION
/*
$datos['emisor']['ExpedidoEn']['calle'] = $lugar['calle'];
$datos['emisor']['ExpedidoEn']['noExterior'] = $lugar['noExt'];
$datos['emisor']['ExpedidoEn']['noInterior'] =  $lugar['noInt'];
$datos['emisor']['ExpedidoEn']['colonia'] =  $lugar['colonia'];
$datos['emisor']['ExpedidoEn']['localidad'] =  $lugar['localidad'];
$datos['emisor']['ExpedidoEn']['municipio'] =  $lugar['municipio'];
$datos['emisor']['ExpedidoEn']['estado'] =  $lugar['estado'];
$datos['emisor']['ExpedidoEn']['pais'] =  $lugar['pais'];
$datos['emisor']['ExpedidoEn']['CodigoPostal'] =  $lugar['codigoPostal'];
*/
$cliente = $sql->get_bind_results("SELECT * FROM `clientes_facturacion` WHERE `idcliente` = ?",array("i",$request['idCliente']));
$cliente = $cliente[0];
// IMPORTANTE PROBAR CON NOMBRE Y RFC REAL O GENERARA ERROR DE XML MAL FORMADO
$datos['receptor']['rfc'] = $cliente['RFC'];
$datos['receptor']['nombre'] = $cliente['nombre'];
//opcional
/*
$datos['receptor']['Domicilio']['calle'] = $cliente['calle'];
$datos['receptor']['Domicilio']['noExterior'] = $cliente['noExterior'];
$datos['receptor']['Domicilio']['noInterior'] = $cliente['noInterior'];
$datos['receptor']['Domicilio']['colonia'] = $cliente['colonia'];
$datos['receptor']['Domicilio']['localidad'] = $cliente['localidad'];
$datos['receptor']['Domicilio']['municipio'] = $cliente['municipio'];
$datos['receptor']['Domicilio']['estado'] = $cliente['estado'];
$datos['receptor']['Domicilio']['pais'] = $cliente['pais'];
$datos['receptor']['Domicilio']['CodigoPostal'] = $cliente['CodigoPostal'];
*/
//AGREGAR 10 CONCEPTOS DE PRUEBA

$conceptos = $_SESSION['conceptos'];
$tmpJConcpetos = json_encode($conceptos);
$conceptos = json_decode($tmpJConcpetos,true);
$subtotal = 0;
$ivaRetenido = 0;
$isrRetenido = 0;

foreach($conceptos as $c){
    unset($concepto);
    $concepto = array();
    $concepto['cantidad'] = $c['cantidad'];
    $concepto['ClaveUnidad'] = $c['unidad'];
    $concepto['ID'] = $c['noSerie'];
//    $concepto['descripcion'] = "PRODUCTO PRUEBA > '$i'";
    $concepto['descripcion'] =$c['descripcion'];
    $concepto['valorunitario'] = round($c['precio'],2);
    $concepto['ClaveProdServ'] =$c['claveProdServ']['codigo'];
    $concepto['importe'] = round($c['importe'],2);
    if(isset($facturaParams['predial'])){
     if($facturaParams['predial']!=""){
        $concepto['predial'] = $facturaParams['predial'];

     }

    }
    if(isset($c['pedimento'])){
        $pedimento =$c['pedimento'];
        $concepto['InformacionAduanera']['NumeroPedimento']=$pedimento['numero'];
        /*
        $concepto['InformacionAduanera']['FechaPedimento']=$pedimento['fecha'];
        $concepto['InformacionAduanera']['AduanaPedimento']=$pedimento['aduana'];
        */
        //$concepto['fecha']=$pedimento['fecha'];
        //$concepto['aduana'] =$pedimento['aduana'];

    }
    // monadas de 3.3
    $concepto['Impuestos']['Traslados'][0]['Base'] = $c['importe'];
    $concepto['Impuestos']['Traslados'][0]['Impuesto'] = '002';
    $concepto['Impuestos']['Traslados'][0]['TipoFactor'] = 'Tasa';
    $concepto['Impuestos']['Traslados'][0]['TasaOCuota'] = '0.160000';
    $ivaProducto = (float)$c['importe']*0.16;
    $concepto['Impuestos']['Traslados'][0]['Importe'] = round($ivaProducto,2);
$subtotal +=(float)$concepto['importe'];
$index_retencion = 0;
if($facturaParams['retencion_IVA']=="true"){
    $concepto['Impuestos']['Retenciones'][$index_retencion]['Base'] =$c['importe'];
    $concepto['Impuestos']['Retenciones'][$index_retencion]['Impuesto'] = '002';
    $concepto['Impuestos']['Retenciones'][$index_retencion]['TipoFactor'] = 'Tasa';
    $concepto['Impuestos']['Retenciones'][$index_retencion]['TasaOCuota'] = '0.160000';
    $concepto['Impuestos']['Retenciones'][$index_retencion]['Importe'] = round($ivaProducto,2);
    $ivaRetenido += $ivaProducto;
    $index_retencion++;
}
if($facturaParams['retener_ISR']=="true"){
    $tasa_isr = (float)$facturaParams['retencion_ISR']/100;  
    $tasa_isr = round($tasa_isr,2);  
    $isr = (float)$c['importe']*(float)$tasa_isr;
    $concepto['Impuestos']['Retenciones'][$index_retencion]['Base'] =$c['importe'];
    $concepto['Impuestos']['Retenciones'][$index_retencion]['Impuesto'] = '001';
    $concepto['Impuestos']['Retenciones'][$index_retencion]['TipoFactor'] = 'Tasa';
    $concepto['Impuestos']['Retenciones'][$index_retencion]['TasaOCuota'] = $tasa_isr;
    $concepto['Impuestos']['Retenciones'][$index_retencion]['Importe'] = round($isr,2);
    $isrRetenido += $isr;
}

    $datos['conceptos'][] = $concepto;
}

$datos['factura']['subtotal'] = round($subtotal,3); // sin impuestos
$datos['factura']['descuento'] = round($facturaParams['descuento'],3); // descuento sin impuestos

$iva = ($subtotal - $facturaParams['descuento'])*0.16;
$total = ($subtotal - $facturaParams['descuento']) + $iva;
$datos['factura']['total'] = round($total,2);



$translado1['impuesto'] = '002';
$translado1['tasa'] = '0.160000';
$translado1['importe'] = round($iva,2);
$translado1['TipoFactor'] = "Tasa";
$datos['impuestos']['TotalImpuestosTrasladados'] =round($iva,2);
$datos['impuestos']['translados'][0] = $translado1;
$total_retenido = 0;

    if($facturaParams['retencion_IVA']=="true"){
       // $iva_retenido =  (float)$iva*(2/3);
        $retenido['impuesto'] = '002';
        $retenido['importe'] = $ivaRetenido; // iva de los productos facturados
        $total_retenido+=$ivaRetenido;
        $datos['impuestos']['retenciones'][] = $retenido;
        $total =$datos['factura']['total'] ; // total incluyendo impuestos
        $total =(float)($total - $ivaRetenido);
        $datos['factura']['total']  = round($total,2);
        $datos['impuestos']['TotalImpuestosRetenidos']=$total_retenido;
        
    }
    if($facturaParams['retener_ISR']=="true"){
        $retenido['impuesto'] = '001';
        $tasa_isr = (float)$facturaParams['retencion_ISR'];
        $importe_isr = (float)($subtotal - $descuento)*($tasa_isr/100);
        $retenido['importe'] = $isrRetenido; // iva de los productos facturados
        $datos['impuestos']['retenciones'][] = $retenido;
        $total =$datos['factura']['total'] ; // total incluyendo impuestos
        $total = (float)($total - $importe_isr);
        $datos['factura']['total']  = $total;
        $total_retenido+=$importe_isr;
        $datos['impuestos']['TotalImpuestosRetenidos']=$total_retenido;
        
    }
    

$res= mf_genera_cfdi($datos);
//print_r($res);

$respuesta = array();
if($res['codigo_mf_numero']==0){
    $respuesta['timbrado']=true;
    $respuesta['datos'] = $datos;
    
 
    $query="SELECT `idSerie` FROM `factura_series` WHERE `serie` = ?";
    $serie_id = $sql->get_bind_results($query,array("s",$facturaParams['serie']));
    $serie_id = $serie_id[0]['idSerie'];

$query="INSERT INTO `factura_generada`
(idSerie,version,`folio_factura`, `folio_impreso`, `idcliente`, `enviado`,
 `xml_file`, `montoTotal`, `uuid`, `fecha_timbrado`,
  `fecha_cancelada`, `tipo`, `json_conceptos`) VALUES ('$serie_id','3.3','$folio','$folio','".$request['idCliente']."',
  '0','$xml_file','$total','".$res['uuid']."',NOW(),'0000-00-00','$tipo','$tmpJConcpetos')";
$sql->ejecutarNoQuery($query);

$query="UPDATE `factura_series` SET `folio_actual` = `folio_actual` + 1 WHERE `serie` = ?";
$sql->execQueryBinders($query,array("s",$facturaParams['serie']));



  $errors = $sql->getErrorLog();
  $errors = array();

include("../wkWorks/generatePdf.php");
$tmp = explode(".",$xml_file);
$filePdf = $tmp[0].".pdf";
$absolutePdf = "facturas/timbradas/pdf/".$filePdf;
$qr = $tmp[0].".png";
$EmitterPhone = $emisor['telefono'];
generatePdf33($request['idCliente'],"https://www.luxline.com.mx/sanAngelprod/LuxFacturacion/Facturacion/facturas/timbradas/xml/".$xml_file,"sanangel.facturas.tabachines@gmail.com",$EmitterPhone,"https://www.luxline.com.mx/sanAngelprod/LuxFacturacion/Facturacion/facturas/timbradas/xml/".$qr,$absolutePdf);
$respuesta['total'] = $total;
//unset($_SESSION['conceptos']);
if(count($errors)>0){
    $respuesta['dbErrors']=$errors;
}else{
    $respuesta['dbErrors']=null;
    
}

}else{
    $respuesta['timbrado']=false;
    $respuesta['datos'] = $datos;
    
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