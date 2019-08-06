<?php
include("Functions.php");
$nombre = MysqlLink::m_filterInput($_POST["NOMBRE"]);
$privilegio = MysqlLink::m_filterInput($_POST["PRIVILEGIOS"]);
$email = MysqlLink::m_filterInput($_POST["MAIL"]);

$objSQL = F_linkDb();
$objSQLLuxline = F_LinkDbLuxLine();


$query = "SELECT idusuario FROM usuarios WHERE email = '".$email."'";
$idUsuario = $objSQL->m_SendCommand($query,MysqlLink::NAMED,MysqlLink::RET_YES)[0]['idusuario'];

$query = "SELECT COUNT(*) as cuenta FROM `clientes` WHERE idGP = '".$idUsuario."'";
$isManager = $objSQLLuxline->m_SendCommand($query,MysqlLink::NAMED, MysqlLink::RET_YES)[0]['cuenta'];

$arr = null;

if($isManager > 0 and $privilegio < 2){
    $arr = array(
       "info"=> "Este usuario es de caracter permanente y sus privilegios no pueden ser modificados",
       "res" => -1
       );



    $query = "UPDATE `usuarios` SET `nombre`='".$nombre."' WHERE email = '".$email."';";
    $objSQL->m_SendCommand($query,MysqlLink::NAMED,MysqlLink::RET_NO);

    echo json_encode($arr);
    return;
}
else if($isManager > 0){
    $query = "UPDATE `usuarios` SET `nombre`='".$nombre."' WHERE email = '".$email."';";
    $objSQL->m_SendCommand($query,MysqlLink::NAMED,MysqlLink::RET_NO);
    $arr = array(
       "info"=> "El usuario fue modificado correctamente",
       "res" => 0
       );

    echo json_encode($arr);
    return;
}
else{
    $query = "UPDATE `usuarios` SET `privilegio`=".$privilegio.",`nombre`='".$nombre."' WHERE email = '".$email."';";
    $objSQL->m_SendCommand($query,MysqlLink::NAMED,MysqlLink::RET_NO);
    $arr = array(
       "info"=> "El usuario fue modificado correctamente",
       "res" => 0
       );

    echo json_encode($arr);
    return;
}
?>