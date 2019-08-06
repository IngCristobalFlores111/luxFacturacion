<?php
require_once 'autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->setIsPhpEnabled(true);
// instantiate and use the dompdf class
$dompdf = new Dompdf($options);

$dompdf->set_option('isHtml5ParserEnabled', true);
$content = file_get_contents("example.html");
$conceptos =array(array("cantidad"=>2,"noSerie"=>1233,"descripcion"=>"Pijita de burron con chocolate ademas de un 
texto bien pinche largo alv me vale verga todo el mundo la neta esta de la chingada"
,"unidad"=>"Pieza","precio"=>123123,"importe"=>4515),
array("cantidad"=>12,"noSerie"=>1211,"descripcion"=>"caca de burron con chocolate ademas de un 
texto bien pinche largo alv me vale verga todo el mundo la neta esta de la chingada"
,"unidad"=>"Pieza","precio"=>123123,"importe"=>45125,
"pedimento"=>123123123123,"aduana"=>"Nuevo Laredo","fecha"=>"2017-12-12"
));
$html_conceptos= "";
foreach($conceptos as $c){
$html_conceptos.="<tr>";
$html_conceptos.="<td>".$c['cantidad']."</td>";
$html_conceptos.="<td>".$c['noSerie']."</td>";
$descripcion = $c['descripcion'];
if(isset($c['pedimento'])){
$descripcion.="<br>";
$descripcion.="<label>Pedimento:".$c['pedimento']."</label><br>";
$descripcion.="<label>Aduana:".$c['aduana']."</label><br>";
$descripcion.="<label>Fecha:".$c['fecha']."</label><br>";

}
$html_conceptos.="<td>".$descripcion."</td>";
$html_conceptos.="<td>".$c['unidad']."</td>";
$html_conceptos.="<td>".$c['precio']."</td>";
$html_conceptos.="<td>".$c['importe']."</td>";
$html_conceptos.="</tr>";

}
$content = str_replace("#conceptos",$html_conceptos,$content);





$dompdf->load_html($content);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('letter', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();

?>
