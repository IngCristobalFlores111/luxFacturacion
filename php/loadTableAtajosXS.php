<?php
include("Functions.php");

$objSQL = F_sqlConn();

$query ="SELECT * FROM atajos LIMIT 25";
$res = $objSQL->executeQuery($query);
$output = '';
if(is_null($res)){
    $output ="<tr><td colspan='6'>No se han encontrado resultados</td></tr>";
    echo $output;
    exit();
}

foreach($res as $atajo){
    $nombre = $atajo['atajo'];
    $descripcion = $atajo['descripcion'];
    $noSerie = $atajo['noSerie'];
    $medida =$atajo['medida'];
    $precio = $atajo['precio'];
    $idAtajo =$atajo['idatajo'];

    $output =$output."<div class='row'><div class='col-xs-12'><table class='table table-bordered table-condensed'><tbody id='i_table_$idAtajo'>";
    $output=$output."<tr><td><strong>Nombre</strong></td>";
    $output=$output."<td>$nombre</td>";
    $output=$output."</tr>";

    $output=$output."<tr><td><strong>Descripci&oacuten</strong></td>";
    $output=$output."<td>$descripcion</td>";
    $output=$output."</tr>";

    $output=$output."<tr><td><strong>No.Serie</strong></td>";
    $output=$output."<td>$noSerie</td>";
    $output=$output."</tr>";

    $output=$output."<tr><td><strong>Medida</strong></td>";
    $output=$output."<td>$medida</td>";
    $output=$output."</tr>";

    $output=$output."<tr><td><strong>Precio</strong></td>";
    $output=$output."<td>$precio</td>";
    $output=$output."</tr>";

    $output =$output."<tr><td><strong>Opciones</strong></td><td>";
    $output =$output."<div class='btn-group-horizontal'>";
    $output=$output.'<button type="button" class="btn btn-default c_Ajustes"
    onclick="atajoModificarXS('.$idAtajo.')">
    <i class="fa fa-cog" style="font-size:20px;"
    aria-hidden="true"></i></button><button type="button"
    style="margin-left: 7%;" class="btn btn-default c_Cancel"
    onclick="atajoEliminarXS('.$idAtajo.')"><i class="fa fa-trash" style="font-size:20px;"
    aria-hidden="true"></i></button>';
    $output =$output."</div></td></tr>";



    $output =$output."</tbody></table></div></div>";

}
echo $output;




?>