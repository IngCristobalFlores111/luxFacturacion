<?php



if (session_status() == PHP_SESSION_NONE) 
            session_start();

if(isset($_GET['accion'])){
    include("../functions.php");
      $sql = createMysqliConnection();
switch($_GET['accion']){
case "obtenerCliente":
$id = $_GET['id'];
$result = $sql->get_bind_results("SELECT * FROM `clientes_facturacion` WHERE `idcliente` = ?",
array("i",$id));
if(count($result)==0){
    echo 0;
}else{
    print_r(json_encode($result[0],JSON_UNESCAPED_UNICODE));
}
break;
case "params":
$query="SELECT * FROM `metodos_pago`";
$metodos = $sql->executeQuery($query);
$query="SELECT * FROM `formas_pago`";
$formas = $sql->executeQuery($query);
$query ="SELECT pedimentos.`idPedimento` AS id,pedimentos.numero,pedimentos.fecha,aduanas.nombre AS aduana FROM `pedimentos` INNER JOIN aduanas ON aduanas.idAduana = pedimentos.idAduana ORDER BY fecha ASC";
$pedimentos = $sql->executeQuery($query);
$query ="SELECT * FROM unidades";
$unidades = $sql->executeQuery($query);

$respuesta = array("unidades"=>$unidades,"pedimentos"=>$pedimentos,"metodos"=>$metodos,"formas"=>$formas);

print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

break;
case "params33":
$query="SELECT c_MetodoPago AS idMetodoPago,`Descripción` AS nombre FROM f4_c_metodopago";
$metodos = $sql->executeQuery($query);
$query="SELECT c_FormaPago AS id,`Descripción` AS nombre FROM `f4_c_formapago`";
$formas = $sql->executeQuery($query);
$query="SELECT idPedimento,f4_c_aduana.Descripción AS aduana, CONCAT(`año_validacion`,'  ',f4_c_aduana.c_Aduana,'  ',`numero_patente`,'  ',`año_num_progresiva`,`num_progresiva`) AS numero_pedimento FROM `pedimentos_33` INNER JOIN f4_c_aduana ON f4_c_aduana.id = idAduana";
$pedimentos = $sql->executeQuery($query);

$respuesta = array("pedimentos"=>$pedimentos,"metodos"=>$metodos,"formas"=>$formas);

print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

break;
case "config":

$idUsr=  $_SESSION['idUsuario'];
$query="SELECT * FROM `user_config`INNER JOIN regimenes_tipo_factura
ON regimenes_tipo_factura.idTipoFactura = user_config.tipo_factura 
WHERE user_config.idusuario = ?";
$config = $sql->get_bind_results($query,array("i",$idUsr));
if(count($config)==0){
    echo 0;
}else{
print_r(json_encode($config[0],JSON_UNESCAPED_UNICODE));
}
break;
case "getPedimentos":
$query ="SELECT pedimentos.`idPedimento` AS id,pedimentos.numero,pedimentos.fecha,aduanas.nombre AS aduana FROM `pedimentos` INNER JOIN aduanas ON aduanas.idAduana = pedimentos.idAduana ORDER BY fecha ASC";
$result = $sql->executeQuery($query);
print_r(json_encode($result,JSON_UNESCAPED_UNICODE));

break;
case "buscarAtajo":
$q = $_GET['q'];
$q = $sql->filter_input($q);
$query="SELECT * FROM `atajos` WHERE MATCH(atajo,descripcion) AGAINST('*".$q."*' IN BOOLEAN MODE)";
$result = $sql->executeQuery($query);
print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));
break;
case "getSessionConceptos":


if(isset($_SESSION['conceptos'])){
    print_r(json_encode($_SESSION['conceptos'],JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));
}else{
    echo 0;
}

break;
case "generarVistaPrevia":
include("../../../wkWorks/generatePdf.php");
$idCliente = $_GET['idCliente'];
generatePdfPreview($idCliente,"https://www.luxline.com.mx/sanAngelprod/LuxFacturacion/wkWorks/26_2017_05_23_129.xml","sanangel.facturas.tabachines@gmail.com","36723465","https://www.luxline.com.mx/sanAngelprod/LuxFacturacion/wkWorks/34_2017_09_04_409.png","previe_newbie.pdf");


break;
case "generarVistaPrevia33":
include("../../../wkWorks/generatePdf.php");
$idCliente = $_GET['idCliente'];
generatePdfPreview33($idCliente,"https://www.luxline.com.mx/sanAngelprod/LuxFacturacion/wkWorks/26_2017_05_23_129.xml","goldman.badger@gmail.com","33 2257 2892","https://www.luxline.com.mx/sanAngelprod/LuxFacturacion/wkWorks/34_2017_09_04_409.png","previe_newbie.pdf");


