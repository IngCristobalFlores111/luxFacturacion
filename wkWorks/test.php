<?php 
include("../php/new/functions.php");
include("generatePdf.php");
$xml ="52_2017_11_13_461.xml";
$png ="https://luxline.com.mx/joseO/LuxFacturacion/Facturacion/facturas/timbradas/xml/52_2017_11_13_461.png";
$sql = createMysqliConnection();

generatePdf33('52',$xml,"goldman.badger@gmail.com","3331293635",$png,"xmlCompleto.pdf");


/*$file = file_get_contents($xml);
$file = str_replace('cfdi:', '', $file);
$file = str_replace('tfd:', '', $file);
$xml = simplexml_load_string($file) or die("Error: Cannot create object");

foreach($xml->Conceptos->Concepto as $c){
echo $c->attributes()['Descripcion'];
}
*/

 ?>
