<?php

include("Functions.php");
$objSQL = F_sqlConn();
$lj_concepto = $_POST['updatedConcepto'];
$lj_concepto = json_decode($lj_concepto);
$lj_concepto = $objSQL->filter_json($lj_concepto);
$set_query ='';
foreach($lj_concepto as $key=>$value){
    $set_query = $set_query."$key ='$value',";
}
$set_query = rtrim($set_query, ",");
$id_concepto = $lj_concepto->id_concepto;
$query ="UPDATE `folio_conceptos` SET $set_query WHERE id_concepto='$id_concepto'  ";
$objSQL->executeCommand($query);
//echo $query;
?>