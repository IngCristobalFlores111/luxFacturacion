<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("createZip.php");
$tmp_files = $_POST['xml_files'];
$pdf_files = array();
$xml_files = array();
foreach($tmp_files as $file){
    $tmp = explode(".",$file);
    $pdf = "../../Facturacion/facturas/timbradas/pdf/".$tmp[0].".pdf";
    $xml ="../../Facturacion/facturas/timbradas/xml/".$tmp[0].".xml";
    array_push($pdf_files,$pdf);
    array_push($xml_files,$xml);
}
$files = array_merge($xml_files,$pdf_files);
$zip_file = "export_".date("Y-m-d").".zip";
$result = create_zip($files,"../../excelExport/".$zip_file,true);
echo $zip_file;


?>