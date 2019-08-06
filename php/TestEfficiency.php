<?php
include("Functions.php");

$start = microtime(true);

$linkObj = F_linkDb();
$userLinkObj = F_userLinkDb();

$Ret = $linkObj->m_SendCommand("SELECT * FROM dblux_facturacion.clientes_facturacion;",MysqlLink::NAMED,MysqlLink::RET_YES);


$Ret2 = $linkObj->m_SendCommand("SELECT * FROM db_luxline.clientes_facturacion;",MysqlLink::NAMED,MysqlLink::RET_YES);




//$linkObj = F_linkDb();
//    $Ret = $linkObj->m_SendCommand("SELECT * FROM db_luxline.clientes_facturacion;",MysqlLink::NAMED,MysqlLink::RET_YES);
   
//    $Ret2 = $linkObj->m_SendCommand("SELECT * FROM db_luxline.clientes_facturacion;",MysqlLink::NAMED,MysqlLink::RET_YES);
   

$time_elapsed_secs = microtime(true) - $start;
print_r($time_elapsed_secs);

?>