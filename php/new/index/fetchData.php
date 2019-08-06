<?php
if(isset($_GET['accion'])){
    include "../../../../Login/php/Functions.php"; 
    $sql= createMysqliConnection();
    switch($_GET['accion']){
     case "initData":

     $query="SELECT clientes_facturacion.idcliente AS id, clientes_facturacion.nombre,clientes_facturacion.RFC,clientes_facturacion.telefono,clientes_facturacion.celular,clientes_facturacion.email,COUNT(factura_generada.folio_factura) as numeroFacturas FROM `clientes_facturacion` LEFT JOIn factura_generada ON factura_generada.idcliente = clientes_facturacion.idcliente GROUP by clientes_facturacion.idcliente ORDER BY numeroFacturas DESC LIMIT 100";
     $clientes = $sql->executeQuery($query);
    print_r(json_encode($clientes,JSON_UNESCAPED_UNICODE));
     break;
     case "buscarCliente":
     $q = $sql->filter_input($_GET['q']);
    $query="SELECT clientes_facturacion.idcliente AS id, clientes_facturacion.nombre,clientes_facturacion.RFC,clientes_facturacion.telefono,clientes_facturacion.celular,clientes_facturacion.email,COUNT(factura_generada.folio_factura) as numeroFacturas FROM `clientes_facturacion` LEFT JOIn factura_generada ON factura_generada.idcliente = clientes_facturacion.idcliente WHERE MATCH(clientes_facturacion.nombre,clientes_facturacion.RFC,clientes_facturacion.telefono,clientes_facturacion.celular,clientes_facturacion.email) AGAINST('*".$q."*' IN BOOLEAN MODE) GROUP by clientes_facturacion.idcliente ORDER BY numeroFacturas DESC LIMIT 100";
    $clientes = $sql->executeQuery($query);
    print_r(json_encode($clientes,JSON_UNESCAPED_UNICODE));
    break;
    }
}

?>