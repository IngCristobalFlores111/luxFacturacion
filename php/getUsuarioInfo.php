<?php
include("Functions.php");
$objSQL = F_sqlConn();
$email = $_POST['email'];
$email = $objSQL->filter_input($email);
$query ="SELECT u.`privilegio`,u.`nombre`,u.`email`,ug.retencion_IVA,ug.retencion_ISR,ug.retener_ISR,ug.tipo_factura FROM `usuarios` u INNER JOIN user_config ug ON ug.idusuario = u.`idusuario` WHERE u.email='$email';";
echo $objSQL->executeQueryJSON($query);





?>