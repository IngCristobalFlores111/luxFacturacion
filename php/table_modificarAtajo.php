<?php
    include("Functions.php");
    $linkObj = F_linkDb();
    if(!filter_input(INPUT_POST,"IDATAJO",FILTER_VALIDATE_INT) && !filter_input(INPUT_POST,"IDATAJOANTERIOR",FILTER_VALIDATE_INT))
    {
        print_r("Non valid input");
        return;
    }
    $Ret = $linkObj->m_SendCommand("SELECT * FROM atajos WHERE idatajo = $_POST[IDATAJO]",MysqlLink::NAMED,MysqlLink::RET_YES);
    $Ret2 = $linkObj->m_SendCommand("SELECT * FROM atajos WHERE idatajo = $_POST[IDATAJOANTERIOR]",MysqlLink::NAMED,MysqlLink::RET_YES);
    $Ret = array_merge($Ret,$Ret2);
    print_r('{"Obj":'.json_encode($Ret).'}');
?>