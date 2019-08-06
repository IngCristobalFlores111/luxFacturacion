<?php
include("../php/functions.php");
include ("FacturaPDFGen.php");

$idCliente = $_POST['idCliente'];
$total = $_POST['total'];
$subtotal = $_POST['subtotal'];
$iva = $_POST['iva'];
$descuento = $_POST['descuento'];
$metodo_pago = $_POST['metodo_pago'];
$forma_pago = $_POST['forma_pago'];
$jsonFile = $_POST['jsonFile'];


error_reporting(0);

//include_once "cfdi32_multifacturas_encoded.php";
date_default_timezone_set('America/Mexico_City');

include_once "lib/cfdi32_multifacturas.php";

/////////////////////////////////////////////////////////////////////////////////
////////////     CREAR ARCHIVOS .PEM
/////////////////////////////////////////////////////////////////////////////////


$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]
$datos['conf']['cer'] = 'pruebas/aaa010101aaa.cer.pem';
$datos['conf']['key'] = 'pruebas/aaa010101aaa.key.pem';
$datos['conf']['pass'] = '12345678a';

$res = execResultSet("SELECT `folio_factura` FROM `factura_generada` ORDER BY `folio_factura` DESC LIMIT 1");
$folio_factura = $res[0]['folio_factura'] + 1;




//RUTA DONDE ALMACENARA EL CFDI
$date = date('Y_m_d');
$datos['cfdi']="timbrados/cfdi_".$folio_factura."_".$date.".xml";  // si se especifica este parametro, se timbrara la factura 

$datos['xml_debug']="timbrados/sin_timbrar_".$folio_factura."_".$date.".xml"; 

$xml_file = "sin_timbrar_".$idCliente."_".$data.".xml";


//OPCIONAL, UTILIZAR LA LIBRERIA PHP DE OPENSSL, DEFAULT SI
$datos['php_openssl']='SI';

$datos['factura']['serie'] = 'A'; //opcional
$datos['factura']['folio'] = $folio_factura;
$datos['factura']['fecha_expedicion'] = date('Y-m-d H:i:s',time()-120);// Opcional  "time()-120" para retrasar la hora 2 minutos para evitar falla de error en rango de fecha
$timestamp = time()-1800;

$datos['factura']['metodo_pago'] = $metodo_pago; // EFECTIV0, CHEQUE, TARJETA DE CREDITO, TRANSFERENCIA BANCARIA, NO IDENTIFICADO
$datos['factura']['forma_pago'] = $forma_pago;  //PAGO EN UNA SOLA EXHIBICION, CREDITO 7 DIAS, CREDITO 15 DIAS, CREDITO 30 DIAS, ETC
$datos['factura']['tipocomprobante'] = 'ingreso'; //ingreso, egreso
$datos['factura']['moneda'] = 'MXN'; // MXN USD EUR
$datos['factura']['tipocambio'] = '1.0000'; // OPCIONAL (MXN = 1.00, OTRAS EJ: USD = 13.45; EUR = 16.86)

$file_expedido = file_get_contents("../JSON/expedido.json");
$json_expedido = json_decode($file_expedido);
$datos['factura']['LugarExpedicion'] = $json_expedido->municipio . ",".$json_expedido->estado;




// datos de JSON 


// datos del emisor de la factura(Empresa que realiza la factura) 
$file_Emisor = file_get_contents("../JSON/emisor.json");
$json_emisor = json_decode($file_Emisor);

$EmitterEmail = $json_emisor->email;
$EmitterPhone = $json_emisor->telefono;


$datos['factura']['RegimenFiscal'] = $json_emisor->RegimenFiscal;


$datos['emisor']['rfc'] = $json_emisor->rfc;   
$datos['emisor']['nombre'] = $json_emisor->nombre;
$datos['emisor']['DomicilioFiscal']['calle'] = $json_emisor->calle;
$datos['emisor']['DomicilioFiscal']['noExterior'] = $json_emisor->noExterior;
$datos['emisor']['DomicilioFiscal']['noInterior'] = $json_emisor->noInterior;
$datos['emisor']['DomicilioFiscal']['colonia'] = $json_emisor->colonia;
$datos['emisor']['DomicilioFiscal']['localidad'] = $json_emisor->localidad;
$datos['emisor']['DomicilioFiscal']['municipio'] = $json_emisor->municipio; // o delegacion
$datos['emisor']['DomicilioFiscal']['estado'] = $json_emisor->estado;
$datos['emisor']['DomicilioFiscal']['pais'] = $json_emisor->pais;
$datos['emisor']['DomicilioFiscal']['CodigoPostal'] = $json_emisor->CodigoPostal;


