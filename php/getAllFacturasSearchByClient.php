<?php
include("Functions.php");

$objSQL = F_sqlConn();
$searchString =$_POST['query'];
$searchString = $objSQL->filter_input($searchString);

$idCliente =$_POST['idCliente'];
$idCliente = $objSQL->filter_input($idCliente);

$query ="SELECT fg.folio_factura,fg.idcliente,fg.xml_file,c.nombre,c.RFC,fg.`folio_impreso`,fg.`fecha_timbrado`,fg.`fecha_cancelada`,fg.`montoTotal`,fg.`tipo` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE UCASE(fg.folio_impreso) LIKE UCASE('%$searchString%') AND c.idcliente='$idCliente' ORDER BY fg.`fecha_timbrado` DESC LIMIT 50";
$result = $objSQL->executeQuery($query);
$printGeneradas = true;
$printPendientes = true;
if(is_null($result))
{
    $printGeneradas = false;
}
$out2='';
if($printGeneradas)
{
    foreach($result as $factura)
    {
        $id_cliente = $factura['idcliente'];
        $RFC = $factura['RFC'];
        $nombre = $factura['nombre'];
        $total = $factura['montoTotal'];
        $fecha = $factura['fecha_timbrado'];
        $folio = $factura['folio_impreso'];
        $fecha_cancelada = $factura['fecha_cancelada'];
        $xml_file = $factura['xml_file'];
        $tipo_factura = $factura['tipo'];
        $tipo = '';
        $folio_factura =$factura['folio_factura'];
        if($fecha_cancelada=='0000-00-00')
        {
            $out2= $out2."<tr id = 'i_tableTimbradas'>";
            $tipo = 't';
        }
        else
        {
            $out2= $out2."<tr id = 'i_tableCanceladas'>";
            $tipo = 'c';
        }
        if($tipo_factura=='0'){$tipo_factura='Productos';}
        if($tipo_factura=='1'){$tipo_factura='Arrendamiento';}
        if($tipo_factura=='2'){$tipo_factura='Honorarios';}

        $out2 = $out2."<td>$folio</td>";
        $out2 = $out2."<td>$nombre</td>";
        $out2 = $out2."<td>$RFC</td>";
        $out2 = $out2."<td>$$total</td>";

        $out2 = $out2."<td>$tipo_factura</td>";
        $out2 = $out2."<td>$fecha</td>";
        if($fecha_cancelada=='0000-00-00')
        {
            $out2 = $out2."<td><i class='fa fa-bell' style = 'font-size:20px;' aria-hidden='true'></i></td>";
        }
        else{
            $out2 = $out2 ."<td><i class='fa fa-times' style = 'font-size:20px;' aria-hidden='true'></i></td>";


        }
        $out2 = $out2."<td>

            <button onclick='mostrar_preview(\"$xml_file\",0,0);' type='button' class='btn btn-default c_buttonTable1'><i class='fa fa-eye' style='font-size:18px;' aria-hidden='true'></i></button>
            <button id='$tipo,$folio_factura,$id_cliente' type='button' class='btn btn-default c_buttonTable2'><i class='fa fa-cog' style='font-size:18px;' aria-hidden='true'></i></button>

        </td>";
        $out2 = $out2."</tr>";

    }
}
$query ="SELECT c.idcliente,fp.`folio_factura`,c.nombre,c.RFC,fp.total,fp.tipo,fp.`fecha` FROM `factura_pendiente` fp INNER JOIN clientes_facturacion c ON c.`idcliente` = fp.`idcliente` WHERE UCASE(fp.folio_factura) LIKE UCASE('%$searchString%') AND c.idcliente='$idCliente' ORDER BY fp.fecha DESC LIMIT 50";
$result = $objSQL->executeQuery($query);
$out1='';
if(is_null($result)){
    $printPendientes=false;
}
if($printPendientes)
{

    foreach($result as $factura)
    {
        $RFC = $factura['RFC'];
        $nombre = $factura['nombre'];
        $total = $factura['total'];
        $fecha = $factura['fecha'];
        $folio = $factura['folio_factura'];
        $tipo =$factura['tipo'];

        //$fecha_cancelada  = $factura['fecha_cancelada'];
        $id_cliente = $factura['idcliente'];
        $out1= $out1."<tr id = 'i_tableGuardadas'>";
        $out1 = $out1."<td>$folio</td>";
        $out1 = $out1."<td>$nombre</td>";
        $out1 = $out1."<td>$RFC</td>";
        if($tipo=='0'){$tipo='Productos';}
        if($tipo=='1'){$tipo='Arrendamiento';}
        if($tipo=='2'){$tipo='Honorarios';}

        $out1 = $out1."<td>$$total</td>";
        $out1 = $out1."<td>$tipo</td>";

        $out1 = $out1."<td>$fecha</td>";
        $out1 = $out1."<td><i class='fa fa-exclamation' style = 'font-size:20px;' aria-hidden='true'></i></td>";
        $out1 = $out1."<td>

        <button onclick='mostrar_preview(0,$folio,$id_cliente);'  type='button'  class='btn btn-default c_buttonTable1'><i class='fa fa-eye' style='font-size:18px;' aria-hidden='true'></i></button>
            <button id='p,$folio,$id_cliente' type='button' class='btn btn-default c_buttonTable2'><i class='fa fa-cog' style='font-size:18px;' aria-hidden='true'></i></button>

        </td>";
        $out1 = $out1."</tr>";

    }
}
echo $out2.$out1;


?>