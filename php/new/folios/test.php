<?php
if(isset($_GET['accion']))
{
include("../functions.php");
$sql = createMysqliConnection();
$opcion = $_GET['accion'];
$opcion = trim($opcion);
switch($opcion){
case "initFacturas":
$query="SELECT factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo ORDER BY factura_generada.fecha_timbrado DESC LIMIT 25";
$results = $sql->executeQuery($query);
print_r($results);
print_r(json_encode($results,JSON_UNESCAPED_UNICODE));

break;
case "filtrar":
$fechaDesde = $sql->filter_input($_GET['fechaDesde']);
$fechaHasta = $sql->filter_input($_GET['fechaHasta']);
$montoDesde = $sql->filter_input($_GET['montoDesde']);
$montoHasta = $sql->filter_input($_GET['montoHasta']);

$montoDesde = (float)$montoDesde;
$montoHasta = (float)$montoHasta;

$q = $sql->filter_input($_GET['q']);
if($q!=""){

}

$results = array();
if($fechaDesde!=""&&$fechaHasta!=""&& $q==""&& $montoDesde==0 && $montoHasta==0){
    $query="SELECT  factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo
    WHERE factura_generada.fecha_timbrado BETWEEN ? AND ?
    ORDER BY factura_generada.fecha_timbrado";
    $results=$sql->get_bind_results($query,array("ss",$fechaDesde,$fechaHasta));
    
}
if($fechaDesde!=""&&$fechaHasta!=""&& $q==""&& $montoDesde>=0 && $montoHasta>0)
{
$query="SELECT  factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo

WHERE factura_generada.fecha_timbrado BETWEEN ? AND ?
AND factura_generada.montoTotal  BETWEEN ? AND ?
ORDER BY factura_generada.fecha_timbrado";
    
    $results=$sql->get_bind_results($query,array("ssdd",$fechaDesde,$fechaHasta,$montoDesde,$montoHasta));    
}  
if($fechaDesde==""&&$fechaHasta==""&& $q=="" && $montoDesde>=0 && $montoHasta>0){
    $query="SELECT  factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo
    
    WHERE factura_generada.montoTotal  BETWEEN ? AND ?
    ORDER BY factura_generada.fecha_timbrado";
        
$results =$sql->get_bind_results($query,array("dd",$montoDesde,$montoHasta));    
} 
if($fechaDesde==""&&$fechaHasta==""&& $q!="" &&$montoDesde>=0 && $montoHasta>0){
    $query="SELECT  factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo WHERE factura_generada.montoTotal 
    BETWEEN '$montoDesde' AND '$montoHasta' AND MATCH(clientes_facturacion.nombre,clientes_facturacion.RFC) AGAINST 
    ('*$q*' IN BOOLEAN MODE) ORDER BY factura_generada.fecha_timbrado";
$results =$sql->executeQuery($query);

}   
if($fechaDesde!=""&&$fechaHasta!=""&& $q!="" && $montoDesde==0 && $montoHasta==0){
$query="SELECT  factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo WHERE 
factura_generada.fecha_timbrado BETWEEN '$fechaDesde' AND '$fechaHasta'
AND MATCH(clientes_facturacion.nombre,clientes_facturacion.RFC) AGAINST 
    ('*$q*' IN BOOLEAN MODE) ORDER BY factura_generada.fecha_timbrado";
$results =$sql->executeQuery($query);

} 
if($fechaDesde!=""&&$fechaHasta!=""&& $q!="" && $montoDesde>=0 && $montoHasta>0){
$query="SELECT  factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo WHERE 
factura_generada.fecha_timbrado BETWEEN '$fechaDesde' AND '$fechaHasta' AND factura_generada.montoTotal BETWEEN '$montoDesde' and '$montoHasta'
AND MATCH(clientes_facturacion.nombre,clientes_facturacion.RFC) AGAINST 
    ('*$q*' IN BOOLEAN MODE) ORDER BY factura_generada.fecha_timbrado";

$results =$sql->executeQuery($query);

}
if($fechaDesde=="" && $fechaHasta==""&& $q!="" && $montoDesde==0 && $montoHasta==0){
    $query="SELECT  factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo WHERE 
    MATCH(clientes_facturacion.nombre,clientes_facturacion.RFC) AGAINST 
        ('*$q*' IN BOOLEAN MODE)  OR folio_factura LIKE'%".$q."%' ORDER BY factura_generada.fecha_timbrado";
$results =$sql->executeQuery($query);

}    
print_r(json_encode($results,JSON_UNESCAPED_UNICODE));

break;
case "obtenerPendientes":
$query="SELECT  factura_pendiente.idFacturaPendiente AS id,clientes_facturacion.idcliente,'' AS uuid,'' AS xml_file,'' AS pdf_file,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_pendiente.total AS montoTotal,0 AS fecha_timbrado,0 AS fecha_cancelada,factura_pendiente.folio_factura AS folio,tipos_factura.nombre AS tipo FROM `factura_pendiente` INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_pendiente.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_pendiente.tipo";
$results =$sql->executeQuery($query);
print_r(json_encode($results,JSON_UNESCAPED_UNICODE));

break;
//clientes
case "initFacturasClientes":
$idCliente = $_GET['idCliente'];
$query="SELECT factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo WHERE clientes_facturacion.idcliente='$idCliente' ORDER BY factura_generada.fecha_timbrado DESC LIMIT 25";
$results = $sql->executeQuery($query);
print_r(json_encode($results,JSON_UNESCAPED_UNICODE));

break;
case "filtrarClientes":
$idCliente = $_GET['idCliente'];
$fechaDesde = $sql->filter_input($_GET['fechaDesde']);
$fechaHasta = $sql->filter_input($_GET['fechaHasta']);
$montoDesde = $sql->filter_input($_GET['montoDesde']);
$montoHasta = $sql->filter_input($_GET['montoHasta']);

$montoDesde = (float)$montoDesde;
$montoHasta = (float)$montoHasta;

$q = $sql->filter_input($_GET['q']);
if($q!=""){

}

$results = array();
if($fechaDesde!=""&&$fechaHasta!=""&& $q==""&& $montoDesde==0 && $montoHasta==0){
    $query="SELECT  factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo
    WHERE factura_generada.fecha_timbrado BETWEEN ? AND ? AND clientes_facturacion.idcliente=$idCliente 
    ORDER BY factura_generada.fecha_timbrado";
    $results=$sql->get_bind_results($query,array("ss",$fechaDesde,$fechaHasta));
    
}
if($fechaDesde!=""&&$fechaHasta!=""&& $q==""&& $montoDesde>=0 && $montoHasta>0)
{
$query="SELECT  factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo

WHERE factura_generada.fecha_timbrado BETWEEN ? AND ?
AND factura_generada.montoTotal  BETWEEN ? AND ? AND clientes_facturacion.idcliente=$idCliente
ORDER BY factura_generada.fecha_timbrado";
    
    $results=$sql->get_bind_results($query,array("ssdd",$fechaDesde,$fechaHasta,$montoDesde,$montoHasta));    
}  
if($fechaDesde==""&&$fechaHasta==""&& $q=="" && $montoDesde>=0 && $montoHasta>0){
    $query="SELECT  factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo
    
    WHERE factura_generada.montoTotal  BETWEEN ? AND ? AND clientes_facturacion.idcliente=$idCliente
    ORDER BY factura_generada.fecha_timbrado";
        
$results =$sql->get_bind_results($query,array("dd",$montoDesde,$montoHasta));    
} 
if($fechaDesde==""&&$fechaHasta==""&& $q!="" &&$montoDesde>=0 && $montoHasta>0){
    $query="SELECT  factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo WHERE factura_generada.montoTotal 
    BETWEEN '$montoDesde' AND '$montoHasta' AND MATCH(clientes_facturacion.nombre,clientes_facturacion.RFC) AGAINST 
    ('*$q*' IN BOOLEAN MODE) AND clientes_facturacion.idcliente=$idCliente ORDER BY factura_generada.fecha_timbrado";
$results =$sql->executeQuery($query);

}   
if($fechaDesde!=""&&$fechaHasta!=""&& $q!="" && $montoDesde==0 && $montoHasta==0){
$query="SELECT  factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo WHERE 
factura_generada.fecha_timbrado BETWEEN '$fechaDesde' AND '$fechaHasta'
AND MATCH(clientes_facturacion.nombre,clientes_facturacion.RFC) AGAINST 
    ('*$q*' IN BOOLEAN MODE) AND clientes_facturacion.idcliente=$idCliente ORDER BY factura_generada.fecha_timbrado";
$results =$sql->executeQuery($query);

} 
if($fechaDesde!=""&&$fechaHasta!=""&& $q!="" && $montoDesde>=0 && $montoHasta>0){
$query="SELECT  factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo WHERE 
factura_generada.fecha_timbrado BETWEEN '$fechaDesde' AND '$fechaHasta' AND factura_generada.montoTotal BETWEEN '$montoDesde' and '$montoHasta'
AND MATCH(clientes_facturacion.nombre,clientes_facturacion.RFC) AGAINST 
    ('*$q*' IN BOOLEAN MODE) AND clientes_facturacion.idcliente=$idCliente ORDER BY factura_generada.fecha_timbrado";

$results =$sql->executeQuery($query);

}
if($fechaDesde=="" && $fechaHasta==""&& $q!="" && $montoDesde==0 && $montoHasta==0){
    $query="SELECT  factura_generada.idSerie,clientes_facturacion.idcliente,factura_generada.uuid,factura_generada.folio_factura AS folio,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_generada.montoTotal,tipos_factura.nombre AS tipo, factura_generada.fecha_timbrado, factura_generada.fecha_cancelada,factura_generada.xml_file,getFileWithExtension(factura_generada.xml_file,'pdf') AS pdf_file FROM factura_generada INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo WHERE 
    MATCH(clientes_facturacion.nombre,clientes_facturacion.RFC) AGAINST 
        ('*$q*' IN BOOLEAN MODE)  OR folio_factura LIKE'%".$q."%' AND clientes_facturacion.idcliente=$idCliente ORDER BY factura_generada.fecha_timbrado";
$results =$sql->executeQuery($query);

}    
print_r(json_encode($results,JSON_UNESCAPED_UNICODE));

break;
case "obtenerPendientesClientes":
$idCliente = $_GET['idCliente'];

$query="SELECT  clientes_facturacion.idcliente,'' AS uuid,'' AS xml_file,'' AS pdf_file,clientes_facturacion.nombre,clientes_facturacion.RFC,factura_pendiente.total AS montoTotal,0 AS fecha_timbrado,0 AS fecha_cancelada,factura_pendiente.folio_factura AS folio,tipos_factura.nombre AS tipo FROM `factura_pendiente` INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_pendiente.idcliente  INNER JOIN tipos_factura ON tipos_factura.id = factura_pendiente.tipo
WHERE clientes_facturacion.idcliente= $idCliente";
$results =$sql->executeQuery($query);
print_r(json_encode($results,JSON_UNESCAPED_UNICODE));

break;

}
}

?>