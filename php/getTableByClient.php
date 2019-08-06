<?php
include("Functions.php");
$objSQL = F_sqlConn();

$searchParams = $_POST['searchParams'];  // cadena en JSON que tiene los parametros de la busqueda
$gj_params = json_decode($searchParams);
$gj_params = $objSQL->filter_json($gj_params);
//$gs_orderBy = $gj_params->order;  // ordenar por algun campo especifico
//$gs_ascDesc= $gj_params->ascDesc;  // ordenar de manera acendente o descendente
$gs_searchString = $gj_params->searchString;
$gs_dateFrom = $gj_params->dateFrom;
$gs_dateTo = $gj_params->dateTo;
$gs_amountFrom = $gj_params->amountFrom;
$gs_amountTo = $gj_params->amountTo;
$gs_showOnly = $gj_params->showOnly;
$gs_idCliente =$gj_params->idCliente;



$amoutQuery = '';
$amoutQuery2 = '';
if((float)$gs_amountFrom>0 ||(float) $gs_amountTo>0)
{
	$amoutQuery = "AND fp.total BETWEEN '$gs_amountFrom' AND '$gs_amountTo'";
	$amoutQuery2 = "AND fg.montoTotal BETWEEN '$gs_amountFrom' AND '$gs_amountTo'";
}
$dateQuery = '';
$dateQuery2 ='';
if( trim($gs_dateFrom)!='' || trim($gs_dateTo)!='')
{

	$dateQuery = "AND fp.fecha BETWEEN '$gs_dateFrom' AND '$gs_dateTo'";
	$dateQuery2 = "AND fg.fecha_timbrado BETWEEN '$gs_dateFrom' AND '$gs_dateTo'";
}
$searchQuery ='';
$searchQuery2 = '';
if(trim($gs_searchString)!='')
{
	$searchQuery = "AND UCASE(CONCAT(fp.folio_factura,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') ";
	$searchQuery2 = "AND UCASE(CONCAT(fg.folio_factura,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') ";
}

$base_query = "SELECT c.idcliente,fp.fecha, fp.`folio_factura`,c.nombre,c.RFC,fp.`total` FROM factura_pendiente fp INNER JOIN clientes_facturacion c ON c.idcliente=fp.idcliente WHERE 1=1 $amoutQuery $dateQuery $searchQuery AND c.idcliente='$gs_idCliente'";

$doResult1 = true;

$result1 = $objSQL->executeQuery($base_query);
if(is_null($result1)){$doResult1=false;}

if($gs_showOnly=='timbradas'|| $gs_showOnly=='canceladas'){$doResult1=false;}

$out1 = '';


if($doResult1)
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

            <button onclick='mostrar_preview(0,$folio,$id_cliente);'  type='button' data-toggle='modal' data-target='#modalPreview' class='btn btn-default c_buttonTable1'><i class='fa fa-eye' style = 'font-size:18px;' aria-hidden='true'></i></button>
            <button onclick='goPendientes($folio,$id_cliente)' id='p_$folio' type='button' class='btn btn-default c_buttonTable2'><i class='fa fa-cog' style = 'font-size:18px;' aria-hidden='true'></i></button>

        </td>";
		$out1 = $out1."</tr>";

	}
}
$out2 ='';
$base_query ="SELECT fg.xml_file,fg.fecha_timbrado,fg.fecha_cancelada,fg.folio_factura,c.nombre,c.RFC,fg.montoTotal FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.idcliente = fg.idcliente WHERE 1=1 $amoutQuery2 $dateQuery2 $searchQuery2 AND c.idcliente='$gs_idCliente';";

if($gs_showOnly=='canceladas'){
	$base_query ="SELECT fg.xml_file,fg.fecha_timbrado,fg.fecha_cancelada,fg.folio_factura,c.nombre,c.RFC,fg.montoTotal FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.idcliente = fg.idcliente WHERE 1=1 $amoutQuery2 $dateQuery2 $searchQuery2 AND fg.fecha_cancelada>'0000-00-00' AND c.idcliente='$gs_idCliente';";

}
if($gs_showOnly=='timbradas')
{
	$base_query ="SELECT fg.xml_file,fg.fecha_timbrado,fg.fecha_cancelada,fg.folio_factura,c.nombre,c.RFC,fg.montoTotal FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.idcliente = fg.idcliente WHERE 1=1 $amoutQuery2 $dateQuery2 $searchQuery2 AND fg.fecha_cancelada='0000-00-00' AND c.idcliente='$gs_idCliente';";

}

$doResult2 = true;
if($gs_showOnly=='guardadas'){$doResult2=false;}
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
            <button id='$tipo"."_$folio' type='button' class='btn btn-default c_buttonTable2'><i class='fa fa-cog' style='font-size:18px;' aria-hidden='true'></i></button>
        </td>";
		$out2 = $out2."</tr>";

	}
}

if(trim($out1)==""&& trim($out2)=="")
{
	echo "<tr><td><h4>No se han encontrado resultados</h4></td></tr>";
}else
{

	echo $out1.$out2;
}



?>