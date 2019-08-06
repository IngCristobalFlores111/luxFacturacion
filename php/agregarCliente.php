<?php
    include("Functions.php");
    
                $linkObj = F_linkDb();

                if(!filter_input(INPUT_POST,"IN",FILTER_SANITIZE_FULL_SPECIAL_CHARS) && 
       !filter_input(INPUT_POST,"RFC",FILTER_SANITIZE_FULL_SPECIAL_CHARS) && 
       !filter_input(INPUT_POST,"TEL",FILTER_VALIDATE_INT) &&
       !filter_input(INPUT_POST,"CELL",FILTER_VALIDATE_INT) &&
       !filter_input(INPUT_POST,"MAIL",FILTER_SANITIZE_FULL_SPECIAL_CHARS) &&
       !filter_input(INPUT_POST,"CALLE",FILTER_SANITIZE_FULL_SPECIAL_CHARS) &&
       !filter_input(INPUT_POST,"COLONIA",FILTER_SANITIZE_FULL_SPECIAL_CHARS) &&
       !filter_input(INPUT_POST,"CP",FILTER_VALIDATE_INT) &&
       !filter_input(INPUT_POST,"NOEX",FILTER_VALIDATE_INT) &&
       !filter_input(INPUT_POST,"NOINT",FILTER_VALIDATE_INT) &&
       !filter_input(INPUT_POST,"LOCALIDAD",FILTER_SANITIZE_FULL_SPECIAL_CHARS) &&
       !filter_input(INPUT_POST,"MUNICIPIO",FILTER_SANITIZE_FULL_SPECIAL_CHARS) &&
       !filter_input(INPUT_POST,"ESTADO",FILTER_SANITIZE_FULL_SPECIAL_CHARS))
        {
            print_r("Wrong input");
        }

    $IN = MysqlLink::m_filterInput($_POST["IN"]);
    $RFC = MysqlLink::m_filterInput($_POST["RFC"]);
    $TEL = MysqlLink::m_filterInput($_POST["TEL"]);
    $CELL = MysqlLink::m_filterInput($_POST["CELL"]);
    $MAIL = MysqlLink::m_filterInput($_POST["MAIL"]);
    $CALLE = MysqlLink::m_filterInput($_POST["CALLE"]);
    $COLONIA = MysqlLink::m_filterInput($_POST["COLONIA"]);
    $CP = MysqlLink::m_filterInput($_POST["CP"]);
    $NOEX = MysqlLink::m_filterInput($_POST["NOEX"]);
    $NOINT = MysqlLink::m_filterInput($_POST["NOINT"]);
    $LOCALIDAD = MysqlLink::m_filterInput($_POST["LOCALIDAD"]);
    $MUNICIPIO = MysqlLink::m_filterInput($_POST["MUNICIPIO"]);
    $ESTADO = MysqlLink::m_filterInput($_POST["ESTADO"]);


                $linkObj->m_SendCommand('
INSERT INTO clientes_facturacion( 
email, 
telefono, 
nombre, 
RFC, 
calle, 
noExterior, 
noInterior, 
colonia, 
localidad, 
municipio, 
estado, 
pais, 
CodigoPostal, 
celular
) 
VALUES(
"'.$MAIL.'" , 
"'.$TEL.'", 
"'.$IN.'" ,
"'.$RFC.'", 
"'.$CALLE.'", 
"'.$NOEX.'" , 
"'.$NOINT.'", 
"'.$COLONIA.'" , 
"'.$LOCALIDAD.'" ,
"'.$MUNICIPIO.'" ,
"'.$ESTADO.'" ,
"MEXICO" ,  
"'.$CP.'" ,  
"'.$CELL.'"
);',MysqlLink::NAMED,MysqlLink::RET_NO);
?>