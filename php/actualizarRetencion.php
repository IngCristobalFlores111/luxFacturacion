<?php
include("Functions.php");
$gs_iva =$_POST['iva'];
$gs_isr = $_POST['isr'];
$gs_retenerISR = $_POST['retenerISR'];
$objSQL = F_sqlConn();
$gs_isr=$objSQL->filter_input($gs_isr);
$gs_iva=$objSQL->filter_input($gs_iva);
$gs_retenerISR=$objSQL->filter_input($gs_retenerISR);


$query ="UPDATE `user_config` SET retener_ISR=$gs_retenerISR, retencion_IVA=$gs_iva, retencion_ISR = $gs_isr";
$objSQL->executeCommand($query);




?>