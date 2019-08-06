<?php
$c_actuales = $_POST['conceptos'];
session_start();
$_SESSION['conceptos'] = $c_actuales;

?>