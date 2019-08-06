<?php
    include("Functions.php");
    $linkObj = F_LinkDb();
    $Output = "";
    $Ret = $linkObj->m_SendCommand("SELECT * FROM atajos LIMIT 25",MysqlLink::NAMED , MysqlLink::RET_YES);
    $i = 0;
    for(; $i < sizeof($Ret); $i++)
    {
    $Output = $Output.'<tr id="i_Atajo_'.$Ret[$i]["idatajo"].'">'.
                                    "<td>".$Ret[$i]["atajo"]."</td>".
                                    "<td>".$Ret[$i]["descripcion"]."</td>".
                                    "<td>".$Ret[$i]["noSerie"]."</td>".
                                    "<td>".$Ret[$i]["medida"]."</td>".
                                    "<td>$".$Ret[$i]["precio"]."</td>".
                                    "<td>".
                                        "<div class='btn-group-horizontal'>".
                                            '<button type="button" class="btn btn-default c_Ajustes" onclick="atajoModificar('.$Ret[$i]["idatajo"].')"><i class="fa fa-cog" style = "font-size:20px; " aria-hidden="true"></i></button>'.
                                            '<button type="button" style = "margin-left: 7%;" class="btn btn-default c_Cancel" onclick="atajoEliminar('.$Ret[$i]["idatajo"].')"><i class="fa fa-trash"  style = "font-size:20px; " aria-hidden="true"></i></button>'.
                                        "</div>".
                                    "</td>".
                                "</tr>";
    }
    print_r($Output);
?>