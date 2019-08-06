<?php
require_once 'autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;
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
 


function generatePdf($xml_file,$correoEmisor,$telReceptor,$png){//,$png_file,$logo,$idCliente,$idLugarExpedicio){

    $xml = OpenCompleteXMLFile($xml_file,false);

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->setIsPhpEnabled(true);
// instantiate and use the dompdf class
$dompdf = new Dompdf($options);

$dompdf->set_option('isHtml5ParserEnabled', true);
$content = file_get_contents("template.html");
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
$html_conceptos.="<td>$".number_format($c['ValorUnitario'],2)."</td>";
$html_conceptos.="<td>$".number_format($c['Importe'],2)."</td>";
$html_conceptos.="</tr>";

}
$content = str_replace("#conceptos",$html_conceptos,$content);
$content = str_replace("#subtotal","$".number_format($xml['Subtotal'],2),$content);
$content = str_replace("#descuento","$".number_format($xml['Descuento'],2),$content);
$retIva= (isset($xml['RetencionesIVA']))?$xml['RetencionesIVA']:"0.0";
$content = str_replace("#retIva","$".$retIva,$content);

$retISR= (isset($xml['RetencionesISR']))?$xml['RetencionesISR']:"0.0";
$content = str_replace("#retIsr","$".$retISR,$content);
$iva =(float)$xml['Subtotal']*0.16;
$content = str_replace("#iva","$".money_format($iva,2),$content);
$content = str_replace("#total","$".money_format($xml['Total'],2),$content);

$content = str_replace("#importeLetra",num2letras($xml['Total']),$content);
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

$dompdf->load_html($content);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('letter', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();
}

generatePdf("52_2017_08_31_406.xml","golman.badger@gmail.com","33384545","11_2016_07_19_3.png");
?>
