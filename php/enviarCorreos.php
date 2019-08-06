<?php
include("Functions.php");
$ga_correos = $_POST['jCorreos'];
$json = json_decode($ga_correos);
//print_r($json);
foreach($json as $obj)
{
}

$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");


?>