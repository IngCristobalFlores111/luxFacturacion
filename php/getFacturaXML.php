<?php
include("../pdfWriter/FacturaPDFGen.php");

setlocale(LC_MONETARY, 'en_US');

$objSQL = F_sqlConn();

$folio =$_POST['folio'];
$folio = $objSQL->filter_input($folio);
$query ="SELECT `xml_file`,idcliente FROM `factura_generada` WHERE `folio_factura`='$folio'";
$result =$objSQL->executeQuery($query);
$xml_file_print = $result[0]['xml_file'];
$idCliente = $result[0]['idcliente'];
$xml = "../Facturacion/facturas/timbradas/xml/".$result[0]['xml_file'];
$factura = OpenCompleteXMLFile($xml,false);
$conceptos  = $factura['Conceptos'];
$output ='';
foreach($conceptos as $c){
    $c['ValorUnitario'] = money_format('%.3n',(float)$c['ValorUnitario']);
    $c['Importe'] = money_format('%.3n',(float)$c['Importe']);
    $popOver = '';
    if(isset($c['Numero'])){

          $content = "<div class='popover-info'>";
                   $content.= "<label>Pedimento:</label> ".$c['Numero']."<br>";
                   $content.= "<label>Aduana:</label> ".$c['Aduana']. "<br>";
                   $content.= "<label>Fecha:</label> ".$c['Fecha']."<br></div>";
                    $popOver = '<a class="a-pop-over" data-placement="top" data-toggle="popover" title="Datos de importaci&oacute;n" data-html="true" data-content="' .$content . '">Importaci&oacute;n</a>';

    }
    $output = $output."<tr>";
    $output =$output."<td>".$c['Descripcion']."<br>".$popOver."</td>";
    $output =$output."<td>".$c['ID']."</td>";
    $output =$output."<td>".$c['Unidad']."</td>";
    $output =$output."<td>".$c['Cantidad']."</td>";
    $output =$output."<td>".$c['ValorUnitario']."</td>";
    $output =$output."<td>".$c['Importe']."</td>";
    $output = $output."</tr>";

}
//echo $output."&%$";
$descuento = (float)$factura['Descuento'];


$subtotal =(float) $factura['Subtotal'];
$sub_descuento = $subtotal - $descuento;
$descuento_percent = ($descuento*100)/($subtotal);

$descuento_percent = round($descuento_percent,3);
$iva = ($subtotal-$descuento)*0.16;

$total =(float) $factura['Total'];
//$iva =(float) $total-$subtotal;


$retencion_isr =(float)$factura['RetencionesISR'];
$retencion_iva = (float)$factura['RetencionesIVA'];
$output2 = '';
$output3 = '';

$porcentaje_isr = ($retencion_isr*100)/$sub_descuento;
$porcentaje_isr = round($porcentaje_isr,3);

$subtotal = money_format('%.3n', $subtotal);
$sub_descuento = money_format('%.3n', $sub_descuento);
$iva =  money_format('%.3n', $iva);
$total = money_format('%.3n', $total);
//$porcentaje_isr = money_format('%.3n', $porcentaje_isr);


if($retencion_isr==0&& $retencion_iva==0)
{
    if($descuento>0){
        $descuento =money_format('%.3n', $descuento);

        $output2 = "<thead><tr><th>Subtotal</th><th>Descuento(%$descuento_percent)</th><th>Subtotal con descuento</th><th>IVA</th><th>Total</th></tr></thead>";
        $output2 =$output2."<tbody><tr><td>$subtotal</td><td>$descuento</td><td>$sub_descuento</td><td>$iva</td><td>$total</td></tr></tbody>";
    }else{
        $output2 = "<thead><tr><th>Subtotal</th><th>IVA</th><th>Total</th></tr></thead>";
        $output2 =$output2."<tbody><tr><td>$subtotal</td><td>$iva</td><td>$total</td></tr></tbody>";

    }

}else{  // mostrar retenciones de ISR e IVA

    $retencion_isr = money_format('%.3n', $retencion_isr);
    $retencion_iva  = money_format('%.3n', $retencion_iva);
    if($descuento>0){
        $descuento =money_format('%.3n', $descuento);

        $output2 = "<thead><tr><th>Subtotal</th><th>Descuento(%$descuento_percent)</th><th>Subtotal con descuento</th><th>IVA</th><th>Total</th></tr></thead>";
        $output2 =$output2."<tbody><tr><td>$subtotal</td><td>$descuento</td><td>$sub_descuento</td><td>$iva</td><td>$total</td></tr></tbody>";
        $output3 =$output3."<tr><td>$retencion_isr</td><td>$retencion_iva</td></tr>";
    }else{

        $output2 = "<thead><tr><th>Subtotal</th><th>IVA</th><th>Total</th></tr></thead>";
        $output2 =$output2."<tbody><tr><td>$subtotal</td><td>$iva</td><td>$total</td></tr> </tbody>";
        $output3 =$output3."<tr><td>$retencion_isr</td><td>$retencion_iva</td></tr>";

    }
}


$cliente = $factura['ReceptorNombre'];
$rfc_cliente = $factura['ReceptorRFC'];
$forma_pago =$factura['FormaDePago'];
$metodo_pago =$factura['MetodoDePago'];
$fecha =$factura['Fecha'];
$UUID =$factura['FolioSAT_UUID'];
$folio_impreso =$factura['Folio']."-".$factura['Serie'];

$factura_params ='{"idcliente":"'.$idCliente.'","folio":"'.$folio_impreso.'","UUID":"'.$UUID.'","cliente":"'.$cliente.'","rfc":"'.$rfc_cliente.'","forma_pago":"'.$forma_pago.'","metodo_pago":"'.$metodo_pago.'","fecha":"'.$fecha.'"}';




echo $output."&%$".$output2."&%$".$output3."&%$".$porcentaje_isr."&%$".$factura_params."&%$".$xml_file_print."&%$".json_encode($conceptos,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT);


?>