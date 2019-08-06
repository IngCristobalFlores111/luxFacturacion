<?php

include("Functions.php");
$objSQL = F_sqlConn();

echo $objSQL->get_json_from_array(array("nombre","edad","domicilio"),array("Cristobal","23","Avenida rio blanco 1676"));


?>