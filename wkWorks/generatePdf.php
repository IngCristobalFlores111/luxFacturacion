<?php

require 'vendor/autoload.php';

use mikehaertl\wkhtmlto\Pdf;

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
        if(isset($XmlDoc["NumCtaPago"])){
            $Factura["NumeroDeCuentaDePago"] =  (string)$XmlDoc["NumCtaPago"];
            
        }
        
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
    $centavos  = 0;
    
    if(isset($float[1])){
    $tmp_num = explode("0",$float[1]);
    if($tmp_num[0]==""){
        $centavos = (int)$float[1];
        $centavos = $centavos/10;
    }else{
        $centavos = $float[1]*10;
    }
 
    if($centavos>100){
        $centavos = $float[1];
    }
}
    $end_num=ucfirst($tex).' pesos '.$centavos.'/100 M.N.';
    return $end_num;
 }
 

function generatePdf($xml_file,$correoEmisor,$telReceptor,$png,$savePath){//,$png_file,$logo,$idCliente,$idLugarExpedicio){

    $xml = OpenCompleteXMLFile($xml_file,false);


$content = file_get_contents("https://www.luxline.com.mx/joseO/LuxFacturacion/wkWorks/template.html");
$content = str_replace("#nombreEmisor",$xml['EmisorDatosNombre'],$content);
$content = str_replace("#calleEmisor",$xml['EmisorDomicilioCalle'],$content);
$content = str_replace("#coloniaEmisor",$xml['EmisorDomicilioColonia'],$content);
$content = str_replace("#estadoMunicipioEmisor",$xml['EmisorExpedicionMunicipio'].",".$xml['EmisorExpedicionEstado'],$content);
$content = str_replace("#RFCEmisor",$xml['EmisorDatosRFC'],$content);
$content = str_replace("#lugarExpedicion",$xml['EmisorExpedicionCalle']." ".$xml['EmisorExpedicionNoExterior']." ".$xml['EmisorExpedicionColonia']." CP ".$xml['EmisorExpedicionCP'].",".$xml['LugarExpedicion'],$content);
$content = str_replace("#correoEmisor",$correoEmisor,$content);
$content = str_replace("#serieFactura",$xml['Serie'],$content);
$content = str_replace("#folioFactura",$xml['Folio'],$content);
$content = str_replace("#noCertificado",$xml['NumeroCertificado'],$content);
$content = str_replace("#noCertificadoSat",$xml['NumeroCertificadoSAT'],$content);
$content = str_replace("#fechaTimbrado",$xml['FechaCertificacion'],$content);
$content = str_replace("#UUID",$xml['FolioSAT_UUID'],$content);
$hoy = date('Y-m-d H:i:s');
$content = str_replace("#fechaImpresion",$hoy,$content);
$content = str_replace("#metodoPago",$xml['MetodoDePago'],$content);
$content = str_replace("#formaPago",$xml['FormaDePago'],$content);

$content = str_replace("#tipoComprobante",$xml['TipoDeComprobante'],$content);
$content = str_replace("#moneda",$xml['Moneda'],$content);
$content = str_replace("#tipoCambio",$xml['TipoCambio'],$content);
$numeroCuenta =(isset($xml['NumeroDeCuentaDePago']))?$xml['NumeroDeCuentaDePago']:"N/A";
$content = str_replace("#numCuenta",$numeroCuenta,$content);
$content = str_replace("#nombreCliente",$xml['ReceptorNombre'],$content);
$content = str_replace("#RFCcliente",$xml['ReceptorRFC'],$content);
$noInterior = (isset($xml['ReceptorDatosNoInterior']))?$xml['ReceptorDatosNoInterior']:"";
$domicilioCliente =$xml['ReceptorDatosCalle']." ".$xml['ReceptorDatosNoExterior']." ".$noInterior." ".$xml['ReceptorDatosCP']." ".$xml['ReceptorDatosMunicipio'].",".$xml['ReceptorDatosEstado'];
$content = str_replace("#domicilioCliente",$domicilioCliente,$content);
$content = str_replace("#telefonoCliente",$telReceptor,$content);


$conceptos = $xml['Conceptos'];

$html_conceptos= "";
foreach($conceptos as $c){
$html_conceptos.="<tr>";
$html_conceptos.="<td>".$c['Cantidad']."</td>";
$html_conceptos.="<td>".$c['ID']."</td>";
$descripcion = $c['Descripcion'];
if(isset($c['Numero'])){
$descripcion.="<br>";
$descripcion.="<label>Pedimento:".$c['Numero']."</label><br>";
$descripcion.="<label>Aduana:".$c['Aduana']."</label><br>";
$descripcion.="<label>Fecha:".$c['Fecha']."</label><br>";

}
$html_conceptos.="<td>".$descripcion."</td>";
$html_conceptos.="<td>".$c['Unidad']."</td>";
$html_conceptos.="<td>$".number_format($c['ValorUnitario'],2,'.', ',')."</td>";
$html_conceptos.="<td>$".number_format($c['Importe'],2,'.', ',')."</td>";
$html_conceptos.="</tr>";

}
$content = str_replace("#conceptos",$html_conceptos,$content);
$content = str_replace("#subtotal","$".number_format(round($xml['Subtotal'],2),2,'.', ','),$content);
$content = str_replace("#descuento","$".number_format(round($xml['Descuento'],2),2,'.', ','),$content);
$retIva= (isset($xml['RetencionesIVA']))?$xml['RetencionesIVA']:"0.00";


$content = str_replace("#retIva","$".$retIva,$content);

$retISR= (isset($xml['RetencionesISR']))?$xml['RetencionesISR']:"0.00";
$content = str_replace("#retIsr","$".$retISR,$content);
$iva =((float)$xml['Subtotal'] - (float)$xml['Descuento'])*0.16;
$content = str_replace("#iva","$".number_format(round($iva,2),2,'.', ','),$content);
$content = str_replace("#total","$".number_format(round($xml['Total'],2),2,'.', ','),$content);

$content = str_replace("#importeLetra",num2letras(round($xml['Total'],2)),$content);
$content = str_replace("#png",$png,$content);
$si = '';
$selloDigital = $xml['SelloDigitalCFDI'];
$si = chunk_split($selloDigital,72,"<br>");

$content = str_replace("#selloDigitalCFDI",$si,$content);

$seloSat = $xml['SelloDigitalSAT'];
$si = chunk_split($seloSat,72,"<br>");


$content = str_replace("#selloDigitalSat",$si,$content);


$cadena_comp = obtenerCadenaComp($xml['FolioSAT_UUID'],$xml['FechaCertificacion'],$xml['SelloDigitalCFDI'],$xml['NumeroCertificado']);
$cadena_comp = chunk_split($cadena_comp,72,"<br>");

$content = str_replace("#cadenaComp",$cadena_comp   ,$content);


// You can pass a filename, a HTML string, an URL or an options array to the constructor
$pdf = new Pdf([
    'commandOptions' => [
        'useExec' => true,
                'escapeArgs' => false,
        'procOptions' => array(
            // This will bypass the cmd.exe which seems to be recommended on Windows
            'bypass_shell' => true,
            // Also worth a try if you get unexplainable errors
            'suppress_errors' => true,
        ),
    ],
]);
// On some systems you may have to set the path to the wkhtmltopdf executable
 $pdf->binary = '/var/www/luxline.com.mx/joseO/LuxFacturacion/wkWorks/wkhtmltox/bin/wkhtmltopdf';
 $pdf->addPage($content);

if (!$pdf->saveAs($savePath)) {
    echo $pdf->getError();
}



}

