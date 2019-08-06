<?php
require_once("../pdfWriter/fpdf.php");
require_once("../pdfWriter/fpdi.php");
include("../php/Functions.php");

/*!
  @function num2letras ()
  @abstract Dado un n?mero lo devuelve escrito.
  @param $num number - N?mero a convertir.
  @param $fem bool - Forma femenina (true) o no (false).
  @param $dec bool - Con decimales (true) o no (false).
  @result string - Devuelve el n?mero escrito en letra.
*/

function num2letras($num, $fem = false, $dec = true) {
   $matuni[2]  = "dos";
   $matuni[3]  = "tres";
   $matuni[4]  = "cuatro";
   $matuni[5]  = "cinco";
   $matuni[6]  = "seis";
   $matuni[7]  = "siete";
   $matuni[8]  = "ocho";
   $matuni[9]  = "nueve";
   $matuni[10] = "diez";
   $matuni[11] = "once";
   $matuni[12] = "doce";
   $matuni[13] = "trece";
   $matuni[14] = "catorce";
   $matuni[15] = "quince";
   $matuni[16] = "dieciseis";
   $matuni[17] = "diecisiete";
   $matuni[18] = "dieciocho";
   $matuni[19] = "diecinueve";
   $matuni[20] = "veinte";
   $matunisub[2] = "dos";
   $matunisub[3] = "tres";
   $matunisub[4] = "cuatro";
   $matunisub[5] = "quin";
   $matunisub[6] = "seis";
   $matunisub[7] = "sete";
   $matunisub[8] = "ocho";
   $matunisub[9] = "nove";

   $matdec[2] = "veint";
   $matdec[3] = "treinta";
   $matdec[4] = "cuarenta";
   $matdec[5] = "cincuenta";
   $matdec[6] = "sesenta";
   $matdec[7] = "setenta";
   $matdec[8] = "ochenta";
   $matdec[9] = "noventa";
   $matsub[3]  = 'mill';
   $matsub[5]  = 'bill';
   $matsub[7]  = 'mill';
   $matsub[9]  = 'trill';
   $matsub[11] = 'mill';
   $matsub[13] = 'bill';
   $matsub[15] = 'mill';
   $matmil[4]  = 'millones';
   $matmil[6]  = 'billones';
   $matmil[7]  = 'de billones';
   $matmil[8]  = 'millones de billones';
   $matmil[10] = 'trillones';
   $matmil[11] = 'de trillones';
   $matmil[12] = 'millones de trillones';
   $matmil[13] = 'de trillones';
   $matmil[14] = 'billones de trillones';
   $matmil[15] = 'de billones de trillones';
   $matmil[16] = 'millones de billones de trillones';

   //Zi hack
   $float=explode('.',$num);
   $num=$float[0];

   $num = trim((string)@$num);
   if ($num[0] == '-') {
      $neg = 'menos ';
      $num = substr($num, 1);
   }else
      $neg = '';
   while ($num[0] == '0') $num = substr($num, 1);
   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num;
   $zeros = true;
   $punt = false;
   $ent = '';
   $fra = '';
   for ($c = 0; $c < strlen($num); $c++) {
      $n = $num[$c];
      if (! (strpos(".,'''", $n) === false)) {
         if ($punt) break;
         else{
            $punt = true;
            continue;
         }

      }elseif (! (strpos('0123456789', $n) === false)) {
         if ($punt) {
            if ($n != '0') $zeros = false;
            $fra .= $n;
         }else

            $ent .= $n;
      }else

         break;

   }
   $ent = '     ' . $ent;
   if ($dec and $fra and ! $zeros) {
      $fin = ' coma';
      for ($n = 0; $n < strlen($fra); $n++) {
         if (($s = $fra[$n]) == '0')
            $fin .= ' cero';
         elseif ($s == '1')
            $fin .= $fem ? ' una' : ' un';
         else
            $fin .= ' ' . $matuni[$s];
      }
   }else
      $fin = '';
   if ((int)$ent === 0) return 'Cero ' . $fin;
   $tex = '';
   $sub = 0;
   $mils = 0;
   $neutro = false;
   while ( ($num = substr($ent, -3)) != '   ') {
      $ent = substr($ent, 0, -3);
      if (++$sub < 3 and $fem) {
         $matuni[1] = 'una';
         $subcent = 'as';
      }else{
         $matuni[1] = $neutro ? 'un' : 'uno';
         $subcent = 'os';
      }
      $t = '';
      $n2 = substr($num, 1);
      if ($n2 == '00') {
      }elseif ($n2 < 21)
         $t = ' ' . $matuni[(int)$n2];
      elseif ($n2 < 30) {
         $n3 = $num[2];
         if ($n3 != 0) $t = 'i' . $matuni[$n3];
         $n2 = $num[1];
         $t = ' ' . $matdec[$n2] . $t;
      }else{
         $n3 = $num[2];
         if ($n3 != 0) $t = ' y ' . $matuni[$n3];
         $n2 = $num[1];
         $t = ' ' . $matdec[$n2] . $t;
      }
      $n = $num[0];
      if ($n == 1) {
         $t = ' ciento' . $t;
      }elseif ($n == 5){
         $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t;
      }elseif ($n != 0){
         $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t;
      }
      if ($sub == 1) {
      }elseif (! isset($matsub[$sub])) {
         if ($num == 1) {
            $t = ' mil';
         }elseif ($num > 1){
            $t .= ' mil';
         }
      }elseif ($num == 1) {
         $t .= ' ' . $matsub[$sub] . '?n';
      }elseif ($num > 1){
         $t .= ' ' . $matsub[$sub] . 'ones';
      }
      if ($num == '000') $mils ++;
      elseif ($mils != 0) {
         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub];
         $mils = 0;
      }
      $neutro = true;
      $tex = $t . $tex;
   }
   $tex = $neg . substr($tex, 1) . $fin;
   //Zi hack --> return ucfirst($tex);
   $end_num=ucfirst($tex).' pesos '.$float[1].'/100 M.N.';
   return $end_num;
}

function obtenerCadenaComp($UUID,$fechaTimbrado,$selloDigital,$numeroCert)
{
    return  "||1.0|$UUID|$fechaTimbrado|$selloDigital|$numeroCert||";
    //FolioSAT_UUID|FechaCertificacion|SelloDigitalCFDI|NumeroCertificadoSAT
}

