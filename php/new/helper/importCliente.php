<?php
    include '../../../PHPExcel/Classes/PHPExcel/IOFactory.php';

    function importExcelFacturacion($sql,$inputFileName,$tipo){
    
    //  Read your Excel workbook
    try {
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFileName);
    }
    catch(Exception $e) {
        die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
    }
    
    //  Get worksheet dimensions
    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();
    
    //  Loop through each row of the worksheet in turn
    $res = array();
    for ($row = 1; $row <= $highestRow; $row++){
        //  Read a row of data into an array
        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                                        NULL,
                                        TRUE,
                                        FALSE);
        //  Insert row data array into your database of choice here
        array_push($res,$rowData[0]);
    }
    $indice= array();
    $table_header= $res[0];
    $len = count($table_header); $i = 0;
    $respuesta =array();
    
    switch($tipo){
        case "clientes":
    
            for(;$i<$len;$i++){
                if($table_header[$i]=="nombre"){$indice["nombre"] = $i; continue;}
                if($table_header[$i]=="RFC"){$indice["RFC"] = $i;  continue;}
                if($table_header[$i]=="email"){$indice["email"] = $i;  continue;}
                if($table_header[$i]=="telefono"){$indice["telefono"] = $i; continue;}
                if($table_header[$i]=="calle"){$indice["calle"] = $i;  continue;}
                if($table_header[$i]=="noExterior"){$indice["noExterior"] = $i;  continue;}
                if($table_header[$i]=="noInterior"){$indice["noInterior"] = $i;  continue;}
                if($table_header[$i]=="colonia"){$indice["colonia"] = $i;  continue;}
                if($table_header[$i]=="localidad"){$indice["localidad"] = $i;  continue;}
                if($table_header[$i]=="municipio"){$indice["municipio"] = $i;  continue;}
                if($table_header[$i]=="estado"){$indice["estado"] = $i; continue;}
                if($table_header[$i]=="pais"){$indice["pais"] = $i; continue;}
                if($table_header[$i]=="codigoPostal"){$indice["codigoPostal"] = $i; continue;}
                if($table_header[$i]=="celular"){$indice["celular"] = $i; continue;}
            }
            if(count($indice)!=14){
                $respuesta['exito'] =false;
                $respuesta['msg']="Excel con formato incorrecto";
            }else{
                include("new/functions.php");
                $sql = createMysqliConnection();
                $query ="";
                $len = count($res); $i =1;
    
                for(;$i<$len;$i++){
                    $query.="INSERT INTO `clientes_facturacion`(
                 `email`, `telefono`,
                  `nombre`, `RFC`, `calle`,
                   `noExterior`, `noInterior`,
                   `colonia`, `localidad`, `municipio`,
                   `estado`, `pais`, `CodigoPostal`, `celular`)
                       VALUES('".$res[$i][$indice['email']]."','".
                               $res[$i][$indice['telefono']]."','"
                               .$res[$i][$indice['nombre']]."','"
                               .$res[$i][$indice['RFC']]."','"
                               .$res[$i][$indice['calle']]."','"
                               .$res[$i][$indice['noExterior']]."','"
                               .$res[$i][$indice['noInterior']]."','"
                               .$res[$i][$indice['colonia']]."','"
                               .$res[$i][$indice['localidad']]."','"
                               .$res[$i][$indice['municipio']]."','"
                               .$res[$i][$indice['estado']]."','"
                               .$res[$i][$indice['pais']]."','"
                               .$res[$i][$indice['codigoPostal']]."','"
                               .$res[$i][$indice['celular']]."');";
    
                }
    
                $sql->ejecutarNoQuery($query);
                $errors = $sql->getErrorLog();
                if(count($errors)>0){
                    $respuesta['exito']=false;
                    $respuesta['msg'] = $errors;
                }else{
                    $respuesta['exito'] = true;
                    $respuesta['msg'] =(count($res)-1)." Clientes importados exitosamente";
                }
    
    
    
            }
    
    
    
            break;
        case "productos":
            for(;$i<$len;$i++){
            if($table_header[$i]=="descripcion"){
                $indice["descripcion"] = $i;
            }
            if($table_header[$i]=="medida"){
                $indice["medida"] = $i;
            }
            if($table_header[$i]=="precio"){
                $indice["precio"] = $i;
            }
            if($table_header[$i]=="nombre"){
                $indice["nombre"] = $i;
            }
            if($table_header[$i]=="noSerie"){
                $indice["noSerie"] = $i;
            }
    
    
            }
            if(count($indice)!=5){
                $respuesta['exito']= false;
                $respuesta['msg'] ="Archivo con formato incorrecto";
            }else{
                include("new/functions.php");
                $sql = createMysqliConnection();
                $query ="";
                $len = count($res); $i = 1;
                for(;$i<$len;$i++){
                    $query.="INSERT INTO `atajos`(`descripcion`, `medida`,`precio`,`atajo`, `noSerie`) VALUES ('".$res[$i][$indice['descripcion']]."','".$res[$i][$indice['medida']]."','".$res[$i][$indice['precio']]."','".$res[$i][$indice['nombre']]."','".$res[$i][$indice['noSerie']]."');";
                }
                     $sql->ejecutarNoQuery($query);
                $errors = $sql->getErrorLog();
                if(count($errors)>0){
                    $respuesta['exito']=false;
                    $respuesta['msg'] = $errors;
                }else{
                    $respuesta['exito'] = true;
                    $respuesta['msg'] =(count($res)-1)." Productos importados exitosamente";
                }
    
    
    
            }
    
    
            break;
    
    
    
    
    }
    return $respuesta;
}
        

?>