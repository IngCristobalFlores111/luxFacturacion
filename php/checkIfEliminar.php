<?php
include("Functions.php");
$objSQL = F_sqlConn();

$idCliente =$_POST['idCliente'];
$idCliente =$objSQL->filter_input($idCliente);

$query="SELECT COUNT(`idcliente`) AS cantidad_pendientes FROM `factura_pendiente` WHERE `idcliente`='$idCliente'";
$result_pendientes =$objSQL->executeQuery($query);

$query="SELECT COUNT(`idcliente`) AS cantidad_generadas FROM `factura_generada` WHERE `idcliente`='$idCliente'";
$result_generadas = $objSQL->executeQuery($query);

$outPut = '{"pendinetes":"'.$result_pendientes[0]['cantidad_pendientes'].'","generadas":"'.$result_generadas[0]['cantidad_generadas'].'"}';
echo $outPut;


?>