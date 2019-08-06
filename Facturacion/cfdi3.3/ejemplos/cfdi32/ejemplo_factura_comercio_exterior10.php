<?php

error_reporting(~E_NOTICE); // OPCIONAL DESACTIVA NOTIFICACIONES DE DEBUG
//include_once "cfdi32_multifacturas_encoded.php";
date_default_timezone_set('America/Mexico_City');

include_once "../../sdk2.php";

/////////////////////////////////////////////////////////////////////////////////
////////////     CREAR ARCHIVOS .PEM
/////////////////////////////////////////////////////////////////////////////////


$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]
$datos['conf']['cer'] = '../../certificados/lan7008173r5.cer.pem';
$datos['conf']['key'] = '../../certificados/lan7008173r5.key.pem';
$datos['conf']['pass'] = '12345678a';



//RUTA DONDE ALMACENARA EL CFDI
$datos['cfdi']='../../timbrados/cfdi_ejemplo_factura_comercio_exterior.xml';
// OPCIONAL GUARDAR EL XML GENERADO ANTES DE TIMBRARLO
$datos['xml_debug']='../../timbrados/sin_timbrar_ejemplo_factura_comercio_exterior.xml';

//OPCIONAL, ACTIVAR SOLO EN CASO DE CONFLICTOS
//$datos['remueve_acentos']='SI';

//OPCIONAL, UTILIZAR LA LIBRERIA PHP DE OPENSSL, DEFAULT SI
$datos['php_openssl']='SI';

$datos['factura']['serie'] = 'A'; //opcional
$datos['factura']['folio'] = '100'; //opcional
$datos['factura']['fecha_expedicion'] = date('Y-m-d H:i:s',time()-120);// Opcional  "time()-120" para retrasar la hora 2 minutos para evitar falla de error en rango de fecha


$datos['factura']['metodo_pago'] = '02'; // EFECTIV0, CHEQUE, TARJETA DE CREDITO, TRANSFERENCIA BANCARIA, NO IDENTIFICADO
$datos['factura']['forma_pago'] = 'PAGO EN UNA SOLA EXHIBICION';  //PAGO EN UNA SOLA EXHIBICION, CREDITO 7 DIAS, CREDITO 15 DIAS, CREDITO 30 DIAS, ETC
$datos['factura']['tipocomprobante'] = 'ingreso'; //ingreso, egreso
$datos['factura']['moneda'] = 'USD'; // MXN USD EUR
$datos['factura']['tipocambio'] = '18.6757'; // OPCIONAL (MXN = 1.00, OTRAS EJ: USD = 13.45; EUR = 16.86)
$datos['factura']['LugarExpedicion'] = 'MEX';
//$datos['factura']['NumCtaPago'] = '0234'; //opcional; 4 DIGITOS pero obligatorio en transferencias y cheques

$datos['factura']['RegimenFiscal'] = 'REGIMEN GENERAL DE LEY PERSONAS MORALES';

$datos['emisor']['rfc'] = 'LAN7008173R5'; //RFC DE PRUEBA  
$datos['emisor']['nombre'] = 'ACCEM SERVICIOS EMPRESARIALES SC';  // EMPRESA DE PRUEBA
$datos['emisor']['DomicilioFiscal']['calle'] = 'JUAREZ';
$datos['emisor']['DomicilioFiscal']['noExterior'] = '100';
$datos['emisor']['DomicilioFiscal']['noInterior'] = ''; //(opcional)
$datos['emisor']['DomicilioFiscal']['colonia'] = '0001';//'CENTRO';
$datos['emisor']['DomicilioFiscal']['localidad'] = '02';//MONTERREY';
$datos['emisor']['DomicilioFiscal']['municipio'] = '067';//Torreon'; // o delegacion
//https://es.wikipedia.org/wiki/ISO_3166-2:MX
$datos['emisor']['DomicilioFiscal']['estado'] = 'OAX';
//https://es.wikipedia.org/wiki/ISO_3166-2:MX
$datos['emisor']['DomicilioFiscal']['pais'] = 'MEX';
$datos['emisor']['DomicilioFiscal']['CodigoPostal'] = '68000'; // 5 digitos

//SI EX EXPEDIDO EN SUCURSAL CAMBIA EL DOMICILIO
//SI ES EN EL MISMO DOMICILIO REPETIR INFORMACION
$datos['emisor']['ExpedidoEn']['calle'] = 'HIDALGO';
$datos['emisor']['ExpedidoEn']['noExterior'] = '240';
$datos['emisor']['ExpedidoEn']['noInterior'] = ''; //(opcional)
$datos['emisor']['ExpedidoEn']['colonia'] = '0001';
$datos['emisor']['ExpedidoEn']['localidad'] = '02';
$datos['emisor']['ExpedidoEn']['municipio'] = '067';//'035'; // O DELEGACION
$datos['emisor']['ExpedidoEn']['estado'] = 'OAX';
$datos['emisor']['ExpedidoEn']['pais'] = 'MEX';
$datos['emisor']['ExpedidoEn']['CodigoPostal'] = '68000'; // 5 digitos

