<?php
include("Functions.php");
$objSQL = F_sqlConn();
$idCliente =$_POST['idCliente'];
$idCliente =$objSQL->filter_input($idCliente);
$query ="DELETE FROM `clientes_facturacion` WHERE `idcliente`='$idCliente'";
$objSQL->executeCommand($query);

?>