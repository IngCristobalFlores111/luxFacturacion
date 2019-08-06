<?php
    include("Functions.php");
    $link = F_linkDb();
    $nombre = MysqlLink::m_filterInput($_POST["NOMBRE"]);
    if($nombre == "" || $nombre == null || $nombre == NULL){
        print_r(json_encode(array()));
        return;
    }
    $sql = "SELECT idusuario,nombre,email FROM `usuarios` WHERE MATCH nombre AGAINST('". $nombre ."*' IN BOOLEAN MODE)";
    $ret = $link->m_SendCommand($sql,MysqlLink::NAMED,MysqlLink::RET_YES);
    print_r(json_encode($ret,JSON_UNESCAPED_UNICODE));
?>