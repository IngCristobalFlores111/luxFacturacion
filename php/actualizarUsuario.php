<?php
include("Functions.php");
$gs_jsonCliente = $_POST['jsonCliente'];
$gj_Cliente = json_decode($gs_jsonCliente);
$objSQL = F_sqlConn();

$out = '';
$gj_Cliente = $objSQL->filter_json($gj_Cliente);
foreach($gj_Cliente as $key=>$value)
{

$out= $out.$key."='".$value."',";

}
$out  =rtrim($out, ",");

$query = "UPDATE usuario_facturacion SET ".$out;

$objSQL->executeCommand($query);








?>