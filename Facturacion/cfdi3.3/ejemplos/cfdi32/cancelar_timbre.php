<?php


//include_once "/var/www/html/pagos_direc_nominas/TimbradoNomina/timbrado_nominas/sdk2/lib/nusoap/nusoap.php";
//include '/var/www/html/pagos_direc_nominas/TimbradoNomina/timbrado_nominas/sdk2/sdk2.php';
include_once "../../lib/nusoap/nusoap.php";		
include_once "../../sdk2.php";			

$datos['cancelar']='SI';
$datos['cfdi']='../../timbrados/TAJE910815MHGBML07.xml';;
$datos['PAC']['usuario'] = "MOLR831010S65";
$datos['PAC']['pass'] = "MOLR831010HHGDRL09";
$datos['PAC']['produccion'] = 'SI'; //   [SI|NO]
$datos['conf']['cer'] = '../../certificados/00001000000404359662.cer.pem';
$datos['conf']['key'] = '../../certificados/00001000000404359662.cer.pem';
$datos['conf']['pass'] = 'molr831010';


//multifacturas_modo_pruebas();

/*print_r($datos);*/
$res= cfdi_cancelar($datos);


echo "<pre>";
print_r($res);
echo "<pre>";


///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////
 
	echo "<h1>Respuesta Generar XML y Timbrado</h1>";
	foreach($res AS $variable=>$valor)
	{
	    $valor=htmlentities($valor);
	    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
	    echo "<b>[$variable]=</b>$valor<hr>";
	}



?>