function OpenCompleteXMLFile($FileName, $Encoding) // [$Encoding := true JSON] , [$Encoding := false PHP ARRAY]
{
    $file = file_get_contents($FileName);
    $file = str_replace('cfdi:', '', $file);
    $file = str_replace('tfd:', '', $file);

    $XmlDoc = simplexml_load_string($file) or die("Error: Cannot create object");

       $Factura = array(
       "Fecha"=>(string)$XmlDoc["fecha"],
       "Folio"=>(int)$XmlDoc["folio"],
       "Moneda"=>(string)$XmlDoc["Moneda"],
       "Serie"=>(string)$XmlDoc["serie"],
       "TipoCambio"=>(float)$XmlDoc["TipoCambio"],
       "FormaDePago"=>(string)$XmlDoc["formaDePago"],
       "MetodoDePago"=>(string)$XmlDoc["metodoDePago"],
       "Subtotal"=>(float)$XmlDoc["subTotal"],
       "Total"=>(float)$XmlDoc["total"],
       "TipoDeComprobante"=>(string)$XmlDoc["tipoDeComprobante"],
       "Descuento"=>(float)$XmlDoc["descuento"],
       "LugarExpedicion"=>(string)$XmlDoc["LugarExpedicion"],
       "EmisorExpedicionCalle"=>(string)$XmlDoc->Emisor->ExpedidoEn["calle"],
       "EmisorExpedicionCP"=>(string)$XmlDoc->Emisor->ExpedidoEn["codigoPostal"],
       "EmisorExpedicionColonia"=>(string)$XmlDoc->Emisor->ExpedidoEn["colonia"],
       "EmisorExpedicionEstado"=>(string)$XmlDoc->Emisor->ExpedidoEn["estado"],
       "EmisorExpedicionLocalidad"=>(string)$XmlDoc->Emisor->ExpedidoEn["localidad"],
       "EmisorExpedicionMunicipio"=>(string)$XmlDoc->Emisor->ExpedidoEn["municipio"],
       "EmisorExpedicionNoExterior"=>(string)$XmlDoc->Emisor->ExpedidoEn["noExterior"],
       "EmisorExpedicionPais"=>(string)$XmlDoc->Emisor->ExpedidoEn["pais"],
       "FechaCertificacion"=>(string)$XmlDoc->Complemento->TimbreFiscalDigital["FechaTimbrado"],
       "NumeroCertificado"=>(string)$XmlDoc["noCertificado"],
       "SelloDigitalCFDI"=>(string)$XmlDoc["sello"],
       "SelloDigitalSAT"=>(string)$XmlDoc->Complemento->TimbreFiscalDigital["selloSAT"],
       "FolioSAT_UUID"=>(string)$XmlDoc->Complemento->TimbreFiscalDigital["UUID"],
       "NumeroCertificadoSAT"=>(string)$XmlDoc->Complemento->TimbreFiscalDigital["noCertificadoSAT"],
       "EmisorDomicilioCalle"=>(string)$XmlDoc->Emisor->DomicilioFiscal["calle"],
       "EmisorDomicilioNoExterior"=>(string)$XmlDoc->Emisor->DomicilioFiscal["noExterior"],
       "EmisorDomicilioColonia"=>(string)$XmlDoc->Emisor->DomicilioFiscal["colonia"],
       "EmisorDomicilioMunicipio"=>(string)$XmlDoc->Emisor->DomicilioFiscal["municipio"],
       "EmisorDomicilioLocalidad"=>(string)$XmlDoc->Emisor->DomicilioFiscal["localidad"],
       "EmisorDomicilioEstado"=>(string)$XmlDoc->Emisor->DomicilioFiscal["estado"],
       "EmisorDomicilioPais"=>(string)$XmlDoc->Emisor->DomicilioFiscal["pais"],
       "EmisorDomicilioCP"=>(string)$XmlDoc->Emisor->DomicilioFiscal["codigoPostal"],
       "EmisorDatosRFC"=>(string)$XmlDoc->Emisor["rfc"],
       "EmisorDatosNombre"=>(string)$XmlDoc->Emisor["nombre"],
       "EmisorRegimen"=>(string)$XmlDoc->Emisor->RegimenFiscal["Regimen"],
       "ReceptorDatosCalle"=>(string)$XmlDoc->Receptor->Domicilio["calle"],
       "ReceptorDatosCP"=>(string)$XmlDoc->Receptor->Domicilio["codigoPostal"],
       "ReceptorDatosColonia"=>(string)$XmlDoc->Receptor->Domicilio["colonia"],
       "ReceptorDatosEstado"=>(string)$XmlDoc->Receptor->Domicilio["estado"],
       "ReceptorDatosLocalidad"=>(string)$XmlDoc->Receptor->Domicilio["localidad"],
       "ReceptorDatosCP"=>(string)$XmlDoc->Receptor->Domicilio["codigoPostal"],
       "ReceptorDatosMunicipio"=>(string)$XmlDoc->Receptor->Domicilio["municipio"],
       "ReceptorDatosNoExterior"=>(int)$XmlDoc->Receptor->Domicilio["noExterior"],
       "ReceptorDatosPais"=>(string)$XmlDoc->Receptor->Domicilio["pais"],
       "ReceptorRFC"=>(string)$XmlDoc->Receptor["rfc"],
       "ReceptorNombre"=>(string)$XmlDoc->Receptor["nombre"]
        );


    //Informaci�n Opcional de la factura
     if($Factura["MetodoDePago"] == "CHEQUE" || $Factura["MetodoDePago"] == "TRANSFERENCIA ELECTRONICA")
        $Factura["NumeroDeCuentaDePago"] = (string)$XmlDoc["NumCtaPago"];

    if(isset($XmlDoc->Receptor->Domicilio["noInterior"])){
        $Factura["ReceptorDatosNoInterior"] = (string)$XmlDoc->Receptor->Domicilio["noInterior"];
    }

    if(isset($XmlDoc->Emisor->Domicilio["noInterior"])){
        $Factura["EmisorDomicilioNoInterior"] = (string)$XmlDoc->Emisor->Domicilio["noInterior"];
    }

    if(isset($XmlDoc->Emisor->ExpedidoEn["noInterior"])){
        $Factura["EmisorExpedicionNoInterior"] = (string)$XmlDoc->Emisor->ExpedidoEn["noInterior"];
    }
    if(isset($XMLDoc->Impuestos["totalImpuestosRetenidos"])){
        $Factura["TotalRetenciones"] = (string)$XMLDoc->Impuestos["totalImpuestosRetenidos"];
    }

    //(string)$XmlDoc->Impuestos->Retenciones->Retencion[0]["importe"];

    if(isset($XmlDoc->Impuestos->Retenciones->Retencion[0]))
    {
        $lb_flagIVAorISR = false; //false: IVA true: ISR

        if((string)$XmlDoc->Impuestos->Retenciones->Retencion[0]["impuesto"] === "IVA")
        {
            $Factura["RetencionesIVA"] = (float)$XmlDoc->Impuestos->Retenciones->Retencion[0]["importe"];
        }


        else{
            $Factura["RetencionesISR"] = (float)$XmlDoc->Impuestos->Retenciones->Retencion[0]["importe"];
            $lb_flagIVAorISR = true; //false: IVA true: ISR
            }


        if(isset($XmlDoc->Impuestos->Retenciones->Retencion[1]))
        {
            if($lb_flagIVAorISR)
            {
                $Factura["RetencionesIVA"] = (float)$XMLDoc->Impuestos->Retenciones->Retencion[1]["importe"];
                }

            else
            {
               $Factura["RetencionesISR"] = (float)$XmlDoc->Impuestos->Retenciones->Retencion[1]["importe"];
            }

        }
    }

    //Informaci�n Opcional de la factura
       $QtyConceptos = sizeof($XmlDoc->Conceptos[0]->Concepto);
       $Conceptos = array();
       for($i = 0; $i < $QtyConceptos; $i++)
       {
           if(isset($XmlDoc->Conceptos->Concepto[$i]->InformacionAduanera)){

               array_push($Conceptos,
           array(
           "Cantidad" => (int)$XmlDoc->Conceptos->Concepto[$i]["cantidad"],
           "noSerie" => (string)$XmlDoc->Conceptos->Concepto[$i]["noIdentificacion"],
           "Descripcion" => (string)$XmlDoc->Conceptos->Concepto[$i]["descripcion"],
           "Unidad" => (string)$XmlDoc->Conceptos->Concepto[$i]["unidad"],
           "ValorUnitario" => (float)$XmlDoc->Conceptos->Concepto[$i]["valorUnitario"],
           "Importe" => (float)$XmlDoc->Conceptos->Concepto[$i]["importe"],
           "ID"=>(string)$XmlDoc->Conceptos->Concepto[$i]["noIdentificacion"],
           "Numero"=>(string)$XmlDoc->Conceptos->Concepto[$i]->InformacionAduanera['numero'],
           "Aduana" =>(string)$XmlDoc->Conceptos->Concepto[$i]->InformacionAduanera['aduana'],
           "Fecha" =>(string)$XmlDoc->Conceptos->Concepto[$i]->InformacionAduanera['fecha']

           ));

           }else{
               array_push($Conceptos,
               array(
               "Cantidad" => (int)$XmlDoc->Conceptos->Concepto[$i]["cantidad"],
               "noSerie" => (string)$XmlDoc->Conceptos->Concepto[$i]["noIdentificacion"],
               "Descripcion" => (string)$XmlDoc->Conceptos->Concepto[$i]["descripcion"],
               "Unidad" => (string)$XmlDoc->Conceptos->Concepto[$i]["unidad"],
               "ValorUnitario" => (float)$XmlDoc->Conceptos->Concepto[$i]["valorUnitario"],
               "Importe" => (float)$XmlDoc->Conceptos->Concepto[$i]["importe"],
               "ID"=>(string)$XmlDoc->Conceptos->Concepto[$i]["noIdentificacion"],
               ));
           }
       }
       $Factura["Conceptos"] = $Conceptos;

       if($Encoding)
        return '{"Factura":'.json_encode($Factura).'}';

       else
        return $Factura;
}

/*
 MODIFICACION HEADER
$pdf->SetXY(5,67);
$pdf->SetFont('Arial','B',6);
$pdf->Cell(20,4,'Cantidad',0,0,'C',true);
$pdf->Cell(20,4,'No.Serie',0,0,'C',true); <--- Agregar No.Serie
$pdf->Cell(111,4,'Descripci�n',0,0,'C',true);
$pdf->Cell(15,4,'Unidad',0,0,'C',true);
$pdf->Cell(20,4,'Precio',0,0,'C',true);
$pdf->Cell(20,4,'Importe',0,0,'C',true);


MODIFICACION TABLA

*/



//EN TODAS LAS INSTANCIAS:
//Cambiar el retorno de carro de descripci�n, ya que el espacio dedicado a dicha
//informaci�n ser� reducida
//$local = ceil(strlen($Factura["Conceptos"][$it]["Descripcion"])/105.0);
//$rowsCharacters = str_split(utf8_decode($Factura["Conceptos"][$IndiceDeConceptos]["Descripcion"]),105); //Regreso de Carro


//El array Conceptos hay que actualizarlo                         Nuevos Datos
//	array_push($Conceptos,array("Cantidad" =>$data['cantidad'],"noSerie" => $data['noSerie'],"Descripcion"=>$data['descripcion'],"Unidad"=>$data['unidad'],"ValorUnitario"=>$data['precio_unitario'],"Importe"=>$data['importe']));


//Agregar la celda y el texto
//$pdf->SetXY(25,71 + (4 * ($PunteroDeFila) ));
//$pdf->Cell(20,4*$rows,(string)$Factura["Conceptos"][$IndiceDeConceptos]["noSerie"],1,0,'C',true);

//Desafasar todo hacia la derecha
//$pdf->SetXY(45,71 + (4 * ($PunteroDeFila)));
//$pdf->Cell(136,4 * $rows,'',1,0,'C',true);
//$pdf->SetDrawColor(255,255,255); //Color de bordes

//for($k = 0; $k < $rows; $k++)
//{
//    $pdf->SetXY(46,71.5 + (4 * ($PunteroDeFila)) + ($k * 4));
//    $pdf->Cell(135,3,$rowsCharacters[$k],1,0,'L',true);
//}


//Como se llama la variable de sesi�n -- Preview
//$_SESSION['Conceptos']['noSerie']

//Como se llama la columna de la tabla -- Factura Pendiente
//noSerie en base de datos

//Como se llama la columna de el xml ---- Factura Generada
//<cfdi:Conceptos>
//<cfdi:Concepto noIdentificacion="Pitp" />
//</cfdi:Conceptos>