break;
case "getCorreosDefault":
$query="SELECT email FROM `correos_default`";
$result = $sql->executeQuery($query);
print_r(json_encode($result,JSON_UNESCAPED_UNICODE));


break;
case "lugaresExpedicionUsr":

$idUsr=  $_SESSION['idUsuario'];
$query="SELECT * FROM `usuarios_expedicion` INNER JOIN lugar_expedicion on lugar_expedicion.idexpedicion = usuarios_expedicion.idexpedicion
WHERE usuarios_expedicion.idusuario = ?";
$lugares = $sql->get_bind_results($query,array("i",$idUsr));
print_r(json_encode($lugares,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));


break;
case "monedas":
$query="SELECT * FROM `monedas`";
$result = $sql->executeQuery($query);
print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));
break;
case "getFactura":
$folio =$_GET['folio'];
$query="SELECT * FROM factura_generada WHERE folio_factura = ?";
$factura = $sql->get_bind_results($query,array("i",$folio));
if(count($factura)==0){
    echo 0;
}else{
print_r(json_encode($factura[0],JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));
}

break;
case "buscarClaveProd":
$q = $_GET['q'];
$q = $sql->filter_input($q);
$query="SELECT f4_c_claveprodserv.id,f4_c_claveprodserv.`c_ClaveProdServ` AS codigo,f4_c_claveprodserv.`Descripción` AS descripcion FROM f4_c_claveprodserv 
WHERE MATCH(f4_c_claveprodserv.`c_ClaveProdServ`,f4_c_claveprodserv.`Descripción`) AGAINST('*".$q."*' IN BOOLEAN MODE) AND id NOT IN (SELECT idClaveProd FROM clavesProdActuales)
LIMIT 30";

$result = $sql->executeQuery($query);
print_r(json_encode($result,JSON_UNESCAPED_UNICODE));

break;
case "clavesProdActuales":
$query="SELECT f4_c_claveprodserv.id,f4_c_claveprodserv.c_ClaveProdServ AS codigo,f4_c_claveprodserv.Descripción AS descripcion FROM `clavesProdActuales` INNER JOIN f4_c_claveprodserv ON f4_c_claveprodserv.id = clavesProdActuales.idClaveProd";

$result = $sql->executeQuery($query);

print_r(json_encode($result,JSON_UNESCAPED_UNICODE));


break;
case "unidadesActuales":
$query="SELECT f4_c_claveunidad.Descripción AS descripcion,f4_c_claveunidad.c_ClaveUnidad AS codigo,idUnidad AS id,f4_c_claveunidad.Nombre AS nombre FROM `unidades_actuales` INNER JOIN f4_c_claveunidad ON f4_c_claveunidad.id = unidades_actuales.idUnidad";
$result = $sql->executeQuery($query);

print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));

break;
case "buscarUnidades":
$q = $_GET['q'];
$q = $sql->filter_input($q);
$query="SELECT id,Nombre AS nombre,Descripción AS descripcion,`c_ClaveUnidad` AS codigo FROM `f4_c_claveunidad` WHERE MATCH(Nombre,Descripción) AGAINST('*".$q."*' IN BOOLEAN MODE) AND id NOT IN(SELECT idUnidad FROM unidades_actuales)";
$result = $sql->executeQuery($query);

print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));

break;
case "usosCFDI":
$query="SELECT c_UsoCFDI AS codigo,`Descripción` AS nombre FROM `f4_c_usocfdi`";
$result = $sql->executeQuery($query);

print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));


break;
case "series":
$query="SELECT serie,folio_actual +1 AS folio FROM `factura_series`";
$result = $sql->executeQuery($query);

print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));
break;
case "seriesOriginal":
$query="SELECT serie,folio_actual AS folio,idSerie AS id FROM `factura_series`";
$result = $sql->executeQuery($query);

print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));
break;
case "buscarClaveProdActuales":
$q = $_GET['q'];
$q = $sql->filter_input($q);
$query="SELECT idClaveProd 
AS id,c_ClaveProdServ AS codigo,Descripción AS descripcion
 FROM clavesProdActuales WHERE
  MATCH(c_ClaveProdServ,Descripción) 
  AGAINST('*".$q."*' IN BOOLEAN MODE)";
$result = $sql->executeQuery($query);

print_r(json_encode($result,JSON_UNESCAPED_UNICODE));

break;



}

}




?>