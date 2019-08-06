<?php
// Se desactivan los mensajes de debug
error_reporting(~(E_WARNING|E_NOTICE));
//error_reporting(E_ALL);

// Se especifica la zona horaria
date_default_timezone_set('America/Mexico_City');

// Se incluye el SDK
require_once '../../sdk2.php';

// Se especifica la version de CFDi 3.3
$datos['version_cfdi'] = '3.3';

// Ruta del XML Timbrado
$datos['cfdi']='../../timbrados/cfdi_ejemplo_factura_33.xml';

// Ruta del XML de Debug
$datos['xml_debug']='../../timbrados/sin_timbrar_ejemplo_factura_33.xml';


$datos['PAC']['usuario'] = 'IIDE660331UU7';
$datos['PAC']['pass'] = 'MULTI6d9180f6da47e579158f09226afeea12!';
$datos['PAC']['produccion'] = 'SI'; //   [SI|NO]
$datos['conf']['cer'] ="../../pruebas/00001000000401234457.cer";
$datos['conf']['key'] = "../../pruebas/CSD_F_IIDE660331UU7_20160121_111926.key";
$datos['conf']['pass'] = 'VENCEDOR';

// Datos de la Factura
$datos['factura']['condicionesDePago'] = 'CONDICIONES';
$datos['factura']['descuento'] = '0.00';
$datos['factura']['fecha_expedicion'] = date('Y-m-d\TH:i:s', time() - 120);
$datos['factura']['folio'] = '100';
$datos['factura']['forma_pago'] = '01';
$datos['factura']['LugarExpedicion'] = '45079';
$datos['factura']['metodo_pago'] = 'PUE';
$datos['factura']['moneda'] = 'MXN';
$datos['factura']['serie'] = 'A';
$datos['factura']['subtotal'] = 1.0;
$datos['factura']['tipocambio'] = 1.00;
$datos['factura']['tipocomprobante'] = 'I';
$datos['factura']['total'] = 1.16;
$datos['factura']['RegimenFiscal'] = '621';

// Datos del Emisor
$datos['emisor']['rfc'] = 'IIDE660331UU7'; //RFC DE PRUEBA
$datos['emisor']['nombre'] = 'MARIA ELENA INIGUEZ DIAZ DE LEON';  // EMPRESA DE PRUEBA
$datos['emisor']['DomicilioFiscal']['calle'] = "GUACAMAYA";
$datos['emisor']['DomicilioFiscal']['noExterior'] =  "1758";
$datos['emisor']['DomicilioFiscal']['noInterior'] = "";
$datos['emisor']['DomicilioFiscal']['colonia'] ="MIRADOR DE SAN ISIDRO";
$datos['emisor']['DomicilioFiscal']['localidad'] =  "Zapopan";
$datos['emisor']['DomicilioFiscal']['municipio'] = "Zapopan";
$datos['emisor']['DomicilioFiscal']['estado'] ="Jalisco";
$datos['emisor']['DomicilioFiscal']['pais'] ="Mexico";
$datos['emisor']['DomicilioFiscal']['CodigoPostal'] = "45133";

//SI EX EXPEDIDO EN SUCURSAL CAMBIA EL DOMICILIO
//SI ES EN EL MISMO DOMICILIO REPETIR INFORMACION
$datos['emisor']['ExpedidoEn']['calle'] = "GUACAMAYA";
$datos['emisor']['ExpedidoEn']['noExterior'] = "1758";
$datos['emisor']['ExpedidoEn']['noInterior'] = "";
$datos['emisor']['ExpedidoEn']['colonia'] =  "MIRADOR DE SAN ISIDRO";
$datos['emisor']['ExpedidoEn']['localidad'] =  "Zapopan";
$datos['emisor']['ExpedidoEn']['municipio'] = "Zapopan";
$datos['emisor']['ExpedidoEn']['estado'] =  "Jalisco";
$datos['emisor']['ExpedidoEn']['pais'] = "Mexico";
$datos['emisor']['ExpedidoEn']['CodigoPostal'] =  "45133";

// Datos del Receptor
$datos['receptor']['rfc'] = 'XAXX010101000';
$datos['receptor']['nombre'] = 'publico general';
$datos['receptor']['UsoCFDI'] = 'G02';
$datos['receptor']['Domicilio']['calle'] = "Madero";
$datos['receptor']['Domicilio']['noExterior'] = "111";
$datos['receptor']['Domicilio']['noInterior'] ="1";
$datos['receptor']['Domicilio']['colonia'] = "de la partia";
$datos['receptor']['Domicilio']['localidad'] = "Zapopan";
$datos['receptor']['Domicilio']['municipio'] = "Zapopan";
$datos['receptor']['Domicilio']['estado'] ="Jalisco";
$datos['receptor']['Domicilio']['pais'] = "Mexico";
$datos['receptor']['Domicilio']['CodigoPostal'] ="45133";


// Se agregan los conceptos

$datos['conceptos'][0]['cantidad'] = 1.00;
$datos['conceptos'][0]['unidad'] = 'NA';
$datos['conceptos'][0]['ID'] = "1726";
$datos['conceptos'][0]['descripcion'] = "prueba";
$datos['conceptos'][0]['valorunitario'] = 1.0;
$datos['conceptos'][0]['importe'] = 1.0;
$datos['conceptos'][0]['ClaveProdServ'] = '01010101';
$datos['conceptos'][0]['ClaveUnidad'] = 'ACT';

$datos['conceptos'][0]['Impuestos']['Traslados'][0]['Base'] = 1.0;
$datos['conceptos'][0]['Impuestos']['Traslados'][0]['Impuesto'] = '002';
$datos['conceptos'][0]['Impuestos']['Traslados'][0]['TipoFactor'] = 'Tasa';
$datos['conceptos'][0]['Impuestos']['Traslados'][0]['TasaOCuota'] = '0.16';
$datos['conceptos'][0]['Impuestos']['Traslados'][0]['Importe'] = 0.16;





// Se agregan los Impuestos
$datos['impuestos']['translados'][0]['impuesto'] = '002';
$datos['impuestos']['translados'][0]['tasa'] = '0.16';
$datos['impuestos']['translados'][0]['importe'] = 0.16;
$datos['impuestos']['translados'][0]['TipoFactor'] = 'Tasa';


$datos['impuestos']['TotalImpuestosTrasladados'] = 0.16;


// Se ejecuta el SDK
$res = mf_genera_cfdi($datos);

///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////
/*echo "<pre>";
print_r($datos);
echo "</pre>";*/
echo "<h1>Respuesta Generar XML y Timbrado</h1>";
foreach ($res AS $variable => $valor) {
    $valor = htmlentities($valor);
    $valor = str_replace('&lt;br/&gt;', '<br/>', $valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}