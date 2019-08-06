<?php
include("Functions.php");
$mail =$_POST['mail'];
$pass = $_POST['pass'];
$objSQL = F_sqlConn();
$mail =$objSQL->filter_input($mail);
$pass =$objSQL->filter_input($pass);
$query ="UPDATE `usuario_facturacion` SET `email`='$mail' ,`mail_pass`='$pass'";
$objSQL->executeCommand($query);


?>