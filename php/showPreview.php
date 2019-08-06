<?php
include("../Facturacion/FacturaPDFGen.php");


include("../Facturacion/ejemplo_factura_xml_sin_timbrar.php");
$folio = $_POST['folio'];

GeneratePDF('logo.png',"timbrados/prevew$folio.xml","timbrados/cfdi_1_2015_12_13.png","timbrados/prevew$folio.pdf","mail emisor","333580292","su correo","su telefonof");



?>