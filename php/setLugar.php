<?php


include("Functions.php");
$gs_id =$_POST['idexpedicion'];
$objSQL = F_sqlConn();
$query="SELECT * FROM `lugar_expedicion` WHERE idexpedicion='$gs_id'";
echo $objSQL->executeQueryJSON($query);

?>