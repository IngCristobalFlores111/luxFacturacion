
<?php
include("Functions.php");
$objSQL = F_sqlConn();

echo $objSQL->executeQueryJSON("SELECT `mensaje_timbrada`,`mensaje_generada` FROM `user_config`");

?>