<?php
// Se desactivan los mensajes de debug
error_reporting(0);

// Se especifica la zona horaria
date_default_timezone_set('America/Mexico_City');

// Se incluye el SDK
require_once '../../sdk2.php';

// Se especifica la version de CFDi 3.3
$datos['version_cfdi'] = '3.3';

// SE ESPECIFICA EL COMPLEMENTO
$datos['complemento'] = 'certificadodestruccion10';

// Ruta del XML Timbrado
$datos['cfdi']='../../timbrados/ejemplo_factura_certificadodestruccion10.xml';

// Ruta del XML de Debug
$datos['xml_debug']='../../timbrados/debug_ejemplo_factura_certificadodestruccion10.xml';

// Credenciales de Timbrado
$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO';

// Rutas y clave de los CSD
$datos['conf']['cer'] = '../../certificados/AAA010101AAA.cer.pem';
$datos['conf']['key'] = '../../certificados/AAA010101AAA.key.pem';
$datos['conf']['pass'] = '12345678a';

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
$datos['factura']['subtotal'] = '100.00';
$datos['factura']['tipocambio'] = '1.0';
$datos['factura']['tipocomprobante'] = 'I';
$datos['factura']['total'] = '100.00';
$datos['factura']['RegimenFiscal'] = '601';

// Datos del Emisor
$datos['emisor']['rfc'] = 'AAA010101AAA'; //RFC DE PRUEBA
$datos['emisor']['nombre'] = 'ACCEM SERVICIOS EMPRESARIALES SC';  // EMPRESA DE PRUEBA

// Datos del Receptor
$datos['receptor']['rfc'] = 'XAXX010101000';
$datos['receptor']['nombre'] = 'Publico en General';
$datos['receptor']['UsoCFDI'] = 'G01';

// Se agregan los conceptos
for ($i = 1; $i <= 1; $i++)
{
    $datos['conceptos'][$i]['cantidad'] = '1.00';
    $datos['conceptos'][$i]['unidad'] = 'PZ';
    $datos['conceptos'][$i]['ID'] = "COD$i";
    $datos['conceptos'][$i]['descripcion'] = "PRODUCTO $i";
    $datos['conceptos'][$i]['valorunitario'] = '100.00';
    $datos['conceptos'][$i]['importe'] = '100.00';
    $datos['conceptos'][$i]['ClaveProdServ'] = '01010101';
    $datos['conceptos'][$i]['ClaveUnidad'] = 'C81';
}

// Se agregan los Impuestos
$datos['impuestos']['TotalImpuestosTrasladados'] = '0.00';
$datos['impuestos']['translados'][0]['impuesto'] = '003';
$datos['impuestos']['translados'][0]['tasa'] = '0.160000';
$datos['impuestos']['translados'][0]['importe'] = '0.00';
$datos['impuestos']['translados'][0]['TipoFactor'] = 'Tasa';

// Complemento Certificado de Destruccion 1.0
$datos['certificadodestruccion10']['Serie']='27833';
$datos['certificadodestruccion10']['NumFolDesVeh']='A1';
$datos['certificadodestruccion10']['VehiculoDestruido']['Marca']='ford';
$datos['certificadodestruccion10']['VehiculoDestruido']['TipooClase']='123456';
$datos['certificadodestruccion10']['VehiculoDestruido']['A�o']='2000';
$datos['certificadodestruccion10']['VehiculoDestruido']['Modelo']='FOB';
$datos['certificadodestruccion10']['VehiculoDestruido']['NIV']='te0';
$datos['certificadodestruccion10']['VehiculoDestruido']['NumSerie']='gera43';
$datos['certificadodestruccion10']['VehiculoDestruido']['NumPlacas']='1034';
$datos['certificadodestruccion10']['VehiculoDestruido']['NumMotor']='116';
$datos['certificadodestruccion10']['VehiculoDestruido']['NumFolTarjCir']='DESO801116HGTLRS08';
$datos['certificadodestruccion10']['InformacionAduanera']['NumPedImp']='HEUJ880222HOCTRR04';
$datos['certificadodestruccion10']['InformacionAduanera']['Fecha']='05-04-2017';
$datos['certificadodestruccion10']['InformacionAduanera']['Aduana']='123456789';

// Se ejecuta el SDK
$res = mf_genera_cfdi($datos);


///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////
 
echo "<h1>Respuesta Generar XML y Timbrado</h1>";
foreach($res AS $variable=>$valor)
{
    $valor=htmlentities($valor);
    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}



?>