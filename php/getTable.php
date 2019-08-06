<?php
include("Functions.php");
$objSQL = F_sqlConn();

$searchParams = $_POST['searchParams'];  // cadena en JSON que tiene los parametros de la busqueda
$gj_params = json_decode($searchParams);
$gs_orderBy = $gj_params->order;  // ordenar por algun campo especifico
$gs_ascDesc= $gj_params->ascDesc;  // ordenar de manera acendente o descendente
$gs_searchString = $gj_params->searchString;
$gs_dateFrom = $gj_params->dateFrom;
$gs_dateTo = $gj_params->dateTo;
$gs_amountFrom = $gj_params->amountFrom;
$gs_amountTo = $gj_params->amountTo;
$gs_showOnly = $gj_params->showOnly;


$gs_searchString = $objSQL->filter_input($gs_searchString);
$gs_orderBy = $objSQL->filter_input($gs_orderBy);
$gs_ascDesc = $objSQL->filter_input($gs_ascDesc);
$gs_dateFrom = $objSQL->filter_input($gs_dateFrom);
$gs_dateTo = $objSQL->filter_input($gs_dateTo);
$gs_amountFrom = $objSQL->filter_input($gs_amountFrom);
$gs_amountTo = $objSQL->filter_input($gs_amountTo);
$gs_showOnly = $objSQL->filter_input($gs_showOnly);

$amoutQuery = '';
$amoutQuery2 = '';

//print_r(
//$gs_searchString .",".
//$gs_orderBy .",".
//$gs_ascDesc .",".
//$gs_dateFrom .",".
//$gs_dateTo .",".
//$gs_amountFrom .",".
//$gs_amountTo .",".
//$gs_showOnly);

if((float)$gs_amountFrom>0 ||(float) $gs_amountTo>0) //  Activa filtro por cantidad
{
	$amoutQuery = "AND fp.total BETWEEN '$gs_amountFrom' AND '$gs_amountTo'";
	$amoutQuery2 = "AND fg.montoTotal BETWEEN '$gs_amountFrom' AND '$gs_amountTo'";
}

$dateQuery = '';
$dateQuery2 ='';
if(trim($gs_dateFrom)!='' || trim($gs_dateTo)!='') //   Activa filtro por fecha
{
	$dateQuery = "AND fp.fecha BETWEEN '$gs_dateFrom' AND '$gs_dateTo'";
	$dateQuery2 = "AND fg.fecha_timbrado BETWEEN '$gs_dateFrom' AND '$gs_dateTo'";
}
$searchQuery ='';
$searchQuery2 = '';
if(trim($gs_searchString)!='') //Activa filtro personalizado
{
	$searchQuery = "AND UCASE(CONCAT(fp.folio_factura,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') ";
	$searchQuery2 = "AND UCASE(CONCAT(fg.folio_factura,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') ";
}

//Query base de unión entre factura pendiente y clientes facturacion
$base_query = "SELECT c.idcliente,fp.fecha, fp.`folio_factura`,c.nombre,c.RFC,fp.`total` FROM factura_pendiente fp INNER JOIN clientes_facturacion c ON c.idcliente=fp.idcliente WHERE 1=1 $amoutQuery $dateQuery $searchQuery";
$doResult1 = true;

//print_r($base_query."---------QUERY1----------");

$result1 = $objSQL->executeQuery($base_query);

//Si no hay facturas que cumplan con los filtros y que cumplan con los requerimientos de filtrado y sean la union entre fp y c
if(is_null($result1))
{$doResult1=false;}

//Si hay algun filtro con respecto a timbradas o canceladas activo
if($gs_showOnly=='timbradas'|| $gs_showOnly=='canceladas'){$doResult1=false;}

$out1 = '';

