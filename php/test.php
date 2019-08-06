<?php
include ("Functions.php");
$objLink = F_linkDb();
$Ret = $objLink->m_SendCommand("SELECT COUNT(Res.idcliente) as noFacturas, Res.idcliente,Res.nombre,Res.RFC,Res.telefono,Res.celular,Res.email,Res.calle,Res.noExterior,Res.noInterior,Res.municipio,Res.localidad,Res.folio_factura FROM (SELECT clientes_facturacion.idcliente,nombre,RFC,telefono,celular,email,calle,noExterior,noInterior,municipio,localidad,factura_generada.folio_factura FROM clientes_facturacion LEFT JOIN factura_generada ON clientes_facturacion.idcliente = factura_generada.idcliente) as Res GROUP BY(Res.idcliente) ORDER BY noFacturas DESC LIMIT 25;",MysqlLink::NAMED,MysqlLink::RET_YES);
print_r($Ret);

?>