<?php
include "functions.php";

$sql = createMysqliConnection();
$id = $_POST['id'];
$descripcion =$_POST['descripcion'];
$medida =$_POST['medida'];
$precio =$_POST['precio'];
$atajo =$_POST['atajo'];
$noSerie =$_POST['noSerie'];
$claveProdServ =$_POST['claveProdServ'];
$nombreProdServ =$_POST['nombreProdServ'];

$query ="UPDATE `atajos` SET `descripcion`=?,`medida`=?,`precio`=?,`atajo`=?,`noSerie`=?,`claveProdServ`=?,`nombreProdServ`=?
WHERE `idatajo` = ?";
$sql->execQueryBinders($query,array("ssdssssi",$descripcion,$medida,
$precio,$atajo,$noSerie,$claveProdServ,$nombreProdServ,$id));
$errores = $sql->getErrorLog();
if(count($errores)){
print_r(json_encode(array("exito"=>false,"errores"=>$errores)));

}else{

    print_r(json_encode(array("exito"=>true)));

}


?>