if($doResult1) //Si hay facturas en la unión de fp y c que cumplan con los filtro y ningun filtro de timbrado y cancelado este activo
{
	foreach($result1 as $factura)
	{
		$RFC = $factura['RFC'];
		$nombre = $factura['nombre'];
		$total = $factura['total'];
		$fecha = $factura['fecha'];
		$folio = $factura['folio_factura'];
		//$fecha_cancelada  = $factura['fecha_cancelada'];
		$id_cliente = $factura['idcliente'];
		$out1= $out1."<tr id = 'i_tableGuardadas'>";
		$out1 = $out1."<td>$folio</td>";
		$out1 = $out1."<td>$nombre</td>";
		$out1 = $out1."<td>$RFC</td>";
		$out1 = $out1."<td>$total</td>";
		$out1 = $out1."<td>$fecha</td>";
		$out1 = $out1."<td><i class='fa fa-exclamation' style = 'font-size:20px;' aria-hidden='true'></i></td>";
		$out1 = $out1."<td>

        <button onclick='mostrar_preview(0,$folio,$id_cliente);'  type='button' data-toggle='modal' data-target='#modalPreview' class='btn btn-default c_buttonTable1'><i class='fa fa-eye' style='font-size:18px;' aria-hidden='true'></i></button>
            <button id='p,$folio,$id_cliente' type='button' class='btn btn-default c_buttonTable2'><i class='fa fa-cog' style='font-size:18px;' aria-hidden='true'></i></button>

        </td>";
		$out1 = $out1."</tr>";

	}
}

$out2 ='';
$base_query = "SELECT fg.xml_file,fg.fecha_timbrado,fg.fecha_cancelada,fg.folio_factura,c.nombre,c.RFC,fg.montoTotal FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.idcliente = fg.idcliente WHERE 1=1 $amoutQuery2 $dateQuery2 $searchQuery2;";

//print_r($base_query."---------QUERY2----------");

if($gs_showOnly=='canceladas'){
	$base_query = "SELECT fg.xml_file,fg.fecha_timbrado,fg.fecha_cancelada,fg.folio_factura,c.nombre,c.RFC,fg.montoTotal FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.idcliente = fg.idcliente WHERE 1=1 $amoutQuery2 $dateQuery2 $searchQuery2 AND fg.fecha_cancelada>'0000-00-00';";
}
if($gs_showOnly=='timbradas'){
	$base_query = "SELECT fg.xml_file,fg.fecha_timbrado,fg.fecha_cancelada,fg.folio_factura,c.nombre,c.RFC,fg.montoTotal FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.idcliente = fg.idcliente WHERE 1=1 $amoutQuery2 $dateQuery2 $searchQuery2 AND fg.fecha_cancelada='0000-00-00';";
}

$doResult2 = true;
if($gs_showOnly=='pendientes'){$doResult2=false;}

else
{
	$result2 = $objSQL->executeQuery($base_query);
	if(is_null($result2)){$doResult2=false;}
}

if($doResult2)
{
	foreach($result2 as $factura)
	{
		$RFC = $factura['RFC'];
		$nombre = $factura['nombre'];
		$total = $factura['montoTotal'];
		$fecha = $factura['fecha_timbrado'];
		$folio = $factura['folio_factura'];
		$fecha_cancelada = $factura['fecha_cancelada'];
		$xml_file = $factura['xml_file'];
		$tipo ='';
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
		$out2 = $out2."<td>$folio</td>";
		$out2 = $out2."<td>$nombre</td>";
		$out2 = $out2."<td>$RFC</td>";
		$out2 = $out2."<td>$total</td>";
		$out2 = $out2."<td>$fecha</td>";
		if($fecha_cancelada=='0000-00-00')
		{
			$out2 = $out2."<td><i class='fa fa-bell' style = 'font-size:20px;' aria-hidden='true'></i></td>";
		}
		else{
			$out2 = $out2 ."<td><i class='fa fa-times' style = 'font-size:20px;' aria-hidden='true'></i></td>";


		}
		$out2 = $out2."<td>

            <button onclick='mostrar_preview(\"$xml_file\",0,0);' type='button' data-toggle='modal' data-target='#modalPreview' class='btn btn-default c_buttonTable1'><i class='fa fa-eye' style='font-size:18px;' aria-hidden='true'></i></button>
            <button id='$tipo,$folio,$id_cliente' type='button' class='btn btn-default c_buttonTable2'><i class='fa fa-cog' style='font-size:18px;' aria-hidden='true'></i></button>

        </td>";
		$out2 = $out2."</tr>";

	}
}

echo $out1.$out2;
//echo $query1."<br>".$query2;
?>