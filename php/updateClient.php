<?php
include("Functions.php");
$gj_clientData = $_POST['json_client'];
$gi_idCliente= $_POST['id'];
$json_client = json_decode($gj_clientData);

$objSQL = F_sqlConn();


$out ='';
foreach ($json_client as $key => $value) // This will search in the 2 jsons
{
	$out = $out.$key."='".$value."',";

}
$out = rtrim($out, ",");

$query = "UPDATE clientes_facturacion SET ".$out." WHERE idcliente=$gi_idCliente";
$objSQL->executeCommand($query);


?>