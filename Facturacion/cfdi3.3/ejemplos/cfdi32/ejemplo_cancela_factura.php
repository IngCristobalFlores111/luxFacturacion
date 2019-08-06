<?php

error_reporting(0);

date_default_timezone_set('America/Mexico_City');

include_once "../../sdk2.php";




/////////////////////////////////////////////////////////////////////////////////
////////////     CREAR ARCHIVOS .PEM
/////////////////////////////////////////////////////////////////////////////////

$datos['cancelar']='NO';
$datos['cfdi']='../../timbrados/ejemplo_cfdi33.xml';
$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]
$datos['conf']['cer'] = '../../certificados/lan7008173r5.cer';
$datos['conf']['key'] = '../../certificados/lan7008173r5.key';
$datos['conf']['pass'] = '12345678a';

$res= cfdi_cancelar($datos);


///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////
 
echo "<h1>Respuesta Generar XML y Timbrado</h1>";
foreach($res AS $variable=>$valor)
{
    $valor=htmlentities($valor);
    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}

?>
