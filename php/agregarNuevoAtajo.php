<?php
    include("Functions.php");
    //NA : $("#i_atajoNombre").val(), ND : $("#i_atajoDescripcion").val(), NM : $("#i_atajoMedida").val(), AP : $("#i_atajoPrecio").val() 
    $linkObj = F_linkDb();

    if(!filter_input(INPUT_POST,"NA",FILTER_SANITIZE_FULL_SPECIAL_CHARS) && !filter_input(INPUT_POST,"ND",FILTER_SANITIZE_FULL_SPECIAL_CHARS) && !filter_input(INPUT_POST,"NM",FILTER_SANITIZE_FULL_SPECIAL_CHARS) && !filter_input(INPUT_POST,"NP",FILTER_VALIDATE_FLOAT))
    {
        print_r("Non valid input");
        return;
    }
    $noSeire = $_POST['noSerie'];
    $ND = MysqlLink::m_filterInput($_POST["ND"]);
    $NA = MysqlLink::m_filterInput($_POST["NA"]);
    $NM = MysqlLink::m_filterInput($_POST["NM"]);
    $NP = MysqlLink::m_filterInput($_POST["NP"]);
    $noSeire =MysqlLink::m_filterInput($_POST["noSerie"]);
    
    $Ret = $linkObj->m_SendCommand("INSERT INTO atajos(noSerie,descripcion,atajo,medida,precio) VALUES('$noSeire','$ND','$NA','$NM',$NP);",MysqlLink::NAMED,MysqlLink::RET_NO);
?>