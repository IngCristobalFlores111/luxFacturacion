<?php
include("Functions.php");

$folio =$_POST['folio'];
$objSQL = F_sqlConn();
$query ="SELECT `xml_file` FROM `factura_generada` WHERE folio_factura=$folio";
$result = $objSQL->executeQuery($query);
if(is_null($result)){echo 0;}
else{
	echo $result[0]['xml_file'];
	}

?>