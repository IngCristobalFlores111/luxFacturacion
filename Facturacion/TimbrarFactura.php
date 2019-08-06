<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
date_default_timezone_set('America/Mexico_City');
include_once "lib/cfdi32_multifacturas_PHP7.php";
include("../pdfWriter/FacturaPDFGen.php");

error_reporting(0);
session_start();

$predial = $_POST['predial'];
$noCuenta = $_POST['noCuenta'];
$id_usuario = $_SESSION['idUsuario'];

function obtainBillingDataFromDb($folio_factura,$idCliente)
{
    $linkObj = F_linkDb();
    $result = $linkObj->m_SendCommand("SELECT * FROM `factura_pendiente` WHERE folio_factura='$folio_factura'",MysqlLink::NAMED,MysqlLink::RET_YES);
	$fecha = $result[0]['fecha'];
	$metodo_pago =$result[0]['metodo_pago'];
	$forma_pago =$result[0]['forma_pago'];
    $total =(float)$result[0]['total'];
	$subtotal = $total/1.16;
	$iva = $subtotal*0.16;
	$descuento = $result[0]['descuento'];


    $result = $linkObj->m_SendCommand("SELECT * FROM usuario_facturacion",MysqlLink::NAMED,MysqlLink::RET_YES);
    $result = $result[0];


    $emitterEmail = $result["email"];
    $emitterPhone = $result["telefono"];

	$emisor_nombre = $result['nombre'];
	$emisor_calle = $result['calle'];
	$emisor_rfc = $result['RFC'];
	$emisor_noExt = $result['noExterior'];
	$emisor_noInt = $result['noInterior'];
	$emisor_colonia = $result['colonia'];
	$emisor_localidad = $result['localidad'];
	$emisor_municipio = $result['municipio'];
	$emisor_estado = $result['estado'];
	$emisor_pais = $result['pais'];
    $emisor_cp = $result['CodigoPostal'];
	$emisor_celular = $result['celular'];
	$emisor_regimen = $result['regimen'];


    $result = $linkObj->m_SendCommand("SELECT * FROM clientes_facturacion WHERE idcliente = $idCliente",MysqlLink::NAMED,MysqlLink::RET_YES);
    $result = $result[0];

    $receptorEmail = $result["email"];
    $receptorPhone = $result["telefono"];

    $receptor_nombre = $result['nombre'];
	$receptor_RFC = $result['RFC'];
	$receptor_calle = $result['calle'];
	$receptor_noExt = $result['noExterior'];
	$receptor_noInt = $result['noInterior'];
	$receptor_colonia = $result['colonia'];
	$receptor_localidad = $result['localidad'];
	$receptor_municipio = $result['municipio'];
	$receptor_estado = $result['estado'];
	$receptor_pais = $result['pais'];
	$receptor_cp = $result['CodigoPostal'];
	$receptor_telefono = $result['telefono'];

    //idcliente, calle, colonia, codigoPostal, noExt, noInt, localidad, municipio, estado, id

    $result = $linkObj->m_SendCommand("SELECT * FROM lugar_expedicion WHERE idexpedicion = $_POST[EXPEDICION]",MysqlLink::NAMED,MysqlLink::RET_YES);
    $result = $result[0];
    $emisorexpedicioncalle = $result["calle"];
    $emisorexpedicioncp = $result["codigoPostal"];
    $emisorexpedicioncolonia = $result["colonia"];
    $emisorexpedicionestado = $result["estado"];
    $emisorexpedicionlocalidad = $result["localidad"];
    $emisorexpedicionmunicipio = $result["municipio"];
    $emisorexpedicionnoexterior = $result["noExt"];
    $emisorexpedicionnointerior = $result["noInt"];
    $emisorexpedicionpais = "México";
    $lugar_expedicion = $emisorexpedicionmunicipio.",".$emisorexpedicionestado;



	$Factura = array(
		"Fecha"=>$fecha,
		"Folio"=>$folio_factura,
		"Moneda"=>"MXN",
		"Serie"=>"A",
		"TipoCambio"=>"1",
		"FormaDePago"=>$forma_pago,
		"MetodoDePago"=>$metodo_pago,
		"Subtotal"=>$subtotal,
       "EmisorExpedicionCalle"=>$emisorexpedicioncalle,
       "EmisorExpedicionCP"=>$emisorexpedicioncp,
       "EmisorExpedicionColonia"=>$emisorexpedicioncolonia,
       "EmisorExpedicionEstado"=>$emisorexpedicionestado,
       "EmisorExpedicionLocalidad"=>$emisorexpedicionlocalidad,
       "EmisorExpedicionMunicipio"=>$emisorexpedicionmunicipio,
       "EmisorExpedicionNoExterior"=>$emisorexpedicionnoexterior,
       "EmisorExpedicionNoInterior" => $emisorexpedicionnointerior,
       "EmisorExpedicionPais"=>$emisorexpedicionpais,
       "EmisorDatosEmail"=>$emitterEmail,
       "EmisorDatosTelefono"=>$emitterPhone,
	   "Total"=>$total,
	   "Descuento"=>$descuento,
		"LugarExpedicion"=>$lugar_expedicion,
		"FechaCertificacion"=>$fecha,
		"EmisorDomicilioCalle"=>$emisor_calle,
		"EmisorDomicilioNoExterior"=>$emisor_noExt,
        "EmisorDomicilioNoInterior" => $emisor_noInt,
		"EmisorDomicilioColonia"=>$emisor_colonia,
		"EmisorDomicilioMunicipio"=>$emisor_municipio,
		"EmisorDomicilioEstado"=>$emisor_estado,
		"EmisorDomicilioPais"=>$emisor_pais,
		"EmisorDomicilioCP"=>$emisor_cp,
		"EmisorDatosRFC"=>$emisor_rfc,
		"EmisorDatosNombre"=>$emisor_nombre,
		"EmisorRegimen"=>$emisor_regimen,
		"ReceptorDatosCalle"=>$receptor_calle,
		"ReceptorDatosCP"=>$receptor_cp,
		"ReceptorDatosColonia"=>$receptor_colonia,
		"ReceptorDatosEstado"=>$receptor_estado,
		"ReceptorDatosLocalidad"=>$receptor_localidad,
		"ReceptorDatosCP"=>$receptor_cp,
        "ReceptorDatosEmail" =>$receptorEmail,
        "ReceptorDatosTelefono" =>$receptorPhone,
		"ReceptorDatosMunicipio"=>$receptor_municipio,
		"ReceptorDatosNoExterior"=>$receptor_noExt,
		"ReceptorDatosNoInterior"=>$receptor_noInt,
		"ReceptorDatosPais"=>$receptor_pais,
		"ReceptorRFC"=>$receptor_RFC,
		"ReceptorNombre"=>$receptor_nombre,
        "NumeroDeCuentaDePago" => ""
		);



    //Información Opcional de la factura
     if($Factura["MetodoDePago"] == "CHEQUE" || $Factura["MetodoDePago"] == "TRANSFERENCIA ELECTRONICA")
         $Factura["NumeroDeCuentaDePago"] =$noCuenta  ; //Aqui va el número de cuenta

    //Información Opcional de la factura
    $result = $linkObj->m_SendCommand("SELECT * FROM folio_conceptos WHERE folio_factura=$folio_factura",MysqlLink::NAMED,MysqlLink::RET_YES);
	$Conceptos = array();

	foreach($result as $data)
		array_push($Conceptos,
        array(
            "Cantidad" =>$data['cantidad'],
            "Descripcion"=>$data['descripcion'],
            "Unidad"=>$data['unidad'],
            "ValorUnitario"=>$data['precio_unitario'],
            "Importe"=>$data['importe'],
            "ID" =>$data['noSerie']
              ));
	$Factura["Conceptos"] = $Conceptos;

    return $Factura;
}


