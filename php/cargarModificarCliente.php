<?php
        require_once("Functions.php");
        print_r($_POST["MAIL"]);

        //if(!filter_input(INPUT_POST,"Email",FILTER_VALIDATE_EMAIL))
        //    echo "El correo eléctronico no tiene el formato esperado";

        //$email = MysqlLink::m_filterInput($_POST["Email"]);



        //$link = F_linkDb();
        //$ret = $link->m_SendCommand("SELECT `idusuario` FROM usuarios WHERE `email` ='$email';",MysqlLink::NAMED,MysqlLink::RET_YES);
        //print_r($ret);
?>