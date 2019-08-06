<?php
include("Functions.php");

$objSQL = F_sqlConn();
$result =$objSQL->executeQuery("SELECT `conceptos_iva` FROM `user_config`");
echo $result[0]['conceptos_iva'];

?>