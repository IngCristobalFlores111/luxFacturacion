<?php

error_reporting(0);

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

    include ("../phpMailer/mailer.php");
    include ("Functions.php");

    $objSQL = F_sqlConn();

    session_start();

    $link = F_LinkDb();


    if(!filter_input(INPUT_POST,"FOLIO",FILTER_VALIDATE_INT) && !filter_input(INPUT_POST,"ID",FILTER_VALIDATE_INT))
    {
        print_r("Invalid Input");
        return;
    }

    $arr = json_decode($_POST["CORREOS"]);
    $i = 0;
    $size = sizeof($arr->Correos);

    $Ret = $link->m_SendCommand("SELECT nombre,email,mail_pass FROM usuario_facturacion;",MysqlLink::NAMED,MysqlLink::RET_YES);
            if(sizeof($Ret) > 1)
            {
                print_r("Several usuario_facturacion entries? Llame a su administrador de base de datos y muestrele este mensaje");
                return;
            }

      $Sender = $Ret[0]["email"];
      $SenderPass = $Ret[0]["mail_pass"];
      $SenderName = $Ret[0]["nombre"];

      $Subject = "Le enviamos su comprobante fiscal";

      $MessageHTML = "";
      $PDFPath = "../Facturacion/facturas";
      $XMLPath = "../Facturacion/facturas";
      $idcliente = $_POST["ID"];

      switch($_POST["UITYPE"])
      {
      case 0: // Pendiente
      $Ret = $link->m_SendCommand('SELECT mensaje_generada FROM user_config WHERE idusuario="'.$_SESSION["idUsuario"].'";',MysqlLink::NAMED, MysqlLink::RET_YES);
      $Subject = "Esta representación impresa ha sido enviada para su revisión, la cual no ha sido timbrada";
      $MessageHTML = $Ret[0]["mensaje_generada"];
      $PDFPath = $PDFPath."/pendientes/pdf/".$_POST["ID"]."_".$_POST["FOLIO"].".pdf";
      $XMLPath = "";
      break;

      case 1: //Timbrada
      $Ret = $link->m_SendCommand("SELECT mensaje_timbrada FROM user_config WHERE idusuario='".$_SESSION["idUsuario"] ."';",MysqlLink::NAMED, MysqlLink::RET_YES);
      $fecha = $link->m_SendCommand("SELECT fecha_timbrado FROM factura_generada WHERE folio_factura = '".$_POST["FOLIO"]."';",MysqlLink::NAMED,MysqlLink::RET_YES);
      $Subject = "Le enviamos su comprobante fiscal";
      $fecha = str_replace("-","_",$fecha[0]["fecha_timbrado"]);
      $MessageHTML = $Ret[0]["mensaje_timbrada"];
      $PDFPath = $PDFPath."/timbradas/pdf/".$_POST["ID"]."_".$fecha."_".$_POST["FOLIO"].".pdf";
      $XMLPath = $XMLPath."/timbradas/xml/".$_POST["ID"]."_".$fecha."_".$_POST["FOLIO"].".xml";

          break;

      case 2: //Cancelada
      $Ret = $link->m_SendCommand("SELECT mensaje_cancelada FROM user_config WHERE idusuario='".$_SESSION["idUsuario"] ."';",MysqlLink::NAMED, MysqlLink::RET_YES);
      $Subject = "Esta factura ha sido cancelada";
      $MessageHTML = $Ret[0]["mensaje_cancelada"];
      break;

      default:
      break;
        }
        $MessageHTML = "<label>".$MessageHTML."</label>";

        $Receiver = "";
        //Identifica que correo esta en la base como defaul y cuales fueron agregados por el usuario FOR FUTURE NEEDS!



    for($i;$i < $size; $i++)
    {
        $mailer = new phpMailerWithGmail($Sender,$SenderPass,$SenderName);
        $Receiver = $arr->Correos[$i][0];
         if($arr->Correos[$i][2] != ""){
                 $Subject = $arr->Correos[$i][2];
         }
        if($arr->Correos[$i][2] != "")
            $MessageHTML = "<label>".$arr->Correos[$i][3]."</label>";

        $MessageHTML = $objSQL->fillMail($MessageHTML,$_POST['FOLIO'],$_POST["ID"]);

        $res = $mailer->sendMail($Receiver,'Estimado cliente',$Subject,$MessageHTML,'',array(0=>$PDFPath,1=>$XMLPath),0);
        $mailer->clearAttachments();
        if($res['status'] < 0)
            $arr->Correos[$i][1] = "-1";
         else
            $arr->Correos[$i][1] = "0";
        }
        print_r('{"Correos":'. json_encode($arr->Correos) . '}');
?>