<?php
include("Functions.php");

$query1 = "SELECT `email`,`mail_pass` FROM usuario_facturacion";
$query2 = "SELECT email FROM `correos_default`";
$query3 ="SELECT `mensaje_timbrada`,`mensaje_cancelada`,`mensaje_generada` FROM `user_config`";


$objSQL = F_sqlConn();
echo $objSQL->multi_query(array($query1,$query2,$query3));


?>