if(!filter_input(INPUT_POST,"ID",FILTER_VALIDATE_INT) || !filter_input(INPUT_POST,"FOLIO",FILTER_VALIDATE_INT) || !filter_input(INPUT_POST,"EXPEDICION",FILTER_VALIDATE_INT))
{
    print_r("Invalid Input");
    return;
}

//CAMBIAR A FACTURA PENDIENTE!!!
$Objlink = F_linkDB();
$Ret = $Objlink->m_SendCommand("SELECT * FROM (SELECT clientes_facturacion.idcliente, factura_pendiente.folio_factura
FROM clientes_facturacion
INNER JOIN factura_pendiente
ON clientes_facturacion.idcliente=factura_pendiente.idcliente) as Res WHERE Res.folio_factura = $_POST[FOLIO] AND Res.idcliente = $_POST[ID];",MysqlLink::NAMED,MysqlLink::RET_YES);

if(!sizeof($Ret))
{
    print_r("0;<h2>El cliente $_POST[ID] no tiene una factura $_POST[FOLIO] vínculada</h2>");
    return;
}
if(sizeof($Ret) > 1)
{
    print_r("0;<h2>La busqueda del cliente $_POST[ID] y el folio $_POST[FOLIO] tiene más de un resultado. Base de datos inconsistente. Llamo a su administrador de software y muestrele este mensaje</h2>");
    return;
}

