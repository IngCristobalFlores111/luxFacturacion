<?php

include ("../pdfWriter/FacturaPDFGen.php");

include("../php/functions.php");
//error_reporting(0);




//include_once "cfdi32_multifacturas_encoded.php";
date_default_timezone_set('America/Mexico_City');

include_once "lib/cfdi32_multifacturas.php";
$gs_idCliente = $_POST['idCliente'];
$gs_facturaParams = $_POST['factura_params'];
$folio = $_POST['folio_factura'];
$gj_facturaParams =json_decode($gs_facturaParams);
$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");

$result = $objSQL->executeQuery("SELECT SUM(`importe`) AS subtotal FROM `folio_conceptos` WHERE `folio_factura`='$folio'");



	$subtotal = (float)$result[0]['subtotal'];	
	$iva = $subtotal*0.16;
	$total = ($subtotal) + ($iva);
	

/////////////////////////////////////////////////////////////////////////////////
////////////     CREAR ARCHIVOS .PEM
/////////////////////////////////////////////////////////////////////////////////



$result =$objSQL->executeQuery("SELECT `archivo_cer`,`archivo_key` FROM `user_config`");
$fileKey = $result[0]['archivo_key'];
$fileCer =$result[0]['archivo_cer'];

$gs_idExpedicion = $_POST['idExpedicion'];

$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]
$datos['conf']['cer'] ="pruebas/$fileCer";
$datos['conf']['key'] = "pruebas/$fileKey";
//$datos['conf']['cer'] = 'pruebas/CSD01_AAA010101AAA.cer';
//$datos['conf']['key'] = 'pruebas/CSD01_AAA010101AAA.key';
$datos['conf']['pass'] = '12345678a';




//RUTA DONDE ALMACENARA EL CFDI
$date = date('Y_m_d');
$datos['cfdi']="facturas/timbradas/xml/".$gs_idCliente."_".$date."_".$folio.".xml";  // si se especifica este parametro, se timbrara la factura 

$datos['xml_debug']="facturas/sin_timbrar/sin_timbrar_".$gs_idCliente."_".$date."_".$folio.".xml";
 
$xml_file = $gs_idCliente."_".$date."_".$folio;


//OPCIONAL, UTILIZAR LA LIBRERIA PHP DE OPENSSL, DEFAULT SI
$datos['php_openssl']='SI';

$datos['factura']['serie'] = 'A'; //opcional
$datos['factura']['folio'] = $folio;
$datos['factura']['fecha_expedicion'] = date('Y-m-d H:i:s',time()-120);// Opcional  "time()-120" para retrasar la hora 2 minutos para evitar falla de error en rango de fecha


$datos['factura']['metodo_pago'] = $gj_facturaParams->metodo_pago; // EFECTIV0, CHEQUE, TARJETA DE CREDITO, TRANSFERENCIA BANCARIA, NO IDENTIFICADO
$datos['factura']['forma_pago'] =  $gj_facturaParams->forma_pago;  //PAGO EN UNA SOLA EXHIBICION, CREDITO 7 DIAS, CREDITO 15 DIAS, CREDITO 30 DIAS, ETC
$datos['factura']['tipocomprobante'] = 'ingreso'; //ingreso, egreso
$datos['factura']['moneda'] = 'MXN'; // MXN USD EUR
$datos['factura']['tipocambio'] = '1.0000'; // OPCIONAL (MXN = 1.00, OTRAS EJ: USD = 13.45; EUR = 16.86)


$query = "SELECT * FROM `lugar_expedicion` WHERE idcliente=$gs_idCliente AND idexpedicion=$gs_idExpedicion";
$lugar_expedicion = $objSQL->executeQuery($query);
$lugar_expedicion = $lugar_expedicion[0];

$datos['factura']['LugarExpedicion'] =$lugar_expedicion['municipio'].",".$lugar_expedicion['estado'];
$query ="SELECT * FROM `usuario_facturacion`";
$result = $objSQL->executeQuery($query);

$datos['factura']['RegimenFiscal'] = $result[0]['regimen'];

$EmitterEmail = $result[0]['email'];
$EmitterPhone =  $result[0]['telefono'];   
$datos['emisor']['rfc'] =  $result[0]['RFC'];   
$datos['emisor']['nombre'] =  $result[0]['nombre']; 
$datos['emisor']['DomicilioFiscal']['calle'] = $result[0]['calle']; 
$datos['emisor']['DomicilioFiscal']['noExterior'] =  $result[0]['noExterior']; ;
$datos['emisor']['DomicilioFiscal']['noInterior'] =  $result[0]['noInterior']; ;
$datos['emisor']['DomicilioFiscal']['colonia'] =  $result[0]['colonia']; 
$datos['emisor']['DomicilioFiscal']['localidad'] = $result[0]['localidad']; 
$datos['emisor']['DomicilioFiscal']['municipio'] = $result[0]['municipio'];  // o delegacion
$datos['emisor']['DomicilioFiscal']['estado'] = $result[0]['estado']; 
$datos['emisor']['DomicilioFiscal']['pais'] = $result[0]['pais']; 
$datos['emisor']['DomicilioFiscal']['CodigoPostal'] =  $result[0]['CodigoPostal']; 








