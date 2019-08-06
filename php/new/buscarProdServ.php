<?php
include "functions.php";
$sql = createMysqliConnection();
$q = $_GET['q'];
$q= $sql->filter_input($q);
$query="SELECT c_ClaveProdServ AS id,Descripción as value FROM `f4_c_claveprodserv` WHERE MATCH(`c_ClaveProdServ`,`Descripción`) AGAINST ('*".$q."*' IN BOOLEAN MODE) LIMIT 25";

$results = $sql->executeQuery($query);
print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

?>