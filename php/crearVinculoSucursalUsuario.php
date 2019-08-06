<?php
    include("Functions.php");
    MysqlLink::m_filterArray($_POST["SUCURSALES_ID"]);
    $sucursales = $_POST["SUCURSALES_ID"];
    $sucursalesLen = sizeof($_POST["SUCURSALES_ID"]);
    $idUsuario = MysqlLink::m_filterInput($_POST["USUARIO_ID"]);

    $i = 0;
    $sql= "INSERT INTO `usuarios_expedicion`(`idusuario`, `idexpedicion`) VALUES";

    $values = "";
    for($i; $i < $sucursalesLen; $i++){
        if($i == $sucursalesLen -1)
            $values = $values . "('".$idUsuario."','".$sucursales[$i]."')";
        else
            $values = $values . "('".$idUsuario."','".$sucursales[$i]."'),";

    }
    $sql = $sql.$values;
    if($sucursalesLen > 0){
        $link = F_linkDb();
        $link->m_SendCommand($sql,MysqlLink::NAMED,MysqlLink::RET_NO);
    }

    $sql = "SELECT usuarios_expedicion.idusuario, CONCAT(lg.`calle`,' ',lg.`noExt`,' ',lg.`municipio`,',',lg.`estado`) AS domicilio,lg.idexpedicion FROM lugar_expedicion as lg INNER JOIN usuarios_expedicion ON usuarios_expedicion.idexpedicion = lg.idexpedicion
WHERE usuarios_expedicion.idusuario = '".$idUsuario."';";
    print_r(json_encode(array("idUsuario" => $idUsuario,"sucursales" => $link->m_SendCommand($sql,MysqlLink::NAMED,MysqlLink::RET_YES),JSON_UNESCAPED_UNICODE)));

?>