$Factura = obtainBillingDataFromDb($_POST["FOLIO"],$_POST["ID"]);




$objSQL = F_sqlConn();

$result =$objSQL->executeQuery("SELECT `archivo_cer`,`archivo_key` FROM usuario_facturacion");
$fileKey = $result[0]['archivo_key'];
$fileCer =$result[0]['archivo_cer'];
$datos['PAC']['usuario'] = 'IIDE660331UU7';
$datos['PAC']['pass'] = 'MULTI6d9180f6da47e579158f09226afeea12!';
$datos['PAC']['produccion'] = 'SI'; //   [SI|NO]
$datos['conf']['cer'] ="pruebas/$fileCer";
$datos['conf']['key'] = "pruebas/$fileKey";
$datos['conf']['pass'] = 'VENCEDOR';


////RUTA DONDE ALMACENARA EL CFDI
$datos['cfdi']= "facturas/timbradas/xml/".$_POST["ID"]."_".date("Y_m_d")."_".$_POST["FOLIO"].".xml";

////OPCIONAL, ACTIVAR SOLO EN CASO DE CONFLICTOS
////$datos['remueve_acentos']='SI';

////OPCIONAL, UTILIZAR LA LIBRERIA PHP DE OPENSSL, DEFAULT SI
$datos['php_openssl']='SI';


$result = $objSQL->executeQuery("SELECT `tipo` FROM `factura_pendiente` WHERE `folio_factura`=". $Factura["Folio"]);
$tipo = $result[0]['tipo'];
$query ="SELECT `folio_impreso`+1 AS nuevo FROM `factura_generada` WHERE `tipo`=$tipo ORDER BY folio_impreso DESC LIMIT 1";
$result = $objSQL->executeQuery($query);
$folio_impreso = $result[0]['nuevo'];

$serie = '';
if($tipo==0){$serie='A';}
if($tipo==1){$serie='B';}
if($tipo==2){$serie='C';}
$Factura["Serie"] = $serie;

$datos['factura']['serie'] = $Factura["Serie"]; //opcional
$datos['factura']['folio'] = $folio_impreso; //opcional
$datos['factura']['fecha_expedicion'] = date('Y-m-d H:i:s',time()-120);// Opcional  "time()-120" para retrasar la hora 2 minutos para evitar falla de error en rango de fecha

$datos['factura']['metodo_pago'] = $Factura["MetodoDePago"]; // EFECTIV0, CHEQUE, TARJETA DE CREDITO, TRANSFERENCIA BANCARIA, NO IDENTIFICADO
$datos['factura']['forma_pago'] = $Factura["FormaDePago"];  //PAGO EN UNA SOLA EXHIBICION, CREDITO 7 DIAS, CREDITO 15 DIAS, CREDITO 30 DIAS, ETC
$datos['factura']['tipocomprobante'] = "ingreso"; //ingreso, egreso
$datos['factura']['moneda'] = $Factura["Moneda"]; // MXN USD EUR
$datos['factura']['tipocambio'] = $Factura["TipoCambio"]; // OPCIONAL (MXN = 1.00, OTRAS EJ: USD = 13.45; EUR = 16.86)
$datos['factura']['LugarExpedicion'] = $Factura["LugarExpedicion"];

