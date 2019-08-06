<?php
    include("Functions.php");

    $objLink = F_linkDb();
    $Ret = $objLink->m_SendCommand("SELECT COUNT(Res.idcliente) as noFacturas, Res.idcliente,Res.nombre,Res.RFC,Res.telefono,Res.celular,Res.email,Res.calle,Res.noExterior,Res.noInterior,Res.municipio,Res.localidad,Res.folio_factura FROM (SELECT clientes_facturacion.idcliente,nombre,RFC,telefono,celular,email,calle,noExterior,noInterior,municipio,localidad,factura_generada.folio_factura FROM clientes_facturacion LEFT JOIN factura_generada ON clientes_facturacion.idcliente = factura_generada.idcliente) as Res GROUP BY(Res.idcliente) ORDER BY noFacturas DESC LIMIT 25;",MysqlLink::NAMED,MysqlLink::RET_YES);
    $Output = '';

    $i = 0;
    $Size = sizeof($Ret);
    if($Size==0){
        echo "<tr><td colspan='8'  ><h4>No se han encontrado resultados</h4></td></tr>";
        exit();
    }

    for($i = 0; $i < $Size; $i++)
    {
	$Output = $Output.
		'<tr>'.
		'<td><button value="'.$Ret[$i]['idcliente'].'" class="c_facturarBtn btn btn-default btn-block c_Accept" id="i_facturarIdC_'.$Ret[$i]['idcliente'].'">Facturar</button></td>'.
		'<td>'.$Ret[$i]['nombre'].'</td>'.
		'<td>'.$Ret[$i]['RFC'].'</td>'.
		'<td>'.$Ret[$i]['telefono'].'</td>'.
		'<td>'.$Ret[$i]['celular'].'</td>'.
		'<td>'.$Ret[$i]['email'].'</td>'.
		'<td>'.($Ret[$i]['noFacturas'] - 1).'</td>'.
		'<td><button value="'.$Ret[$i]['idcliente'].'" class="c_modificarBtn btn btn-lg c_Ajustes" id="i_modificarIdC_'.$Ret[$i]['idcliente'].'"><i class="fa fa-cog" aria-hidden="true"></i></button></td>'.
        '</tr>';
    }
   

    print_r($Output);
?>