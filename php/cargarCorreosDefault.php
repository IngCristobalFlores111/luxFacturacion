<?php
    include("Functions.php");

    $link = F_LinkDb();
    if(!filter_input(INPUT_POST,"ID",FILTER_VALIDATE_INT))
    {
        print_r("Invalid Input");
        return;
    }

    $Ret = $link->m_SendCommand("SELECT * FROM correo_default;",MysqlLink::NAMED,MysqlLink::RET_YES);
    $Ret2 = $link->m_SendCommand("SELECT email FROM clientes_facturacion WHERE idcliente = ". $_POST["ID"] .";",MysqlLink::NAMED,MysqlLink::RET_YES);
    $i = sizeof($Ret) - 1;

    echo '<tr>'.
                '<td id="i_inputCorreo_'.($i + 1).'"><div>'.$Ret2[0]["email"].'</div></td>'.
                '<td> <button class="btn btn-default c_Accept" id="i_btnAgregarMensajeCorreo_'.($i + 1).'" onclick="agregar_mensaje(this)"><i class="fa fa-envelope" style = "font-size:23px;" aria-hidden="true"></i></button>  <button class="btn btn-default c_Cancel" style = "margin-left:5%;" id="i_btnEliminarCorreo_'.($i + 1).'" onclick="eliminar_correo(this)"><i class="fa fa-trash" style = "font-size:23px;" aria-hidden="true"></i></button> </td>'.
          '</tr>';

    for($i; $i > -1; $i--)
    {
        echo '<tr>'.
                '<td id="i_inputCorreo_'.$i.'"><p hidden id="i_correoId_'.$Ret[$i]["id_email"].'">'.$Ret[$i]["id_email"].'</p><div>'.$Ret[$i]["email"].'</div></td>'.
                '<td> <button class="btn btn-default c_Accept" id="i_btnAgregarMensajeCorreo_'.($i).'" onclick="agregar_mensaje(this)"><i class="fa fa-envelope" style = "font-size: 23px;" aria-hidden="true"></i></button>  <button class="btn btn-default c_Cancel" style = "margin-left:5%;" id="i_btnEliminarCorreo_'.($i).'" onclick="eliminar_correo(this)"><i class="fa fa-trash"  style = "font-size: 23px;" aria-hidden="true"></i></button> </td>'.
             '</tr>';
    }
       
    
?>