<?php
include("functions.php");
$gi_idCliente = $_POST['id'];
$gi_idCliente = str_replace("'",'"',$gi_idCliente);


$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");

$query ="SELECT * FROM `clientes_facturacion` WHERE idcliente = '$gi_idCliente'";

echo $objSQL->executeQueryJSON($query);




?>