function generatePdfPreview($idCliente,$xml_file,$correoEmisor,$telReceptor,$png,$savePath){//,$png_file,$logo,$idCliente,$idLugarExpedicio){
    
        $xml = OpenCompleteXMLFile($xml_file,false);
        $query="SELECT * FROM `clientes_facturacion` WHERE `idcliente` = ?";
         $sql = createMysqliConnection();
         $cliente = $sql->get_bind_results($query,array("i",$idCliente));
         $domicilioCliente ='';
         $nombreCliente ="";
         $rfcCliente ="";
    if(count($cliente)==0){
        $domicilioCliente =$xml['ReceptorDatosCalle']." ".$xml['ReceptorDatosNoExterior']." ".$noInterior." ".$xml['ReceptorDatosCP']." ".$xml['ReceptorDatosMunicipio'].",".$xml['ReceptorDatosEstado'];
        $rfcCliente = $xml['ReceptorRFC'];
        $nombreCliente = $xml['ReceptorNombre'];
    }else{
    
         $cliente = $cliente[0];
         $domicilioCliente = $cliente['calle']." ".$cliente['noExterior']." ".$cliente['noInterior']." ".$cliente['colonia']." ".$cliente['CodigoPostal']." ".$cliente['municipio'].",".$cliente['estado'];
         $rfcCliente = $cliente['RFC'];
         $nombreCliente = $cliente['nombre'];
         $telReceptor = $cliente['telefono'];
    }
    $content = file_get_contents("https://www.luxline.com.mx/sanAngel/LuxFacturacion/wkWorks/template.html");
    $content = str_replace("#nombreEmisor",$xml['EmisorDatosNombre'],$content);
    $content = str_replace("#calleEmisor",$xml['EmisorDomicilioCalle'],$content);
    $content = str_replace("#coloniaEmisor",$xml['EmisorDomicilioColonia'],$content);
    $content = str_replace("#estadoMunicipioEmisor",$xml['EmisorExpedicionMunicipio'].",".$xml['EmisorExpedicionEstado'],$content);
    $content = str_replace("#RFCEmisor",$xml['EmisorDatosRFC'],$content);
    $content = str_replace("#lugarExpedicion",$xml['EmisorExpedicionCalle']." ".$xml['EmisorExpedicionNoExterior']." ".$xml['EmisorExpedicionColonia']." CP ".$xml['EmisorExpedicionCP'].",".$xml['LugarExpedicion'],$content);
    $content = str_replace("#correoEmisor",$correoEmisor,$content);
    $content = str_replace("#serieFactura",$xml['Serie'],$content);
    $content = str_replace("#folioFactura",$xml['Folio'],$content);
    $content = str_replace("#noCertificado",$xml['NumeroCertificado'],$content);
    $content = str_replace("#noCertificadoSat",$xml['NumeroCertificadoSAT'],$content);
    $content = str_replace("#fechaTimbrado",$xml['FechaCertificacion'],$content);
    $content = str_replace("#UUID",$xml['FolioSAT_UUID'],$content);
    $hoy = date('Y-m-d H:i:s');
    $content = str_replace("#fechaImpresion",$hoy,$content);
    $content = str_replace("#formaPago",$xml['FormaDePago'],$content);
    
    $content = str_replace("#metodoPago",$xml['MetodoDePago'],$content);
    $content = str_replace("#tipoComprobante",$xml['TipoDeComprobante'],$content);
    $content = str_replace("#moneda",$xml['Moneda'],$content);
    $content = str_replace("#tipoCambio",$xml['TipoCambio'],$content);
    $numeroCuenta =(isset($xml['NumeroDeCuentaDePago']))?$xml['NumeroDeCuentaDePago']:"N/A";
    $content = str_replace("#numCuenta",$numeroCuenta,$content);
    $content = str_replace("#nombreCliente",$nombreCliente,$content);
    
    $content = str_replace("#RFCcliente",$rfcCliente,$content);
    $noInterior = (isset($xml['ReceptorDatosNoInterior']))?$xml['ReceptorDatosNoInterior']:"";
    $content = str_replace("#domicilioCliente",$domicilioCliente,$content);
    $content = str_replace("#telefonoCliente",$telReceptor,$content);
    
    
    //$conceptos = $xml['Conceptos'];
    
    $html_conceptos= "";
    session_start();
    $conceptos = $_SESSION['conceptos'];
    $tmp =json_encode($conceptos);
    $conceptos  = json_decode($tmp,true);
 $subtotal = 0;
    foreach($conceptos as $c){
    $html_conceptos.="<tr>";
    $html_conceptos.="<td>".$c['cantidad']."</td>";
    $html_conceptos.="<td>".$c['noSerie']."</td>";
    $descripcion = $c['descripcion'];
    if(isset($c['numPedimento'])){
    $descripcion.="<br>";
    $descripcion.="<label>Pedimento:".$c['numPedimento']."</label><br>";
    $descripcion.="<label>Aduana:".$c['aduana']."</label><br>";
    $descripcion.="<label>Fecha:".$c['fechaPedimento']."</label><br>";
    
    }
    if(isset($c['pedimento'])){
        $p = $c['pedimento'];
        $descripcion.="<br>";
        $descripcion.="<label>Pedimento:".$p['numero']."</label><br>";
        $descripcion.="<label>Aduana:".$p['aduana']."</label><br>";
        $descripcion.="<label>Fecha:".$p['fecha']."</label><br>";
        
        }



    $html_conceptos.="<td>".$descripcion."</td>";
    $html_conceptos.="<td>".$c['unidad']."</td>";
    $precio = (isset($c['precio_unitario']))?$c['precio_unitario']:$c['precio'];
    $html_conceptos.="<td>$".number_format($precio,2,'.', ',')."</td>";
    $html_conceptos.="<td>$".number_format($c['importe'],2,'.', ',')."</td>";
    $html_conceptos.="</tr>";
    $subtotal+=(float)$c['importe'];
    
    }

    $iva = $subtotal*0.16;
    $total = $subtotal+$iva;
    $content = str_replace("#conceptos",$html_conceptos,$content);
    $content = str_replace("#subtotal","$".number_format(round($subtotal,2),2,'.', ','),$content);
    $content = str_replace("#descuento","$".number_format($xml['Descuento'],2),$content);
    $retIva= (isset($xml['RetencionesIVA']))?$xml['RetencionesIVA']:"0.00";

    $content = str_replace("#retIva","$".$retIva,$content);
    
    $retISR= (isset($xml['RetencionesISR']))?$xml['RetencionesISR']:"0.00";
    $content = str_replace("#retIsr","$".$retISR,$content);
 
    $content = str_replace("#iva","$".number_format(round($iva,2),2,'.', ','),$content);
    $content = str_replace("#total","$".number_format(round($total,2),2, '.', ','),$content);
    
    $content = str_replace("#importeLetra",num2letras(round($total,2),true,true),$content);
    $content = str_replace("#png",$png,$content);
    $si = '';
    $selloDigital = $xml['SelloDigitalCFDI'];
    $si = chunk_split($selloDigital,72,"<br>");
    
    $content = str_replace("#selloDigitalCFDI",$si,$content);
    
    $seloSat = $xml['SelloDigitalSAT'];
    $si = chunk_split($seloSat,72,"<br>");
    
    
    $content = str_replace("#selloDigitalSat",$si,$content);
    
    
    $cadena_comp = obtenerCadenaComp($xml['FolioSAT_UUID'],$xml['FechaCertificacion'],$xml['SelloDigitalCFDI'],$xml['NumeroCertificado']);
    $cadena_comp = chunk_split($cadena_comp,72,"<br>");
    
    $content = str_replace("#cadenaComp",$cadena_comp   ,$content);
    
    // You can pass a filename, a HTML string, an URL or an options array to the constructor
    $pdf = new Pdf([
        'commandOptions' => [
            'useExec' => true,
                    'escapeArgs' => false,
            'procOptions' => array(
                // This will bypass the cmd.exe which seems to be recommended on Windows
                'bypass_shell' => true,
                // Also worth a try if you get unexplainable errors
                'suppress_errors' => true,
            ),
        ],
    ]);
    // On some systems you may have to set the path to the wkhtmltopdf executable
     $pdf->binary = '/var/www/luxline.com.mx/phpsandbox/testpdf/wkWorks/wkhtmltox/bin/wkhtmltopdf';
    $pdf->addPage($content);
    
    if (!$pdf->saveAs($savePath)) {
        echo $pdf->getError();
    }
    
    
    
    }

    
