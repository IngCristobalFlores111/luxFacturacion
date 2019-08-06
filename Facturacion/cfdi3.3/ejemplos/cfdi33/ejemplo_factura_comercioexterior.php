<?php
/**
 * @author MultiFacturas.com
 * @copyright 2014
 * 
 * EL array $datos contiene la información de la factura a generar
 * 
 * GENERA EL XML Y LO TIMBRA EN BASE A LA INFORMACION DEL ARREGLO $datos
 * 
 * VALIDADOR DE ESTRUCTURA DEL XML
 * https://www.consulta.sat.gob.mx/sicofi_web/moduloECFD_plus/ValidadorCFDI/Validador%20cfdi.html
 * 
 * PARA NOTA DE CREDITO SOLO CAMBIA EL PARAMETRO $datos['factura']['tipocomprobante'] a egreso
 * 
 * EN ALGUNOS EJEMPLOS SON ILUSTRATIVOS DE LOS PARAMETROS, ASI QUE LOS MONTOS NO CONCORDARAN
 * 
 */


error_reporting(0); // OPCIONAL DESACTIVA NOTIFICACIONES DE DEBUG
date_default_timezone_set('America/Mexico_City');
include_once "lib/cfdi32_multifacturas.php";

/////////////////////////////////////////////////////////////////////////////////
////////////     CREAR ARCHIVOS .PEM
/////////////////////////////////////////////////////////////////////////////////


$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]
$datos['conf']['cer'] = '../../certificados/AAA010101AAA.cer.pem';
$datos['conf']['key'] = '../../certificados/AAA010101AAA.key.pem';
$datos['conf']['pass'] = '12345678a';



//RUTA DONDE ALMACENARA EL CFDI
$datos['cfdi']='../../timbrados/ejemplo_factura_comercioexterior.xml';
// OPCIONAL GUARDAR EL XML GENERADO ANTES DE TIMBRARLO
$datos['xml_debug']='../../timbrados/debug_ejemplo_factura_comercioexterior.xml';

//OPCIONAL, ACTIVAR SOLO EN CASO DE CONFLICTOS
//$datos['remueve_acentos']='SI';

//OPCIONAL, UTILIZAR LA LIBRERIA PHP DE OPENSSL, DEFAULT SI
$datos['php_openssl']='SI';

$datos['factura']['serie'] = 'A'; //opcional
$datos['factura']['folio'] = '100'; //opcional
$datos['factura']['fecha_expedicion'] = date('Y-m-d H:i:s',time()-120);// Opcional  "time()-120" para retrasar la hora 2 minutos para evitar falla de error en rango de fecha


$datos['factura']['metodo_pago'] = '01'; // EFECTIV0, CHEQUE, TARJETA DE CREDITO, TRANSFERENCIA BANCARIA, NO IDENTIFICADO
$datos['factura']['forma_pago'] = 'PAGO EN UNA SOLA EXHIBICION';  //PAGO EN UNA SOLA EXHIBICION, CREDITO 7 DIAS, CREDITO 15 DIAS, CREDITO 30 DIAS, ETC
$datos['factura']['tipocomprobante'] = 'ingreso'; //ingreso, egreso
$datos['factura']['moneda'] = 'MXN'; // MXN USD EUR
$datos['factura']['tipocambio'] = '1.0000'; // OPCIONAL (MXN = 1.00, OTRAS EJ: USD = 13.45; EUR = 16.86)
$datos['factura']['LugarExpedicion'] = 'MONTERREY, NUEVO LEON';
//$datos['factura']['NumCtaPago'] = '0234'; //opcional; 4 DIGITOS pero obligatorio en transferencias y cheques

$datos['factura']['RegimenFiscal'] = 'MI REGIMEN';

$datos['emisor']['rfc'] = 'AAA010101AAA'; //RFC DE PRUEBA  
$datos['emisor']['nombre'] = 'ACCEM SERVICIOS EMPRESARIALES SC';  // EMPRESA DE PRUEBA
$datos['emisor']['DomicilioFiscal']['calle'] = 'JUAREZ';
$datos['emisor']['DomicilioFiscal']['noExterior'] = '100';
$datos['emisor']['DomicilioFiscal']['noInterior'] = ''; //(opcional)
$datos['emisor']['DomicilioFiscal']['colonia'] = 'CENTRO';
$datos['emisor']['DomicilioFiscal']['localidad'] = 'MONTERREY';
$datos['emisor']['DomicilioFiscal']['municipio'] = 'MONTERREY'; // o delegacion
$datos['emisor']['DomicilioFiscal']['estado'] = 'NUEVO LEON';
$datos['emisor']['DomicilioFiscal']['pais'] = 'MEXICO';
$datos['emisor']['DomicilioFiscal']['CodigoPostal'] = '01234'; // 5 digitos

//SI EX EXPEDIDO EN SUCURSAL CAMBIA EL DOMICILIO
//SI ES EN EL MISMO DOMICILIO REPETIR INFORMACION
$datos['emisor']['ExpedidoEn']['calle'] = 'HIDALGO';
$datos['emisor']['ExpedidoEn']['noExterior'] = '240';
$datos['emisor']['ExpedidoEn']['noInterior'] = ''; //(opcional)
$datos['emisor']['ExpedidoEn']['colonia'] = 'LAS CUMBRES 3 SECTOR';
$datos['emisor']['ExpedidoEn']['localidad'] = 'MONTERREY';
$datos['emisor']['ExpedidoEn']['municipio'] = 'MONTERREY'; // O DELEGACION
$datos['emisor']['ExpedidoEn']['estado'] = 'NUEVO LEON';
$datos['emisor']['ExpedidoEn']['pais'] = 'MEXICO';
$datos['emisor']['ExpedidoEn']['CodigoPostal'] = '64610'; // 5 digitos

