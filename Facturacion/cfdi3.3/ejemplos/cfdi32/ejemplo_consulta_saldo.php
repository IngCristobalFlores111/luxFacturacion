<?php

date_default_timezone_set('America/Mexico_City');
error_reporting(0);
include_once "../../sdk2.php";

$usuario='DEMO700101XXX';
$clave='DEMO700101XXX';
$res= saldo_mf($usuario,$clave);



///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////
 
echo "<h1>Respuesta Consulta de Saldo</h1>";
foreach($res AS $variable=>$valor)
{
    $valor=htmlentities($valor);
    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}



?>