<?php
include("Functions.php");
$objSQL = F_sqlConn();
$gs_desc = $_POST['desc'];
$gs_folio =$_POST['folio'];
$gs_cantidad = $_POST['cantidad'];
$query = "DELETE FROM folio_conceptos WHERE descripcion='$gs_desc' AND folio_factura='-1' AND cantidad = $gs_cantidad";

$objSQL->executeCommand($query);
echo $query;
?>