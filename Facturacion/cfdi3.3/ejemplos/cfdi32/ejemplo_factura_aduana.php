<?php
error_reporting(0); // OPCIONAL DESACTIVA NOTIFICACIONES DE DEBUG
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
$datos['cfdi']='../../timbrados/cfdi_ejemplo_factura.xml';
// OPCIONAL GUARDAR EL XML GENERADO ANTES DE TIMBRARLO
$datos['xml_debug']='../../timbrados/sin_timbrar_ejemplo_factura.xml';

//OPCIONAL, ACTIVAR SOLO EN CASO DE CONFLICTOS
//$datos['remueve_acentos']='SI';

//OPCIONAL, UTILIZAR LA LIBRERIA PHP DE OPENSSL, DEFAULT SI
$datos['php_openssl']='SI';

$datos['factura']['serie'] = 'A'; //opcional
$datos['factura']['folio'] = '100'; //opcional
$datos['factura']['fecha_expedicion'] = date('Y-m-d\TH:i:s',time()-120);// Opcional  "time()-120" para retrasar la hora 2 minutos para evitar falla de error en rango de fecha


$datos['factura']['metodo_pago'] = 'EFECTIVO'; // EFECTIV0, CHEQUE, TARJETA DE CREDITO, TRANSFERENCIA BANCARIA, NO IDENTIFICADO
$datos['factura']['forma_pago'] = 'PAGO EN UNA SOLA EXHIBICION';  //PAGO EN UNA SOLA EXHIBICION, CREDITO 7 DIAS, CREDITO 15 DIAS, CREDITO 30 DIAS, ETC
$datos['factura']['tipocomprobante'] = 'ingreso'; //ingreso, egreso
$datos['factura']['moneda'] = 'MXN'; // MXN USD EUR
$datos['factura']['tipocambio'] = '1.0000'; // OPCIONAL (MXN = 1.00, OTRAS EJ: USD = 13.45; EUR = 16.86)
$datos['factura']['LugarExpedicion'] = 'MONTERREY, NUEVO LEON';
//$datos['factura']['NumCtaPago'] = '0234'; //opcional; 4 DIGITOS pero obligatorio en transferencias y cheques

$datos['factura']['RegimenFiscal'] = 'MI REGIMEN';

$datos['emisor']['rfc'] = 'LAN7008173R5'; //RFC DE PRUEBA  
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


// UN PRODUCTO CON DETALLES ADUANALES

    $datos['conceptos'][0]['unidad'] = 'PIEZA';
    $datos['conceptos'][0]['cantidad'] = 1;
    $datos['conceptos'][0]['ID'] = "COD";
    $datos['conceptos'][0]['descripcion'] = "PRODUCTO PRUEBA";
    $datos['conceptos'][0]['valorunitario'] = '1000.00'; // SIN IVA
    $datos['conceptos'][0]['importe'] = '1000.00';

    $datos['conceptos'][0]['numero'] = '123434755001456';
    $datos['conceptos'][0]['fecha'] = '2015-09-25';
    $datos['conceptos'][0]['aduana'] = 'NUEVO LAREDO';





$datos['factura']['subtotal'] = 1000.00; // sin impuestos
$datos['factura']['total'] = 1160.00; // total incluyendo impuestos



$translado1['impuesto'] = 'IVA';
$translado1['tasa'] = '16';
$translado1['importe'] = 160.00; // iva de los productos facturados
$datos['impuestos']['translados'][0] = $translado1;



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