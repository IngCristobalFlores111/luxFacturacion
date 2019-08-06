<?php
include("Functions.php");
$gs_Cliente =$_POST['idCliente'];
$objSQL = F_sqlConn();
$query="SELECT `idexpedicion`,calle FROM `lugar_expedicion` WHERE `idcliente`='$gs_Cliente'";
echo $objSQL->executeQueryJSON($query);


?>