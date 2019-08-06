<?php
include("Functions.php");
$idCliente =$_POST['idCliente'];
$folio =$_POST['folio'];
$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");
$query ="DELETE FROM `factura_pendiente` WHERE `folio_factura`=$folio AND `idcliente`=$idCliente;";
$query = $query." DELETE FROM `folio_conceptos` WHERE `folio_factura`=$folio";
$objSQL->executeCommand($query);



?>