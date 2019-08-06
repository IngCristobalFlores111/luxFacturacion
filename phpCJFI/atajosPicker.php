<?php
include("functions.php");
$gs_searchString = $_POST['query'];

$gs_searchString = str_replace("'",'"',$gs_searchString);
$gs_searchString = htmlspecialchars($gs_searchString,ENT_COMPAT);


$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");
$query = "SELECT * FROM `atajos` WHERE UCASE(CONCAT(`atajo`,' ',`descripcion`)) LIKE UCASE('%$gs_searchString%') LIMIT 10";
echo $objSQL->executeQueryJSON($query);


?>