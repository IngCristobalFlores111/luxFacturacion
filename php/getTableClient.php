<?php
include("Functions.php");

$searchParams = $_POST['searchParams'];  // cadena en JSON que tiene los parametros de la busqueda

$idClient = $_POST['id'];

$gj_params = json_decode($searchParams);
$gs_orderBy = $gj_params->order;  // ordenar por algun campo especifico
$gs_ascDesc= $gj_params->ascDesc;  // ordenar de manera acendente o descendente
$gs_searchString = $gj_params->searchString;
$gs_dateFrom = $gj_params->dateFrom;
$gs_dateTo = $gj_params->dateTo;
$gs_amountFrom = $gj_params->amountFrom;
$gs_amountTo = $gj_params->amountTo;
$gs_showOnly = $gj_params->showOnly;


$query1 = "SELECT fp.fecha,fp.total,fp.`folio_factura`,fp.`idcliente`,c.nombre,c.RFC FROM `factura_pendiente` fp INNER JOIN clientes_facturacion c ON fp.`idcliente`=c.`idcliente` WHERE UCASE(CONCAT(fp.`folio_factura`,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') AND c.idcliente = $idClient ORDER BY c.nombre ASC LIMIT 50";
$query2 = "SELECT fg.montoTotal,fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE  UCASE(CONCAT(fg.`folio_factura`,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') AND c.idcliente = $idClient  ORDER BY c.nombre ASC LIMIT 50";
if($gs_showOnly=='timbradas')
$query2 = "SELECT fg.montoTotal,fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE fg.fecha_cancelada=0 AND c.idcliente = $idClient ORDER BY c.nombre ASC LIMIT 50";
if($gs_showOnly=='canceladas')
$query2 = "SELECT fg.montoTotal,fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE fg.fecha_cancelada>0 AND c.idcliente = $idClient ORDER BY c.nombre ASC LIMIT 50";



