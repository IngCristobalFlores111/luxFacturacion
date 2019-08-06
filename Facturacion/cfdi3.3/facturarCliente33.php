<?php
// Se desactivan los mensajes de debug
//error_reporting(E_ALL);

// Se especifica la zona horaria
date_default_timezone_set('America/Mexico_City');
session_start();
error_reporting(0);

ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$id_usuario = $_SESSION['idUsuario'];
$gs_idCliente = $_POST['idCliente'];

// Se incluye el SDK
require_once 'sdk2.php';
include "../../php/new/functions.php";
include ("../../pdfWriter/FacturaPDFGen.php");


$sql = createMysqliConnection();
$query ="SELECT * FROM `usuario_facturacion`";
$emisor = $sql->executeQuery($query);
$emisor = $emisor[0];

$facturaParams = json_decode($_POST['factura_params'],true);
$idLugar = $_POST['idExpedicion'];

$query ="SELECT * FROm lugar_expedicion WHERE `idexpedicion` = ?";
$lugarExpedicion = $sql->get_bind_results($query,array("i",$idLugar));
$lugarExpedicion =$lugarExpedicion[0];

$query ="SELECT `folio_actual`,serie FROM `factura_series` WHERE `idSerie` = ?";
$serie = $sql->get_bind_results($query,array("i",$facturaParams['serie']));
$serie = $serie[0];
$folio = (int)$serie['folio_actual'];
$folio++;

$query="SELECT * FROM `clientes_facturacion` WHERE `idcliente` = ?";
$cliente = $sql->get_bind_results($query,array("i",$gs_idCliente));
$cliente = $cliente[0];

$query ="SELECT `tipo_cambio` FROM `f4_c_moneda` WHERE `c_Moneda` = ?";
$moneda = $sql->get_bind_results($query,array("s",$facturaParams['moneda']));
$moneda = $moneda[0];



// Se especifica la version de CFDi 3.3
$datos['version_cfdi'] = '3.3';

// Ruta del XML Timbrado

$date = date('Y_m_d');
$xml_file = $gs_idCliente."_".$date."_".$folio.".xml";
$qr =  $gs_idCliente."_".$date."_".$folio.".png";
$filePdf = $gs_idCliente."_".$date."_".$folio.".pdf";
$datos['cfdi']="timbrados/xml/".$xml_file;  // si se especifica este parametro, se timbrara la factura

$datos['xml_debug']="sin_timbrar/sin_timbrar_".$gs_idCliente."_".$date."_".$folio.".xml";

// Credenciales de Timbrado
$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO';

// Rutas y clave de los CSD

//$datos['conf']['cer'] = 'certificados/'.$emisor['archivo_cer'];
//$datos['conf']['key'] = 'certificados/'.$emisor['archivo_key'];

$datos['conf']['cer'] = 'certificados/AAA010101AAA.cer.pem';
$datos['conf']['key'] = 'certificados/AAA010101AAA.key.pem';
$datos['conf']['pass'] = '12345678a';

// Datos de la Factura
$datos['factura']['condicionesDePago'] = $facturaParams['condicionesPago']; //ejemplo: 3 meses Se pueden registrar las condiciones comerciales aplicables para el
//pago del comprobante fiscal, cuando existan �stas y cuando el tipo
//de comprobante sea I (Ingreso) o E (Egreso).
$datos['factura']['descuento'] = $facturaParams['descuento'];
$datos['factura']['fecha_expedicion'] = date('Y-m-d\TH:i:s', time() - 120);
$datos['factura']['folio'] = $folio;
$formaPago = (int)$facturaParams['forma_pago'];
$sFormaPago = '';
if($formaPago<10){
$sFormaPago = '0'.(string)$formaPago;
}else{
$sFormaPago =(string)$formaPago;

}
$montoTotal =  round($facturaParams['total'],3);
$datos['factura']['forma_pago'] = $sFormaPago;
$datos['factura']['LugarExpedicion'] = $lugarExpedicion['codigoPostal'];
$datos['factura']['metodo_pago'] =$facturaParams['metodo_pago'];   // PUE =>PAGO EN UNS SOLA EXHIBICION PPD=>Pago en parcialidades
$datos['factura']['moneda'] = $facturaParams['moneda'];
$datos['factura']['serie'] = $serie['serie'];
//$datos['factura']['subtotal'] = round($facturaParams['subtotal'],3);
$datos['factura']['tipocambio'] = $moneda['tipo_cambio'];
$datos['factura']['tipocomprobante'] = $facturaParams['tipoComp'];
//$datos['factura']['total'] = $montoTotal;
$datos['factura']['RegimenFiscal'] = $emisor['regimen'];  // codigo del regimen fiscal,
// codigo del regimen puede ser 601 =>persona moral
// Datos del Emisor
$datos['emisor']['rfc'] = $emisor['RFC']; //RFC DE PRUEBA
$datos['emisor']['nombre'] = $emisor['nombre'];  // EMPRESA DE PRUEBA
$datos['emisor']['DomicilioFiscal']['calle'] = $emisor['calle'];
$datos['emisor']['DomicilioFiscal']['noExterior'] =  $emisorresult[0]['noExterior']; ;
$datos['emisor']['DomicilioFiscal']['noInterior'] =  $emisor['noInterior']; ;
$datos['emisor']['DomicilioFiscal']['colonia'] =  $emisor['colonia'];
$datos['emisor']['DomicilioFiscal']['localidad'] = $emisor['localidad'];
$datos['emisor']['DomicilioFiscal']['municipio'] = $emisor['municipio'];  // o delegacion
$datos['emisor']['DomicilioFiscal']['estado'] = $emisor['estado'];
$datos['emisor']['DomicilioFiscal']['pais'] = $emisor['pais'];
$datos['emisor']['DomicilioFiscal']['CodigoPostal'] =  $emisor['CodigoPostal'];

