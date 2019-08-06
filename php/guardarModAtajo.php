<?php
    include("Functions.php");
    $linkObj = F_linkDb();
    print_r($_POST["ID"]);

    //ID: id, NA: $("#i_nuevoAtajo").val(), ND: $("#i_nuevaDescripcion").val(), NM: $("#i_nuevaMedida").val(), NP : $("#i_nuevoPrecio").val()

    if(!filter_input(INPUT_POST,"ID",FILTER_VALIDATE_INT) &&
       !filter_input(INPUT_POST,"NA",FILTER_VALIDATE_INT) && 
       !filter_input(INPUT_POST,"ND",FILTER_VALIDATE_INT) && 
       !filter_input(INPUT_POST,"NM",FILTER_VALIDATE_INT) && 
       !filter_input(INPUT_POST,"NP",FILTER_VALIDATE_INT))
    {
        print_r("Non valid input");
        return;
    }

    $_POST["ID"] = MysqlLink::m_filterInput($_POST["ID"]);
    $_POST["NA"] = MysqlLink::m_filterInput($_POST["NA"]);
    $_POST["ND"] = MysqlLink::m_filterInput($_POST["ND"]);
    $_POST["NM"] = MysqlLink::m_filterInput($_POST["NM"]);
    $_POST["NP"] = MysqlLink::m_filterInput($_POST["NP"]);
    $noSerie = MysqlLink::m_filterInput($_POST["noSerie"]);
    $Ret = $linkObj->m_SendCommand("UPDATE atajos SET noSerie='$noSerie',descripcion='$_POST[ND]',atajo='$_POST[NA]', medida='$_POST[NM]',precio='$_POST[NP]' WHERE idatajo='$_POST[ID]'",MysqlLink::NAMED,MysqlLink::RET_NO);
?>