if($Factura["MetodoDePago"] == "CHEQUE" || $Factura["MetodoDePago"] == "TRANSFERENCIA ELECTRONICA")
    $datos['factura']['NumCtaPago'] = $Factura["NumeroDeCuentaDePago"]; //opcional; 4 DIGITOS pero obligatorio en transferencias y cheques

$datos['factura']['RegimenFiscal'] = $Factura["EmisorRegimen"];
if(trim($noCuenta)!="")
    $datos['factura']['NumCtaPago'] = $noCuenta;

$datos['emisor']['rfc'] = $Factura["EmisorDatosRFC"] ; //RFC DE PRUEBA CAMBIAR POR => $Factura["EmisorDatosRFC"]
$datos['emisor']['nombre'] = $Factura["EmisorDatosNombre"];  // EMPRESA DE PRUEBA
$datos['emisor']['DomicilioFiscal']['calle'] = $Factura["EmisorDomicilioCalle"];
$datos['emisor']['DomicilioFiscal']['noExterior'] = $Factura["EmisorDomicilioNoExterior"];

if(isset($Factura["EmisorDomicilioNoInterior"]))
    $datos['emisor']['DomicilioFiscal']['noInterior'] = $Factura["EmisorDomicilioNoInterior"]; //(opcional)
 //(opcional)
else
    $datos['emisor']['DomicilioFiscal']['noInterior'] = "";

$datos['emisor']['DomicilioFiscal']['colonia'] = $Factura["EmisorDomicilioColonia"];
$datos['emisor']['DomicilioFiscal']['localidad'] = $Factura["EmisorDomicilioMunicipio"];
$datos['emisor']['DomicilioFiscal']['municipio'] = $Factura["EmisorDomicilioMunicipio"]; // o delegacion
$datos['emisor']['DomicilioFiscal']['estado'] = $Factura["EmisorDomicilioEstado"];
$datos['emisor']['DomicilioFiscal']['pais'] = $Factura["EmisorDomicilioPais"];
$datos['emisor']['DomicilioFiscal']['CodigoPostal'] = $Factura["EmisorDomicilioCP"]; // 5 digitos

//////SI EX EXPEDIDO EN SUCURSAL CAMBIA EL DOMICILIO
//////SI ES EN EL MISMO DOMICILIO REPETIR INFORMACION
$datos['emisor']['ExpedidoEn']['calle'] = $Factura["EmisorExpedicionCalle"];
$datos['emisor']['ExpedidoEn']['noExterior'] = $Factura["EmisorExpedicionNoExterior"];

//(opcional)
if(isset($Factura["EmisorExpedicionNoInterior"]))
    $datos['emisor']['ExpedidoEn']['noInterior'] = $Factura["EmisorExpedicionNoInterior"];

else
    $datos['emisor']['ExpedidoEn']['noInterior'] = "";


$datos['emisor']['ExpedidoEn']['colonia'] = $Factura["EmisorExpedicionColonia"];
$datos['emisor']['ExpedidoEn']['localidad'] = $Factura["EmisorExpedicionLocalidad"];
$datos['emisor']['ExpedidoEn']['municipio'] = $Factura["EmisorExpedicionMunicipio"]; // O DELEGACION
$datos['emisor']['ExpedidoEn']['estado'] = $Factura["EmisorExpedicionEstado"];
$datos['emisor']['ExpedidoEn']['pais'] = $Factura["EmisorExpedicionPais"];
$datos['emisor']['ExpedidoEn']['CodigoPostal'] = $Factura["EmisorExpedicionCP"]; // 5 digitos

////// IMPORTANTE PROBAR CON NOMBRE Y RFC REAL O GENERARA ERROR DE XML MAL FORMADO
$datos['receptor']['rfc'] = $Factura["ReceptorRFC"];
$datos['receptor']['nombre'] = $Factura["ReceptorNombre"];
////opcional
$datos['receptor']['Domicilio']['calle'] = $Factura["ReceptorDatosCalle"];
$datos['receptor']['Domicilio']['noExterior'] = $Factura["ReceptorDatosNoExterior"];

