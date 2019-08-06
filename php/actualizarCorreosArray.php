<?php
include("Functions.php");
$gs_idcliente =$_POST['idCliente'];
$gs_idcliente = trim($gs_idcliente);
$gs_jsonCorreos = $_POST['jsonCorreos'];

$gj_jsonCorreos = json_decode($gs_jsonCorreos);
$objSQL = F_sqlConn();
$objSQL->executeCommand("DELETE FROM correos_default");
$query ='';
foreach($gj_jsonCorreos as $correo)
{
	$correo = trim($correo);
	$query =$query. "INSERT INTO correos_default (`email`, `idcliente`) VALUES ('$correo', '$gs_idcliente');";




}
//echo $query;
$objSQL->executeCommand($query);



?>