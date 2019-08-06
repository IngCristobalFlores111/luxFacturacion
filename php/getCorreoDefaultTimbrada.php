<?php
include("Functions.php");
$objSQL = F_sqlConn();
echo $objSQL->multi_query(array("SELECT `email` FROM `correos_default`","SELECT `mensaje_timbrada` FROM user_config"));


?>