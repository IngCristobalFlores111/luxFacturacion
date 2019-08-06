<?php
include("Functions.php");
$gi_idCliente = $_POST['id'];
$objSQL = F_sqlConn();
$gi_idCliente = $objSQL->filter_input($gi_idCliente);


$query ="SELECT nombre,RFC,telefono,celular,email,`calle`,colonia,CodigoPostal,`noExterior`,`noInterior`,`localidad`,`municipio`,`estado` FROM clientes_facturacion WHERE idcliente=$gi_idCliente";

echo $objSQL->executeQueryJSON($query);




?>