// datos del lugar donde se expide la factura


$datos['emisor']['ExpedidoEn']['calle'] = $json_expedido->calle;
$datos['emisor']['ExpedidoEn']['noExterior'] = $json_expedido->noExterior;
$datos['emisor']['ExpedidoEn']['noInterior'] = $json_expedido->noInterior;
$datos['emisor']['ExpedidoEn']['colonia'] = $json_expedido->colonia;
$datos['emisor']['ExpedidoEn']['localidad'] = $json_expedido->localidad;
$datos['emisor']['ExpedidoEn']['municipio'] = $json_expedido->municipio;
$datos['emisor']['ExpedidoEn']['estado'] = $json_expedido->estado;
$datos['emisor']['ExpedidoEn']['pais'] = $json_expedido->pais;
$datos['emisor']['ExpedidoEn']['CodigoPostal'] = $json_expedido->CodigoPostal;

////




//Datos del cliente en cuestion

$query = "SELECT * FROM clientes_facturacion WHERE idcliente=$idCliente";
$cliente = execResultSet($query);

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
///////////////

// agregar conceptos




$file_content = file_get_contents("../JSON/Temp_json/$jsonFile.json");
$conceptos = json_decode($file_content,true);

foreach($conceptos['items'] as $data)
{
	unset($concepto);
	$concepto['cantidad'] = $data['cantidad'];
	$concepto['unidad'] = $data['medida'];
	//	$concepto['ID'] = $data['codigo'];
	$concepto['ID'] = 'N/A';
	$concepto['descripcion'] = $data['descripcion'];
	$concepto['valorunitario'] = $data['precio_unitario']; // SIN IVA
	$concepto['importe'] = $data['importe'];

	$datos['conceptos'][] = $concepto;
	
}

$datos['factura']['subtotal'] = $subtotal;

if($descuento>0)
$datos['factura']['descuento'] = $descuento; // descuento sin impuestos

$datos['factura']['total'] = $total; // total incluyendo impuestos



$translado1['impuesto'] = 'IVA';
$translado1['tasa'] = '16';
$translado1['importe'] = $iva;
$datos['impuestos']['translados'][0] = $translado1;



$res= cfdi_generar_xml($datos);

$saldo = $res['saldo'];





$timbrado = 0;


$pngFile = '';
$pdfFile = '';
if($res['cancelada']=='NO')
{
	$timbrado=1;
	$ruta = "cfdi_".$folio_factura."_".$date.".xml";
	$pngFile = "cfdi_".$folio_factura."_".$date.".png";
	$pdfFile = "cfdi_".$folio_factura."_".$date.".pdf";
	
	$xml_file = $ruta;
	
	$subtotal = round($subtotal,3);
	$iva = round($iva,3);
	$descuento = round($descuento,3);
	$total = round($total,3);

	
	//solo si la factura se genera de manera satisfactoria, es llenaran las tablas factura_generada y folio_nota_factura
	$query ="INSERT INTO `factura_generada`(`folio_factura`, `idcliente`, `timbrado`, `enviado`, `xml_file`, `TIMESTAMP`,`montoTotal`,`generica`) VALUES($folio_factura,$idCliente,$timbrado,0,'$xml_file',$timestamp,$total,1);";
	ejecutarSQLCommand($query);
	// eliminar archivo temporal de factura
	unlink('../JSON/Temp_json/'.$jsonFile.'.json');
	
	
	GeneratePDF('logo.png',"timbrados/$xml_file","timbrados/$pngFile","timbrados/$pdfFile",$EmitterEmail,$EmitterPhone,$correo_cliente,$ReceptorPhone);
      // facturacion satsifatoria....
	echo '{"respuesta_servidor":"'.$res['codigo_mf_texto'].'","result":1,"idcliente":'.$idCliente.',"folio_factura":'.$folio_factura.'}';

	
	
}
else  // facturacion no fue realizada con exito 
{
	echo '{"respuesta_servidor":"'.$res['codigo_mf_texto'].'","result":0,"idcliente":'.$idCliente.',"folio_factura":'.$folio_factura.'}';

	



}




?>