// IMPORTANTE PROBAR CON NOMBRE Y RFC REAL O GENERARA ERROR DE XML MAL FORMADO
$datos['receptor']['rfc'] = 'SOHM7509289MA';
$datos['receptor']['nombre'] = 'MIGUEL ANGEL SOSA HERNANDEZ';
//opcional
$datos['receptor']['Domicilio']['calle'] = 'PERIFERICO';
$datos['receptor']['Domicilio']['noExterior'] = '1024';
$datos['receptor']['Domicilio']['noInterior'] = 'B';
$datos['receptor']['Domicilio']['colonia'] = 'SAN ANGEL';
$datos['receptor']['Domicilio']['localidad'] = 'CIUDAD DE MÉXICO';
$datos['receptor']['Domicilio']['municipio'] = 'ALVARO OBREGON';
$datos['receptor']['Domicilio']['estado'] = 'DISTRITO FEDERAL';
$datos['receptor']['Domicilio']['pais'] = 'MEXICO';
$datos['receptor']['Domicilio']['CodigoPostal'] = '23010'; // 5 digitos

//AGREGAR 10 CONCEPTOS DE PRUEBA
for ($i = 1; $i < 11; $i++) {
    unset($concepto);
    $concepto['cantidad'] = 1;
    $concepto['unidad'] = 'PIEZA';
    $concepto['ID'] = "COD$i"; //ID, REF, CODIGO O SKU DEL PRODUCTO
//    $concepto['descripcion'] = "PRODUCTO PRUEBA > '$i'";
    $concepto['descripcion'] = "PRODUCTO PRUEBA 2 para el rfc OOYL940109213 $i";
    $concepto['valorunitario'] = '100.00'; // SIN IVA
    $concepto['importe'] = '100.00';

    $datos['conceptos'][] = $concepto;
}

$datos['factura']['subtotal'] = 1100.00; // sin impuestos
$datos['factura']['descuento'] = 100.00; // descuento sin impuestos
$datos['factura']['total'] = 1160.00; // total incluyendo impuestos



$translado1['impuesto'] = 'IVA';
$translado1['tasa'] = '16';
$translado1['importe'] = 160.00; // iva de los productos facturados
$datos['impuestos']['translados'][0] = $translado1;

$datos['comercio']['TipoOperacion']='2';
$datos['comercio']['ClaveDePedimento']='A1';
$datos['comercio']['CertificadoOrigen']='0';
$datos['comercio']['NumCertificadoOrigen']='123456';
$datos['comercio']['NumeroExportadorConfiable']='0';
$datos['comercio']['Incoterm']='FOB';
$datos['comercio']['Subdivision']='0';
$datos['comercio']['Observaciones']='detalles';
$datos['comercio']['TipoCambioUSD']='10.23';
$datos['comercio']['TotalUSD']='116.00';

$datos['comercio']['Emisor']['Curp']='DESO801116HGTLRS08';

$datos['comercio']['Receptor']['Curp']='HEUJ880222HOCTRR04';
$datos['comercio']['Receptor']['NumRegIdTrib']='123456789';

$datos['comercio']['Destinatario']['NumRegIdTrib']='123456789';
$datos['comercio']['Destinatario']['Rfc']='ESI920427886';
$datos['comercio']['Destinatario']['Curp']='HIOK800212HOCTRR01';
$datos['comercio']['Destinatario']['Nombre']='str1234';
$datos['comercio']['Destinatario']['Domicilio']['Calle']='calle de pruebas';
$datos['comercio']['Destinatario']['Domicilio']['NumeroExterior']='1234';
$datos['comercio']['Destinatario']['Domicilio']['NumeroInterior']='str1234';
$datos['comercio']['Destinatario']['Domicilio']['Colonia']='0995';
$datos['comercio']['Destinatario']['Domicilio']['Localidad']='11';
$datos['comercio']['Destinatario']['Domicilio']['Referencia']='str1234';
$datos['comercio']['Destinatario']['Domicilio']['Municipio']='035';
$datos['comercio']['Destinatario']['Domicilio']['Estado']='COA';
$datos['comercio']['Destinatario']['Domicilio']['Pais']='MEX';
$datos['comercio']['Destinatario']['Domicilio']['CodigoPostal']='27000';


$datos['comercio']['Mercancias'][0]['NoIdentificacion']='A-1215';
$datos['comercio']['Mercancias'][0]['FraccionArancelaria']='01011001';
$datos['comercio']['Mercancias'][0]['CantidadAduana']='1.0';
$datos['comercio']['Mercancias'][0]['UnidadAduana']='1';
$datos['comercio']['Mercancias'][0]['ValorUnitarioAduana']='116.00';
$datos['comercio']['Mercancias'][0]['ValorDolares']='116.00';

$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][0]['Marca']='YAMAHA';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][0]['Modelo']='DDA-21';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][0]['SubModelo']='DDD1';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][0]['NumeroSerie']='3292383823';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][1]['Marca']='str12342';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][1]['Modelo']='str12342';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][1]['SubModelo']='str12342';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][1]['NumeroSerie']='3292383823';

$res= cfdi_generar_xml($datos);


///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////
 
echo "<h1>Respuesta Generar XML y Timbrado</h1>";
foreach($res AS $variable=>$valor)
{
    $valor=htmlentities($valor);
    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}



?>