if(trim($gs_dateFrom)!=''&& trim($gs_dateTo)!='' && trim($gs_amountFrom)!='' && trim($gs_amountTo)!='')// filtrar por fecha y por monto
{
	

	if(trim($gs_searchString)!='')  // si la busqueda viene con una parametro de cadena 
	{
		
		
		$query1 = "SELECT fp.fecha,fp.total,fp.`folio_factura`,fp.`idcliente`,c.nombre,c.RFC FROM `factura_pendiente` fp INNER JOIN clientes_facturacion c ON fp.`idcliente`=c.`idcliente` WHERE UCASE(CONCAT(fp.`folio_factura`,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') AND fp.`fecha`BETWEEN '$gs_dateFrom' AND '$gs_dateTo' AND fp.`total` BETWEEN $gs_amountFrom AND $gs_amountTo AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";
          
		$gs_dateFrom = strtotime($gs_dateFrom);
		$gs_dateTo = strtotime($gs_dateTo);
		if($gs_showOnly=='timbradas')
		{
			$query2 ="SELECT fg.montoTotal,fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE UCASE(CONCAT(fg.`folio_factura`,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') AND fg.`montoTotal` BETWEEN $gs_amountFrom AND $gs_amountTo AND `TIMESTAMP` BETWEEN $gs_dateFrom and $gs_dateTo AND fg.fecha_cancelada = 0 AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";
		}
		if($gs_showOnly=='canceladas')
		{
			$query2 ="SELECT fg.montoTotal,fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE UCASE(CONCAT(fg.`folio_factura`,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') AND fg.`montoTotal` BETWEEN $gs_amountFrom AND $gs_amountTo AND `TIMESTAMP` BETWEEN $gs_dateFrom and $gs_dateTo AND fg.fecha_cancelada > 0 AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";

		} 
	}
		else
	{
		$query1 = "SELECT fp.total,fp.fecha, fp.`folio_factura`,fp.`idcliente`,c.nombre,c.RFC FROM `factura_pendiente` fp INNER JOIN clientes_facturacion c ON fp.`idcliente`=c.`idcliente` WHERE fp.`fecha`BETWEEN '$gs_dateFrom' AND '$gs_dateTo' AND fp.`total` BETWEEN $gs_amountFrom AND $gs_amountTo AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";

		$gs_dateFrom = strtotime($gs_dateFrom);
		$gs_dateTo = strtotime($gs_dateTo);
		if($gs_showOnly=='timbradas')
		$query2 ="SELECT fg.montoTotal,fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE fg.`montoTotal` BETWEEN $gs_amountFrom AND $gs_amountTo AND `TIMESTAMP` BETWEEN $gs_dateFrom and $gs_dateTo AND fg.fecha_cancelada =0 AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";
	     if($gs_showOnly =='canceladas')
		$query2 ="SELECT fg.montoTotal,fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE fg.`montoTotal` BETWEEN $gs_amountFrom AND $gs_amountTo AND `TIMESTAMP` BETWEEN $gs_dateFrom and $gs_dateTo AND fg.fecha_cancelada >0 AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";

	}
	
}
if(trim($gs_dateFrom)==''&& trim($gs_dateTo)=='' && trim($gs_amountFrom)!='' && trim($gs_amountTo)!='')// filtrar por monto
{

	if(trim($gs_searchString)!='')  // si la busqueda viene con una parametro de cadena 
	{
		$query1 = "SELECT fp.fecha,fp.total,fp.`folio_factura`,fp.`idcliente`,c.nombre,c.RFC FROM `factura_pendiente` fp INNER JOIN clientes_facturacion c ON fp.`idcliente`=c.`idcliente` WHERE UCASE(CONCAT(fp.`folio_factura`,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') AND fp.`total` BETWEEN $gs_amountFrom AND $gs_amountTo AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";
		
		$gs_dateFrom = strtotime($gs_dateFrom);
		$gs_dateTo = strtotime($gs_dateTo);
		
		if($gs_showOnly=='timbradas')
		$query2 ="SELECT fg.montoTotal,fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE UCASE(CONCAT(fg.`folio_factura`,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') AND fg.`montoTotal` BETWEEN $gs_amountFrom AND $gs_amountTo  AND fg.`fecha_cancelada`=0 AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";	
	     if($gs_showOnly=='canceladas')
		$query2 ="SELECT fg.montoTotal,fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE UCASE(CONCAT(fg.`folio_factura`,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') AND fg.`montoTotal` BETWEEN $gs_amountFrom AND $gs_amountTo  AND fg.`fecha_cancelada`>0 AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";	

	}else
	{
		$query1 = "SELECT fp.fecha,fp.`folio_factura`,fp.`idcliente`,c.nombre,c.RFC FROM `factura_pendiente` fp INNER JOIN clientes_facturacion c ON fp.`idcliente`=c.`idcliente` WHERE fp.`total` BETWEEN $gs_amountFrom AND $gs_amountTo AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";
		$gs_dateFrom = strtotime($gs_dateFrom);
		$gs_dateTo = strtotime($gs_dateTo);
		if($gs_showOnly=='timbradas')
		$query2 ="SELECT fg.montoTotal,fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE fg.`montoTotal` BETWEEN $gs_amountFrom AND $gs_amountTo AND fg.`fecha_cancelada`>0 AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";
	     if($gs_showOnly=='canceladas')
		$query2 ="SELECT fg.montoTotal,fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE fg.`montoTotal` BETWEEN $gs_amountFrom AND $gs_amountTo AND fg.`fecha_cancelada`=0 AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";

	}
	
}



if(trim($gs_dateFrom)!=''&& trim($gs_dateTo)!='' && trim($gs_amountFrom)=='' && trim($gs_amountTo)=='')// filtrar por solo por fecha
{

	if(trim($gs_searchString)!='')  // si la busqueda viene con una parametro de cadena 
	{
		$query1 = "SELECT fp.fecha,fp.total,fp.`folio_factura`,fp.`idcliente`,c.nombre,c.RFC FROM `factura_pendiente` fp INNER JOIN clientes_facturacion c ON fp.`idcliente`=c.`idcliente` WHERE UCASE(CONCAT(fp.`folio_factura`,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') AND fp.`fecha`BETWEEN '$gs_dateFrom' AND '$gs_dateTo' AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";
		$gs_dateFrom = strtotime($gs_dateFrom);
		$gs_dateTo = strtotime($gs_dateTo);
		if($gs_showOnly=='timbradas')
		$query2 ="SELECT fg.montoTotal,fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE UCASE(CONCAT(fg.`folio_factura`,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') AND `TIMESTAMP` BETWEEN $gs_dateFrom and $gs_dateTo AND fg.fecha_cancelada>0 AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";	
		if($gs_showOnly=='canceladas')
		$query2 ="SELECT fg.montoTotal,fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE UCASE(CONCAT(fg.`folio_factura`,' ',c.nombre,' ',c.RFC)) LIKE UCASE('%$gs_searchString%') AND `TIMESTAMP` BETWEEN $gs_dateFrom and $gs_dateTo AND fg.fecha_cancelada=0 AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";	

	}else
	{
		$query1 = "SELECT fp.fecha,fp.total,fp.`folio_factura`,fp.`idcliente`,c.nombre,c.RFC FROM `factura_pendiente` fp INNER JOIN clientes_facturacion c ON fp.`idcliente`=c.`idcliente` WHERE fp.`fecha`BETWEEN '$gs_dateFrom' AND '$gs_dateTo' AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";

		$gs_dateFrom = strtotime($gs_dateFrom);
		$gs_dateTo = strtotime($gs_dateTo);
		if($gs_showOnly =='timbradas')
		$query2 ="SELECT fg.montoTotal, fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE `TIMESTAMP` BETWEEN $gs_dateFrom and $gs_dateTo AND fg.fecha_cancelada >0 AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";
        if($gs_showOnly =='canceladas')
		$query2 ="SELECT fg.montoTotal, fg.`folio_factura`,c.nombre,c.RFC,fg.`xml_file`,fg.`TIMESTAMP`,fg.`fecha_cancelada`,fg.`montoTotal` FROM `factura_generada` fg INNER JOIN clientes_facturacion c ON c.`idcliente`=fg.`idcliente` WHERE `TIMESTAMP` BETWEEN $gs_dateFrom and $gs_dateTo AND fg.fecha_cancelada =0 AND c.idcliente = $idClient ORDER BY $gs_orderBy $gs_ascDesc LIMIT 50";

		
	}
	
}
$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");


