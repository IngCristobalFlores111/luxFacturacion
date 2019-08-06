<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../wkWorks/generatePdf.php");
$idCliente = $_GET['idCliente'];
generatePdfPreview($idCliente,"https://www.luxline.com.mx/joseO/LuxFacturacion/Facturacion/facturas/timbradas/xml/34_2017_09_04_409.xml","golman.badger@gmail.com","33384545","https://www.luxline.com.mx/joseO/LuxFacturacion/Facturacion/facturas/timbradas/xml/34_2017_09_04_409.png","preview.pdf");


?>