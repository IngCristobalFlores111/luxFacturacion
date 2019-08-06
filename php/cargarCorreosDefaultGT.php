<?php
    include("Functions.php");

    $link = F_LinkDb();
    if(!filter_input(INPUT_POST,"ID",FILTER_VALIDATE_INT))
    {
        print_r("Invalid Input");
        return;
    }

    $Ret = $link->m_SendCommand("SELECT * FROM correos_default;",MysqlLink::NAMED,MysqlLink::RET_YES);
    $Ret2 = $link->m_SendCommand("SELECT email FROM clientes_facturacion WHERE idcliente = ". $_POST["ID"] .";",MysqlLink::NAMED,MysqlLink::RET_YES);
    $i = sizeof($Ret) - 1;

    echo '<tr>'.
                '<td class="c_mail">'.$Ret2[0]["email"].'</td>'.
                '<td> <button class="btn btn-default c_Accept" onclick="agregar_mensaje(this)"><i class="fa fa-envelope" style = "font-size:23px;" aria-hidden="true"></i></button>  <button class="btn btn-default c_Cancel" style = "margin-left:5%;" onclick="eliminar_correo(this)"><i class="fa fa-trash" style = "font-size:23px;" aria-hidden="true"></i></button> </td>'.
                '<td class="c_body" hidden></td><td class="c_subject" hidden></td>'.
          '</tr>';

    for($i; $i > -1; $i--)
    {
        echo '<tr>'.
        '<td class="c_mail">'.$Ret[$i]["email"].'</td>'.
        '<td> <button class="btn btn-default c_Accept" onclick="agregar_mensaje(this)"><i class="fa fa-envelope" style = "font-size:23px;" aria-hidden="true"></i></button>  <button class="btn btn-default c_Cancel" style = "margin-left:5%;" onclick="eliminar_correo(this)"><i class="fa fa-trash" style = "font-size:23px;" aria-hidden="true"></i></button> </td>'.
        '<td class="c_body" hidden></td><td class="c_subject" hidden></td>'.
        '</tr>';
    }


?>