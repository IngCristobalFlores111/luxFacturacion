<?php
include("Functions.php");
$objSQL = F_sqlConn();
$lj_modAtajo =$_POST['atajo'];
$id_atajo =$_POST['idAtajo'];
$lj_modAtajo =$objSQL->filter_json($lj_modAtajo);
$id_atajo =$objSQL->filter_input($id_atajo);
$update_query ='';
foreach($lj_modAtajo as $key=>$value){
    $update_query =$update_query."$key='$value',";

}
$update_query =rtrim($update_query, ",");
$query ="UPDATE `atajos` SET $update_query WHERE `idatajo`='$id_atajo'";
$objSQL->executeCommand($query);


?>