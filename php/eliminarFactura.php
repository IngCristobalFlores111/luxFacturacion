<?php
include("Functions.php");
$gs_folio = $_POST['folioFactura'];

$objSQL = F_sqlConn();
$gs_folio = $objSQL->filter_input($gs_folio);
$query = "DELETE FROM `factura_pendiente` WHERE folio_factura=$gs_folio;";
$query =$query."DELETE FROM folio_conceptos WHERE folio_factura=$gs_folio;";
// eliminar todas las instancias del folio en las facturas pendientes y en los conceptos
$objSQL->executeCommand($query);



?>