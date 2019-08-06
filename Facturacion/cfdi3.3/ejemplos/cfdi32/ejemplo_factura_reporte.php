<?php

error_reporting(0); // OPCIONAL DESACTIVA NOTIFICACIONES DE DEBUG
date_default_timezone_set('America/Mexico_City');

include_once "../../sdk2.php";

$usuario='DEMO700101XXX';
$clave='DEMO700101XXX';
$mes=01;
$ano=2015;

$res= factura_reporte($usuario,$clave,$mes,$ano);
print_r($res);

///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////
 
echo "<h1>Respuesta Reporte de Facturas Emitidas</h1>";
foreach($res AS $variable=>$valor)
{
    $valor=htmlentities($valor, ENT_IGNORE);
    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}



?>