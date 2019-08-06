<?php


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$concepto = $_POST['concepto'];
$_SESSION["idCliente"] = $_POST['idCliente'];
//$_SESSION["folio"] =$_POST['folio'];
$_SESSION['total'] =$_POST['total_importe'];


$tempArray = json_decode($concepto, true);
$precio = (float)$tempArray['precio_unitario'];
$cantidad =(float) $tempArray['cantidad'];
$importe =(float) $tempArray['importe'];

$tempArray['precio_unitario'] = round($precio,4);
$tempArray['cantidad'] = round($cantidad,4);
$tempArray['importe'] = round($importe,4);


if(isset($_SESSION['conceptos']))
{
$jsonArrayConceptos =  $_SESSION['conceptos'];
array_push($jsonArrayConceptos, $tempArray);
$_SESSION['conceptos'] = $jsonArrayConceptos;
}
	else{
	$jsonArrayConceptos = array();
	array_push($jsonArrayConceptos, $tempArray);
	$_SESSION['conceptos'] = $jsonArrayConceptos;


}







?>