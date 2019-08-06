<?php
$state = $_POST['option'];

include("Functions.php");
$objSQL = F_sqlConn();
$state = $objSQL->filter_input($state);
$query ="UPDATE `user_config` SET `tipo_factura`='$state'";
$objSQL->executeCommand($query);
?>