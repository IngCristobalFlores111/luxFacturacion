<?php
error_reporting(0);
include("../php/functions.php");
include ("FacturaPDFGen.php");


//$metodo_pago = $_POST['metodo_pago'];
$subtotal = $_POST['subtotal'];
$iva = $_POST['iva'];
$total = $_POST['total'];
$descripcion = $_POST['descripcion'];


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
$datos['cfdi']="timbrados/cfdi_publico_general_".$folio_factura."_".$date.".xml";  // si se especifica este parametro, se timbrara la factura 

$datos['xml_debug']="timbrados/sin_timbrar_publico_general_".$folio_factura."_".$date.".xml"; 
 
$xml_file = "sin_timbrar_publico_general".$date.".xml";


//OPCIONAL, UTILIZAR LA LIBRERIA PHP DE OPENSSL, DEFAULT SI
$datos['php_openssl']='SI';

$datos['factura']['serie'] = 'A'; //opcional
$datos['factura']['folio'] = $folio_factura;
$datos['factura']['fecha_expedicion'] = date('Y-m-d H:i:s',time()-120);// Opcional  "time()-120" para retrasar la hora 2 minutos para evitar falla de error en rango de fecha
$timestamp = time()-1800;

$datos['factura']['metodo_pago'] = 'EFECTIVO'; // EFECTIV0, CHEQUE, TARJETA DE CREDITO, TRANSFERENCIA BANCARIA, NO IDENTIFICADO
$datos['factura']['forma_pago'] = 'PAGO EN UNA SOLA EXHIBICION';  //PAGO EN UNA SOLA EXHIBICION, CREDITO 7 DIAS, CREDITO 15 DIAS, CREDITO 30 DIAS, ETC
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



$nombre_cliente = "PUBLICO GENERAL";


$rfcCliente =$json_emisor->rfc_publico_general;


$datos['receptor']['rfc'] = $rfcCliente;
$datos['receptor']['nombre'] = 'PUBLICO GENERAL';
$datos['receptor']['Domicilio']['calle'] = 'N/A';
$datos['receptor']['Domicilio']['noExterior'] = 'N/A';
$datos['receptor']['Domicilio']['noInterior'] = 'N/A';
$datos['receptor']['Domicilio']['colonia'] = 'N/A';
$datos['receptor']['Domicilio']['localidad'] = 'N/A';
$datos['receptor']['Domicilio']['municipio'] = 'N/A';
$datos['receptor']['Domicilio']['estado'] = 'N/A';
$datos['receptor']['Domicilio']['pais'] = 'N/A';
$datos['receptor']['Domicilio']['CodigoPostal'] = 'N/A';

///////////////

// agregar conceptos





$concepto = array();

	$concepto['cantidad'] = '1';
	$concepto['unidad'] = 'N/A';
	$concepto['ID'] = 'N/A';
	
	$concepto['descripcion'] = $descripcion;
	$concepto['valorunitario'] = $subtotal;
	$concepto['importe'] = $subtotal;

	$datos['conceptos'][] = $concepto;
	


$datos['factura']['subtotal'] = $subtotal;



$datos['factura']['total'] = $total; // total incluyendo impuestos



$translado1['impuesto'] = 'IVA';
$translado1['tasa'] = '16';
$translado1['importe'] = $iva;
$datos['impuestos']['translados'][0] = $translado1;



$res= cfdi_generar_xml($datos);

$saldo = $res['saldo'];
///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////

echo "<h4>Respuesta Generar XML y Timbrado</h4>";
echo "<br><h4>Cliente</h4>";



echo "<div class=\"table-responsive\">          
  <table class=\"table table-condensed\">
    <thead>
      <tr>
       <th>Nombre</th>
	<th>RFC</th>
    <th>Correo Electronico</th>  
	</tr>
    </thead>
    <tbody>
      <tr class=\"success\">
        <td>PUBLICO GENERAL</td>
		<td>$rfcCliente</td>
		<td>N/A</td>
      </tr>
    </tbody>
  </table></div>
";





$timbrado = 0;
$pngFile = '';
$pdfFile = '';
echo "Respuesta:".$res['codigo_mf_texto']." <strong>Saldo:</strong><strong style='color:red'>".$res['saldo']."</strong><br>";
if($res['cancelada']=='NO')
{
	$timbrado=1;
	$ruta = "cfdi_publico_general_".$folio_factura."_".$date.".xml";
	$pngFile = "cfdi_publico_general_".$folio_factura."_".$date.".png";
	$pdfFile = "cfdi_publico_general_".$folio_factura."_".$date.".pdf";
	$xml_file = $ruta;
	
	
	$subtotal = round($subtotal,3);
	$iva = round($iva,3);
	$descuento = round($descuento,3);
	$total = round($total,3);

	
	echo "<h4>Factura generada con exito,CFDI: $ruta</h4>";
	echo "<div class='row'><div class='col-xs-4 well'>";
	echo "<strong class='finalDataDesc'>SUBTOTAL:</strong><strong class='finalDataDesc' style='color:red'>$$subtotal</strong><br>";
	echo "<strong class='finalDataDesc'>IVA:</strong><strong class='finalDataDesc' style='color:red'>$$iva</strong><br>";
	echo "<strong class='finalDataDesc'>DESCUENTO:</strong><strong class='finalDataDesc' style='color:red'>N/A</strong><br>";
	echo "<strong class='finalDataDesc'>TOTAL:</strong><strong class='finalDataDesc' style='color:red'>$$total</strong><br><p id='xmlfile' hidden>$ruta</p>";

echo "</div>"; 



	//solo si la factura se genera de manera satisfactoria, es llenaran las tablas factura_generada y folio_nota_factura
	
	
	$query ="INSERT INTO `factura_generada`(`folio_factura`, `idcliente`, `timbrado`, `enviado`, `xml_file`, `TIMESTAMP`,`montoTotal`,`generica`) VALUES($folio_factura,1,$timbrado,0,'$xml_file',$timestamp,$total,1);";
	
	//echo $query;
	ejecutarSQLCommand($query);
	// eliminar archivo temporal de factura
	GeneratePDF('logo.png',"timbrados/$xml_file","timbrados/$pngFile","timbrados/$pdfFile",$EmitterEmail,$EmitterPhone,'N/A','N/A');
	$folios_raw = $_POST['folios'];

     $folios = explode(":",$folios_raw);
        $query = '';
 foreach($folios as $folio)
 {
	if(trim($folio)!='')
		{
			
			
			$query = $query. "INSERT INTO `folio_nota_factura`(`folio_factura`, `folio_nota_venta`) VALUES ('$folio_factura','$folio');";
		}
 	
	
}
	ejecutarSQLCommand($query);


}








?>