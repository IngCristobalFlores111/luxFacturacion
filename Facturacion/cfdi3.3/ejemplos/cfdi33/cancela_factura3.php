<?php
/**
 * @author MultiFacturas.com
 * @copyright 2013
 * 
 * EL array $datos contiene la información de la factura a generar
 * 
 * GENERACION DEL CERTIFICADO Y LLAVE .PEM
 * ESTOS SE REQUIEREN PARA LA GENERACION DEL SELLO
 * SE RECOMIENDA GENERARLOS UNA SOLA VES Y ALMACENAR SU INFORMACION
 * PARA EVITAR PROCESAMIENTO ADICIONAL
 * 
 * $respuesta=certificado_pem($datos);
 * 
 * GENERA EL XML Y LO TIMBRA EN BASE A LA INFORMACION DEL ARREGLO $datos
 * 
 * VALIDADOR DE ESTRUCTURA DEL XML
 * https://www.consulta.sat.gob.mx/sicofi_web/moduloECFD_plus/ValidadorCFDI/Validador%20cfdi.html
 */
 
 
$tcer = $_GET["p40a"];
$tkey = $_GET["p40b"];
$pas = $_GET["p40c"];

$usuario_pac = $_GET["p59"];
$pass_pac = $_GET["p60"];

$num_fac = $_GET["pfac"];


 
error_reporting(~E_NOTICE);

date_default_timezone_set('America/Mexico_City');

include_once "sdk2.php";

include "lib/nusoap/nusoap.php";
//include "C:/Archivos de programa/NetMake/v9/wwwroot/clientes/luna/sdk2-R2/lib/nusoap/nusoap.php";

/////////////////////////////////////////////////////////////////////////////////
////////////     CREAR ARCHIVOS .PEM
/////////////////////////////////////////////////////////////////////////////////

$datos['cancelar']='NO';
$facturatimbrada="siafel_timbrados/FacturaNum".$num_fac.".xml";
//$datos['cfdi']=$facturatimbrada;
//$datos['cfdi']="C:/Archivos de programa/NetMake/v9/wwwroot/clientes/luna/sdk2-R2/siafel_timbrados/FacturaNum".$num_fac.".xml";
$datos['cfdi']="C:/Program Files/NetMake/v9/wwwroot/clientes/luna/sdk2-R2/siafel_timbrados/FacturaNum".$num_fac.".xml";

// Credenciales de Timbrado
$datos['PAC']['usuario'] = $usuario_pac; //DEMO700101XXX
$datos['PAC']['pass'] = $pass_pac; //DEMO700101XXX
$datos['PAC']['produccion'] = 'NO';

// Rutas y clave de los CSD
$datos['conf']['cer'] = 'certificados/'.$tcer; //AAA010101AAA.cer.pem
$datos['conf']['key'] = 'certificados/'.$tkey; //pemAAA010101AAA.key.pem
$datos['conf']['pass'] = $pas; //12345678a

multifacturas_modo_pruebas();
$res= cfdi_cancelar($datos);


///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////
 
echo "<h1>Respuesta Generar XML y Timbrado</h1>";
//foreach ($res AS $variable => $valor) {
    $valor = htmlentities($valor);
    $valor = str_replace('&lt;br/&gt;', '<br/>', $valor);
    echo "<b>[$variable]=</b>$valor<hr>";
	print_r($res);
//}

?>
