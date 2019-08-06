<?php
session_start();
$privilegios =$_SESSION['privilegios'];

if($privilegios=='2'){echo "1";}
else{echo "0";}

?>