if(isset($Factura["ReceptorDatosNoInterior"]))
    $datos['receptor']['Domicilio']['noInterior'] = $Factura["ReceptorDatosNoInterior"];
else
    $datos['receptor']['Domicilio']['noInterior'] = '';

$datos['receptor']['Domicilio']['colonia'] = $Factura["ReceptorDatosColonia"];
$datos['receptor']['Domicilio']['localidad'] = $Factura["ReceptorDatosLocalidad"];
$datos['receptor']['Domicilio']['municipio'] = $Factura["ReceptorDatosMunicipio"];
$datos['receptor']['Domicilio']['estado'] = $Factura["ReceptorDatosEstado"];
$datos['receptor']['Domicilio']['pais'] = $Factura["ReceptorDatosPais"];
$datos['receptor']['Domicilio']['CodigoPostal'] = $Factura["ReceptorDatosCP"]; // 5 digitos

$QtyArt = sizeof($Factura["Conceptos"]);
$Factura["Subtotal"] = 0;
$subtotal_conceptos =0;
for ($i = 0; $i < $QtyArt; $i++) {
    unset($concepto);
    $concepto['cantidad'] = $Factura["Conceptos"][$i]["Cantidad"];
    $concepto['unidad'] = $Factura["Conceptos"][$i]["Unidad"];
    $concepto['ID'] = $Factura["Conceptos"][$i]["ID"];
    $concepto['descripcion'] = $Factura["Conceptos"][$i]["Descripcion"];
    $concepto['valorunitario'] = $Factura["Conceptos"][$i]["ValorUnitario"]; // SIN IVA
    $concepto['importe'] = $Factura["Conceptos"][$i]["Importe"];
    if(trim($predial)!='')
    {
        $concepto['predial'] = $predial;
    }
    $datos['conceptos'][] = $concepto;
    $subtotal_conceptos +=(float)$concepto['importe'];
}
$Factura["Subtotal"] = $subtotal_conceptos;

//$Factura["Total"] = iva + subtotal - descuento ( sin retenciones)
$Factura["Descuento"] = (float)$Factura["Descuento"];
$Factura["Descuento"] = (float)($Factura["Descuento"]/100)*$Factura["Subtotal"] ;
$datos['factura']['subtotal'] = round($Factura["Subtotal"],3); // sin impuestos
$datos['factura']['subtotal'] = round($datos['factura']['subtotal'],3);


$datos['factura']['descuento'] =round((float)$Factura["Descuento"],3); // descuento sin impuestos

$IVA = (float)($Factura["Subtotal"]- $Factura["Descuento"])*0.16;
$Factura["Total"] = (float)$IVA + ($Factura["Subtotal"] - $Factura["Descuento"]);

$datos['factura']['total'] = $Factura["Total"]; // total incluyendo impuestos

$datos['factura']['total'] = round($datos['factura']['total'],3);
$IVA = round($IVA,3);

$translado1['impuesto'] = 'IVA';
$translado1['tasa'] = '16';

$translado1['importe'] = $IVA; //iva de los productos facturados
$datos['impuestos']['translados'][0] = $translado1;

$result = $objSQL->executeQuery("SELECT `retencion_IVA`,`retencion_ISR`,`retener_ISR` FROM `user_config` WHERE idusuario='$id_usuario'");

$tipo = trim($tipo);
if($tipo=='1' || $tipo=='2')
{

    if(trim($result[0]['retencion_IVA'])=='1'){
        $iva_retenido =  (float)$IVA*(2/3);
        $retenido['impuesto'] = 'IVA';
        $retenido['importe'] = $iva_retenido; // iva de los productos facturados
        $datos['impuestos']['retenidos'][] = $retenido;
        $total =$datos['factura']['total'] ; // total incluyendo impuestos
        $total =(float)($total - $iva_retenido);
        $datos['factura']['total']  = $total;
    }
    if(trim($result[0]['retener_ISR'])=='1'){
        $retenido['impuesto'] = 'ISR';
        $tasa_isr = (float)$result[0]['retencion_ISR'];
        $importe_isr = (float)($Factura["Subtotal"] - $Factura["Descuento"])*($tasa_isr/100);
        $retenido['importe'] = $importe_isr; // iva de los productos facturados
        $datos['impuestos']['retenidos'][] = $retenido;
        $total =$datos['factura']['total'] ; // total incluyendo impuestos
        $total = (float)($total - $importe_isr);
        $datos['factura']['total']  = $total;


    }

}

