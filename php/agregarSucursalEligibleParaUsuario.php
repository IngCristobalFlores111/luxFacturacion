<?php
include("Functions.php");

$link = F_linkDb();
$idUsuario = MysqlLink::m_filterInput($_POST["ID_USUARIO"]);

$sql = "SELECT CONCAT(lg.`calle`,' ',lg.`noExt`,' ',lg.`municipio`,',',lg.`estado`) AS domicilio, lg.idexpedicion, ue.idusuario FROM lugar_expedicion as lg
LEFT JOIN
(SELECT idusuario, idexpedicion FROM usuarios_expedicion WHERE idusuario = '".$idUsuario."') as ue
ON ue.idexpedicion = lg.idexpedicion
WHERE ue.idusuario IS NULL;";

$ret = $link->m_SendCommand($sql,MysqlLink::NAMED,MysqlLink::RET_YES);
print_r(json_encode(array("idUsuario"=>$idUsuario,"sucursales" => $ret),JSON_UNESCAPED_UNICODE));

?>