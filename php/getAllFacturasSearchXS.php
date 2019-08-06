<?php
include("Functions.php");

$objSQL = F_sqlConn();
$searchString =$_POST['query'];
$searchString = $objSQL->filter_input($searchString);

$query ="SELECT fg.folio_factura,fg.idcliente,fg.xml_file,c.nombre,c.RFC,fg.`folio_impreso`,fg.`fecha_timbrado`,fg.`fecha_cancelada`,fg.`montoTotal`,fg.`tipo` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE UCASE(CONCAT(c.RFC,' ',c.nombre, ' ',fg.folio_impreso)) LIKE UCASE('%$searchString%') ORDER BY fg.`fecha_timbrado` DESC LIMIT 50";
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
        $folio_Factura = $factura['folio_factura'];

        if($fecha_cancelada=='0000-00-00')
        {
            if($tipo_factura=='0'){$tipo_factura='Productos';}
            if($tipo_factura=='1'){$tipo_factura='Arrendamiento';}
            if($tipo_factura=='2'){$tipo_factura='Honorarios';}

            $out2 = $out2."<div class='row'><div class='col-xs-12'><table class='table table-bordered'><tbody>";
            $out2= $out2."<tr id = 'i_tableTimbradas'>";
            $out2 = $out2."<td><strong>Folio</strong></td>";
            $out2 = $out2."<td>$folio</td>";
            $out2 = $out2."</tr>";

            $out2= $out2."<tr id = 'i_tableTimbradas'>";
            $out2 = $out2."<td><strong>Nombre</strong></td>";
            $out2 = $out2."<td>$nombre</td>";
            $out2 = $out2."</tr>";

            $out2= $out2."<tr id = 'i_tableTimbradas'>";
            $out2 = $out2."<td><strong>RFC</strong></td>";
            $out2 = $out2."<td>$RFC</td>";
            $out2 = $out2."</tr>";

            $out2= $out2."<tr id = 'i_tableTimbradas'>";
            $out2 = $out2."<td><strong>Monto</strong></td>";
            $out2 = $out2."<td>$$total</td>";
            $out2 = $out2."</tr>";

            $out2= $out2."<tr id = 'i_tableTimbradas'>";
            $out2 = $out2."<td><strong>Tipo</strong></td>";
            $out2 = $out2."<td>$tipo_factura</td>";
            $out2 = $out2."</tr>";

            $out2= $out2."<tr id = 'i_tableTimbradas'>";
            $out2 = $out2."<td><strong>Timbrado</strong></td>";
            $out2 = $out2."<td>$fecha</td>";
            $out2 = $out2."</tr>";

            $out2= $out2."<tr id = 'i_tableTimbradas'>";
            $out2 = $out2."<td><strong>Estatus</strong></td>";
            $out2 = $out2."<td><i class='fa fa-bell' style = 'font-size:20px;' aria-hidden='true'></i></td>";
            $out2 = $out2."</tr>";

            $out2= $out2."<tr id = 'i_tableTimbradas'>";
            $out2 = $out2."<td><strong>Opciones</strong></td>";
            $out2 = $out2."<td>
            <button onclick='mostrar_preview(\"$xml_file\",0,0);' type='button' class='btn btn-default c_buttonTable1'><i class='fa fa-eye' style='font-size:18px;' aria-hidden='true'></i></button>
            <button id='t,$folio_Factura,$id_cliente' type='button' class='btn btn-default c_buttonTable2'><i class='fa fa-cog' style='font-size:18px;' aria-hidden='true'></i></button></td>";
            $out2 = $out2."</tr>";

            $out2 =$out2."</tbody></table></div></div>";
        }
        else
        {
            if($tipo_factura=='0'){$tipo_factura='Productos';}
            if($tipo_factura=='1'){$tipo_factura='Arrendamiento';}
            if($tipo_factura=='2'){$tipo_factura='Honorarios';}

            $out2 = $out2."<div class='row'><div class='col-xs-12'><table class='table table-bordered'><tbody>";
            $out2= $out2."<tr id = 'i_tableCanceladas'>";
            $out2 = $out2."<td><strong>Folio</strong></td>";
            $out2 = $out2."<td>$folio</td>";
            $out2 = $out2."</tr>";

            $out2= $out2."<tr id = 'i_tableCanceladas'>";
            $out2 = $out2."<td><strong>Nombre</strong></td>";
            $out2 = $out2."<td>$nombre</td>";
            $out2 = $out2."</tr>";

            $out2= $out2."<tr id = 'i_tableCanceladas'>";
            $out2 = $out2."<td><strong>RFC</strong></td>";
            $out2 = $out2."<td>$RFC</td>";
            $out2 = $out2."</tr>";

            $out2= $out2."<tr id = 'i_tableCanceladas'>";
            $out2 = $out2."<td><strong>Monto</strong></td>";
            $out2 = $out2."<td>$$total</td>";
            $out2 = $out2."</tr>";

            $out2= $out2."<tr id = 'i_tableCanceladas'>";
            $out2 = $out2."<td><strong>Tipo</strong></td>";
            $out2 = $out2."<td>$tipo_factura</td>";
            $out2 = $out2."</tr>";

            $out2= $out2."<tr id = 'i_tableCanceladas'>";
            $out2 = $out2."<td><strong>Timbrado</strong></td>";
            $out2 = $out2."<td>$fecha</td>";
            $out2 = $out2."</tr>";

            $out2= $out2."<tr id = 'i_tableCanceladas'>";
            $out2 = $out2."<td><strong>Estatus</strong></td>";
            $out2 = $out2."<td><i class='fa fa-times' style = 'font-size:20px;' aria-hidden='true'></i></td>";
            $out2 = $out2."</tr>";

            $out2= $out2."<tr id = 'i_tableCanceladas'>";
            $out2 = $out2."<td><strong>Opciones</strong></td>";
            $out2 = $out2."<td>
            <button onclick='mostrar_preview(\"$xml_file\",0,0);' type='button' class='btn btn-default c_buttonTable1'><i class='fa fa-eye' style='font-size:18px;' aria-hidden='true'></i></button>
            <button id='c,$folio_Factura,$id_cliente' type='button' class='btn btn-default c_buttonTable2'><i class='fa fa-cog' style='font-size:18px;' aria-hidden='true'></i></button></td>";
            $out2 = $out2."</tr>";

            $out2 =$out2."</tbody></table></div></div>";



        }




    }
}
$query ="SELECT c.idcliente,fp.`folio_factura`,c.nombre,c.RFC,fp.total,fp.tipo,fp.`fecha` FROM `factura_pendiente` fp INNER JOIN clientes_facturacion c ON c.`idcliente` = fp.`idcliente` WHERE UCASE(CONCAT(fp.folio_factura,' ',c.RFC,' ',c.nombre)) LIKE UCASE('%$searchString%') ORDER BY fp.fecha DESC LIMIT 50";
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

        $out1 = $out1."<div class='row'><div class='col-xs-12'><table class='table table-bordered'><tbody>";
        $out1= $out1."<tr id = 'i_tableGuardadas'>";
        $out1 = $out1."<td><strong>Folio</strong></td>";
        $out1 = $out1."<td>$folio</td>";
        $out1 = $out1."</tr>";

        $out1= $out1."<tr id = 'i_tableGuardadas'>";
        $out1 = $out1."<td><strong>Nombre</strong></td>";
        $out1 = $out1."<td>$nombre</td>";
        $out1 = $out1."</tr>";


        $out1= $out1."<tr id = 'i_tableGuardadas'>";
        $out1 = $out1."<td><strong>RFC</strong></td>";
        $out1 = $out1."<td>$RFC</td>";
        $out1 = $out1."</tr>";


        if($tipo=='0'){$tipo='Productos';}
        if($tipo=='1'){$tipo='Arrendamiento';}
        if($tipo=='2'){$tipo='Honorarios';}

        $out1= $out1."<tr id = 'i_tableGuardadas'>";
        $out1 = $out1."<td><strong>Monto</strong></td>";
        $out1 = $out1."<td>$$total</td>";
        $out1 = $out1."</tr>";



        $out1= $out1."<tr id = 'i_tableGuardadas'>";
        $out1 = $out1."<td><strong>Tipo</strong></td>";
        $out1 = $out1."<td>$tipo</td>";
        $out1 = $out1."</tr>";

        $out1= $out1."<tr id = 'i_tableGuardadas'>";
        $out1 = $out1."<td><strong>Fecha</strong></td>";
        $out1 = $out1."<td>$fecha</td>";
        $out1 = $out1."</tr>";

        $out1= $out1."<tr id = 'i_tableGuardadas'>";
        $out1 = $out1."<td><strong>Status</strong></td>";
        $out1 = $out1."<td><i class='fa fa-exclamation' style = 'font-size:20px;' aria-hidden='true'></i></td>";
        $out1 = $out1."</tr>";

        $out1= $out1."<tr id = 'i_tableGuardadas'>";
        $out1 = $out1."<td><strong>Opciones</strong></td>";
        $out1 = $out1."<td>

        <button onclick='mostrar_preview(0,$folio,$id_cliente);'  type='button' class='btn btn-default c_buttonTable1'><i class='fa fa-eye' style='font-size:18px;' aria-hidden='true'></i></button>
            <button id='p,$folio,$id_cliente' type='button' class='btn btn-default c_buttonTable2'><i class='fa fa-cog' style='font-size:18px;' aria-hidden='true'></i></button>

        </td>";
        $out1 = $out1."</tr>";
        $out1 = $out1."</div></div></tbody></table>";
    }
}
echo $out2.$out1;


?>