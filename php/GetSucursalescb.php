<?php
require_once("Functions.php");
$link = F_linkDb();
$ret = $link->m_SendCommand("SELECT colonia,idexpedicion FROM lugar_expedicion;",MysqlLink::NAMED,MysqlLink::RET_YES);
$i = 0;
$size = sizeof($ret);
$output = array();

for($i; $i < $size; $i++)
    $output[$i] = '<div class="checkbox"><label><input type="checkbox" value="'.$ret[$i]["idexpedicion"].'">'.$ret[$i]["colonia"].'</label></div>';

$output = join('',$output);

                        //
                        //
                        //
                        //<div class="checkbox">
                        //    <label><input type="checkbox" value="">Las Flores</label>
                        //</div>
                        //<div class="checkbox">
                        //    <label><input type="checkbox" value="" disabled>La Martinica</label>
                        //</div>

echo $output;
?>