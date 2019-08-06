<?php
include("functions.php");
$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");
$gs_desc = $_POST['desc'];
$gs_folio =$_POST['folio'];
$gs_cantidad = $_POST['cantidad'];
$query = "DELETE FROM folio_conceptos WHERE descripcion='$gs_desc' AND folio_factura=$gs_folio AND cantidad = $gs_cantidad";

$objSQL->executeCommand($query);
echo $query;
?>