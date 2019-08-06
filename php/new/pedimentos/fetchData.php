<?php

if(isset($_GET['accion'])){
    include("../functions.php");
$sql =createMysqliConnection();
switch($_GET['accion']){
    case "initData":
 $query="SELECT f4_c_aduana.id,c_Aduana AS codigo,Descripción AS nombre FROM `f4_c_aduana`";
 $aduanas = $sql->executeQuery($query);
 $query="SELECT idPedimento AS id,num_progresiva, `año_validacion` AS ano_validacion,numero_patente AS patente, `año_num_progresiva` AS ano_num_progresiva,idAduana,f4_c_aduana.c_Aduana AS codigoAduana,f4_c_aduana.Descripción AS nombreAduana FROM `pedimentos_33` INNER JOIN f4_c_aduana ON f4_c_aduana.id = pedimentos_33.idAduana";
 $pedimentos = $sql->executeQuery($query);
 foreach($pedimentos as &$p){
     $p['aduana'] = array(
    "id"=>$p['idAduana'],
    "codigo"=>$p['codigoAduana'],
     "nombre"=>$p['nombreAduana']);

     unset($p['idAduana']);
     unset($p['codigoAduana']);
     unset($p['nombreAduana']);
    

 }
 $results = array("pedimentos"=>$pedimentos,"aduanas"=>$aduanas);
 print_r(json_encode($results,JSON_UNESCAPED_UNICODE));

    break;
    case "aduanas":
    $q=$_GET['q'];
    $q = $sql->filter_input($q);
  $query="SELECT f4_c_aduana.id,c_Aduana AS codigo,Descripción AS nombre FROM `f4_c_aduana` WHERE MATCH(`c_Aduana`,`Descripción`) AGAINST('*$q*' IN BOOLEAN MODE)";
  $results = $sql->executeQuery($query);
  print_r(json_encode($results,JSON_UNESCAPED_UNICODE));
  
    break;
    case "pedimentos":
$query="SELECT f4_c_aduana.id as idAduana,pedimentos_33.patente,pedimentos_33.año AS ano,f4_c_aduana.c_Aduana AS codigoAduana,f4_c_aduana.Descripción as nombreAduana,pedimentos_33.idPedimento AS id FROM `pedimentos_33` INNER JOIN f4_c_aduana ON f4_c_aduana.id = pedimentos_33.idAduana";
    $results = $sql->executeQuery($query);
   print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));
    
   break;
}    

}



?>