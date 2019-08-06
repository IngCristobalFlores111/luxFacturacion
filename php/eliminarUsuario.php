<?php
include("Functions.php");

$objSQL = F_linkDb();
$link = F_luxlineDb();

$email = $_POST['email'];
$email = MysqlLink::m_filterInput($email);

$idUsuario = $objSQL->m_SendCommand("SELECT idusuario FROM `usuarios` WHERE email = '".$email."';",MysqlLink::NAMED,MysqlLink::RET_YES)[0]["idusuario"];


//Cuenta maestra?
$cuenta = $link->m_SendCommand("SELECT COUNT(*) AS cuenta FROM clientes WHERE idGP = '".$idUsuario."';",MysqlLink::NAMED,MysqlLink::RET_YES)[0]["cuenta"];
if($cuenta > 0){
    $arr = array(
         "info"=> "Esta cuenta es una cuenta maestra y no puede ser eliminada",
         "res" => -1
         );

    echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    return;
}

//Borrar usuario_ui ?
$activeUiQty = $link->m_SendCommand("SELECT COUNT(*) as activeUiQty FROM  usuarios_ui WHERE id_usuario = '$idUsuario';",MysqlLink::NAMED,MysqlLink::RET_YES)[0]["activeUiQty"];
if($activeUiQty === 1){
    $link->m_SendCommand("DELETE FROM usuarios_ui WHERE id_usuario = '$idUsuario';",MysqlLink::NAMED,MysqlLink::RET_NO); //Si el usuario ya no tienen interfacez activas entonces ya no tiene caso mantenerlo como usuario de nuestro cliente
    $link->m_SendCommand("DELETE FROM `clientes_usuario` WHERE `id_usuario` = '$idUsuario';",MysqlLink::NAMED,MysqlLink::RET_NO); //Si el usuario ya no tienen interfacez activas entonces ya no tiene caso mantenerlo como usuario de nuestro cliente
}
else
    $link->m_SendCommand("DELETE FROM usuarios_ui WHERE id_usuario = '$idUsuario' AND ui = 0;",MysqlLink::NAMED,MysqlLink::RET_NO); //El usuario cuenta con otras interfaces, solo borrar de este servicio


$objSQL->m_SendCommand("DELETE FROM `user_config` WHERE `idusuario`='$idUsuario';",MysqlLink::NAMED,MysqlLink::RET_NO);

$objSQL->m_SendCommand("DELETE FROM `usuarios_expedicion` WHERE `idusuario` =  '$idUsuario' ;",MysqlLink::NAMED,MysqlLink::RET_NO);


$objSQL->m_SendCommand("DELETE FROM `usuarios` WHERE `idusuario`='$idUsuario';",MysqlLink::NAMED,MysqlLink::RET_NO);


$arr = array(
         "info"=> "Usuario eliminado correctamente",
         "res" => 0
         );

echo json_encode($arr,JSON_UNESCAPED_UNICODE);
?>