<?php
    include("Functions.php");
    $link = F_linkDb();
    print_r($_POST["DESCUENTO"].",");
print_r($_POST["ID"]);    
$Ret = $link->m_SendCommand("UPDATE factura_pendiente SET descuento=".$_POST["DESCUENTO"]." WHERE folio_factura=".$_POST["ID"]."; ",MysqlLink::NAMED,MysqlLink::RET_NO);
?>