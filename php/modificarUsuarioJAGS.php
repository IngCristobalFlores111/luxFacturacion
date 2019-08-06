<?php
    require_once("Functions.php");

    $params = $_POST["PARAMS"];
    MysqlLink::m_filterArray($params);

    $link = F_linkDb();
    $ID = $link->m_SendCommand('SELECT `idusuario` FROM `usuarios` WHERE `email` ="'.$params[3].'";',MysqlLink::NAMED,MysqlLink::RET_YES)[0]["idusuario"];

    //Lugares de Expedición
    $ret = $link->m_SendCommand('DELETE FROM `usuarios_expedicion` WHERE `idusuario` = "'.$ID.'"',MysqlLink::NAMED,MysqlLink::RET_NO);

    $i = 0;
    $size = sizeof($params[1]);
    for($i; $i < $size; $i++)
        $link->m_SendCommand('INSERT INTO usuarios_expedicion(idusuario, idexpedicion) VALUES ("'.$ID.'","'.$params[1][$i].'");',MysqlLink::NAMED,MysqlLink::RET_NO);

    $link->m_SendCommand('UPDATE `usuarios` SET `privilegio`="'. $params[0] .'" WHERE `idusuario` ="'. $ID .'";',MysqlLink::NAMED,MysqlLink::RET_NO);



    if($params[2]["retenerIVA"] == "true")
        $params[2]["retenerIVA"] = 1;
    else
        $params[2]["retenerIVA"] = 0;

    if($params[2]["retenerISR"] == "true")
        $params[2]["retenerISR"] = 1;
    else
        $params[2]["retenerISR"] = 0;


    print_r($params[2]);

    $link->m_SendCommand('UPDATE `user_config` SET `retencion_IVA`="'.$params[2]["retenerIVA"].'",`retencion_ISR`="'.$params[2]["tasaRet"].'",`retener_ISR`="'.$params[2]["retenerISR"].'",`tipo_factura`="'.$params[2]["tipoFactura"].'" WHERE `idusuario` = "'.$ID.'";',MysqlLink::NAMED,MysqlLink::RET_NO);

    //$ret = $link->m_SendCommand("",MysqlLink::NAMED,MysqlLink::RET_NO);
    //$ret = $link->m_SendCommand("",MysqlLink::NAMED,MysqlLink::RET_NO);
    //$ret = $link->m_SendCommand("",MysqlLink::NAMED,MysqlLink::RET_NO);
?>