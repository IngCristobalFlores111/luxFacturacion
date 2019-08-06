<?php
include("Functions.php");
$gs_folio = $_POST['folio'];
$gs_idCliente = $_POST['idCliente'];
$objSQL = F_sqlConn();

$query = "SELECT fc.`id_concepto`,fc.`importe`,fc.`precio_unitario`,fc.`unidad`,fc.`descripcion`,fc.`cantidad` FROM `folio_conceptos` fc WHERE fc.`folio_factura` IN (SELECT `folio_factura` FROM factura_pendiente WHERE `folio_factura`=$gs_folio AND idcliente=$gs_idCliente)";

echo $objSQL->executeQueryJSON($query);


?>