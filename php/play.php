<?php

include("Functions.php");
$objSQL = F_sqlConn();
$mvc = new MVC_framework($objSQL);
$mvc->render_table("SELECT * FROM `clientes_facturacion` WHERE 1",null);



?>