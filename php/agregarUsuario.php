<?php
include("Functions.php");

session_start();

$json = null;
$arr = null;
$data  = $_POST['PARAMS'];

$objSQL = F_linkDb();

session_start();

$link = F_LinkDbLuxLine();

$idUsuario = $data[3]["Perfil"];


//Aqui se usa el ID del usuario que está dando de alta
$idcliente = $link->m_SendCommand("SELECT id_cliente FROM clientes_usuario WHERE id_usuario = '".$_SESSION['idUsuario']."';",MysqlLink::NAMED,MysqlLink::RET_YES)[0]['id_cliente'];

//Aquí se usa el id del usuario que trata de fdar de alta
$cuenta = $link->m_SendCommand("SELECT COUNT(*) as cuenta FROM clientes_usuario WHERE id_usuario = '".$idUsuario."'",MysqlLink::NAMED,MysqlLink::RET_YES)[0]['cuenta'];

if((int)($cuenta) === 0){
    $clienteFacturacionFolder = $link->m_SendCommand("SELECT CONCAT(folder,'/LuxFacturacion') as folder FROM `clientes` WHERE id_cliente = ".$idcliente,MysqlLink::NAMED,MysqlLink::RET_YES)[0]['folder'];

    //Si el usuario no existe hay que crearlo y luego agregar el servicio
    $link->m_SendCommand("INSERT INTO `clientes_usuario`(`id_cliente`, `id_usuario`) VALUES ('".$idcliente."','".$idUsuario."');",MysqlLink::NAMED,MysqlLink::RET_NO);

    //Insertar las interfaces a las cuales tendrá acceso este usuario
    $link->m_SendCommand("INSERT INTO `usuarios_ui`(`folder`, `ui`, `id_usuario`) VALUES ('".$clienteFacturacionFolder."',0,'".$idUsuario."')",MysqlLink::NAMED,MysqlLink::RET_NO);

    $objSQL->m_SendCommand('INSERT INTO usuarios(privilegio, idusuario, nombre, email) VALUES ("'.$data[0].'","'.$idUsuario.'","'.$data[3]["Nombre"].'","'.$data[3]["Mail"].'");',MysqlLink::NAMED,MysqlLink::RET_NO);

    $i = 0;
    $size = sizeof($data[1]);
    for($i; $i < $size; $i++){
        $objSQL->m_SendCommand('INSERT INTO usuarios_expedicion(idusuario, idexpedicion) VALUES ("'.$idUsuario.'","'.$data[1][$i].'");',MysqlLink::NAMED,MysqlLink::RET_NO);
    }

    if($data[2]["retenerIVA"])
        $data[2]["retenerIVA"] = 1;
    else
        $data[2]["retenerIVA"] = 0;

    if($data[2]["retenerISR"])
        $data[2]["retenerISR"] = 1;
    else
        $data[2]["retenerISR"] = 0;

    $objSQL->m_SendCommand('INSERT INTO user_config(idusuario, retencion_IVA, retencion_ISR, retener_ISR, conceptos_iva, mensaje_timbrada, mensaje_cancelada, mensaje_generada, tipo_factura) VALUES ("'.$data[3]["Perfil"].'","'.$data[2]["retenerIVA"].'","'.$data[2]["tasaRet"].'","'.$data[2]["retenerISR"].'","1","La factura se ha timbrado correctamente","La factura fue cancelada satisfactoriamente","Esta factura no tiene validez fiscal y fue generada con propositos demostrativos","'.$data[2]["tipoFactura"].'");',MysqlLink::NAMED,MysqlLink::RET_NO);

    $arr = array(
             "info"=> "",
             "res" => 0
             );
    echo json_encode($arr);
    $_SESSION["Privilegios"] = $data[0];
    return;
}

//Si ya existe el usuario, hay que ver si tiene el servicio
$cuenta = $link->m_SendCommand("SELECT COUNT(*) as cuenta FROM usuarios_ui WHERE id_usuario = '".$id."' AND ui = 0;",MysqlLink::NAMED,MysqlLink::RET_YES)[0]["cuenta"];
if($cuenta > 0){
    $arr = array(
         "info"=> "Este usuario ya está registrado",
         "res" => -1
         );

    echo json_encode($arr);
    return;
}

$clienteFacturacionFolder = $link->m_SendCommand("SELECT CONCAT(folder,'/LuxFacturacion') FROM `clientes` WHERE id_cliente = ".$idcliente,MysqlLink::NAMED,MysqlLink::RET_YES);

//Insertar las interfaces a las cuales tendrá acceso este usuario
$link->m_SendCommand("INSERT INTO `usuarios_ui`(`folder`, `ui`, `id_usuario`) VALUES ('".$clienteFacturacionFolder."',0,'".$data[3]["Perfil"]."')",MysqlLink::NAMED,MysqlLink::RET_NO);


$objSQL->m_SendCommand('INSERT INTO usuarios(privilegio, idusuario, nombre, email) VALUES ("'.$data[0].'","'.$data[3]["Perfil"].'","'.$data[3]["Nombre"].'","'.$data[3]["Mail"].'");',MysqlLink::NAMED,MysqlLink::RET_NO);

$i = 0;
$size = sizeof($data[1]);
for($i; $i < $size; $i++){
    $objSQL->m_SendCommand('INSERT INTO usuarios_expedicion(idusuario, idexpedicion) VALUES ("'.$data[3]["Perfil"].'","'.$data[1][$i].'");',MysqlLink::NAMED,MysqlLink::RET_NO);
}

if($data[2]["retenerIVA"])
    $data[2]["retenerIVA"] = 1;
else
    $data[2]["retenerIVA"] = 0;

if($data[2]["retenerISR"])
    $data[2]["retenerISR"] = 1;
else
    $data[2]["retenerISR"] = 0;

$objSQL->m_SendCommand('INSERT INTO user_config(idusuario, retencion_IVA, retencion_ISR, retener_ISR, conceptos_iva, mensaje_timbrada, mensaje_cancelada, mensaje_generada, tipo_factura) VALUES ("'.$data[3]["Perfil"].'","'.$data[2]["retenerIVA"].'","'.$data[2]["tasaRet"].'","'.$data[2]["retenerISR"].'","1","La factura se ha timbrado correctamente","La factura fue cancelada satisfactoriamente","Esta factura no tiene validez fiscal y fue generada con propositos demostrativos","'.$data[2]["tipoFactura"].'");',MysqlLink::NAMED,MysqlLink::RET_NO);

$arr = array(
         "info"=> "",
         "res" => 0
         );
echo json_encode($arr);
$_SESSION["Privilegios"] = $data[0];
return;
?>