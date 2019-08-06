<?php
    include("Functions.php");
    $linkObj = F_linkDb();
    if(!filter_input(INPUT_POST,"IDATAJO",FILTER_VALIDATE_INT))
    {
        print_r("Non valid input");
        return;
    }

    $Ret = $linkObj->m_SendCommand("DELETE FROM atajos WHERE idatajo = $_POST[IDATAJO];",MysqlLink::NAMED,MysqlLink::RET_NO);
?>