function generatePdfPreview33($idCliente,$xml_file,$correoEmisor,$telReceptor,$png,$savePath){//,$png_file,$logo,$idCliente,$idLugarExpedicio){
    
        $xml = OpenCompleteXMLFile($xml_file,false);
        $query="SELECT * FROM `clientes_facturacion` WHERE `idcliente` = ?";
         $sql = createMysqliConnection();
         $cliente = $sql->get_bind_results($query,array("i",$idCliente));
         $domicilioCliente ='';
         $nombreCliente ="";
         $rfcCliente ="";
    if(count($cliente)==0){
        $domicilioCliente =$xml['ReceptorDatosCalle']." ".$xml['ReceptorDatosNoExterior']." ".$noInterior." ".$xml['ReceptorDatosCP']." ".$xml['ReceptorDatosMunicipio'].",".$xml['ReceptorDatosEstado'];
        $rfcCliente = $xml['ReceptorRFC'];
        $nombreCliente = $xml['ReceptorNombre'];
    }else{
    
         $cliente = $cliente[0];
         $domicilioCliente = $cliente['calle']." ".$cliente['noExterior']." ".$cliente['noInterior']." ".$cliente['colonia']." ".$cliente['CodigoPostal']." ".$cliente['municipio'].",".$cliente['estado'];
         $rfcCliente = $cliente['RFC'];
         $nombreCliente = $cliente['nombre'];
         $telReceptor = $cliente['telefono'];
    }
    $content = file_get_contents("https://www.luxline.com.mx/sanAngel/LuxFacturacion/wkWorks/template33.html");
    $content = str_replace("#nombreEmisor",$xml['EmisorDatosNombre'],$content);
    $content = str_replace("#calleEmisor",$xml['EmisorDomicilioCalle'],$content);
    $content = str_replace("#coloniaEmisor",$xml['EmisorDomicilioColonia'],$content);
    $content = str_replace("#estadoMunicipioEmisor",$xml['EmisorExpedicionMunicipio'].",".$xml['EmisorExpedicionEstado'],$content);
    $content = str_replace("#RFCEmisor",$xml['EmisorDatosRFC'],$content);
    $content = str_replace("#lugarExpedicion",$xml['EmisorExpedicionCalle']." ".$xml['EmisorExpedicionNoExterior']." ".$xml['EmisorExpedicionColonia']." CP ".$xml['EmisorExpedicionCP'].",".$xml['LugarExpedicion'],$content);
    $content = str_replace("#correoEmisor",$correoEmisor,$content);
    $content = str_replace("#serieFactura",$xml['Serie'],$content);
    $content = str_replace("#folioFactura",$xml['Folio'],$content);
    $content = str_replace("#noCertificado",$xml['NumeroCertificado'],$content);
    $content = str_replace("#noCertificadoSat",$xml['NumeroCertificadoSAT'],$content);
    $content = str_replace("#fechaTimbrado",$xml['FechaCertificacion'],$content);
    $content = str_replace("#UUID",$xml['FolioSAT_UUID'],$content);
    $hoy = date('Y-m-d H:i:s');
    $content = str_replace("#fechaImpresion",$hoy,$content);
    $content = str_replace("#formaPago",$xml['FormaDePago'],$content);
    
    $content = str_replace("#metodoPago",$xml['MetodoDePago'],$content);
    $content = str_replace("#tipoComprobante",$xml['TipoDeComprobante'],$content);
    $content = str_replace("#moneda",$xml['Moneda'],$content);
    $content = str_replace("#tipoCambio",$xml['TipoCambio'],$content);
    $numeroCuenta =(isset($xml['NumeroDeCuentaDePago']))?$xml['NumeroDeCuentaDePago']:"N/A";
    $content = str_replace("#numCuenta",$numeroCuenta,$content);
    $content = str_replace("#nombreCliente",$nombreCliente,$content);
    
    $content = str_replace("#RFCcliente",$rfcCliente,$content);
    $noInterior = (isset($xml['ReceptorDatosNoInterior']))?$xml['ReceptorDatosNoInterior']:"";
    $content = str_replace("#domicilioCliente",$domicilioCliente,$content);
    $content = str_replace("#telefonoCliente",$telReceptor,$content);
    
    
    //$conceptos = $xml['Conceptos'];
    
    $html_conceptos= "";
    session_start();
    $conceptos = $_SESSION['conceptos'];
    $tmp =json_encode($conceptos);
    $conceptos  = json_decode($tmp,true);
 $subtotal = 0;
    foreach($conceptos as $c){
    $html_conceptos.="<tr>";
    $html_conceptos.="<td>".$c['cantidad']."</td>";
    $html_conceptos.="<td>".$c['claveProdServ']['codigo']."</td>";
    
    $html_conceptos.="<td>".$c['noSerie']."</td>";
    $descripcion = $c['descripcion'];
    if(isset($c['numPedimento'])){
    $descripcion.="<br>";
    $descripcion.="<label>Pedimento:".$c['numPedimento']."</label><br>";
    $descripcion.="<label>Aduana:".$c['aduana']."</label><br>";
    $descripcion.="<label>Fecha:".$c['fechaPedimento']."</label><br>";
    
    }
    
    if(isset($c['pedimento'])){
        $p = $c['pedimento'];
        $descripcion.="<br>";
        $descripcion.="<label>Pedimento:".$p['numero']."</label><br>";
        $descripcion.="<label>Aduana:".$p['aduana']."</label><br>";
        $descripcion.="<label>Fecha:".$p['fecha']."</label><br>";
        
        }



    $html_conceptos.="<td>".$descripcion."</td>";
    $html_conceptos.="<td>".$c['unidad']."</td>";
    $precio = (isset($c['precio_unitario']))?$c['precio_unitario']:$c['precio'];
    $html_conceptos.="<td>$".number_format($precio,2,'.', ',')."</td>";
    $html_conceptos.="<td>$".number_format($c['importe'],2,'.', ',')."</td>";
    $html_conceptos.="</tr>";
    $subtotal+=(float)$c['importe'];
    
    }

    $iva = $subtotal*0.16;
    $total = $subtotal+$iva;
    $content = str_replace("#conceptos",$html_conceptos,$content);
    $content = str_replace("#subtotal","$".number_format(round($subtotal,2),2,'.', ','),$content);
    $content = str_replace("#descuento","$".number_format($xml['Descuento'],2),$content);
    
    if(isset($xml['Impuestos']['Retenciones']['Retencion'])){
      $retenciones = $xml['Impuestos']['Retenciones']['Retencion'];
      if(isset($retenciones['@attributes'])){ // solo se retien iva o isr
         $reten = $retenciones['@attributes'];
         if($reten['Impuesto']=='002'){  // retencion de iva 
            $xml['RetencionesIVA'] =$reten['Importe'];

         }
         if($reten['Impuesto']=='001'){  // retencion de isr 
            $xml['RetencionesISR'] =$reten['Importe'];

         }
      }else{ // se retiene iva e isr
        foreach($xml['Impuestos']['Retenciones']['Retencion'] as $ret){
            $reten = $ret['@attributes'];
            if($reten['Impuesto']=='002'){  // retencion de iva 
                $xml['RetencionesIVA'] =$reten['Importe'];
    
             }
             if($reten['Impuesto']=='001'){  // retencion de isr 
                $xml['RetencionesISR'] =$reten['Importe'];
    
             }
        }

      }

    }
    $retIva= (isset($xml['RetencionesIVA']))?$xml['RetencionesIVA']:"0.00";

    $content = str_replace("#retIva","$".$retIva,$content);
    
    $retISR= (isset($xml['RetencionesISR']))?$xml['RetencionesISR']:"0.00";
    $content = str_replace("#retIsr","$".$retISR,$content);
 
    $content = str_replace("#iva","$".number_format(round($iva,2),2,'.', ','),$content);
    $content = str_replace("#total","$".number_format(round($total,2),2, '.', ','),$content);
    
    $content = str_replace("#importeLetra",num2letras(round($total,2),true,true),$content);
    $content = str_replace("#png",$png,$content);
    $si = '';
    $selloDigital = $xml['SelloDigitalCFDI'];
    $si = chunk_split($selloDigital,72,"<br>");
    
    $content = str_replace("#selloDigitalCFDI",$si,$content);
    
    $seloSat = $xml['SelloDigitalSAT'];
    $si = chunk_split($seloSat,72,"<br>");
    
    
    $content = str_replace("#selloDigitalSat",$si,$content);
    
    
    $cadena_comp = obtenerCadenaComp($xml['FolioSAT_UUID'],$xml['FechaCertificacion'],$xml['SelloDigitalCFDI'],$xml['NumeroCertificado']);
    $cadena_comp = chunk_split($cadena_comp,72,"<br>");
    
    $content = str_replace("#cadenaComp",$cadena_comp   ,$content);
    
    // You can pass a filename, a HTML string, an URL or an options array to the constructor
    $pdf = new Pdf([
        'commandOptions' => [
            'useExec' => true,
                    'escapeArgs' => false,
            'procOptions' => array(
                // This will bypass the cmd.exe which seems to be recommended on Windows
                'bypass_shell' => true,
                // Also worth a try if you get unexplainable errors
                'suppress_errors' => true,
            ),
        ],
    ]);
    // On some systems you may have to set the path to the wkhtmltopdf executable
    $pdf->binary = '/var/www/luxline.com.mx/sanAngel/LuxFacturacion/wkWorks/wkhtmltox/bin/wkhtmltopdf';
    $pdf->addPage($content);
    
    if (!$pdf->saveAs($savePath)) {
        echo $pdf->getError();
    }
    
    
    
    }
    
