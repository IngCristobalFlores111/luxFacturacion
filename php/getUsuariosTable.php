<?php
include("Functions.php");
$objSQL = F_sqlConn();

$query ="SELECT u.`privilegio`,u.`nombre`,u.`email` FROM `usuarios` u ORDER BY u.`nombre` DESC LIMIT 25";
$out = '';
$result = $objSQL->executeQuery($query);
if(is_null($result))
{
    $out = '<tr>
        <td>No hay usuarios en el sistema</td>
    </tr>';
}else{

    foreach($result as $u)
    {
        $nombre = $u['nombre'];
        $email = $u['email'];
        $privilegio =$u['privilegio'];
        $out=$out."<tr>";
        $out=$out."<td>$nombre</td>";
        $out =$out."<td>$email</td>";
        $out =$out."<td>$privilegio</td>";
        $out = $out."<td>";
        $out = $out.'<button value="'.$email.'" class="btn btn-default c_Cancel c_eliminarUsuario" data-toggle="modal" ><i class="fa fa-trash" style="font-size:20px;" aria-hidden="true"></i></button><button value="'.$email.'" class="btn btn-default c_actualizarUsuario c_actualizarUsuarioTrans1"><i class="fa fa-cog" style="font-size:20px;" aria-hidden="true"></i></button>';
        $out = $out.'<button style="display:none;" value="'.$email.'" class="btn btn-default btn-success c_actualizarUsuarioTrans2"  ><i class="fa fa-check" style="font-size:20px;" aria-hidden="true"></i></button>';
        $out = $out."</tr>";
    }
}



echo $out;



?>