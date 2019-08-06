<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_GET['accion'])){
    include("../functions.php");
$sql =createMysqliConnection();
switch($_GET['accion']){
    case "alterPedimento":  // alta y modifcacion
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
 $query ="";
 $binders = array();
    if($request->accion=="0"){ // alta
        $pedimento = $request->pedimento;
        $idAduana = $pedimento->aduana->id;
        $query="INSERT INTO `pedimentos_33`
        (`idAduana`, `año_validacion`,
         `numero_patente`, `num_progresiva`, 
         `año_num_progresiva`) 
        VALUES (?,?,?,?,?)";
        $binders = array("issss",$idAduana,
        $pedimento->ano_validacion,
        $pedimento->patente,
        $pedimento->num_progresiva,
        $pedimento->ano_num_progresiva);
    }
    if($request->accion=="1"){  // modificar pedimento
        $pedimento = $request->pedimento;
        $idAduana = $pedimento->aduana->id;
        $query="UPDATE `pedimentos_33` SET 
        idAduana= ?, 
        `año_validacion` = ?
        ,`numero_patente` = ?,
        `num_progresiva` = ?,
        `año_num_progresiva` = ? 
        WHERE idPedimento = ?";
           $binders = array("issssi",$idAduana,
           $pedimento->ano_validacion,
           $pedimento->patente,
           $pedimento->num_progresiva,
           $pedimento->ano_num_progresiva,
           $pedimento->id
        );

    }
    $sql->execQueryBinders($query,$binders);

    $respuesta = array();
    $errors = $sql->getErrorLog();
    if(count($errors)>0){
    $respuesta['exito']=false;
    $respuesta['errors']=$errors;
    }else{
      $respuesta['exito']=true;
      
    }
    print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));  
    break;
    case "altaPedimento":
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $query="INSERT INTO `pedimentos_33`(`idAduana`, `patente`, `año`) VALUES (?,?,?);";
    $sql->execQueryBinders($query,array("iis",$request->idAduana,$request->patente,$request->ano));
    $errors = $sql->getErrorLog();
    
    $respuesta = array();
    if(count($errors)>0){
    $respuesta['exito']=false;
    $respuesta['errors']=$errors;
    }else{
      $respuesta['exito']=true;
      $respuesta['idPedimento']= $sql->getLastId();
      
    }
    print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));     
    break;
    case "modificarPedimento":

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $respuesta = array();
        
$query="UPDATE `pedimentos_33` SET idAduana = ?,patente = ? ,`año` = ? WHERE idPedimento = ?";
try{        
$sql->execQueryBinders($query,array("iisi",$request->idAduana,$request->patente,$request->ano,$request->idPedimento));
        $errors = $sql->getErrorLog();
        
        if(count($errors)>0){
        $respuesta['exito']=false;
        $respuesta['errors']=$errors;
        }else{
          $respuesta['exito']=true;
          
        }
    }catch(Exception $e){
        $respuesta['exito']=false;
        $respuesta['errors'] =$e->getMessage();
        
    }
        print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));    

        break;
        case "eliminar":
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $respuesta = array();
        
        $query="DELETE FROM `pedimentos_33` WHERE idPedimento = ?";
        try{        
            $sql->execQueryBinders($query,array("i",$request->idPedimento));
                    $errors = $sql->getErrorLog();      
                    if(count($errors)>0){
                    $respuesta['exito']=false;
                    $respuesta['errors']=$errors;
                    }else{
                      $respuesta['exito']=true;
                      
                    }
                }catch(Exception $e){
                    $respuesta['exito']=false;
                    $respuesta['errors'] =$e->getMessage();
                    
                }
                    print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));    

        break;

}    

}



?>