// Datos del Receptor
$datos['receptor']['rfc'] = $cliente['RFC'];
$datos['receptor']['nombre'] = $cliente['nombre'];
$datos['receptor']['UsoCFDI'] = $facturaParams['usoCFDI'];// depende del uso de la factura en cuesiton
//G01 => compra de mercancias POR EJEMPLO

// Se agregan los conceptos
$ivaProds =0;
$conceptos = $_SESSION['conceptos'];
$sql->filter_array($conceptos);
$i = 0; $len =count($conceptos);
$retenerIva = ((int)$_POST['retenerIva']==1)?true:false;
$retenerIsr = ((int)$_POST['reternIsr']==1)?true:false;

$tasaIsr = (float)$_POST['tasaISR']/100;
$subtotal_productos = 0;
for(;$i<$len;$i++){
$c = $conceptos[$i];
if($facturaParams['predial']!=""){
    $datos['conceptos'][$i]['CuentaPredial']['Numero'] = $facturaParams['predial'];
}

$datos['conceptos'][$i]['cantidad'] = $c['cantidad'];
$datos['conceptos'][$i]['unidad'] = $c['unidad'];
$datos['conceptos'][$i]['ID'] = $c['noSerie'];
$datos['conceptos'][$i]['descripcion'] = $c['descripcion'];
$datos['conceptos'][$i]['valorunitario'] = $c['precio_unitario'];
$datos['conceptos'][$i]['importe'] = $c['importe'];
$datos['conceptos'][$i]['ClaveProdServ'] = $c['prodServ']['clave'];  // puede obtenerse del catalogo
$datos['conceptos'][$i]['ClaveUnidad'] = $c['unidad'];  // se obtine dle excell clave de unidades por ejempl 26=>tonelada
// OJO ahora se especifica el iva por cada concepto como se puede ver a continuaci�n
$datos['conceptos'][$i]['Impuestos']['Traslados'][0]['Base'] =round($c['importe'],3);
$datos['conceptos'][$i]['Impuestos']['Traslados'][0]['Impuesto'] = '002';  // clave del tipo de impuesto en cuestion 002=>iva 001=>ISR
$datos['conceptos'][$i]['Impuestos']['Traslados'][0]['TipoFactor'] = 'Tasa';  //DEL  cat�logo c_TipoFactor
$datos['conceptos'][$i]['Impuestos']['Traslados'][0]['TasaOCuota'] = '0.160000'; //16% de iva
$iva_prod = (float)$c['importe'] * 0.16;
$datos['conceptos'][$i]['Impuestos']['Traslados'][0]['Importe'] =round($iva_prod,3);
$ivaProds+=$iva_prod;
if(isset($c['numPedimento'])){
    $datos['conceptos'][$i]['numero']=$c['numPedimento'];
    $datos['conceptos'][$i]['fecha']=$c['fechaPedimento'];
    $datos['conceptos'][$i]['aduana']=$c['aduana'];
}


$subtotal_productos +=(float)$c['importe'];
$index_isr = 0;
if($retenerIva){

    $datos['conceptos'][$i]['Impuestos']['Retenciones'][0]['Impuesto'] = '002';
	$datos['conceptos'][$i]['Impuestos']['Retenciones'][0]['Importe'] = round($iva_prod,3); // iva de los productos facturados
	$datos['conceptos'][$i]['Impuestos']['Retenciones'][0]['Base'] =  $c['importe'];
	$datos['conceptos'][$i]['Impuestos']['Retenciones'][0]['TasaOCuota'] = '0.160000';
	$datos['conceptos'][$i]['Impuestos']['Retenciones'][0]['TipoFactor'] = 'Tasa';
$index_isr = 1;
}
if($retenerIsr){
    $importeRetencion = (float) $c['importe']*$tasaIsr ;
    
        $datos['conceptos'][$i]['Impuestos']['Retenciones'][$index_isr]['Impuesto'] = '001';
        $datos['conceptos'][$i]['Impuestos']['Retenciones'][$index_isr]['Importe'] =$importeRetencion; // iva de los productos facturados
        $datos['conceptos'][$i]['Impuestos']['Retenciones'][$index_isr]['Base'] =  $c['importe'];
        $datos['conceptos'][$i]['Impuestos']['Retenciones'][$index_isr]['TasaOCuota'] = $tasaIsr ;
        $datos['conceptos'][$i]['Impuestos']['Retenciones'][$index_isr]['TipoFactor'] = 'Tasa';
    }


}