$datos['emisor']['ExpedidoEn']['calle'] = $lugar_expedicion['calle'];
$datos['emisor']['ExpedidoEn']['noExterior'] = $lugar_expedicion['noExt'];
$datos['emisor']['ExpedidoEn']['noInterior'] = $lugar_expedicion['noInt'];
$datos['emisor']['ExpedidoEn']['colonia'] =  $lugar_expedicion['colonia'];
$datos['emisor']['ExpedidoEn']['localidad'] = $lugar_expedicion['localidad'];
$datos['emisor']['ExpedidoEn']['municipio'] = $lugar_expedicion['municipio'];
$datos['emisor']['ExpedidoEn']['estado'] =$lugar_expedicion['estado'];
$datos['emisor']['ExpedidoEn']['pais'] = 'MEXICO';
$datos['emisor']['ExpedidoEn']['CodigoPostal'] = $lugar_expedicion['codigoPostal'];

////




//Datos del cliente en cuestion

$query = "SELECT * FROM clientes_facturacion WHERE idcliente=$gs_idCliente";
$cliente = $objSQL->executeQuery($query);


$nombre_cliente = trim($cliente[0]['nombre']);
$rfcCliente =trim($cliente[0]['RFC']);
$correo_cliente = trim($cliente[0]['email']);
$ReceptorPhone = trim($cliente[0]['telefono']);


$datos['receptor']['rfc'] = trim($cliente[0]['RFC']);
$datos['receptor']['nombre'] = trim($cliente[0]['nombre']);
$datos['receptor']['Domicilio']['calle'] = trim($cliente[0]['calle']);
$datos['receptor']['Domicilio']['noExterior'] = trim($cliente[0]['noExterior']);
$datos['receptor']['Domicilio']['noInterior'] = trim($cliente[0]['noInterior']);
$datos['receptor']['Domicilio']['colonia'] = trim($cliente[0]['colonia']);
$datos['receptor']['Domicilio']['localidad'] = trim($cliente[0]['localidad']);
$datos['receptor']['Domicilio']['municipio'] = trim($cliente[0]['municipio']);
$datos['receptor']['Domicilio']['estado'] = trim($cliente[0]['estado']);
$datos['receptor']['Domicilio']['pais'] = trim($cliente[0]['pais']);
$datos['receptor']['Domicilio']['CodigoPostal'] = trim($cliente[0]['CodigoPostal']);
$ReceptorEmail =$correo_cliente; 
///////////////

// agregar conceptos

$query = "SELECT * FROM `folio_conceptos` WHERE folio_factura=$folio";
$result = $objSQL->executeQuery($query);

foreach($result as $c)
{
	unset($concepto);
	$concepto['cantidad'] = $c['cantidad'];
	$concepto['unidad'] = $c['unidad'];
	$concepto['ID'] = $c['id_concepto'];
	
	$concepto['descripcion'] = $c['descripcion'];
	$concepto['valorunitario'] = $c['precio_unitario'];
	$concepto['importe'] = $c['importe'];

	$datos['conceptos'][] = $concepto;
}



$datos['factura']['subtotal'] = $subtotal;
$descuento = 0;
if($gj_facturaParams->descuento!=''){$descuento =(float)$gj_facturaParams->descuento;$total = $total-$descuento;}
if($descuento>0){$datos['factura']['descuento'] = $descuento;}
$datos['factura']['total'] = $total; // total incluyendo impuestos



$translado1['impuesto'] = 'IVA';
$translado1['tasa'] = '16';
$translado1['importe'] = $iva;
$datos['impuestos']['translados'][0] = $translado1;



$res= cfdi_generar_xml($datos);
$res_corta = $res['codigo_mf_texto'];
$jRespuesta = '';
if($res['cancelada']=='NO')  // fue correctamente timbrada
{
	$uuid = $res['uuid'];

	$jRespuesta = '{"status":1,"respuesta":"'.$res_corta.'","folio":"'.$folio.'"}';
	$tmp = explode(".xml",$xml_file);
	$qr = $tmp[0].".png";
	$filePdf = "facturas/timbradas/pdf/".$tmp[0].".pdf";
	       $xml_file = $xml_file.".xml";
	GeneratePDF("logo/logo.png","facturas/timbradas/xml/".$xml_file,"facturas/timbradas/xml/".$qr,$filePdf,$EmitterEmail,$EmitterPhone,$ReceptorEmail,$ReceptorPhone);
$query ="INSERT INTO `factura_generada`(`folio_factura`, `idcliente`, `enviado`, `xml_file`, `fecha_timbrado`, `fecha_cancelada`, `montoTotal`, `uuid`) VALUES ('$folio','$gs_idCliente','1','$xml_file','$date','','$total','$uuid')";
	$objSQL->executeCommand($query);
	//echo $query;
	
	// borrar de factura pendiente-....
	$query ="DELETE FROM `factura_pendiente` WHERE folio_factura=$folio;";
	$query =$query."DELETE FROM `folio_conceptos` WHERE folio_factura=$folio;";
	
	$objSQL->executeCommand($query);

		
	
	
}else
{
	
	$res_corta = str_replace("'"," ",$res_corta);
	$jRespuesta = '{"status":0,"respuesta":"'.$res_corta.'","folio":"'.$folio.'"}';
	
}
echo $jRespuesta;




?>