// IMPORTANTE PROBAR CON NOMBRE Y RFC REAL O GENERARA ERROR DE XML MAL FORMADO
$datos['receptor']['rfc'] = 'XEXX010101000';
$datos['receptor']['nombre'] = 'PUBLICO EN GENERAL';
//opcional
$datos['receptor']['Domicilio']['calle'] = 'PERIFERICO';
$datos['receptor']['Domicilio']['noExterior'] = '1024';
$datos['receptor']['Domicilio']['noInterior'] = 'B';
$datos['receptor']['Domicilio']['colonia'] = '3738';
$datos['receptor']['Domicilio']['localidad'] = 'CIUDAD DE MÉXICO';
$datos['receptor']['Domicilio']['municipio'] = 'ALVARO OBREGON';
$datos['receptor']['Domicilio']['estado'] = '';
$datos['receptor']['Domicilio']['pais'] = 'JPN';
$datos['receptor']['Domicilio']['CodigoPostal'] = '23010'; // 5 digitos

$datos['conceptos'][0]['cantidad'] = '180';
$datos['conceptos'][0]['unidad'] = '20';
$datos['conceptos'][0]['ID'] = '76515001191';
$datos['conceptos'][0]['descripcion'] = 'CHOCOLATE CREM CUP CAKES 360G/12.70Z SL';
$datos['conceptos'][0]['valorunitario'] = '10.93';
$datos['conceptos'][0]['importe'] = '1967.40';

$datos['conceptos'][1]['cantidad'] = '180';
$datos['conceptos'][1]['unidad'] = '20';
$datos['conceptos'][1]['ID'] = '76515001139';
$datos['conceptos'][1]['descripcion'] = 'DESVILS FOOD CREME CAKES 354G/12.50Z SL';
$datos['conceptos'][1]['valorunitario'] = '11.02';
$datos['conceptos'][1]['importe'] = '1983.60';

$datos['factura']['subtotal'] = '3951.00'; // sin impuestos
$datos['factura']['total'] = '3951.00'; // total incluyendo impuestos
$datos['moneda'] = 'USD';

//COMPLEMENTO COMERCIO EXTERIOR

$datos['comercio']['TipoOperacion']='2';
$datos['comercio']['ClaveDePedimento']='A1';
$datos['comercio']['CertificadoOrigen']='0';
//$datos['comercio']['NumCertificadoOrigen'] = '';
$datos['comercio']['NumeroExportadorConfiable']='34555888';
$datos['comercio']['Incoterm']='FCA';
$datos['comercio']['Subdivision']='0';
$datos['comercio']['Observaciones']='EJEMPLO FACTURA EXPORTACION';
$datos['comercio']['TipoCambioUSD']='18.6757';
$datos['comercio']['TotalUSD']='3951.00';

// Emisor
//$datos['comercio']['Emisor']['Curp']='DESO801116HGTLRS08'; // Opcional

// Receptor
//$datos['comercio']['Receptor']['Curp']='75-2491201';
$datos['comercio']['Receptor']['NumRegIdTrib']='75-2491201';

// Destinatario
$datos['comercio']['Destinatario']['NumRegIdTrib']='75-2491201';
//$datos['comercio']['Destinatario']['Rfc']='RFC Destinatario';
//$datos['comercio']['Destinatario']['Curp']='Curp Destinatario';
//$datos['comercio']['Destinatario']['Nombre']='Nombre Destinatario';
$datos['comercio']['Destinatario']['Domicilio']['Calle'] = '255 BUSINESS CENTER DRIVE';
//$datos['comercio']['Destinatario']['Domicilio']['NumeroExterior'] = 'Calle';
//$datos['comercio']['Destinatario']['Domicilio']['NumeroInterior'] = 'Calle';
//$datos['comercio']['Destinatario']['Domicilio']['Colonia'] = 'Calle';
//$datos['comercio']['Destinatario']['Domicilio']['Localidad'] = 'Calle';
//$datos['comercio']['Destinatario']['Domicilio']['Referencia'] = 'Calle';
//$datos['comercio']['Destinatario']['Domicilio']['Municipio'] = 'Calle';
$datos['comercio']['Destinatario']['Domicilio']['Estado'] = 'PA';
$datos['comercio']['Destinatario']['Domicilio']['Pais'] = 'USA';
$datos['comercio']['Destinatario']['Domicilio']['CodigoPostal'] = '19044-0000';

// Mercancias
$datos['comercio']['Mercancias'][0]['NoIdentificacion']='76515001191';
$datos['comercio']['Mercancias'][0]['FraccionArancelaria']='19059099';
//$datos['comercio']['Mercancias'][0]['CantidadAduana']='';
//$datos['comercio']['Mercancias'][0]['UnidadAduanaAduana']='';
//$datos['comercio']['Mercancias'][0]['ValorUnitarioAduana']='';
$datos['comercio']['Mercancias'][0]['ValorDolares']='1967.40';

$datos['comercio']['Mercancias'][1]['NoIdentificacion']='76515001139';
$datos['comercio']['Mercancias'][1]['FraccionArancelaria']='19059099';
$datos['comercio']['Mercancias'][1]['ValorDolares']='1983.60';

/*$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][0]['Marca']='YAMAHA';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][0]['Modelo']='DDA-21';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][0]['SubModelo']='DDD1';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][0]['NumeroSerie']='3292383823';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][1]['Marca']='str12342';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][1]['Modelo']='str12342';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][1]['SubModelo']='str12342';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][1]['NumeroSerie']='3292383823';*/


$res= mf_genera_cfdi($datos);

///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////
 
echo "<h1>Respuesta Generar XML y Timbrado</h1>";
foreach($res AS $variable=>$valor)
{
    $valor=htmlentities($valor, ENT_IGNORE);
    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}



?>
