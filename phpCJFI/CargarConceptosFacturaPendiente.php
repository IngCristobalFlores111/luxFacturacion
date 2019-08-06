<?php
include("functions.php");
$gs_folio = $_POST['folio'];
$gs_idCliente = $_POST['idCliente'];
$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");

$query = "SELECT fc.`id_concepto`,fc.`importe`,fc.`precio_unitario`,fc.`unidad`,fc.`descripcion`,fc.`cantidad` FROM `folio_conceptos` fc WHERE fc.`folio_factura` IN (SELECT `folio_factura` FROM factura_pendiente WHERE `folio_factura`=$gs_folio AND idcliente=$gs_idCliente)";

echo $objSQL->executeQueryJSON($query);


?>