<?php
// Se desactivan los mensajes de debug
error_reporting(~(E_WARNING|E_NOTICE));
//error_reporting(E_ALL);

// Se especifica la zona horaria
date_default_timezone_set('America/Mexico_City');

// Se incluye el SDK
require_once '../../sdk2.php';
////////////////////////////////////////////////////////////
////// PRUEBA DIVIDENDOS
////////////////////////////////////////////////////////////

//multifacturas_modo_pruebas();

$datos['cfdi']='C:\SDK2\timbrados\ejemplo_retencion_dividendos.xml';
$datos['remueve_acentos']='SI';
$datos['retencion']='SI';

$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]

$datos['conf']['cer'] = '../../certificados/AAA010101AAA.cer.pem';
$datos['conf']['key'] = '../../certificados/AAA010101AAA.key.pem';
$datos['conf']['pass'] = '12345678a';

//OPCIONAL, ACTIVAR SOLO EN CASO DE CONFLICTOS
//$datos['remueve_acentos']='SI';

//OPCIONAL, UTILIZAR LA LIBRERIA PHP DE OPENSSL, DEFAULT SI
$datos['php_openssl']='SI';


$datos['factura']['FolioInt'] = '001';
$datos['factura']['FechaExp'] = date('Y-m-d\TH:i:s',time()-120);
$datos['factura']['CveRetenc'] = '003';
$datos['factura']['DescRetenc'] = '004';

$datos['emisor']['RFCEmisor'] = 'a';
$datos['emisor']['NomDenRazSocE'] = 'a';
$datos['emisor']['CURPE'] = 'a';


$datos['receptor']['Nacionalidad'] = '';
$datos['receptor']['Nacional']['RFCReceptor'] = '';
$datos['receptor']['Nacional']['NomDesRazSocR'] = '';
$datos['receptor']['Nacional']['CURPR'] = '';


$datos['receptor']['Extranjero']['NumRegIdTrib'] = '';
$datos['receptor']['Extranjero']['NomDenRazSocR'] = '';

$datos['periodo']['MesIni'] = '';
$datos['periodo']['MesFin'] = '';
$datos['periodo']['Ejerc'] = '';

$datos['totales']['montoTotOperacion'] = '';
$datos['totales']['montoTotGrav'] = '';
$datos['totales']['montoTotExent'] = '';
$datos['totales']['montoTotRet'] = '';

//dividendos
$datos['dividendos']['DividOUtil']['CveTipDivOUtil']='04';
$datos['dividendos']['DividOUtil']['MontISRAcredRetMexico']='100.00';
$datos['dividendos']['DividOUtil']['MontISRAcredRetExtranjero']='200.00';
$datos['dividendos']['DividOUtil']['MontRetExtDivExt']='300.00';
$datos['dividendos']['DividOUtil']['TipoSocDistrDiv']='Sociedad Nacional';
$datos['dividendos']['DividOUtil']['MontISRAcredNal']='400.00';
$datos['dividendos']['DividOUtil']['MontDivAcumNal']='500.00';
$datos['dividendos']['DividOUtil']['MontDivAcumExt']='600.00';


$datos['dividendos']['Remanente']['ProporcionRem']='1000.00';



$res= cfdi_retenicion_generar_xml($datos,$produccion='NO');


echo "<h1>Respuesta </h1>";
foreach($res AS $variable=>$valor)
{
    $valor=htmlentities($valor);
    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}
?>