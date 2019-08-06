<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);   
include ("generatePdf.php");
$xml="https://www.luxline.com.mx/joseO/LuxFacturacion/Facturacion/facturas/timbradas/xml/52_2017_08_15_9.xml";
$png ="https://www.luxline.com.mx/joseO/LuxFacturacion/Facturacion/facturas/timbradas/xml/52_2017_08_15_9.png";
$pdf = "../Facturacion/facturas/timbradas/pdf/52_2017_08_15_9.pdf";
generatePdf($xml,"goldman-badger@gmail.com","36270141",$png,$pdf);


?>