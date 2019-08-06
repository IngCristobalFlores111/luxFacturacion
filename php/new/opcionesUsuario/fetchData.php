<?php
if(isset($_GET['accion'])){
    include "../functions.php";
    $sql= createMysqliConnection();
    switch($_GET['accion']){
    case "regimenes":
    $query="SELECT id,c_RegimenFiscal AS codigo,`Descripción` AS descripcion FROM `f4_c_regimenfiscal`";
    $result = $sql->executeQuery($query);
    print_r(json_encode($result,JSON_UNESCAPED_UNICODE));
    break;
    case "config":
    session_start();
    $idUsr=  $_SESSION['idUsuario'];
    $query="SELECT user_config.`tipo_factura`,f4_c_regimenfiscal.Descripción AS regimen_nombre,user_config.`regimen` FROM `user_config` INNER JOIN f4_c_regimenfiscal ON f4_c_regimenfiscal.id = user_config.regimen
    WHERE user_config.idusuario = ?";
    
    $result = $sql->get_bind_results($query,array("s",$idUsr));
    $result = $result[0];
    print_r(json_encode($result,JSON_UNESCAPED_UNICODE));
    
    break;

    }
}

?>