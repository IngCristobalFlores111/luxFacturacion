<?php
    require_once("Functions.php");
    $link = F_linkDb();
    $ret = $link->m_SendCommand("SELECT colonia,calle,idexpedicion,noExt FROM lugar_expedicion;",MysqlLink::NAMED,MysqlLink::RET_YES);
    $i = 0;
    $size = sizeof($ret);
    $output = array();

    for($i; $i < $size; $i++)
     $output[$i] = '<tr><td><div class="checkbox"><label><input type="checkbox" value="'.$ret[$i]["idexpedicion"].'"></label></div></td><td>'.$ret[$i]["calle"].'</td><td>'.$ret[$i]["colonia"].', '.$ret[$i]["calle"].', '.$ret[$i]["noExt"].'</td></tr>';
    
     $output = join('',$output);

     echo $output;
?>