//Ejemplo
//GeneratePDF("logo.png","cfdi_3_2015_12_08 (1).xml","cfdi_1_2015_11_25.png","TestPDF2.pdf","micaguama@hotmail.com","33556754","zacariasblancodelatorre@gmail.com","36609999");
function generateFacturaPreview($factura_paramas,$idCliente,$LogoPath,$Code2DPath,$OutputFileName,$EmitterEmail,$EmitterPhone,$ReceptorEmail,$ReceptorPhone)
{
    $objSQL = F_sqlConn();

	// factrua params
	$lj_factua_params = json_decode($factura_paramas);

	$fecha = date("Y-m-d");
	$metodo_pago = $lj_factua_params->metodo_pago;
	$forma_pago = $lj_factua_params->forma_pago;
	$descuento = $lj_factua_params->descuento;

	//
    $result= $_SESSION['conceptos']; //De variables de session
    $result = $result;
    $subtotal = 0;
    $total = 0;
    $iva = 0;
    foreach($result as $data){
        $subtotal += $data['importe'];

    }


	if($descuento!=''){
        $descuento = (float) $subtotal*($descuento/100);
		$iva = ($subtotal-$descuento)*0.16;
		$total = ($subtotal-$descuento) + $iva;
	}else{
        $iva = $subtotal*0.16;
        $total = $subtotal + $iva;
        $descuento = 0;
	}



	$query ="SELECT * FROM `lugar_expedicion`";
	$result =$objSQL->executeQuery($query);
	$result =$result[0];
	$le_calle =$result['calle'];
	$le_colonia =$result['colonia'];
	$le_cp =$result['codigoPostal'];
	$le_noExt =$result['noExt'];
	$le_noInt =$result['noInt'];
	$le_localidad =$result['localidad'];
	$le_municipio =$result['municipio'];
	$le_estado =$result['estado'];
    $lugar_expedicion = $le_municipio.",".$le_estado;

	$query ="SELECT * FROM `usuario_facturacion`";
	$result =$objSQL->executeQuery($query);
	$result =$result[0];
	$emisor_nombre =$result['nombre'];
	$emisor_calle =$result['calle'];
	$emisor_rfc =$result['RFC'];
	$emisor_noExt =$result['noExterior'];
	$emisor_noInt =$result['noInterior'];
	$emisor_colonia =$result['colonia'];
	$emisor_localidad =$result['localidad'];
	$emisor_municipio =$result['municipio'];
	$emisor_estado =$result['estado'];
	$emisor_pais =$result['pais'];
    $emisor_cp =$result['CodigoPostal'];
	$emisor_celular =$result['celular'];
	$emisor_regimen =$result['regimen'];

	$query ="SELECT * FROM `clientes_facturacion` WHERE idcliente=$idCliente";
	$result =$objSQL->executeQuery($query);
	$result = $result[0];
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
	//$receptor_regimen = $result['regimen'];
	$receptor_telefono = $result['telefono'];

	$Factura = array(
		"Fecha"=>$fecha,
		"Folio"=>11,
		"Moneda"=>"MXN",
		"Serie"=>"A",
		"TipoCambio"=>"1",
		"FormaDePago"=>$forma_pago,
		"MetodoDePago"=>$metodo_pago,
		"Subtotal"=>$subtotal,
		"Total"=>$total,
		"TipoDeComprobante"=>"Ingreso",
		"Descuento"=>$descuento,
		"LugarExpedicion"=>$lugar_expedicion,
		"EmisorExpedicionCalle"=>$le_calle,
		"EmisorExpedicionCP"=>$le_cp,
		"EmisorExpedicionColonia"=>$le_colonia,
		"EmisorExpedicionEstado"=>$le_estado,
		"EmisorExpedicionLocalidad"=>$le_localidad,
		"EmisorExpedicionMunicipio"=>$le_municipio,
		"EmisorExpedicionNoExterior"=>$le_noExt,
		"EmisorExpedicionPais"=>'MEXICO',
		"FechaCertificacion"=>$fecha,
		"Moneda"=>"MXN",
		"TipoCambio"=>"1",
		"NumeroCertificado"=>"123451678",
		"SelloDigitalCFDI"=>"12345678910",
		"SelloDigitalSAT"=>"123456879",
		"FolioSAT_UUID"=>"550e8400-e29b-41d4-a716-446655440000",
		"NumeroCertificadoSAT"=>"123456789",
		"EmisorDomicilioCalle"=>$emisor_calle,
		"EmisorDomicilioNoExterior"=>$emisor_noExt,
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
		"ReceptorDatosMunicipio"=>$receptor_municipio,
		"ReceptorDatosNoExterior"=>$receptor_noExt,
		"ReceptorDatosNoInterior"=>$receptor_noInt,
		"ReceptorDatosPais"=>$receptor_pais,
		"ReceptorRFC"=>$receptor_RFC,
		"ReceptorNombre"=>$receptor_nombre
		);


	$result= $_SESSION['conceptos'];

	$Conceptos = array();

	foreach($result as $data)
	{
		array_push($Conceptos,array("Cantidad" =>$data['cantidad'],"noSerie" => $data['noSerie'],"Descripcion"=>$data['descripcion'],"Unidad"=>$data['unidad'],"ValorUnitario"=>$data['precio_unitario'],"Importe"=>$data['importe']));
	}
	$Factura["Conceptos"] = $Conceptos;
	$pdf = new FPDI('P','mm','Letter');

	//Find out the amount of pages
	$TotalRows = 0;
	$Conceptos = sizeof($Factura["Conceptos"]);
	for($it = 0; $it < $Conceptos; $it++)
	{
		$local = ceil(strlen($Factura["Conceptos"][$it]["Descripcion"])/105.0);
		$TotalRows = $TotalRows + $local;
	}

	$TotalPages = ceil($TotalRows/30.0);
	$IndiceDeConceptos = 0;
	for($Pages = 0; $Pages < $TotalPages; $Pages++)
	{
		$pdf->AddPage();

		//Seccci�n 1
		$pdf->SetMargins(0,0);
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY(44,5);
		$pdf->Cell(116,5,utf8_decode($Factura["EmisorDatosNombre"]),0,0,'C');

		$pdf->SetFont('Arial','',12);
		$pdf->SetXY(44,10);
		$pdf->Cell(116,5,utf8_decode($Factura["EmisorDomicilioCalle"])." ".utf8_decode($Factura["EmisorDomicilioNoExterior"]),0,0,'C');

		$pdf->SetFont('Arial','',12);
		$pdf->SetXY(44,15);
		$pdf->Cell(116,5,'Colonia:'.utf8_decode($Factura["EmisorDomicilioColonia"]). ' C.P :'.utf8_decode($Factura["EmisorDomicilioCP"]).' ',0,0,'C');

		$pdf->SetFont('Arial','',12);
		$pdf->SetXY(44,20);
		$pdf->Cell(116,5,utf8_decode($Factura["EmisorDomicilioMunicipio"]).','.utf8_decode($Factura["EmisorDomicilioEstado"]),0,0,'C');

		$pdf->SetFont('Arial','',12);
		$pdf->SetXY(44,25);
		$pdf->Cell(116,5,'R.F.C. :'.utf8_decode($Factura["EmisorDatosRFC"]),0,0,'C');

		$pdf->SetFont('Arial','',12);
		$pdf->SetXY(44,30);
		$pdf->Cell(116,5,'Lugar de Expedicion: '.utf8_decode($Factura["LugarExpedicion"]),0,0,'C');

		$pdf->SetFont('Arial','',12);
		$pdf->SetXY(44,35);
		$pdf->Cell(116,5,'Correo Electronico: '.utf8_decode($EmitterEmail),0,0,'C');

		$pdf->SetFont('Arial','',12);
		$pdf->SetXY(44,40);
		$pdf->Cell(116,5,'Telefono: '.utf8_decode($EmitterPhone),0,0,'C');

		$pdf->SetXY(161,5);
		$pdf->SetFont('Arial','B',8);
		$pdf->SetFillColor(20,20,20); //Color de fondo
		$pdf->SetTextColor(255,255,255); //Color de texto
		$pdf->Cell(48,5,'F A C T U R A',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		$pdf->SetXY(161,10);
		$pdf->SetFont('Arial','',12);
		$pdf->SetFillColor(255,255,255); //Color de fondo
		$pdf->SetTextColor(0,0,0); //Color de texto
		$pdf->Cell(48,5,'Serie: '.utf8_decode($Factura["Serie"]).' Folio: '.utf8_decode($Factura["Folio"]),1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		//Secci�n 2
		$pdf->SetXY(161,15);
		$pdf->SetFont('Arial','B',8);
		$pdf->SetFillColor(20,20,20); //Color de fondo
		$pdf->SetTextColor(255,255,255); //Color de texto
		$pdf->Cell(48,5,'No. de Certificado',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		$pdf->SetXY(161,20);
		$pdf->SetFont('Arial','',12);
		$pdf->SetFillColor(255,255,255); //Color de fondo
		$pdf->SetTextColor(0,0,0); //Color de texto
		$pdf->Cell(48,5,$Factura["NumeroCertificado"],1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		$pdf->SetXY(161,25);
		$pdf->SetFont('Arial','B',8);
		$pdf->SetFillColor(20,20,20);    //Color de fondo
		$pdf->SetTextColor(255,255,255); //Color de texto
		$pdf->Cell(48,5,'No. Certificado SAT',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		$pdf->SetXY(161,30);
		$pdf->SetFont('Arial','',12);
		$pdf->SetFillColor(255,255,255); //Color de fondo
		$pdf->SetTextColor(0,0,0); //Color de texto
		$pdf->Cell(48,5,utf8_decode($Factura["NumeroCertificadoSAT"]),1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		$pdf->SetXY(161,35);
		$pdf->SetFont('Arial','B',8);
		$pdf->SetFillColor(20,20,20); //Color de fondo
		$pdf->SetTextColor(255,255,255); //Color de texto
		$pdf->Cell(48,5,'Fecha y Hora de Timbrado',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		$pdf->SetXY(161,40);
		$pdf->SetFont('Arial','',12);
		$pdf->SetFillColor(255,255,255); //Color de fondo
		$pdf->SetTextColor(0,0,0); //Color de texto
		$pdf->Cell(48,5,utf8_decode($Factura["FechaCertificacion"]),1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		//Logo
		$pdf->SetXY(5,5);
		$pdf->Image($LogoPath,null,null,55);

		//Secci�n 3
		$pdf->SetXY(5,40);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(75,5,'Folio SAT (UUID): '.utf8_decode($Factura["FolioSAT_UUID"]),1,0,'C',true);

		$pdf->SetXY(81,40);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(75,5,'Tipo De Pago: '.utf8_decode($Factura["FormaDePago"]),1,0,'C',true);

		$pdf->SetXY(5,44);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(75,4,'Metodo de Pago: '.utf8_decode($Factura["MetodoDePago"]),1,0,'C',true);

		$pdf->SetXY(81,44);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(75,4,'Tipo de Comprobante: '.utf8_decode($Factura["TipoDeComprobante"]),1,0,'C',true);

		$pdf->SetXY(5,48);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(75,4,'Fecha de Impresion: '.utf8_decode($Factura["Fecha"]),1,0,'C',true);

		$pdf->SetXY(81,48);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(37.5,4,'Moneda: '.utf8_decode($Factura["Moneda"]),1,0,'C',true);

		$pdf->SetXY(118.5,48);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(37.5,4,'Tipo de Cambio: '.utf8_decode($Factura["TipoCambio"]),1,0,'C',true);

		$pdf->SetXY(0,53);
		$pdf->SetFont('Arial','B',6);
		$pdf->SetFillColor(20,20,20); //Color de fondo
		$pdf->SetTextColor(255,255,255); //Color de texto
		$pdf->Cell(216,5,'D A T O S  D E L  C L I E N T E',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo


		$pdf->SetFillColor(255,255,255); //Color de fondo
		$pdf->SetTextColor(0,0,0); //Color de texto

		$pdf->SetXY(5,59);
		$pdf->SetFont('Arial','',6);

		$pdf->Cell(103,4,'Nombre: '.utf8_decode($Factura["ReceptorNombre"]),0,0,'L',true);

		$pdf->SetXY(108,59);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(103,4,'Calle: '.utf8_decode($Factura["ReceptorDatosCalle"]).' #'.utf8_decode($Factura["ReceptorDatosNoExterior"])." No.Int: ".utf8_decode($Factura["ReceptorDatosNoInterior"]),0,0,'L',true);

		$pdf->SetXY(5,63);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(103,4,'Colonia: '.utf8_decode($Factura["ReceptorDatosColonia"]).' '.$Factura["ReceptorDatosMunicipio"].' , '.utf8_decode($Factura["ReceptorDatosEstado"]),0,0,'L',true);

		$pdf->SetXY(108,63);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(103,4,'C.P.: '.$Factura["ReceptorDatosCP"].' Telefono: '.utf8_decode($ReceptorPhone),0,0,'L',true);

		$pdf->SetXY(5,67);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(103,4,'RFC: '.utf8_decode($Factura["ReceptorRFC"]),0,0,'L',true);

		$pdf->SetXY(108,67);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(103,4,'Correo Electronico: '.utf8_decode($ReceptorEmail),0,0,'L',true);


		$pdf->SetFillColor(20,20,20); //Color de fondo
		$pdf->SetTextColor(255,255,255); //Color de texto

		$pdf->SetXY(5,67);
		$pdf->SetFont('Arial','B',6);
		$pdf->Cell(20,4,'Cantidad',0,0,'C',true);
        $pdf->Cell(20,4,'No.Serie',0,0,'C',true);
		$pdf->Cell(111,4,'Descripcion',0,0,'C',true);
		$pdf->Cell(15,4,'Unidad',0,0,'C',true);
		$pdf->Cell(20,4,'Precio',0,0,'C',true);
		$pdf->Cell(20,4,'Importe',0,0,'C',true);


		$QtyConceptos = sizeof($Factura["Conceptos"]);

		$pdf->SetDrawColor(0,0,0); //Color de bordes
		$pdf->SetFillColor(255,255,255); //Color de fondo
		$pdf->SetTextColor(0,0,0);
		$PunteroDeFila = 0;
		$pdf->SetFont('Arial','B',8);
		while($IndiceDeConceptos < $QtyConceptos)
		{
			$rowsCharacters = str_split(utf8_decode($Factura["Conceptos"][$IndiceDeConceptos]["Descripcion"]),105); //Regreso de Carro
			$rows = sizeof($rowsCharacters);

			if($PunteroDeFila + $rows > 30 && ($Pages + 1) == $TotalPages) //Mejor que Sobre a que falte...
			{
				$TotalPages++; //Agregar otra hoja
				break;
			}

			else if($PunteroDeFila + $rows > 30 && ($Pages + 1) < $TotalPages)
				break;

			$pdf->SetXY(5,71 + (4 * ($PunteroDeFila) ));
			$pdf->Cell(20,4*$rows,utf8_decode((string)$Factura["Conceptos"][$IndiceDeConceptos]["Cantidad"]),1,0,'C',true);

            $pdf->SetXY(25,71 + (4 * ($PunteroDeFila) ));
			$pdf->Cell(20,4*$rows,utf8_decode((string)$Factura["Conceptos"][$IndiceDeConceptos]["noSerie"]),1,0,'C',true);

			$pdf->SetXY(45,71 + (4 * ($PunteroDeFila)));
			$pdf->Cell(136,4 * $rows,'',1,0,'C',true);
			$pdf->SetDrawColor(255,255,255); //Color de bordes

			for($k = 0; $k < $rows; $k++)
			{
				$pdf->SetXY(46,71.5 + (4 * ($PunteroDeFila)) + ($k * 4));
				$pdf->Cell(135,3,$rowsCharacters[$k],1,0,'L',true);
			}

			$pdf->SetDrawColor(0,0,0); //Color de bordes

	
			$valorU =round($Factura["Conceptos"][$IndiceDeConceptos]["ValorUnitario"],2);
			$conceptoImporte = round($Factura["Conceptos"][$IndiceDeConceptos]["Importe"],2);
			
				$pdf->SetXY(156,71 + (4 * ($PunteroDeFila)));
				$pdf->Cell(15,4*$rows,utf8_decode($Factura["Conceptos"][$IndiceDeConceptos]["Unidad"]),1,0,'C',true);
				$pdf->Cell(20,4*$rows,utf8_decode((string)$valorU),1,0,'C',true);
				$pdf->Cell(20,4*$rows,utf8_decode((string)$conceptoImporte),1,0,'C',true);



			if($rows > 0)
                $PunteroDeFila = $PunteroDeFila + $rows;
			else
				$PunteroDeFila++;

			$IndiceDeConceptos++;
		}
		if($Pages + 1 == $TotalPages)
		{
			$pdf->SetDrawColor(20,20,20); //Color de bordes
			$pdf->SetFillColor(20,20,20); //Color de fondo
			$pdf->SetTextColor(255,255,255); //Color de texto


            //if($Factura["Descuento"] <= 0)
            //{
            //$pdf->SetXY(5,192);
            //$pdf->Cell(155,4,"Importe con Letra",1,0,'C',true);

            //$pdf->SetXY(160,192);
            //$pdf->Cell(25.75,4,"SubTotal: ",1,0,'C',true);

            //$pdf->SetXY(160,196);
            //$pdf->Cell(25.75,4,"I.V.A.: ",1,0,'C',true);

            //$pdf->SetXY(160,200);
            //$pdf->Cell(25.75,4,"Total: ",1,0,'C',true);

            //$pdf->SetDrawColor(255,255,255); //Color de bordes
            //$pdf->SetFillColor(255,255,255); //Color de fondo
            //$pdf->SetTextColor(0,0,0); //Color de texto

            //$TotalLetras = num2letras($Factura["Total"]);

            //$pdf->SetXY(5,196);
            //$pdf->SetFont('Arial','',7);
            //$pdf->Cell(154.5,4,$TotalLetras,1,0,'C',true);
            //$pdf->SetFont('Arial','B',5);

            //$pdf->SetXY(185.25,192);
            //$pdf->Cell(25.75,4,$Factura["Subtotal"],1,0,'C',true);

            //$IVA = round($Factura["Total"] - $Factura["Subtotal"],2);

            //$pdf->SetXY(185.25,196);
            //$pdf->Cell(25.75,4,$IVA,1,0,'C',true);

            //$pdf->SetXY(185.25,200);
            //$pdf->Cell(25.75,4,$Factura["Total"],1,0,'C',true);
            ////}
            //else
            //{
            $pdf->SetXY(5,192);
			$pdf->Cell(155,4,"Importe con Letra",1,0,'C',true);
			$pdf->SetFont('Arial','B',12);
			
			$pdf->SetXY(160,192);
			$pdf->Cell(25.75,4,"SubTotal: ",1,0,'C',true);

            $pdf->SetXY(160,196);
			$pdf->Cell(25.75,4,"Descuento: ",1,0,'C',true);

			$pdf->SetXY(160,200);
			$pdf->Cell(25.75,4,"I.V.A.: ",1,0,'C',true);

			$pdf->SetXY(160,204);
			$pdf->Cell(25.75,4,"Total: ",1,0,'C',true);

			$pdf->SetDrawColor(255,255,255); //Color de bordes
			$pdf->SetFillColor(255,255,255); //Color de fondo
			$pdf->SetTextColor(0,0,0); //Color de texto

			$TotalLetras = num2letras($Factura["Total"]);

			$pdf->SetXY(5,196);
			$pdf->SetFont('Arial','',11);
			$pdf->Cell(154.5,4,$TotalLetras,1,0,'C',true);

			$pdf->SetFont('Arial','B',14);
			$Factura["Total"] = round($Factura["Total"],2);
			
			$Factura["Subtotal"] = round($Factura["Subtotal"],2);
			$Factura["Descuento"] = round($Factura["Descuento"],2);
			$IVARet = round($IVARet,2);
			$ISRRet = round($ISRRet,2);
			$IVA = round($IVA,2);

			$pdf->SetXY(185.25,192);
			$pdf->Cell(25.75,4,'$'.$Factura["Subtotal"],1,0,'C',true);

            $pdf->SetXY(185.25,196);
			$pdf->Cell(25.75,4,'$'.$Factura["Descuento"],1,0,'C',true);


            //Modificar
			$IVA = round($Factura["Total"] - $Factura["Subtotal"],2);

			$pdf->SetXY(185.25,200);
			$pdf->Cell(25.75,4,'$'.$IVA,1,0,'C',true);

			$pdf->SetXY(185.25,204);
			$pdf->Cell(25.75,4,'$'.$Factura["Total"],1,0,'C',true);
            //}

		}

	
		$pdf->SetDrawColor(20,20,20); //Color de bordes
		$pdf->SetFillColor(20,20,20); //Color de fondo
		$pdf->SetTextColor(255,255,255); //Color de texto

		$pdf->SetXY(56,216);
		$pdf->Cell(154.5,4,"Sello Digital del CFDI",1,0,'C',true);

		$pdf->SetXY(56,229);
		$pdf->Cell(154.5,4,"Sello Digital SAT",1,0,'C',true);

		$pdf->SetXY(5,242);
		$pdf->Cell(206,4,"Cadena Original del Complemento de Certificacion del SAT",1,0,'C',true);

		$pdf->SetDrawColor(255,255,255); //Color de bordes
		$pdf->SetFillColor(255,255,255); //Color de fondo
		$pdf->SetTextColor(0,0,0); //Color de texto
		$pdf->SetFont('Arial','B',5);

		$pdf->SetXY(56,220);
		$QtyChars = strlen($Factura["SelloDigitalCFDI"]);
		$SelloDigitalCFDI = str_split($Factura["SelloDigitalCFDI"],$QtyChars/2);
		$pdf->Cell(154.5,4,$SelloDigitalCFDI[0],1,0,'C',true);
		$pdf->SetXY(56,224);
		$pdf->Cell(154.5,4,$SelloDigitalCFDI[1],1,0,'C',true);

		$pdf->SetXY(56,233);
		$QtyChars = strlen($Factura["SelloDigitalSAT"]);
		$SelloDigitalSAT = str_split($Factura["SelloDigitalSAT"],$QtyChars/2);
		$pdf->Cell(154.5,4,$SelloDigitalSAT[0],1,0,'C',true);
		$pdf->SetXY(56,237);
		$pdf->Cell(154.5,4,$SelloDigitalSAT[1],1,0,'C',true);

		$ComplementoCertificacion = ObtenerCadenaComp($Factura["FolioSAT_UUID"],$Factura["FechaCertificacion"],$Factura["SelloDigitalCFDI"],$Factura["NumeroCertificadoSAT"]);

		$pdf->SetXY(5,246);
		$QtyChars = strlen($ComplementoCertificacion);
		$ComplementoCertificacion = str_split($ComplementoCertificacion,$QtyChars/2);
		$pdf->Cell(206,4,$ComplementoCertificacion[0],1,0,'C',true);
		$pdf->SetXY(5,250);
		$pdf->Cell(206,4,$ComplementoCertificacion[1],1,0,'C',true);

		$pdf->SetXY(5,201);
		$pdf->Image($Code2DPath,null,null,40);

		$pdf->SetXY(5,255);
		$pdf->Cell(206,4,"Este documento es una representacion impresa de un CFDI",1,0,'C',true);
	}

	$pdf->Output($OutputFileName);

}
function GeneratePDF($LogoPath,$XmlPath,$Code2DPath,$OutputFileName,$EmitterEmail,$EmitterPhone,$ReceptorEmail,$ReceptorPhone)
{
$Factura = OpenCompleteXMLFile($XmlPath, false);

$pdf = new FPDI('P','mm','Letter');

//Find out the amount of pages
$TotalRows = 0;
$Conceptos = sizeof($Factura["Conceptos"]);
for($it = 0; $it < $Conceptos; $it++)
{
   $local = ceil(strlen($Factura["Conceptos"][$it]["Descripcion"])/105.0);
   $TotalRows = $TotalRows + $local;
}

$TotalPages = ceil($TotalRows/30.0);
$IndiceDeConceptos = 0;
for($Pages = 0; $Pages < $TotalPages; $Pages++)
{
$pdf->AddPage();

//Seccci�n 1
$pdf->SetMargins(0,0);
$pdf->SetFont('Arial','B',12);
$pdf->SetXY(44,5);
$pdf->Cell(116,5,utf8_decode($Factura["EmisorDatosNombre"]),0,0,'C');

$pdf->SetFont('Arial','',12);
$pdf->SetXY(44,10);
$pdf->Cell(116,5,utf8_decode($Factura["EmisorDomicilioCalle"])." ".utf8_decode($Factura["EmisorDomicilioNoExterior"]),0,0,'C');

$pdf->SetFont('Arial','',12);
$pdf->SetXY(44,15);
$pdf->Cell(116,5,'Colonia:'.utf8_decode($Factura["EmisorDomicilioColonia"]). ' C.P :'.utf8_decode($Factura["EmisorDomicilioCP"]).' ',0,0,'C');

$pdf->SetFont('Arial','',12);
$pdf->SetXY(44,20);
$pdf->Cell(116,5,utf8_decode($Factura["EmisorDomicilioMunicipio"]).','.utf8_decode($Factura["EmisorDomicilioEstado"]),0,0,'C');

$pdf->SetFont('Arial','',12);
$pdf->SetXY(44,25);
$pdf->Cell(116,5,'R.F.C. :'.utf8_decode($Factura["EmisorDatosRFC"]),0,0,'C');

$pdf->SetFont('Arial','',12);
$pdf->SetXY(44,30);
$pdf->Cell(116,5,'Lugar de Expedicion: '.utf8_decode($Factura["LugarExpedicion"]),0,0,'C');

$pdf->SetFont('Arial','',12);
$pdf->SetXY(44,35);
$pdf->Cell(116,5,'Correo Electronico: '.utf8_decode($EmitterEmail),0,0,'C');

$pdf->SetFont('Arial','',12);
$pdf->SetXY(44,40);
$pdf->Cell(116,5,'Telefono: '.utf8_decode($EmitterPhone),0,0,'C');

$pdf->SetXY(161,5);
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(20,20,20); //Color de fondo
$pdf->SetTextColor(255,255,255); //Color de texto
$pdf->Cell(48,5,'F A C T U R A',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

$pdf->SetXY(161,10);
$pdf->SetFont('Arial','',12);
$pdf->SetFillColor(255,255,255); //Color de fondo
$pdf->SetTextColor(0,0,0); //Color de texto
$pdf->Cell(48,5,'Serie: '.utf8_decode($Factura["Serie"]).' Folio: '.utf8_decode($Factura["Folio"]),1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

//Secci�n 2
$pdf->SetXY(161,15);
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(20,20,20); //Color de fondo
$pdf->SetTextColor(255,255,255); //Color de texto
$pdf->Cell(48,5,'No. de Certificado',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

$pdf->SetXY(161,20);
$pdf->SetFont('Arial','',12);
$pdf->SetFillColor(255,255,255); //Color de fondo
$pdf->SetTextColor(0,0,0); //Color de texto
$pdf->Cell(48,5,utf8_decode($Factura["NumeroCertificado"]),1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

$pdf->SetXY(161,25);
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(20,20,20);    //Color de fondo
$pdf->SetTextColor(255,255,255); //Color de texto
$pdf->Cell(48,5,'No. Certificado SAT',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

$pdf->SetXY(161,30);
$pdf->SetFont('Arial','',12);
$pdf->SetFillColor(255,255,255); //Color de fondo
$pdf->SetTextColor(0,0,0); //Color de texto
$pdf->Cell(48,5,utf8_decode($Factura["NumeroCertificadoSAT"]),1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

$pdf->SetXY(161,35);
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(20,20,20); //Color de fondo
$pdf->SetTextColor(255,255,255); //Color de texto
$pdf->Cell(48,5,'Fecha y Hora de Timbrado',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

$pdf->SetXY(161,40);
$pdf->SetFont('Arial','',12);
$pdf->SetFillColor(255,255,255); //Color de fondo
$pdf->SetTextColor(0,0,0); //Color de texto
$pdf->Cell(48,5,utf8_decode($Factura["FechaCertificacion"]),1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

//Logo
$pdf->SetXY(5,5);
$pdf->Image($LogoPath,null,null,55);

//Secci�n 3
$pdf->SetXY(5,40);
$pdf->SetFont('Arial','',8);
$pdf->Cell(75,5,'Folio SAT (UUID): '.utf8_decode($Factura["FolioSAT_UUID"]),1,0,'C',true);

$pdf->SetXY(81,40);
$pdf->SetFont('Arial','',8);
$pdf->Cell(75,5,'Tipo De Pago: '.utf8_decode($Factura["FormaDePago"]),1,0,'C',true);

$pdf->SetXY(5,44);
$pdf->SetFont('Arial','',8);
$pdf->Cell(75,4,'Metodo de Pago: '.utf8_decode($Factura["MetodoDePago"]),1,0,'C',true);

$pdf->SetXY(81,44);
$pdf->SetFont('Arial','',8);
$pdf->Cell(75,4,'Tipo de Comprobante: '.utf8_decode($Factura["TipoDeComprobante"]),1,0,'C',true);

$pdf->SetXY(5,48);
$pdf->SetFont('Arial','',8);
$pdf->Cell(75,4,'Fecha de Impresion: '.utf8_decode($Factura["Fecha"]),1,0,'C',true);

$pdf->SetXY(81,48);
$pdf->SetFont('Arial','',8);
$pdf->Cell(37.5,4,'Moneda: '.utf8_decode($Factura["Moneda"]),1,0,'C',true);

$pdf->SetXY(118.5,48);
$pdf->SetFont('Arial','',8);
$pdf->Cell(37.5,4,'Tipo de Cambio: '.utf8_decode($Factura["TipoCambio"]),1,0,'C',true);

$pdf->SetXY(0,53);
$pdf->SetFont('Arial','B',8);
$pdf->SetFillColor(20,20,20); //Color de fondo
$pdf->SetTextColor(255,255,255); //Color de texto
$pdf->Cell(216,5,'D A T O S  D E L  C L I E N T E',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo


$pdf->SetFillColor(255,255,255); //Color de fondo
$pdf->SetTextColor(0,0,0); //Color de texto

$pdf->SetXY(5,59);
$pdf->SetFont('Arial','',10);
$pdf->Cell(103,4,'Nombre: '.utf8_decode($Factura["ReceptorNombre"]),0,0,'L',true);

$pdf->SetXY(108,59);
$pdf->SetFont('Arial','',10);
$pdf->Cell(103,4,'Calle: '.utf8_decode($Factura["ReceptorDatosCalle"]).' #'.utf8_decode($Factura["ReceptorDatosNoExterior"])." No.Int: ".utf8_decode($Factura["ReceptorDatosNoInterior"]),0,0,'L',true);

$pdf->SetXY(5,63);
$pdf->SetFont('Arial','',10);
$pdf->Cell(103,4,'Colonia: '.utf8_decode($Factura["ReceptorDatosColonia"]).' '.utf8_decode($Factura["ReceptorDatosMunicipio"]).' , '.utf8_decode($Factura["ReceptorDatosEstado"]),0,0,'L',true);

$pdf->SetXY(108,63);
$pdf->SetFont('Arial','',10);
$pdf->Cell(103,4,'C.P.: '.utf8_decode($Factura["ReceptorDatosCP"]).' Telefono: '.utf8_decode($ReceptorPhone),0,0,'L',true);

$pdf->SetXY(5,67);
$pdf->SetFont('Arial','',10);
$pdf->Cell(103,4,'RFC: '.utf8_decode($Factura["ReceptorRFC"]),0,0,'L',true);

$pdf->SetXY(108,67);
$pdf->SetFont('Arial','',10);
$pdf->Cell(103,4,'Correo Electronico: '.utf8_decode($ReceptorEmail),0,0,'L',true);


$pdf->SetFillColor(20,20,20); //Color de fondo
$pdf->SetTextColor(255,255,255); //Color de texto

$pdf->SetXY(5,67);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(20,4,'Cantidad',0,0,'C',true);
$pdf->Cell(20,4,'No.Serie',0,0,'C',true);
$pdf->Cell(111,4,'Descripcion',0,0,'C',true);
$pdf->Cell(15,4,'Unidad',0,0,'C',true);
$pdf->Cell(20,4,'Precio',0,0,'C',true);
$pdf->Cell(20,4,'Importe',0,0,'C',true);

$QtyConceptos = sizeof($Factura["Conceptos"]);

$pdf->SetDrawColor(0,0,0); //Color de bordes
$pdf->SetFillColor(255,255,255); //Color de fondo
$pdf->SetTextColor(0,0,0);
$PunteroDeFila = 0;

while($IndiceDeConceptos < $QtyConceptos)
{
    $content_descripcion = utf8_decode($Factura["Conceptos"][$IndiceDeConceptos]["Descripcion"]);
    $rowsCharacters = str_split($content_descripcion,105);
    $rows = sizeof($rowsCharacters);
    $importaction = '';
if(isset($Factura["Conceptos"][$IndiceDeConceptos]['Aduana'])){
    $importaction.="Pedimento:".$Factura["Conceptos"][$IndiceDeConceptos]['Numero'];
    $importaction.=" Aduana:".$Factura["Conceptos"][$IndiceDeConceptos]['Aduana'];
    $importaction.=" Fecha:".$Factura["Conceptos"][$IndiceDeConceptos]['Fecha'];
    $rows++;

    }


   if($PunteroDeFila + $rows > 30 && ($Pages + 1) == $TotalPages) //Mejor que Sobre a que falte...
    {
        $TotalPages++; //Agregar otra hoja
        break;
    }

    else if($PunteroDeFila + $rows > 30 && ($Pages + 1) < $TotalPages)
        break;

    $pdf->SetXY(5,71 + (4 * ($PunteroDeFila) ));
    $pdf->Cell(20,4*$rows,utf8_decode((string)$Factura["Conceptos"][$IndiceDeConceptos]["Cantidad"]),1,0,'C',true);

    $pdf->SetXY(25,71 + (4 * ($PunteroDeFila) ));
    $pdf->Cell(20,4*$rows,utf8_decode((string)$Factura["Conceptos"][$IndiceDeConceptos]["noSerie"]),1,0,'C',true);

    $pdf->SetXY(45,71 + (4 * ($PunteroDeFila)));
    $pdf->Cell(136,4 * $rows,'',1,0,'C',true);
    $pdf->SetDrawColor(255,255,255); //Color de bordes
    $k = 0;
    $limit =($importaction!='')?($rows -1):$rows;
    for(; $k < $limit; $k++)
    {
        $pdf->SetXY(46,71.5 + (4 * ($PunteroDeFila)) + ($k * 4));
        $pdf->Cell(135,3,$rowsCharacters[$k],1,0,'L',true);
    }
    if($importaction!=''){
        $pdf->SetXY(46,71.5 + (4 * ($PunteroDeFila)) + ($limit * 4));
        $pdf->Cell(135,3,$importaction,1,0,'L',true);
    }
    $pdf->SetDrawColor(0,0,0); //Color de bordes
$valorU =round($Factura["Conceptos"][$IndiceDeConceptos]["ValorUnitario"],2);
$conceptoImporte = round($Factura["Conceptos"][$IndiceDeConceptos]["Importe"],2);

    $pdf->SetXY(156,71 + (4 * ($PunteroDeFila)));
    $pdf->Cell(15,4*$rows,utf8_decode($Factura["Conceptos"][$IndiceDeConceptos]["Unidad"]),1,0,'C',true);
    $pdf->Cell(20,4*$rows,utf8_decode((string)$valorU),1,0,'C',true);
    $pdf->Cell(20,4*$rows,utf8_decode((string)$conceptoImporte),1,0,'C',true);

    if($rows > 0)
        $PunteroDeFila = $PunteroDeFila + $rows;
    else
        $PunteroDeFila++;

    $IndiceDeConceptos++;
}
    if($Pages + 1 == $TotalPages)
    {
        $IVA = (float)0.0;
        $SubtotalConDescuento = (float)$Factura["Subtotal"];

        if(isset($Factura["Descuento"])){
            $SubtotalConDescuento = $Factura["Subtotal"] - $Factura["Descuento"];
            $IVA =  round(($SubtotalConDescuento) * 0.16,2);
        }
        else
            $IVA = round($SubtotalConDescuento * 0.16,2);


        //IVARet = 0.66666 * IVA
        $IVARet = (float)0.0;
        if(isset($Factura["RetencionesIVA"]))
           $IVARet = round((float)$Factura["RetencionesIVA"],2);


        //ISRRet = (10-30%) * ISR
        $ISRRet = (float)0.0;
        if(isset($Factura["RetencionesISR"]))
            $ISRRet = round((float)$Factura["RetencionesISR"],2);

        //print_r("ISRRet".$ISRRet."..--..");
        //print_r("IVARet".$IVARet."..--..");
        //print_r("ISR".$ISR."..--..");
        //print_r("IVA".$IVA."..--..");
        //print_r("SubtotalCon".$SubtotalConDescuento."..--..");

        $pdf->SetDrawColor(20,20,20); //Color de bordes
        $pdf->SetFillColor(20,20,20); //Color de fondo
        $pdf->SetTextColor(255,255,255); //Color de texto

        $pdf->SetXY(5,192);
        $pdf->Cell(155,4,"Importe con Letra",1,0,'C',true);

			$pdf->SetXY(160,192);
			
            $pdf->Cell(25.75,4,"SubTotal: ",1,0,'C',true);

            $pdf->SetXY(160,196);
            $pdf->Cell(25.75,4,"Descuento: ",1,0,'C',true);

            $pdf->SetXY(160,200);
            $pdf->Cell(25.75,4,"RetIVA: ",1,0,'C',true);

            $pdf->SetXY(160,204);
            $pdf->Cell(25.75,4,"RetISR: ",1,0,'C',true);

            $pdf->SetXY(160,208);
            $pdf->Cell(25.75,4,"I.V.A.: ",1,0,'C',true);

            $pdf->SetXY(160,212);
            $pdf->Cell(25.75,4,"Total: ",1,0,'C',true);

            $pdf->SetDrawColor(255,255,255); //Color de bordes
            $pdf->SetFillColor(255,255,255); //Color de fondo
            $pdf->SetTextColor(0,0,0); //Color de texto

            $TotalLetras = num2letras($Factura["Total"]);

            $pdf->SetXY(5,196);
            $pdf->SetFont('Arial','',11);
            $pdf->Cell(154.5,4,$TotalLetras,1,0,'C',true);
             
			 $pdf->SetFont('Arial','B',14);
			 $Factura["Total"] = round($Factura["Total"],2);
			 
			 $Factura["Subtotal"] = round($Factura["Subtotal"],2);
			 $Factura["Descuento"] = round($Factura["Descuento"],2);
			 $IVARet = round($IVARet,2);
			 $ISRRet = round($ISRRet,2);
			 $IVA = round($IVA,2);

			 
            $pdf->SetXY(185.25,192);
            $pdf->Cell(25.75,4,'$'.$Factura["Subtotal"],1,0,'C',true);

            $pdf->SetXY(185.25,196);
            $pdf->Cell(25.75,4,'$'.$Factura["Descuento"],1,0,'C',true);

            $pdf->SetXY(185.25,200);
            $pdf->Cell(25.75,4,'$'.$IVARet,1,0,'C',true);

            $pdf->SetXY(185.25,204);
            $pdf->Cell(25.75,4,'$'.$ISRRet,1,0,'C',true);

            $pdf->SetXY(185.25,208);
            $pdf->Cell(25.75,4,'$'.$IVA,1,0,'C',true);

            $pdf->SetXY(185.25,212);
            $pdf->Cell(25.75,4,'$'.$Factura["Total"],1,0,'C',true);

    }
    $pdf->SetFont('Arial','B',5);

    $pdf->SetDrawColor(20,20,20); //Color de bordes
    $pdf->SetFillColor(20,20,20); //Color de fondo
    $pdf->SetTextColor(255,255,255); //Color de texto

    $pdf->SetXY(56,216);
    $pdf->Cell(154.5,4,"Sello Digital del CFDI",1,0,'C',true);

    $pdf->SetXY(56,229);
    $pdf->Cell(154.5,4,"Sello Digital SAT",1,0,'C',true);

    $pdf->SetXY(5,242);
    $pdf->Cell(206,4,"Cadena Original del Complemento de Certificacion del SAT",1,0,'C',true);

    $pdf->SetDrawColor(255,255,255); //Color de bordes
    $pdf->SetFillColor(255,255,255); //Color de fondo
    $pdf->SetTextColor(0,0,0); //Color de texto


    $pdf->SetXY(56,220);
    $QtyChars = strlen($Factura["SelloDigitalCFDI"]);
    $SelloDigitalCFDI = str_split($Factura["SelloDigitalCFDI"],$QtyChars/2);
    $pdf->Cell(154.5,4,$SelloDigitalCFDI[0],1,0,'C',true);
    $pdf->SetXY(56,224);
    $pdf->Cell(154.5,4,$SelloDigitalCFDI[1],1,0,'C',true);

    $pdf->SetXY(56,233);
    $QtyChars = strlen($Factura["SelloDigitalSAT"]);
    $SelloDigitalSAT = str_split($Factura["SelloDigitalSAT"],$QtyChars/2);
    $pdf->Cell(154.5,4,$SelloDigitalSAT[0],1,0,'C',true);
    $pdf->SetXY(56,237);
    $pdf->Cell(154.5,4,$SelloDigitalSAT[1],1,0,'C',true);

    $ComplementoCertificacion = ObtenerCadenaComp($Factura["FolioSAT_UUID"],$Factura["FechaCertificacion"],$Factura["SelloDigitalCFDI"],$Factura["NumeroCertificadoSAT"]);

    $pdf->SetXY(5,246);
    $QtyChars = strlen($ComplementoCertificacion);
    $ComplementoCertificacion = str_split($ComplementoCertificacion,$QtyChars/2);
    $pdf->Cell(206,4,$ComplementoCertificacion[0],1,0,'C',true);
    $pdf->SetXY(5,250);
    $pdf->Cell(206,4,$ComplementoCertificacion[1],1,0,'C',true);

    $pdf->SetXY(5,201);
    $pdf->Image($Code2DPath,null,null,40);

    $pdf->SetXY(5,255);
    $pdf->Cell(206,4,"Este documento es una representacion impresa de un CFDI",1,0,'C',true);
}

$pdf->Output($OutputFileName);
}
function generatePendientePDF($idUsuario,$folio,$idCliente,$LogoPath,$Code2DPath,$OutputFileName,$EmitterEmail,$EmitterPhone,$ReceptorEmail,$ReceptorPhone)
{
     $objSQL =F_sqlConn();

    $query ="SELECT * FROM `factura_pendiente` WHERE folio_factura='$folio';";
	$factura_pendienteData = $objSQL->executeQuery($query);
    $factura_pendienteData = $factura_pendienteData[0];
    // factrua params

	$fecha = date("Y-m-d");
	$metodo_pago = $factura_pendienteData['metodo_pago'];
	$forma_pago = $factura_pendienteData['forma_pago'];
	$descuento = $factura_pendienteData['descuento'];

	//
    $resultConceptos= $objSQL->executeQuery("SELECT * FROM `folio_conceptos` WHERE folio_factura='$folio';");

    $subtotal = 0;
    $total = 0;
    $iva = 0;
    foreach($resultConceptos as $data){
        $subtotal +=(float)$data['importe'];
    }


	if($descuento!=''){
		$iva = ($subtotal-$descuento)*0.16;
		$total = ($subtotal-$descuento) + $iva;
	}else{
        $iva = $subtotal*0.16;
        $total = $subtotal + $iva;
	}



	$query ="SELECT * FROM `lugar_expedicion` WHERE idusuario='$idUsuario'";
	$result =$objSQL->executeQuery($query);
	$result =$result[0];
	$le_calle =$result['calle'];
	$le_colonia =$result['colonia'];
	$le_cp =$result['codigoPostal'];
	$le_noExt =$result['noExt'];
	$le_noInt =$result['noInt'];
	$le_localidad =$result['localidad'];
	$le_municipio =$result['municipio'];
	$le_estado =$result['estado'];
    $lugar_expedicion = $le_municipio.",".$le_estado;

	$query ="SELECT * FROM `usuario_facturacion`";
	$result =$objSQL->executeQuery($query);
	$result =$result[0];
	$emisor_nombre =$result['nombre'];
	$emisor_calle =$result['calle'];
	$emisor_rfc =$result['RFC'];
	$emisor_noExt =$result['noExterior'];
	$emisor_noInt =$result['noInterior'];
	$emisor_colonia =$result['colonia'];
	$emisor_localidad =$result['localidad'];
	$emisor_municipio =$result['municipio'];
	$emisor_estado =$result['estado'];
	$emisor_pais =$result['pais'];
    $emisor_cp =$result['CodigoPostal'];
	$emisor_celular =$result['celular'];
	$emisor_regimen =$result['regimen'];

	$query ="SELECT * FROM `clientes_facturacion` WHERE idcliente=$idCliente";
	$result =$objSQL->executeQuery($query);
	$result = $result[0];
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
	//$receptor_regimen = $result['regimen'];
	$receptor_telefono = $result['telefono'];

	$Factura = array(
		"Fecha"=>$fecha,
		"Folio"=>11,
		"Moneda"=>"MXN",
		"Serie"=>"A",
		"TipoCambio"=>"1",
		"FormaDePago"=>$forma_pago,
		"MetodoDePago"=>$metodo_pago,
		"Subtotal"=>$subtotal,
		"Total"=>$total,
		"TipoDeComprobante"=>"Ingreso",
		"Descuento"=>$descuento,
		"LugarExpedicion"=>$lugar_expedicion,
		"EmisorExpedicionCalle"=>$le_calle,
		"EmisorExpedicionCP"=>$le_cp,
		"EmisorExpedicionColonia"=>$le_colonia,
		"EmisorExpedicionEstado"=>$le_estado,
		"EmisorExpedicionLocalidad"=>$le_localidad,
		"EmisorExpedicionMunicipio"=>$le_municipio,
		"EmisorExpedicionNoExterior"=>$le_noExt,
		"EmisorExpedicionPais"=>'MEXICO',
		"FechaCertificacion"=>$fecha,
		"Moneda"=>"MXN",
		"TipoCambio"=>"1",
		"NumeroCertificado"=>"123451678",
		"SelloDigitalCFDI"=>"12345678910",
		"SelloDigitalSAT"=>"123456879",
		"FolioSAT_UUID"=>"550e8400-e29b-41d4-a716-446655440000",
		"NumeroCertificadoSAT"=>"123456789",
		"EmisorDomicilioCalle"=>$emisor_calle,
		"EmisorDomicilioNoExterior"=>$emisor_noExt,
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
		"ReceptorDatosMunicipio"=>$receptor_municipio,
		"ReceptorDatosNoExterior"=>$receptor_noExt,
		"ReceptorDatosNoInterior"=>$receptor_noInt,
		"ReceptorDatosPais"=>$receptor_pais,
		"ReceptorRFC"=>$receptor_RFC,
		"ReceptorNombre"=>$receptor_nombre
		);



	$Conceptos = array();

	foreach($resultConceptos as $data)
	{
		array_push($Conceptos,array("Cantidad" =>$data['cantidad'],"noSerie"=>$data['noSerie'],"Descripcion"=>$data['descripcion'],"Unidad"=>$data['unidad'],"ValorUnitario"=>$data['precio_unitario'],"Importe"=>$data['importe']));
	}
	$Factura["Conceptos"] = $Conceptos;
	$pdf = new FPDI('P','mm','Letter');

	//Find out the amount of pages
	$TotalRows = 0;
	$Conceptos = sizeof($Factura["Conceptos"]);
	for($it = 0; $it < $Conceptos; $it++)
	{
		$local = ceil(strlen($Factura["Conceptos"][$it]["Descripcion"])/105.0);
		$TotalRows = $TotalRows + $local;
	}

	$TotalPages = ceil($TotalRows/30.0);
	$IndiceDeConceptos = 0;
	for($Pages = 0; $Pages < $TotalPages; $Pages++)
	{
		$pdf->AddPage();

		//Seccci�n 1
		$pdf->SetMargins(0,0);
		$pdf->SetFont('Arial','B',12);
		$pdf->SetXY(44,5);
		$pdf->Cell(116,5,utf8_decode($Factura["EmisorDatosNombre"]),0,0,'C');

		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(44,10);
		$pdf->Cell(116,5,utf8_decode($Factura["EmisorDomicilioCalle"])." ".utf8_decode($Factura["EmisorDomicilioNoExterior"]),0,0,'C');

		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(44,15);
		$pdf->Cell(116,5,'Colonia:'.utf8_decode($Factura["EmisorDomicilioColonia"]). ' C.P :'.utf8_decode($Factura["EmisorDomicilioCP"]).' ',0,0,'C');

		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(44,20);
		$pdf->Cell(116,5,utf8_decode($Factura["EmisorDomicilioMunicipio"]).','.utf8_decode($Factura["EmisorDomicilioEstado"]),0,0,'C');

		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(44,25);
		$pdf->Cell(116,5,'R.F.C. :'.utf8_decode($Factura["EmisorDatosRFC"]),0,0,'C');

		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(44,30);
		$pdf->Cell(116,5,'Lugar de Expedicion: '.utf8_decode($Factura["LugarExpedicion"]),0,0,'C');

		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(44,35);
		$pdf->Cell(116,5,'Correo Electronico: '.utf8_decode($EmitterEmail),0,0,'C');

		$pdf->SetFont('Arial','',8);
		$pdf->SetXY(44,40);
		$pdf->Cell(116,5,'Telefono: '.utf8_decode($EmitterPhone),0,0,'C');

		$pdf->SetXY(161,5);
		$pdf->SetFont('Arial','B',8);
		$pdf->SetFillColor(20,20,20); //Color de fondo
		$pdf->SetTextColor(255,255,255); //Color de texto
		$pdf->Cell(48,5,'F A C T U R A',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		$pdf->SetXY(161,10);
		$pdf->SetFont('Arial','',8);
		$pdf->SetFillColor(255,255,255); //Color de fondo
		$pdf->SetTextColor(0,0,0); //Color de texto
		$pdf->Cell(48,5,'Serie: '.utf8_decode($Factura["Serie"]).' Folio: '.utf8_decode($Factura["Folio"]),1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		//Secci�n 2
		$pdf->SetXY(161,15);
		$pdf->SetFont('Arial','B',8);
		$pdf->SetFillColor(20,20,20); //Color de fondo
		$pdf->SetTextColor(255,255,255); //Color de texto
		$pdf->Cell(48,5,'No. de Certificado',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		$pdf->SetXY(161,20);
		$pdf->SetFont('Arial','',8);
		$pdf->SetFillColor(255,255,255); //Color de fondo
		$pdf->SetTextColor(0,0,0); //Color de texto
		$pdf->Cell(48,5,$Factura["NumeroCertificado"],1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		$pdf->SetXY(161,25);
		$pdf->SetFont('Arial','B',8);
		$pdf->SetFillColor(20,20,20);    //Color de fondo
		$pdf->SetTextColor(255,255,255); //Color de texto
		$pdf->Cell(48,5,'No. Certificado SAT',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		$pdf->SetXY(161,30);
		$pdf->SetFont('Arial','',8);
		$pdf->SetFillColor(255,255,255); //Color de fondo
		$pdf->SetTextColor(0,0,0); //Color de texto
		$pdf->Cell(48,5,$Factura["NumeroCertificadoSAT"],1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		$pdf->SetXY(161,35);
		$pdf->SetFont('Arial','B',8);
		$pdf->SetFillColor(20,20,20); //Color de fondo
		$pdf->SetTextColor(255,255,255); //Color de texto
		$pdf->Cell(48,5,'Fecha y Hora de Timbrado',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		$pdf->SetXY(161,40);
		$pdf->SetFont('Arial','',8);
		$pdf->SetFillColor(255,255,255); //Color de fondo
		$pdf->SetTextColor(0,0,0); //Color de texto
		$pdf->Cell(48,5,$Factura["FechaCertificacion"],1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo

		//Logo
		$pdf->SetXY(5,5);
		$pdf->Image($LogoPath,null,null,55);

		//Secci�n 3
		$pdf->SetXY(5,40);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(75,5,'Folio SAT (UUID): '.utf8_decode($Factura["FolioSAT_UUID"]),1,0,'C',true);

		$pdf->SetXY(81,40);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(75,5,'Tipo De Pago: '.utf8_decode($Factura["FormaDePago"]),1,0,'C',true);

		$pdf->SetXY(5,44);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(75,4,'Metodo de Pago: '.utf8_decode($Factura["MetodoDePago"]),1,0,'C',true);

		$pdf->SetXY(81,44);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(75,4,'Tipo de Comprobante: '.utf8_decode($Factura["TipoDeComprobante"]),1,0,'C',true);

		$pdf->SetXY(5,48);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(75,4,'Fecha de Impresion: '.utf8_decode($Factura["Fecha"]),1,0,'C',true);

		$pdf->SetXY(81,48);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(37.5,4,'Moneda: '.utf8_decode($Factura["Moneda"]),1,0,'C',true);

		$pdf->SetXY(118.5,48);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(37.5,4,'Tipo de Cambio: '.utf8_decode($Factura["TipoCambio"]),1,0,'C',true);

		$pdf->SetXY(0,53);
		$pdf->SetFont('Arial','B',6);
		$pdf->SetFillColor(20,20,20); //Color de fondo
		$pdf->SetTextColor(255,255,255); //Color de texto
		$pdf->Cell(216,5,'D A T O S  D E L  C L I E N T E',1,0,'C',true); //El ultimo elemento le indica al lapiz si debe dibujar el fondo


		$pdf->SetFillColor(255,255,255); //Color de fondo
		$pdf->SetTextColor(0,0,0); //Color de texto

		$pdf->SetXY(5,59);
		$pdf->SetFont('Arial','',6);



		$pdf->Cell(103,4,'Nombre: '.utf8_decode($Factura["ReceptorNombre"]),0,0,'L',true);

		$pdf->SetXY(108,59);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(103,4,'Calle: '.utf8_decode($Factura["ReceptorDatosCalle"]).' #'.$Factura["ReceptorDatosNoExterior"]." No.Int: ".$Factura["ReceptorDatosNoInterior"],0,0,'L',true);

		$pdf->SetXY(5,63);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(103,4,'Colonia: '.utf8_decode($Factura["ReceptorDatosColonia"]).' '.$Factura["ReceptorDatosMunicipio"].' , '.$Factura["ReceptorDatosEstado"],0,0,'L',true);

		$pdf->SetXY(108,63);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(103,4,'C.P.: '.utf8_decode($Factura["ReceptorDatosCP"]).' Telefono: '.$ReceptorPhone,0,0,'L',true);

		$pdf->SetXY(5,67);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(103,4,'RFC: '.utf8_decode($Factura["ReceptorRFC"]),0,0,'L',true);

		$pdf->SetXY(108,67);
		$pdf->SetFont('Arial','',6);
		$pdf->Cell(103,4,'Correo Electronico: '.utf8_decode($ReceptorEmail),0,0,'L',true);


		$pdf->SetFillColor(20,20,20); //Color de fondo
		$pdf->SetTextColor(255,255,255); //Color de texto

        $pdf->SetXY(5,67);
        $pdf->SetFont('Arial','B',6);
        $pdf->Cell(20,4,'Cantidad',0,0,'C',true);
        $pdf->Cell(20,4,'No.Serie',0,0,'C',true);
        $pdf->Cell(111,4,'Descripcion',0,0,'C',true);
        $pdf->Cell(15,4,'Unidad',0,0,'C',true);
        $pdf->Cell(20,4,'Precio',0,0,'C',true);
        $pdf->Cell(20,4,'Importe',0,0,'C',true);

		$QtyConceptos = sizeof($Factura["Conceptos"]);

		$pdf->SetDrawColor(0,0,0); //Color de bordes
		$pdf->SetFillColor(255,255,255); //Color de fondo
		$pdf->SetTextColor(0,0,0);
		$PunteroDeFila = 0;

		while($IndiceDeConceptos < $QtyConceptos)
		{
			$rowsCharacters = str_split(utf8_decode($Factura["Conceptos"][$IndiceDeConceptos]["Descripcion"]),105);
			$rows = sizeof($rowsCharacters);

			if($PunteroDeFila + $rows > 30 && ($Pages + 1) == $TotalPages) //Mejor que Sobre a que falte...
			{
				$TotalPages++; //Agregar otra hoja
				break;
			}

			else if($PunteroDeFila + $rows > 30 && ($Pages + 1) < $TotalPages)
				break;

			$pdf->SetXY(5,71 + (4 * ($PunteroDeFila) ));
			$pdf->Cell(20,4*$rows,utf8_decode((string)$Factura["Conceptos"][$IndiceDeConceptos]["Cantidad"]),1,0,'C',true);

            $pdf->SetXY(25,71 + (4 * ($PunteroDeFila) ));
            $pdf->Cell(20,4*$rows,utf8_decode((string)$Factura["Conceptos"][$IndiceDeConceptos]["noSerie"]),1,0,'C',true);


			$pdf->SetXY(45,71 + (4 * ($PunteroDeFila)));
			$pdf->Cell(136,4 * $rows,'',1,0,'C',true);
			$pdf->SetDrawColor(255,255,255); //Color de bordes

			for($k = 0; $k < $rows; $k++)
			{
				$pdf->SetXY(46,71.5 + (4 * ($PunteroDeFila)) + ($k * 4));
				$pdf->Cell(135,3,$rowsCharacters[$k],1,0,'L',true);
			}

			$pdf->SetDrawColor(0,0,0); //Color de bordes

			$pdf->SetXY(156,71 + (4 * ($PunteroDeFila)));
			$pdf->Cell(15,4*$rows,utf8_decode($Factura["Conceptos"][$IndiceDeConceptos]["Unidad"]),1,0,'C',true);
			$pdf->Cell(20,4*$rows,utf8_decode((string)$Factura["Conceptos"][$IndiceDeConceptos]["ValorUnitario"]),1,0,'C',true);
			$pdf->Cell(20,4*$rows,utf8_decode((string)$Factura["Conceptos"][$IndiceDeConceptos]["Importe"]),1,0,'C',true);

			if($rows > 0)
                $PunteroDeFila = $PunteroDeFila + $rows;
			else
				$PunteroDeFila++;

			$IndiceDeConceptos++;
		}
		if($Pages + 1 == $TotalPages)
		{
			$pdf->SetDrawColor(20,20,20); //Color de bordes
			$pdf->SetFillColor(20,20,20); //Color de fondo
			$pdf->SetTextColor(255,255,255); //Color de texto


            //if($Factura["Descuento"] <= 0)
            //{
            //$pdf->SetXY(5,192);
            //$pdf->Cell(155,4,"Importe con Letra",1,0,'C',true);

            //$pdf->SetXY(160,192);
            //$pdf->Cell(25.75,4,"SubTotal: ",1,0,'C',true);

            //$pdf->SetXY(160,196);
            //$pdf->Cell(25.75,4,"I.V.A.: ",1,0,'C',true);

            //$pdf->SetXY(160,200);
            //$pdf->Cell(25.75,4,"Total: ",1,0,'C',true);

            //$pdf->SetDrawColor(255,255,255); //Color de bordes
            //$pdf->SetFillColor(255,255,255); //Color de fondo
            //$pdf->SetTextColor(0,0,0); //Color de texto

            //$TotalLetras = num2letras($Factura["Total"]);

            //$pdf->SetXY(5,196);
            //$pdf->SetFont('Arial','',7);
            //$pdf->Cell(154.5,4,$TotalLetras,1,0,'C',true);
            //$pdf->SetFont('Arial','B',5);

            //$pdf->SetXY(185.25,192);
            //$pdf->Cell(25.75,4,$Factura["Subtotal"],1,0,'C',true);

            //$IVA = round($Factura["Total"] - $Factura["Subtotal"],2);

            //$pdf->SetXY(185.25,196);
            //$pdf->Cell(25.75,4,$IVA,1,0,'C',true);

            //$pdf->SetXY(185.25,200);
            //$pdf->Cell(25.75,4,$Factura["Total"],1,0,'C',true);
            ////}
            //else
            //{
            $pdf->SetXY(5,192);
			$pdf->Cell(155,4,"Importe con Letra",1,0,'C',true);

			$pdf->SetXY(160,192);
			$pdf->Cell(25.75,4,"SubTotal: ",1,0,'C',true);

            $pdf->SetXY(160,196);
			$pdf->Cell(25.75,4,"Descuento: ",1,0,'C',true);

			$pdf->SetXY(160,200);
			$pdf->Cell(25.75,4,"I.V.A.: ",1,0,'C',true);

			$pdf->SetXY(160,204);
			$pdf->Cell(25.75,4,"Total: ",1,0,'C',true);

			$pdf->SetDrawColor(255,255,255); //Color de bordes
			$pdf->SetFillColor(255,255,255); //Color de fondo
			$pdf->SetTextColor(0,0,0); //Color de texto

			$TotalLetras = num2letras($Factura["Total"]);

			$pdf->SetXY(5,196);
			$pdf->SetFont('Arial','',7);
			$pdf->Cell(154.5,4,$TotalLetras,1,0,'C',true);
			$pdf->SetFont('Arial','B',5);

			$pdf->SetXY(185.25,192);
			$pdf->Cell(25.75,4,'$'.$Factura["Subtotal"],1,0,'C',true);

            $pdf->SetXY(185.25,196);
			$pdf->Cell(25.75,4,'$'.$Factura["Descuento"],1,0,'C',true);


            //Modificar
			$IVA = round($Factura["Total"] - $Factura["Subtotal"],2);

			$pdf->SetXY(185.25,200);
			$pdf->Cell(25.75,4,'$'.$IVA,1,0,'C',true);

			$pdf->SetXY(185.25,204);
			$pdf->Cell(25.75,4,'$'.$Factura["Total"],1,0,'C',true);
            //}

		}


		$pdf->SetDrawColor(20,20,20); //Color de bordes
		$pdf->SetFillColor(20,20,20); //Color de fondo
		$pdf->SetTextColor(255,255,255); //Color de texto

		$pdf->SetXY(56,216);
		$pdf->Cell(154.5,4,"Sello Digital del CFDI",1,0,'C',true);

		$pdf->SetXY(56,229);
		$pdf->Cell(154.5,4,"Sello Digital SAT",1,0,'C',true);

		$pdf->SetXY(5,242);
		$pdf->Cell(206,4,"Cadena Original del Complemento de Certificacion del SAT",1,0,'C',true);

		$pdf->SetDrawColor(255,255,255); //Color de bordes
		$pdf->SetFillColor(255,255,255); //Color de fondo
		$pdf->SetTextColor(0,0,0); //Color de texto
		$pdf->SetFont('Arial','B',5);

		$pdf->SetXY(56,220);
		$QtyChars = strlen($Factura["SelloDigitalCFDI"]);
		$SelloDigitalCFDI = str_split($Factura["SelloDigitalCFDI"],$QtyChars/2);
		$pdf->Cell(154.5,4,$SelloDigitalCFDI[0],1,0,'C',true);
		$pdf->SetXY(56,224);
		$pdf->Cell(154.5,4,$SelloDigitalCFDI[1],1,0,'C',true);

		$pdf->SetXY(56,233);
		$QtyChars = strlen($Factura["SelloDigitalSAT"]);
		$SelloDigitalSAT = str_split($Factura["SelloDigitalSAT"],$QtyChars/2);
		$pdf->Cell(154.5,4,$SelloDigitalSAT[0],1,0,'C',true);
		$pdf->SetXY(56,237);
		$pdf->Cell(154.5,4,$SelloDigitalSAT[1],1,0,'C',true);

		$ComplementoCertificacion = ObtenerCadenaComp($Factura["FolioSAT_UUID"],$Factura["FechaCertificacion"],$Factura["SelloDigitalCFDI"],$Factura["NumeroCertificadoSAT"]);

		$pdf->SetXY(5,246);
		$QtyChars = strlen($ComplementoCertificacion);
		$ComplementoCertificacion = str_split($ComplementoCertificacion,$QtyChars/2);
		$pdf->Cell(206,4,$ComplementoCertificacion[0],1,0,'C',true);
		$pdf->SetXY(5,250);
		$pdf->Cell(206,4,$ComplementoCertificacion[1],1,0,'C',true);

		$pdf->SetXY(5,201);
		$pdf->Image($Code2DPath,null,null,40);

		$pdf->SetXY(5,255);
		$pdf->Cell(206,4,"Este documento es una representacion impresa de un CFDI",1,0,'C',true);
	}

	$pdf->Output($OutputFileName);

}
?>