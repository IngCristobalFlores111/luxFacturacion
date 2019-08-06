<?php

include("Functions.php");
$objSQL = F_sqlConn();

echo $objSQL->multi_query(array("SELECT * FROM `correos_default`","SELECT `mensaje_cancelada` FROM `user_config` "));




?>