if($gs_showOnly=='timbradas'||$gs_showOnly=='canceladas'){ $query1 ='';}
if($gs_showOnly=='pendientes'){$query2='';}	
$result1=$objSQL->executeQuery($query1);
$result2 = $objSQL->executeQuery($query2);

$out1 = '';

$doResult1 =false;
$doResult2 = false;

if(!is_null($result1)){$doResult1=true;}
if(!is_null($result2)){$doResult2 = true;}

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
		
		$out1= $out1."<tr id = 'i_tableGuardadas'>";
		$out1 = $out1."<td>$folio</td>";
		$out1 = $out1."<td>$nombre</td>";
		$out1 = $out1."<td>$RFC</td>";
		$out1 = $out1."<td>$total</td>";
		$out1 = $out1."<td>$fecha</td>";
		$out1 = $out1."<td><i class='fa fa-exclamation' aria-hidden='true'></i></td>";
		$out1 = $out1."<td>
          <div class='btn-group' role='group' aria-label='...'>
            <button id='$folio' type='button' data-toggle='modal' data-target='#modalPreview' class='btn btn-default c_buttonTablePreview'><i class='fa fa-eye' aria-hidden='true'></i></button>
            <button id='$folio' type='button' class='btn btn-default c_buttonTableConfig'><i class='fa fa-cog' aria-hidden='true'></i></button>
          </div>
        </td>";
		$out1 = $out1."</tr>";

	}
}
$out2 ='';

if($doResult2)
{
	foreach($result2 as $factura)
	{
		$RFC = $factura['RFC'];
		$nombre = $factura['nombre'];
		$total = $factura['montoTotal'];
		$fecha = $factura['TIMESTAMP'];
		$fecha = date("Y-m-d",$fecha);  
		$folio = $factura['folio_factura'];
		$fecha_cancelada = $factura['fecha_cancelada'];
		
		if($fecha_cancelada=='0')
		$out2= $out2."<tr id = 'i_tableTimbradas'>";
		else
			$out2= $out2."<tr id = 'i_tableCanceladas'>";

		$out2 = $out2."<td>$folio</td>";
		$out2 = $out2."<td>$nombre</td>";
		$out2 = $out2."<td>$RFC</td>";
		$out2 = $out2."<td>$total</td>";
		$out2 = $out2."<td>$fecha</td>";
		if($fecha_cancelada=='0')
		{
			$out2 = $out2."<td><i class='fa fa-bell' aria-hidden='true'></i></td>";
		}
		else{
			$out2 = $out2 ."<td><i class='fa fa-times' aria-hidden='true'></i></td>";

			
		}
		$out2 = $out2."<td>
          <div class='btn-group' role='group' aria-label='...'>
            <button id='$folio' type='button' data-toggle='modal' data-target='#modalPreview' class='btn btn-default c_buttonTablePreview'><i class='fa fa-eye' aria-hidden='true'></i></button>
            <button id='$folio' type='button' class='btn btn-default c_buttonTableConfig'><i class='fa fa-cog' aria-hidden='true'></i></button>
          </div>
        </td>";
		$out2 = $out2."</tr>";
		
	}	
}	

echo $out1.$out2;
//echo $query1."<br>".$query2;




?>