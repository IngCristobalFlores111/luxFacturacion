<?php
include("Functions.php");

$gs_jsonCorreos = $_POST['jsonCorreos'];

$gj_jsonCorreos = json_decode($gs_jsonCorreos);
$objSQL = F_sqlConn();
$objSQL->executeCommand("DELETE FROM correos_default");
$query ='';
foreach($gj_jsonCorreos as $correo)
{
	$correo = $objSQL->filter_input($correo);
$correo = trim($correo);
$query =$query. "INSERT INTO correos_default (`email`) VALUES ('$correo');";


}
$objSQL->executeCommand($query);

?>