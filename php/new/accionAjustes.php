<?php
if(isset($_GET['accion'])){
    include("functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "agregarAduana":
            $aduana = $_POST['aduana'];
            $query="INSERT INTO `aduanas`(`nombre`) VALUES (?);";
            $sql->execQueryBinders($query,array("s",$aduana));
            $errores= $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)){
                $respuesta['exito'] = false;
                $respuesta['errores'] = $errores;
            }else{
                $respuesta['exito']= true;
                $respuesta['id'] = $sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
            break;
        case "modificarAduana":
            $id= $_POST['id'];
            $nombre = $_POST['nombre'];
            $query="UPDATE `aduanas` SET nombre=? WHERE `idAduana` = ?";
            $sql->execQueryBinders($query,array("si",$nombre,$id));
            $errores= $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)!=0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = $errores;
            }else{
                $respuesta['exito']= true;
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "eliminarAduana":
            $id= $_POST['id'];
            $query="DELETE FROM `aduanas` WHERE `idAduana` = ?";
            $sql->execQueryBinders($query,array("i",$id));
            $errores= $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)!=0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = $errores;
            }else{
                $respuesta['exito']= true;
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


            break;
        case "agregarPedimento":
            $num = $_POST['pedimento'];
            $aduana = $_POST['aduana'];
            $fecha = $_POST['fecha'];
            $query ="INSERT INTO `pedimentos`(`numero`, `fecha`, `idAduana`) VALUES (?,?,?);";
            $sql->execQueryBinders($query,array("ssi",$num,$fecha,$aduana));
            $errores= $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)!=0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = $errores;
            }else{
                $respuesta['exito']= true;
                $respuesta['id'] = $sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));



            break;
        case "modificarPedimento":
            $pedimento =$_POST['pedimento'];
            $aduana = $_POST['aduana'];
            $fecha = $_POST['fecha'];
            $id = $_POST['id'];
            $query ="UPDATE `pedimentos` SET numero = ?,fecha= ? ,`idAduana` = ? WHERE `idPedimento` = ?";
            $sql->execQueryBinders($query,array("ssii",$pedimento,$fecha,$aduana,$id));
            $errores= $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)!=0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = $errores;
            }else{
                $respuesta['exito']= true;
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


            break;
        case "eliminarPedimento":
            $id = $_POST['id'];
            $query="DELETE FROM `pedimentos` WHERE `idPedimento` = ?";
            $sql->execQueryBinders($query,array("i",$id));
            $errores= $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)!=0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = $errores;
            }else{
                $respuesta['exito']= true;
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));



            break;

    }

}


?>