$res= cfdi_generar_xml($datos);

//print_r($datos);

if(!isset($res["uuid"]))
{
    print_r("0;<h2>El proceso de timbrado falló. </h2>" . "<p>Mas Información: " . $res["codigo_mf_texto"]."</p>");
    return;
}
print_r("1;<h2>El proceso de timbrado fue concretado con éxito. </h2>" . "<p>Mas Información: " . $res["codigo_mf_texto"]."</p>");
include("../wkWorks/generatePdf.php");
$filePdf= "facturas/timbradas/pdf/".$_POST["ID"]."_".date("Y_m_d")."_".$_POST["FOLIO"].".pdf";
$xml_file =$_POST["ID"]."_".date("Y_m_d")."_".$_POST["FOLIO"].".xml";
$qr = $_POST["ID"]."_".date("Y_m_d")."_".$_POST["FOLIO"].".png";
$absolutePdf= "https://www.luxline.com.mx/joseO/LuxFacturacion/Facturacion/".$filePdf;
generatePdf("https://www.luxline.com.mx/joseO/LuxFacturacion/Facturacion/facturas/timbradas/xml/".
$xml_file,"golman.badger@gmail.com",$Factura["EmisorDatosTelefono"],
"https://www.luxline.com.mx/joseO/LuxFacturacion/Facturacion/facturas/timbradas/xml/".
$qr,$absolutePdf);
/*GeneratePDF(
"logo/logo.png",
"facturas/timbradas/xml/".$_POST["ID"]."_".date("Y_m_d")."_".$_POST["FOLIO"].".xml",
"facturas/timbradas/xml/".$_POST["ID"]."_".date("Y_m_d")."_".$_POST["FOLIO"].".png",
"facturas/timbradas/pdf/".$_POST["ID"]."_".date("Y_m_d")."_".$_POST["FOLIO"].".pdf",
$Factura["EmisorDatosEmail"],$Factura["EmisorDatosTelefono"],$Factura["ReceptorDatosEmail"],$Factura["ReceptorDatosTelefono"]
);*/

//Borrar el pdf provisional que se crea al momento de generar pero no timbrar
if(file_exists("facturas/pendientes/pdf/".$_POST["ID"]."_".date("Y_m_d")."_".$_POST["FOLIO"].".pdf"))
            unlink("facturas/pendientes/pdf/".$_POST["ID"]."_".date("Y_m_d")."_".$_POST["FOLIO"].".pdf");

//1.- Borrar factura de la tabla factura_pendiente junto con sus conceptos vínculados
$Objlink->m_SendCommand("DELETE FROM folio_conceptos WHERE folio_factura =".$_POST["FOLIO"],MysqlLink::NAMED,MysqlLink::RET_NO);
$Objlink->m_SendCommand("DELETE FROM factura_pendiente WHERE folio_factura =".$_POST["FOLIO"],MysqlLink::NAMED,MysqlLink::RET_NO);
$js_concpetos = json_encode($Factura["Conceptos"]);
//2.- Insertar esa misma información en la tabla factura_generada
$Objlink->m_SendCommand(
'INSERT INTO factura_generada(json_conceptos,tipo,folio_impreso,folio_factura, idcliente, enviado, xml_file, montoTotal, uuid, fecha_timbrado, fecha_cancelada)
VALUES("'.$js_concpetos,'","'.$tipo.'","'.$folio_impreso.'","'.$_POST["FOLIO"].'","'.$_POST["ID"].'","0","'.$_POST["ID"].'_'.date("Y_m_d").'_'.$_POST["FOLIO"].'.xml"'.',"'.$datos["factura"]["total"].'","'.$res["uuid"].'","'.date("Y-m-d").'","0000-00-00");',MysqlLink::NAMED,MysqlLink::RET_NO);
?>