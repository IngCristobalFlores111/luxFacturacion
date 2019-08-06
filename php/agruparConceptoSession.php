<?php
session_start();
$conceptos = $_SESSION['conceptos'];
$index = $_POST['index'];
$updated_concepto = $_POST['updated_concepto'];
$lj_updatedConcepto = json_decode($updated_concepto,true);

$precio = (float)$lj_updatedConcepto['precio_unitario'];
$cantidad = (float)$lj_updatedConcepto['cantidad'];
$importe = (float)$lj_updatedConcepto['importe'];

$lj_updatedConcepto['precio_unitario'] = round($precio,4);
$lj_updatedConcepto['cantidad'] = round($cantidad,4);
$lj_updatedConcepto['importe'] = round($importe,4);




$conceptos[$index] =(array) $lj_updatedConcepto;
$_SESSION['conceptos'] =(array)$conceptos;
//print_r($_SESSION['conceptos']);
?>