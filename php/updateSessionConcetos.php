<?php
session_start();
include("Functions.php");
$objSQL = F_sqlConn();

$i = $_POST['index'];
$updatedConcepto = $_POST['updatedConcepto'];
$i = $objSQL->filter_input($i);
$lj_updatedConcepto = json_decode($updatedConcepto,true);
$lj_updatedConcepto = $objSQL->filter_json($lj_updatedConcepto);

$precio = (float)$lj_updatedConcepto['precio_unitario'];
$cantidad = (float)$lj_updatedConcepto['cantidad'];
$importe = (float)$lj_updatedConcepto['importe'];

$lj_updatedConcepto['precio_unitario'] = round($precio,4);
$lj_updatedConcepto['cantidad'] = round($cantidad,4);
$lj_updatedConcepto['importe'] = round($importe,4);




$lj_conceptos = $_SESSION['conceptos'];
$lj_conceptos[$i] = (array)$lj_updatedConcepto;

$_SESSION['conceptos'] = $lj_conceptos;

?>