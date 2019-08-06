<?php
include("Functions.php");

//const NAMED = PDO::FETCH_NAMED;
       // const NUM = PDO::FETCH_NUM;
       // const RET_YES = 1;
       // const RET_NO = 0;

$s_NombreCliente = MysqlLink::m_FilterInput($_POST["NombreCliente"]); //Convención de comillas dobles
$objLink = F_linkDb();



//public function m_SendCommand($Command,$FetchMode,$Will_be_a_return_val_0_Will_not_be_a_return_val_1) //Si $Will_be_a_return_val_0_Will_not_be_a_return_val_1:0 -> ret: db info , else: number of rows affected by your script
$Ret = $objLink->m_SendCommand("SELECT COUNT(Res.idcliente) as noFacturas, Res.idcliente,Res.nombre,Res.RFC,Res.telefono,Res.celular,Res.email,Res.calle,Res.noExterior,Res.noInterior,Res.municipio,Res.localidad,Res.folio_factura FROM (SELECT clientes_facturacion.idcliente,nombre,RFC,telefono,celular,email,calle,noExterior,noInterior,municipio,localidad,factura_generada.folio_factura FROM clientes_facturacion LEFT JOIN factura_generada ON clientes_facturacion.idcliente = factura_generada.idcliente) as Res WHERE UCASE(CONCAT(Res.nombre,' ',Res.RFC,' ',Res.email,' ',Res.calle)) LIKE UCASE('%$s_NombreCliente%')   GROUP BY(Res.idcliente) ORDER BY noFacturas DESC LIMIT 25;",MysqlLink::NAMED,MysqlLink::RET_YES);
$i = 0;
$Size = sizeof($Ret);

$Output = '';
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
		'<td>'.$Ret[$i]['noFacturas'].'</td>'.
		'<td><button value="'.$Ret[$i]['idcliente'].'" class="c_modificarBtn btn btn-lg c_Ajustes" id="i_modificarIdC_'.$Ret[$i]['idcliente'].'"><i class="fa fa-cog" aria-hidden="true"></i></button></td>'.
        '</tr>';
}

print_r($Output);

?>