<?php
session_start();
$conceptos = $_SESSION['conceptos'];
$tmpJConcpetos = json_encode($conceptos);
$conceptos = json_decode($tmpJConcpetos,true);
print_r($conceptos);

?>