$retenido_iva = 0;
$retenido_isr = 0;
if($retenerIva){
$retenido['impuesto'] = '002';
$retenido['importe'] = round($ivaProds,3);
$datos['impuestos']['retenciones'][0] = $retenido;
$retenido_iva = $ivaProds;
}
if($retenerIsr){
$retenido2['impuesto'] = '001';
$retenido2['importe'] = round($subtotal_productos*$tasaIsr,3);
$datos['impuestos']['retenciones'][1] = $retenido2;
$retenido_isr = (float)$subtotal_productos*$tasaIsr;
}
$datos['factura']['subtotal'] = round($subtotal_productos,3);
$datos['factura']['total'] =round($subtotal_productos + $ivaProds - $retenido_iva - $retenido_isr ,3);

// Se agregan los Impuestos
$datos['impuestos']['translados'][0]['impuesto'] = '002';
$datos['impuestos']['translados'][0]['tasa'] = '0.160000';
$datos['impuestos']['translados'][0]['importe'] = round($ivaProds,3);
$datos['impuestos']['translados'][0]['TipoFactor'] = 'Tasa';


$datos['impuestos']['TotalImpuestosTrasladados'] =round($ivaProds,3);
if($retenido_iva==0&&$retenido_isr==0){
// other conditions may be met here

}else{
$datos['impuestos']['TotalImpuestosRetenidos'] =round($retenido_iva + $retenido_isr,3);
}
// Se ejecuta el SDK
$res = mf_genera_cfdi($datos);
$respuesta = array();
if($res['codigo_mf_numero']=='0'){

$tipoFactura = $_POST['tipoFactura'];


$uuid = $res['uuid'];
$jsonConceptos = json_encode($conceptos,JSON_UNESCAPED_UNICODE);
$query ="INSERT INTO `factura_generada`(`folio_factura`, `folio_impreso`, `idcliente`, `enviado`,
 `xml_file`, `montoTotal`,
  `uuid`, `fecha_timbrado`,
   `fecha_cancelada`, `tipo`, `json_conceptos`,version) VALUES ";
$query.=" ('$folio','$folio','$gs_idCliente','0','$xml_file','".$datos['factura']['total']."','$uuid',NOW(),'0000-00-00','$tipoFactura','$jsonConceptos','3.3')";
 $sql->ejecutarNoQuery($query);
$idSerie = $facturaParams['serie'];
$query ="UPDATE `factura_series` SET `folio_actual` = ? WHERE `idSerie` = ?";
$sql->execQueryBinders($query,array("ii",$folio,$idSerie));
$respuesta['exito'] = true;
$respuesta['msg']  =$res['codigo_mf_texto'];
$respuesta['dbErrors']=$sql->getErrorLog();
$respuesta['folio']=$folio;
$EmitterEmail = $emisor['email'];
$EmitterPhone = $emisor['telefono'];
$ReceptorEmail = $cliente['email'];
$ReceptorPhone = $cliente['telefono'];
    GeneratePDF("3.3","../logo/logo.png","timbrados/xml/".$xml_file,"timbrados/xml/".$qr,"timbrados/pdf/".$filePdf,$EmitterEmail,$EmitterPhone,$ReceptorEmail,$ReceptorPhone);

}else{
$respuesta['exito'] = false;
$respuesta['msg']  =$res['codigo_mf_texto'];
}

print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

?>