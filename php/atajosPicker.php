<?php
include("Functions.php");
$gs_searchString = $_POST['query'];

$gs_searchString = str_replace("'",'"',$gs_searchString);
$gs_searchString = htmlspecialchars($gs_searchString,ENT_COMPAT);


$objSQL = F_sqlConn();
$query = "SELECT idatajo,descripcion FROM `atajos` WHERE UCASE(CONCAT(`atajo`,' ',`descripcion`)) LIKE UCASE('%$gs_searchString%') LIMIT 10";
echo $objSQL->executeQueryJSON($query);


?>