<?php
include("Functions.php");
$ga_correos = $_POST['correos'];
$json = json_encode($ga_correos);

foreach($json as $obj)
{
	echo $obj['mail'];
}

$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");


?>