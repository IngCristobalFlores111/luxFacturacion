<?php
include("Functions.php");

$link = F_linkDb();
$idUsuario = MysqlLink::m_filterInput($_POST["ID_USUARIO"]);
$sql = "SELECT usuarios_expedicion.idusuario, CONCAT(lg.`calle`,' ',lg.`noExt`,' ',lg.`municipio`,',',lg.`estado`) AS domicilio,lg.idexpedicion FROM lugar_expedicion as lg INNER JOIN usuarios_expedicion ON usuarios_expedicion.idexpedicion = lg.idexpedicion 
WHERE usuarios_expedicion.idusuario = '".$idUsuario."';";
print_r(json_encode(array("idUsuario" => $idUsuario, "sucursales" => $link->m_SendCommand($sql,MysqlLink::NAMED,MysqlLink::RET_YES),JSON_UNESCAPED_UNICODE)));    
?>