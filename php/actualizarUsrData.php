<?php
include("Functions.php");
$objSQL = F_sqlConn();
//session_start();
$ls_usrData = $_POST['usr_data'];
$lj_usrData = json_decode($ls_usrData);
$lj_usrData = $objSQL->filter_json($lj_usrData);

$ls_usrConfigData = $_POST['usr_config'];
$lj_usrConfigData = json_decode($ls_usrConfigData);
$lj_usrConfigData = $objSQL->filter_json($lj_usrConfigData);
$ls_update1Data = '';
foreach($lj_usrData as $key=>$value){

    $ls_update1Data= $ls_update1Data."$key='$value',";
}
$email = $lj_usrData->email;

$ls_update1Data = rtrim($ls_update1Data, ",");
$query="UPDATE `usuarios` SET $ls_update1Data WHERE email='$email';";
// obtener id de usuario para actualizar user_config
$result = $objSQL->executeQuery("SELECT idusuario FROM usuarios WHERE email='$email';");
$idUsuario =$result[0]['idusuario'];

$ls_update2Data = '';
foreach($lj_usrConfigData as $key=>$value){
    $ls_update2Data = $ls_update2Data."$key='$value',";
}
$ls_update2Data = rtrim($ls_update2Data, ",");
$query = $query."UPDATE `user_config` SET $ls_update2Data WHERE idusuario ='$idUsuario';";

echo $query;
$objSQL->executeCommand($query);




?>