//ejemplo
//generatePdf("34_2017_09_04_409.xml","golman.badger@gmail.com","33384545","34_2017_09_04_409.png","page_new.pdf");

function generatePdf33($idCliente,$xml_file,$correoEmisor,$telReceptor,$png,$numCuentaPago,$savePath,$userFolderAndSystem){//,$png_file,$logo,$idCliente,$idLugarExpedicio){
    //Input (userFolderAndsystem): 'my/folder/from/root/isHere/'
    $folder = $userFolderAndSystem;

    $query="SELECT * FROM `clientes_facturacion` WHERE `idcliente` = ?";
    $sql = createMysqliConnection();
    $cliente = $sql->get_bind_results($query,array("i",$idCliente));
    $cliente = $cliente[0];
    $query="SELECT * FROM `usuario_facturacion`";
    $emisor = $sql->executeQuery($query);
     $emisor = $emisor[0];


    $file = file_get_contents($xml_file);
    $file = str_replace('cfdi:', '', $file);
    $file = str_replace('tfd:', '', $file);
    $xml = simplexml_load_string($file) or die("Error: Cannot create object in generatePdf33");


    $attrs = $xml->attributes();
    //$conceptos = $xml['Conceptos'];
    $receptorXml = $xml->Receptor->attributes();
    $emisorXml = $xml->Emisor->attributes();
    $uso = $sql->get_bind_results("SELECT Descripción AS descripcion FROM `f4_c_usocfdi` WHERE `c_UsoCFDI` = ?",array("s",$receptorXml['UsoCFDI']));
     $usoCFDI = $receptorXml['UsoCFDI'] ."(".$uso[0]['descripcion'].")";
     
     $complemento = $xml->Complemento->TimbreFiscalDigital->attributes();
     

     //CAMBIAR
    $content = file_get_contents("/var/www/luxline.com.mx/".$folder."wkWorks/template33.html");
    $content = str_replace("#condPago",$attrs['CondicionesDePago'],$content);
    $content = str_replace("#usoCFDI", $usoCFDI ,$content);
    
    $content = str_replace("#nombreEmisor",$emisor['nombre'],$content);
    $content = str_replace("#calleEmisor",$emisor['calle'],$content);
    $content = str_replace("#coloniaEmisor",$emisor['colonia'],$content);
    $content = str_replace("#estadoMunicipioEmisor",$emisor['municipio'].",".$emisor['estado'],$content);
    $content = str_replace("#RFCEmisor",$emisor['RFC'],$content);
    $content = str_replace("#lugarExpedicion",$emisor['calle']." ".$emisor['noExterior']." ".$emisor['noInterior']." ".$emisor['colonia']." CP ".$emisor['CodigoPostal'].",".$emisor['municipio'].",".$emisor['estado'],$content);
    $content = str_replace("#correoEmisor",$emisor['email'],$content);
    $content = str_replace("#serieFactura",$attrs['Serie'],$content);
    $content = str_replace("#folioFactura",$attrs['Folio'],$content);
    $content = str_replace("#noCertificado",$attrs['NoCertificado'],$content);
    $content = str_replace("#noCertificadoSat",$complemento['NoCertificadoSAT'],$content);
    $content = str_replace("#fechaTimbrado",$complemento['FechaTimbrado'],$content);
    $content = str_replace("#UUID",$complemento['UUID'],$content);
    $hoy = date('Y-m-d H:i:s');
    $content = str_replace("#fechaImpresion",$hoy,$content);
     

    $metodoPago = $sql->get_bind_results("SELECT `Descripción` AS descripcion FROM `f4_c_metodopago` WHERE `c_MetodoPago` = ?",array("s",$attrs['MetodoPago']));
    $metodoPago  = $metodoPago[0]; 
    $query="SELECT `Descripción` AS descripcion FROM `f4_c_formapago` WHERE `c_FormaPago` = ?";
     $idFormaPago = (int)$attrs['FormaPago'];
   $formaPago = $sql->get_bind_results($query,array("s",$idFormaPago));
   $formaPago =  $formaPago[0];

    $content = str_replace("#metodoPago",$attrs['MetodoPago']."(".$metodoPago['descripcion'].")",$content);
    $content = str_replace("#formaPago",$attrs['FormaPago']."(".$formaPago['descripcion'].")",$content);
    $tipoComp = ($attrs['TipoDeComprobante']=="I")?"Ingreso":"Egreso";

    $content = str_replace("#tipoComprobante",$tipoComp,$content);
    $content = str_replace("#moneda",$attrs['Moneda'],$content);
    $content = str_replace("#tipoCambio",$attrs['TipoCambio'],$content);
    //$numeroCuenta =(isset($attrs['NumeroDeCuentaDePago']))?$attrs['NumeroDeCuentaDePago']:"N/A";
    
    if(empty($numCuentaPago)){
     $numCuentaPago ="N/A";   
    }
    $content = str_replace("#numCuenta",$numCuentaPago,$content);
    // datos del receptos
    $content = str_replace("#nombreCliente", $cliente['nombre'] ,$content);
    $content = str_replace("#RFCcliente",$cliente['RFC'],$content);
    $noInterior = (isset($cliente['noInterior']))?$cliente['noInterior']:"";
    $domicilioCliente =$cliente['calle']." ".$cliente['noExterior']." ".$noInterior." ".$cliente['CodigoPostal']." ".$cliente['municipio'].",".$cliente['estado'];
    $content = str_replace("#domicilioCliente",$domicilioCliente,$content);
    $content = str_replace("#telefonoCliente",$cliente['telefono'],$content);
    
    
    
    $html_conceptos= "";
    foreach(  $xml->Conceptos->Concepto  as $c){
 
        $attrs =$c->attributes(); 
    
    $html_conceptos.="<tr>";
    $html_conceptos.="<td>". $attrs->Cantidad."</td>";
    $html_conceptos.="<td>". $attrs->ClaveProdServ."</td>";
    $html_conceptos.="<td>". $attrs->NoIdentificacion."</td>";
    $descripcion =  $attrs->Descripcion;
 $predial = (string)$c->CuentaPredial->attributes()->Numero;
    if(!empty($predial)){
        $descripcion.="<br><label>Predial:".$predial."</label><br>";
    }
    $pedimento =  (string)$c->InformacionAduanera->attributes()->NumeroPedimento;
    if(!empty($pedimento)){
    $descripcion.="<br>";
    $descripcion.="<label>Num. Pedimento:".$pedimento."</label><br>";
 
    }
    $html_conceptos.="<td>".$descripcion."</td>";
    $html_conceptos.="<td>".$attrs->ClaveUnidad."</td>";
    $html_conceptos.="<td>$".number_format((float)$attrs->ValorUnitario,2,'.', ',')."</td>";
    $html_conceptos.="<td>$".number_format((float)$attrs->Importe,2,'.', ',')."</td>";
    $html_conceptos.="</tr>";
    
    }
    $content = str_replace("#conceptos",$html_conceptos,$content);
    $content = str_replace("#descuento","$".number_format(round($attrs['Descuento'],2),2,'.', ','),$content);
    $retencionIva = 0;
    $retencionIsr = 0;
    if(!empty($xml->Impuestos->attributes()->TotalImpuestosRetenidos)){
        $rets= $xml->Impuestos->Retenciones->Retencion;
        
        
        if(!empty($rets)){
         foreach($rets as $r){
        
        $retAttrs = $r->attributes();
    
        $impuesto_ret = (string)$retAttrs->Impuesto;
         if($impuesto_ret=='002'){ // retencion IVA  // castear impuestos
            $retencionIva = (float)$retAttrs->Importe;
         }
         if($impuesto_ret=='001'){  // retencion ISR
             $retencionIsr = (float)$retAttrs->Importe;
         }       
        }
       
     }
       
      
  
      }
    $retIva="0.00";
  

    $html_tr_iva = "";
    $html_tr_isr = "";
    
      if($retencionIva!=0){
$retIva = number_format($retencionIva,2);
$html_tr_iva='<tr id="tr_ret_iva">
<td class="col-xs-8 border-less"></td>
<td class="col-xs-2">Ret. Iva</td>
<td class="col-xs-2">$'.$retIva.'</td>
</tr>';
      }
      if($retencionIsr!=0){
        $retISR = number_format($retencionIsr,2);
        $html_tr_isr='
        <tr id="tr_ret_isr">
            <td class="col-xs-8 border-less"></td>
            <td class="col-xs-2">Ret. ISR</td>
            <td class="col-xs-2">$'.$retISR.'</td>
          </tr>';
          
        
              }
              $content = str_replace("#tr_ret_iva",$html_tr_iva,$content);
              $content = str_replace("#tr_ret_isr",$html_tr_isr,$content);
              
              
   // $content = str_replace("#retIva","$".$retIva,$content);
    //$content = str_replace("#retIsr","$".$retISR,$content);

    $attrs_comp =  $xml->attributes();
    $subtotal =   (float)$attrs_comp->SubTotal;
    $descuento = (float)$attrs_comp->Descuento;
    $iva =((float)$subtotal- (float)$descuento)*0.16;

$total = (float)$attrs_comp->Total;


$content = str_replace("#subtotal","$".number_format($subtotal,2,'.', ','),$content);

    $content = str_replace("#iva","$".number_format($iva,2,'.', ','),$content);
    $content = str_replace("#total","$".number_format($total,2,'.', ','),$content);
    
   
    $content = str_replace("#importeLetra",num2letras(round($total,2)),$content);
    $content = str_replace("#png",$png,$content);
    $si = '';
    $selloDigital =  $complemento['SelloCFD'];
    $si = chunk_split($selloDigital,72,"<br>");
    
    $content = str_replace("#selloDigitalCFDI",$si,$content);
    
    $seloSat = $complemento['SelloSAT'];
    $si = chunk_split($seloSat,72,"<br>");
    
    
    $content = str_replace("#selloDigitalSat",$si,$content);
    
    
    $cadena_comp = obtenerCadenaComp($complemento['UUID'],$complemento['FechaTimbrado'],$complemento['SelloCFD'],$attrs['NoCertificado']);
    $cadena_comp = chunk_split($cadena_comp,72,"<br>");
    
    $content = str_replace("#cadenaComp",$cadena_comp   ,$content);
    


    $logoClienteURL ="https://www.luxline.com.mx/".$folder."Facturacion/logo/logo.png";
    $content = str_replace("#logoCliente",$logoClienteURL   ,$content);

    file_put_contents("tmp_html.html",$content);
    
    // You can pass a filename, a HTML string, an URL or an options array to the constructor
    $pdf = new Pdf([
        'commandOptions' => [
            'useExec' => true,
                    'escapeArgs' => false,
            'procOptions' => array(
                // This will bypass the cmd.exe which seems to be recommended on Windows
                'bypass_shell' => true,
                // Also worth a try if you get unexplainable errors
                'suppress_errors' => true,
            ),
        ],
    ]);
    // On some systems you may have to set the path to the wkhtmltopdf executable
     $pdf->binary = '/var/www/luxline.com.mx/LuxFacturacion/wkWorks/wkhtmltox/bin/wkhtmltopdf';
     
    $pdf->addPage($content);
    
    if (!$pdf->saveAs($savePath)) {
        echo $pdf->getError();
    }
    
    
    
    }
    
?>
