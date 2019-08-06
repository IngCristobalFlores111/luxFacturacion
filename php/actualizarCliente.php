<?php
include("new/functions.php");
$objSQL = createMysqliConnection();
$lj_datos = json_decode ($_POST['json_datos']);
$lj_datos =  $objSQL->filter_json($lj_datos);
$ls_idCliente = $_POST['idCliente'];
$ls_updateQuery = '';
foreach($lj_datos as $key=>$value)
{
	$ls_updateQuery = $ls_updateQuery.$key."='".$value."',";
}
$ls_updateQuery = rtrim($ls_updateQuery, ",");
$query ="UPDATE `clientes_facturacion` SET $ls_updateQuery WHERE idcliente='$ls_idCliente'";
$objSQL->ejecutarNoQuery($query);
$errors = $objSQL->getErrorLog();
$respuesta = array();
if(count($errors)>0){
$respuesta['exito']= false;
$respuesta['errors'] =$errors; 
$respuesta['msg'] ="ha ocurrido un error inesperado,contacte a soporte";

}else{
	$respuesta['exito']= true;
	$respuesta['msg'] ="se actualizaron los datos de manera correcta";
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


?>