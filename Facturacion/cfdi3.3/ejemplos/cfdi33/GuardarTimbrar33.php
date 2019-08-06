
<?php  if(!isset($_POST['id']) && !isset($_POST['factura_params']) &&!isset($_POST['folioFactura']) ){ header("location: index.html");}


       include("php/new/functions.php");
       $sql = createMysqliConnection();
       $query ="SELECT * FROM `clientes_facturacion` WHERE `idcliente` = ?";
       $cliente = $sql->get_bind_results($query,array("i",$_POST['id']));
       $cliente = $cliente[0];


?>
<?php
   require_once("../../Login/php/logInPreemptiveCheckup.php");
     session_start();
     logInPreemptiveCheckup();
?>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Guardar y Timbrar</title>

    <!--MetaArea-->
    <meta charset="utf-8"> <!--Caracteres Codificados-->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!--Para Dispositivos Moviles habilita touch-zoom etc-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--/MetaArea-->
    <!--CSS-->
    <link href="css/bootstrap.min.css" rel="stylesheet"><!-- Bootstrap Core CSS -->
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="icon" href="img/luxline1.ico" type="image/x-icon">

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!--/CSS-->
   
</head>

<body >
     <div id="i_modalBlur">

    <div class="container">
        <div class="c_roundedBackground c_background">
            <h3 class="c_marginTitle">Cliente</h3>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>RFC</th>
                            <th>Domicilio</th>
                            <th>Correo Electrónico</th>
                            <th>Telefono</th>

                        </tr>

                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $cliente['nombre'] ?></td>
                            <td>
                                <?= $cliente['RFC'] ?>
                            </td>
                            <td>
                                <?= $cliente['calle'].' '.$cliente['noExterior'].' '.$cliente['noInterior']." ".$cliente['colonia']." ".$cliente['CodigoPostal']." ".$cliente['municipio']." ".$cliente['estado']." ".$cliente['pais'] ?>
                            </td>
                            <td><?= $cliente['email']?></td>
                            <td><?=$cliente['telefono']?></td>
                        </tr>

                    </tbody>

                </table>
            </div>


        </div>

            <div class="c_roundedBackground c_background">

                <h3 class="c_marginTitle">Factura</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <?php
                    $params = $_POST['factura_params'];
                    $params = json_decode($params,true);
                    $metodos_pago = array("PUE"=>"Pago en una sola exhibición","PIP"=>"Pago inicial y parcialidades","PPD"=>"Pago en parcialidades o diferido");
                    $tiposComp = array("I"=>"Ingreso","E"=>"Egreso");

?>
                        <thead>
                            <tr>
                             <?php if($params['predial']!=''){ ?>
                                <th>Predial</th>
                                <?php } ?>
                                <th>Forma de Pago</th>
                                <th>Metodo de Pago</th>
                                <?php if($params['descuento']>0){ ?>
                                <th>Descuento</th>
                                <?php } ?>
                                <?php if($params['noCuenta']!=""){ ?>
                                <th>Numero de Cuenta Pago</th>
                                <?php } ?>
                                <th>Moneda</th>
                                <th>Uso CFDI</th>
                                <th>Tipo de comprobante</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                             <?php if($params['predial']!=''){ ?>
                                <td><?=$params['predial']?></td>
                                <?php } ?>
                                <td>
                                    <?= $params['formaPagoNombre'] ?>
                                </td>
                                <td>
                                    <?= $metodos_pago[$params['metodo_pago']] ?>
                                </td>
                                <?php if($params['descuento']>0){ ?>

                                <td>
                                    <?= $params['descuento'] ?>

                                    <?php } ?>
                                </td>
                                <?php if($params['noCuenta']!=""){ ?>

                                <td>
                                    <?= $params['noCuenta'] ?>
                                </td>
                                <?php  }?>
                                <td><?= $params['moneda'] ?></td>
                                <td><?= $params['usoCFDI'] ?></td>
                                <td><?= $tiposComp[$params['tipoComp']] ?></td>

                            </tr>

                        </tbody>
                    </table>

                </div>

            </div>
            <div class="c_roundedBackground c_background">
                <h3 class="c_marginTitle">
                    Conceptos de Factura
                    <button class="btn btn-primary" onclick="">
                        <i class="fa fa-undo"></i>Regresar
                    </button>
                </h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th>Clave ProdServ(Catalogo)</th>
                                <th>Numero de Serie</th>
                                <th>Descripción</th>
                                <th>Unidad</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Importe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                        setlocale(LC_MONETARY,"es_MX");
                        
                        foreach($_SESSION['conceptos'] as $concepto){
                            $popOver ='';
                            
                            if(isset($concepto['aduana'])){
                                 $content = "<div class='popover-info'>";
                                 $content .= "<label>Pedimento:</label> " . $concepto['numPedimento']."<br>";
                                 $content .= "<label>Aduana:</label> " . $concepto['aduana'] . "<br>";
                                 $content .= "<label>Fecha:</label> " . $concepto['fechaPedimento'] . "<br></div>";
                   $popOver = '<a class="a-pop-over" data-placement="top" data-toggle="popover" title="Datos de importación" data-html="true" data-content="' . $content . '">Importación</a>';

                            }
                           
                            ?>
                            <tr>
                            <td><label class="label label-success">
                            <?= $concepto['prodServ']['clave']; ?> </label>
                            <label class="label label-info"><?= $concepto['prodServ']['nombre']; ?></label>
                            </td>
                                <td>
                                    <?=$concepto['noSerie']?>
                                </td>
                                <td>
                                    <?=$concepto['descripcion'].'<br>'.$popOver?>
                                </td>
                                <td>
                                    <?=$concepto['unidad']?>
                                </td>
                                <td>
                                    <?=money_format("$%i",(float)$concepto['precio_unitario']);?>
                                </td>
                                <td>
                                    <?=$concepto['cantidad']?>
                                </td>
                                <td>
                                    <?=money_format("$%i",(float)$concepto['importe']);?>
                                </td>



                            </tr>
                            <?php } ?>
                        </tbody>

                    </table>

                </div>


            </div>


            <div class="c_roundedBackground c_background">

                <h3 class="c_marginTitle">Correos de Envio</h3>
                <hr />

                <div class="row">

                    <div class="col-xs-9">

                        <input id="i_inputCorreo" type="text" class="form-control c_marginInput c_input" placeholder="Correo Electronico" />

                    </div><!-- /col-xs-10 -->

                    <div class="col-xs-3">

                        <button id="i_btnAgregarCorreo" class="btn btn-block c_Accept" type="button" onclick="agregarCorreo()">
                            <i class="fa fa-plus" style="font-size: 20px;" aria-hidden="true"></i>
                        </button>

                    </div><!-- /col-xs-10 -->



                </div><!-- /row Correo Inputs -->


                <div class="table-responsive table-responsiveCorreo" style="margin-top: 2%">

                    <table class="table table-bordered">

                        <thead></thead>

                        <tbody id="i_tableCorreosDefault">
                            <tr>
                                <td style="color:white; text-align:center;">
                                    <span style="margin-right:5px; display:inline;"></span>
                                </td>
                                <td>
                                    <div class="btn-group-horizontal">
                                        <button id="i_btnTrash_'+key+'" class="c_btnTrash btn btn-default fa fa-trash c_Cancel" style="font-size:20px;"></button>
                                        <button style="margin-left:5px; font-size:20px;" type="button" id="i_btnSetMsg_'+key+'" class="c_btnSetMsg btn btn-default fa fa-envelope c_Accept"></button>
                                    </div>
                                </td>
                            </tr>

                        </tbody>



                    </table>

                </div><!-- table-responsive -->



            </div><!--/c_roundedBackground-->



            <div class="c_roundedBackground c_background">

                <div class="row">

                    <div class="col-xs-12">

                        <h3>Lugar de expedición</h3>
                        <hr />
                        <select class="form-control c_input" id="i_selectLugares"></select>
                    </div>

                </div><!--/row-->

                <div class="row c_marginElement" style="margin:3%;">

                    <div class="col-xs-6">
                        <label class="radio-inline">
                            <input id="i_radioGuardar" type="radio" name="optradio" />Guardar
                        </label>
                    </div>

                    <div class="col-xs-6">
                        <label class="radio-inline">
                            <input id="i_radioGyT" type="radio" name="optradio" />Guardar y Timbrar
                        </label>
                    </div>

                    <button id="i_btnContinuar" style="margin-top: inherit;" class="btn btn-lg c_Accept">Continuar</button>
                    <button onclick="location.href='index.html'" id="i_goOn" style="display:none; margin-top: inherit;" class="btn btn-lg c_Accept">Regresar</button>


                </div><!--/row-->

            </div><!--/c_roundedBackground-->

        </div> <!-- /container -->
    
</div><!-- /i_modalBlur -->

   
    <!-- ModalMessageCorreo -->
    <div class="modal fade" id="i_modalAgregarMensajeExtra" role="dialog">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 text-center modal-box">
                    
                        <h3 id="i_correoEnviarHeader">Mensaje para correo Electrónico</h3>
                        <div class="form-group">
                            <label for="i_inputAsunto">Asunto</label>
                            <input class="form-control c_input" id="i_inputAsunto" type="text">
                        </div>
                        <div class="form-group">
                            <label id="i_tableCorreosDefault" for="i_inputCuerpo">Cuerpo</label>
                            <textarea class="form-control c_input" id="i_inputCuerpo"></textarea>
                        </div>
                        <button id="i_btnAceptarMensaje" style = "margin:5%;" class="btn btn-default c_Accept" onclick="agregarBodySubject()">Aceptar</button>
    
                </div> <!--col -->
            </div>
        </div>
    </div><!--/ModalMessageCorreo -->
    
    <!--ModalMessageCorreoInvalido -->
    <div class="modal fade" id="i_modalCorreoInvalido" role="dialog">
           <div class="container">
             <div class="row">
              <div class="col-sm-6 col-sm-offset-3 text-center modal-box">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  
                  <h3 class = "h3Modal">Correo Invalido</h3>
                  
                  <p>Verifique que el correo corresponda adecuadamente.</p>
                  
                  <button type="button" class="btn btn-default c_modalDismiss c_Accept" data-dismiss="modal" autofocus>Aceptar</button>
                  <button type="button" class="btn btn-default c_modalDismiss c_Cancel" data-dismiss="modal">Cancelar</button>
                  
                  
             </div>
            </div>
           </div>
        </div><!--/ModalMessageCorreoInvalido -->
    
    <!--ModalLoader -->
    <div class="modal fade" id="modalPreview" role="dialog">
     <div class="sk-cube-grid">
      <div class="sk-cube sk-cube1"></div>
      <div class="sk-cube sk-cube2"></div>
      <div class="sk-cube sk-cube3"></div>
      <div class="sk-cube sk-cube4"></div>
      <div class="sk-cube sk-cube5"></div>
      <div class="sk-cube sk-cube6"></div>
      <div class="sk-cube sk-cube7"></div>
      <div class="sk-cube sk-cube8"></div>
      <div class="sk-cube sk-cube9"></div>
     </div>
     
     
    
     
     
 
    
</div><!--/ModalLoader -->

    <!--ModalMessageGuardaryTimbrar -->
    <div class="modal fade" id="i_modalContinuar" role="dialog">
           <div class="container">
             <div class="row">
              <div class="col-sm-6 col-sm-offset-3 text-center modal-box">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  
                  <h3 class = "h3Modal">Status de Factura</h3>
                  
                   <div hidden id="i_alertSuccessTimbrado" class="alert alert-success" style="display: block;">

                                            <i class="fa fa-3x fa-check-circle" style="display:block;" aria-hidden="true"></i>

                                            <strong style="display:block;">Se ha Guardado y Timbrado Correctamente</strong>
                                            
                                            <p id = "i_MensajeServidorSucces"></p>

    

                                        </div>
                                        
                   <div hidden id = "i_alertFailTimbrado" class="alert alert-danger" style="display: none;">


                                            <i class="fa fa-3x fa-times-circle" style="display:block;" aria-hidden="true"></i>

                                            <strong style="display:block;">Sucedio algún error</strong>
                                            <p id = "i_MensajeServidorFail"></p>
                                            
                                            

            

                  </div>
                                        
                    
                   <p> MENSAJE PARA PROGRAMADOR: Después del aceptar de este modal hay que hacer redirección a index, aqui finaliza la factura </p> 
                 
                  
                  <button type="button" id = "i_btnModalAccept" class="btn btn-default c_modalDismiss c_Accept" data-dismiss="modal" autofocus>Aceptar</button>
                  <button type="button" id = "i_btnModalCancel" class="btn btn-default c_modalDismiss c_Cancel" data-dismiss="modal">Cancelar</button>
                  
                  
             </div>
            </div>
           </div>
        </div><!--/ModalMessageGuardaryTimbrar -->
        
    <!--ModalMessageGuardar -->
    <div class="modal fade" id="i_modalContinuar" role="dialog">
           <div class="container">
             <div class="row">
              <div class="col-sm-6 col-sm-offset-3 text-center modal-box">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  
                  <h3 class = "h3Modal">¿Seguro que solo desea guardar la factura</h3>
                  
                  <p> MENSAJE PARA PROGRAMADOR: Después del aceptar de este modal hay que hacer redirección a index, aqui finaliza la factura </p> 
                  
                  
                  <button type="button" class="btn btn-default c_modalDismiss c_Accept" data-dismiss="modal" autofocus>Aceptar</button>
                  <button type="button" class="btn btn-default c_modalDismiss c_Cancel" data-dismiss="modal">Cancelar</button>
                  
                  
             </div>
            </div>
           </div>
        </div><!--/ModalMessageGuardar -->
    
    
    <div class="modal fade" id="i_modalEnvioCorreo" role="dialog">
        <div class="container">
            <div class="row" id="i_cubitoWaiting">
                <div class="col-xs-12">
                    <div class="sk-cube-grid">
                        <div class="sk-cube sk-cube1"></div>
                        <div class="sk-cube sk-cube2"></div>
                        <div class="sk-cube sk-cube3"></div>
                        <div class="sk-cube sk-cube4"></div>
                        <div class="sk-cube sk-cube5"></div>
                        <div class="sk-cube sk-cube6"></div>
                        <div class="sk-cube sk-cube7"></div>
                        <div class="sk-cube sk-cube8"></div>
                        <div class="sk-cube sk-cube9"></div>
                    </div>
                </div>
            </div>
            <div class="row" id="i_contentModalEnviarCorreo">
                <div class="col-sm-6 col-sm-offset-3 text-center modal-box">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="h3Modal" id="i_h3ModalMessageEnvioCorreo">¿Esta seguro que desea enviar la factura a los siguientes clientes?</h3>
                    <h4 class="h3Modal" id="i_h3ModalMessageEnvioCorreo2">¿Esta seguro que desea enviar la factura a los siguientes clientes?</h4>
                    <div class="alert alert-danger" id="i_enviarCorreosFallido">
                        <i class="fa fa-3x fa-times-circle" style="display:block;" aria-hidden="true"></i>
                        <strong style="display:block;">No se ha podido enviar</strong>
                    </div>
                    <div class="alert alert-success" id="i_enviarCorreosExito">
                        <i class="fa fa-3x fa-check-circle" style="display:block;" aria-hidden="true"></i>
                        <strong style="display:block;">Se ha enviado correctamente</strong>
                    </div>
                    <div class="alert alert-success" id="i_enviarCorreosConfirmacion">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>Correo</td>
                                        <td>Status</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="Correo_0">
                                        <td>tobalin01@gmail.com</td>
                                        <td><i class="fa fa-check-circle" style="color: rgb(76, 175, 80);"></i></td>
                                    </tr>
                                    <tr id="Correo_1">
                                        <td>chor_bajado@chor.com</td>
                                        <td><i class="fa fa-check-circle" style="color: rgb(76, 175, 80);"></i></td>
                                    </tr>
                                    <tr id="Correo_2">
                                        <td>chor_bajado@chor.com</td>
                                        <td><i class="fa fa-check-circle" style="color: rgb(76, 175, 80);"></i></td>
                                    </tr>
                                    <tr id="Correo_3">
                                        <td>chor_bajado@chor.com</td>
                                        <td><i class="fa fa-check-circle" style="color: rgb(76, 175, 80);"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                    </div>
                    <button type="button" class="btn btn-default c_modalDismiss c_AcceptModal" autofocus onclick="enviar()" id="i_botonModalEnviar">Aceptar</button>
                    <button type="button" class="btn btn-default c_modalDismiss c_Cancel" data-dismiss="modal" id="i_botonModalCancelar">Cancelar</button>
                    <button type="button" class="btn btn-default c_modalDismiss c_AcceptModal" data-dismiss="modal" autofocus id="i_botonModalTerminar">Terminar</button>
                </div>
            </div>
        </div>

    </div><!--/ModalMessageEnvioCorreo -->
    
        
    

   <script charset="utf-8">

        //var gm_correos = new Map();  // mapa que guarada los correos con su respectivo cuerpo del mail y asunto
        //var gd_inputCorreo = $("#i_inputCorreo");
        //var gd_tableCorreos = $("#i_tableCorreosAgregafos");
        var gn_folioFactura = 0;

        var gd_modalAgregarMsjExtra = null;
        var gd_AgregarAsunto = null;
        var gd_AgregarCuerpo = null;
        var gd_ClickedRow = null;
        var gd_correosContainer = null
        var gd_inputCorreo = null;
        var gd_modalCorreoInvalido = null;
        var gd_modalEnviarCorreosCubitoWaiting = null;
        var gd_modalEnviarCorreosCancelar = null;
        var gd_modalEnviarCorreosEnviar = null;
        var gd_modalEnviarCorreosTerminar = null;
        var gd_modalReporteCorreos = null;
        var gd_modalContentCorreos = null;
        var gn_newFolio = null;
        var gd_modalEnviarCorreosMsj = null;
        var gd_modalEnviarCorreosMsj2 = null;
        var gd_modalEnviarCorreosTable = null;
        var ga_correos = [];
        var gn_ui = null; //0:= guardar 1:= guardar y trimbrar

        $("#i_btnRegresar").click(function () {
            var options = this.innerHTML;
            if(options=="Regresar")
            {
                regresar();
            } else {
                location.href = "index.html";
            }
        });

        function regresar()
        {
            var id = "<?php  echo $_POST['id'];  ?>";
            var folio = "<?php echo $_POST['folioFactura']; ?>";
            location.href = "facturar.html?id="+id+"&folio="+folio;
        }

        function showFactura()
        {
            $.post("php/getXMLfilename.php", { folio: gn_folioFactura }, function (xmlFile) {
                if (xmlFile != '0') {
                    var tmp = xmlFile.split(".xml");
                    var file = tmp[0];
                    window.open("printPDF.php?pdfFile="+file+".pdf");
                }
            });


        }

        function validateEmail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }

        //$("#i_btnAceptarMensaje").click(function () {
        //    var email = $("#i_correoEnviarHeader").val();
        //    var asunto = $("#i_inputAsunto").val();
        //    var cuerpo = $("#i_inputCuerpo").val();
        //    var lj_mailParams = { asunto: asunto, cuerpo: cuerpo };
        //    gm_correos.set(email.trim(), JSON.stringify(lj_mailParams));
        //    $("#i_modalAgregarMensajeExtra").modal("hide");

        //});

        //$(document).on("click", ".c_btnSetMsg", function () {
        //    var ls_temp = $(this).attr("id");
        //    var la_a = ls_temp.split("i_btnSetMsg_");
        //    var email = la_a[1]; email = email.trim();
        //    var ls_get = gm_correos.get(email);

        //    if (ls_get == 0) {
        //        $("#i_inputAsunto").val('');
        //        $("#i_inputCuerpo").val('');
        //    }
        //    else {
        //        var lj_mailParams = JSON.parse(ls_get);
        //        var asunto = lj_mailParams.asunto;
        //        var cuerpo = lj_mailParams.cuerpo;
        //        $("#i_inputAsunto").val(asunto);
        //        $("#i_inputCuerpo").val(cuerpo);
        //    }
        //    var header = $("#i_correoEnviarHeader");
        //    header.html("Mensaje para " + email);
        //    header.val(email.trim());
        //    $("#i_modalAgregarMensajeExtra").modal();


        //});

        //$(document).on("click",".c_btnTrash",function () {
        //    var ls_temp = $(this).attr("id");
        //    var la_a = ls_temp.split("i_btnTrash_");
        //    var email = la_a[1]; email = email.trim();
        //    gm_correos.delete(email);
        //    update_emailList();
        //});

        //$("#i_btnAgregarCorreo").click(function () {
        //    var email = gd_inputCorreo.val();
        //    email = email.trim();
        //    if (!validateEmail(email)) { $("#i_modalCorreoInvalido").modal("show"); } else {
        //        gm_correos.set(email.trim(), 0);
        //        gd_inputCorreo.val("");
        //        update_emailList();

        //    }

        //});

        //function update_emailList() {
        //    var buff = [];
        //    for (var key of gm_correos.keys()) {
        //        buff.push('<tr><td style="color:white; text-align:center;"><span style="margin-right:5px; display:inline;">'+key+'</span></td><td><div class="btn-group-horizontal"><button id="i_btnTrash_'+key+'" class="c_btnTrash btn btn-default c_Cancel"><i class="fa fa-trash" style = "font-size:23px" aria-hidden="true"></i></button><button style="margin-left:7%;" type="button" id="i_btnSetMsg_'+key+'" class="c_btnSetMsg btn btn-default c_Accept"><i class="fa fa-envelope-o" style = "font-size:23px;"aria-hidden="true"></i></button></div></td></tr>');

        //    }
        //    gd_tableCorreos.html(buff.join(""));
       //}

       //FUNCIONALIDAD ENVIAR CORREOS
        function cargarCorreosDefault(idcliente) {
            gd_correosContainer.load("php/cargarCorreosDefaultGT.php", { ID: idcliente });
        }

        function agregar_mensaje(self){
            var ld_row = $(self).parent().parent().children();
            //2:= body 3:= subject
            var ld_body =  ld_row.eq(2).html();
            var ld_subject = ld_row.eq(3).html();

            gd_modalAgregarMsjExtra.modal("show");
            gd_AgregarCuerpo.val(ld_body);
            gd_AgregarAsunto.val(ld_subject);
            gd_ClickedRow = self;
        }

        function eliminar_correo(self){
            $(self).parent().parent().remove();
        }

        function agregarBodySubject() {
            var ld_row = $(gd_ClickedRow).parent().parent().children();
            //2:= body 3:= subject
            ld_row.eq(2).html(gd_AgregarCuerpo.val());
            ld_row.eq(3).html(gd_AgregarAsunto.val());

            gd_modalAgregarMsjExtra.modal("hide");
            gd_ClickedRow = null;
        }

        function enviar() {
            gd_modalEnviarCorreosCubitoWaiting.show();
            gd_modalContentCorreos.hide();

            //CORREOS: '{"Correos":' + JSON.stringify(la_correos) + '}', FOLIO: folio, ID: ls_idCliente, UITYPE: 0

            ga_correos = JSON.stringify(ga_correos);
            ga_correos = '{"Correos":' + ga_correos + '}';
            var ls_idUsuario = "<?php echo $_POST['id']; ?>";
            $.post("php/enviarCorreosJAGS.php", { ID: ls_idUsuario, UITYPE: gn_ui, FOLIO: gn_folioFactura, CORREOS: ga_correos }, function (data, status) {
                console.log(data);
                gn_ui = null;
                gd_modalEnviarCorreosCubitoWaiting.hide();
                gd_modalContentCorreos.show();
                gd_modalEnviarCorreosCancelar.hide();
                gd_modalEnviarCorreosEnviar.hide();
                gd_modalEnviarCorreosTerminar.show();

                var res = data.split("????");
                var size = parseInt(res.length);
                res = JSON.parse(res[size - 1]);
                size = parseInt(res.Correos.length);
                var ln_i =0;
                for (ln_i = 0; ln_i < size; ln_i++) {
                    var ld_mail_status = gd_modalEnviarCorreosTable.find("#Correo_" + ln_i).find("i");
                    if (parseInt(res.Correos[ln_i][1]) < 0) {
                        ld_mail_status.css("color", "red");
                        ld_mail_status.removeClass();
                        ld_mail_status.addClass("fa fa-times-circle")
                    }
                    else {
                        ld_mail_status.css("color", "blue");
                        ld_mail_status.removeClass();
                        ld_mail_status.addClass("fa fa-check-circle")
                    }
                }

                gn_newFolio = null;
                ga_correos = [];
            });
            //ENVIAR CORREOS JAGS
        }

        function agregarCorreo() {
            var ls_correo = gd_inputCorreo.val();

            if (!validateEmail(ls_correo)) {
                gd_inputCorreo.val("");
                gd_modalCorreoInvalido.modal("show");
                return;
            }
            gd_correosContainer.prepend('<tr>'+
                '<td class="c_mail">'+ ls_correo +'</td>'+
                '<td> <button class="btn btn-default c_Accept" onclick="agregar_mensaje(this)"><i class="fa fa-envelope" style = "font-size:23px;" aria-hidden="true"></i></button>  <button class="btn btn-default c_Cancel" style = "margin-left:5%;" onclick="eliminar_correo(this)"><i class="fa fa-trash" style = "font-size:23px;" aria-hidden="true"></i></button> </td>'+
                '<td class="c_body" hidden></td><td class="c_subject" hidden></td>'+
          '</tr>');
            gd_inputCorreo.val("");
        }
       //FUNCIONALIDAD ENVIAR CORREOS


        //var msj_pendiente = '';
        //var msj_timbrado = '';
        var msj_timbrada = '';
       
       

        $(document).ready(function () {
            // check_postVars();
            $('[data-toggle="popover"]').popover();

            gd_modalAgregarMsjExtra = $("#i_modalAgregarMensajeExtra");
            gd_AgregarAsunto = $("#i_inputAsunto");
            gd_AgregarCuerpo = $("#i_inputCuerpo");
            gd_correosContainer = $("#i_tableCorreosDefault");
            gd_inputCorreo = $("#i_inputCorreo");
            gd_modalCorreoInvalido = $("#i_modalCorreoInvalido");
            gd_modalEnviarCorreosCubitoWaiting = $("#i_cubitoWaiting");
            gd_modalEnviarCorreosCancelar = $("#i_botonModalCancelar");
            gd_modalEnviarCorreosEnviar = $("#i_botonModalEnviar");
            gd_modalEnviarCorreosTerminar = $("#i_botonModalTerminar");
            gd_modalReporteCorreos = $("#i_modalEnvioCorreo");
            gd_modalContentCorreos = $("#i_contentModalEnviarCorreo");
            gd_modalEnviarCorreosMsj = $("#i_h3ModalMessageEnvioCorreo");
            gd_modalEnviarCorreosMsj2 = $("#i_h3ModalMessageEnvioCorreo2");
            gd_modalEnviarCorreosTable = $("#i_enviarCorreosConfirmacion");
        //    $.post("php/getMSJDefault.php", function (data) {
        //        var lj_data = JSON.parse(data);
        //        lj_data = lj_data.result[0];
        //        msj_pendiente = lj_data.mensaje_generada;
        //        msj_timbrado = lj_data.mensaje_timbrada;
        //    });

        //    $.post("php/getCorreoDefaultTimbrada.php", function (data) {
        //        var lj_result = JSON.parse(data);
        //        var lj_correos = lj_result.result0; var len = lj_correos.length; var i = 0;
        //        var asunto = "Comprobante fiscal"; var cuerpo = lj_result.result1[0].mensaje_timbrada;
        //        var tmp = {asunto:asunto,cuerpo:cuerpo};
        //        for (; i < len; i++) {
        //            gm_correos.set(lj_correos[i].email,JSON.stringify(tmp));
        //        }
        //        update_emailList();
            //    });




            gd_modalEnviarCorreosTerminar.click(function(){
            
                if(is_timbrada){
                    $("#i_btnContinuar").remove();
                   // location.href = "index.html";
                    location.href = "opcionesTimbrado.html?id=<?= $_POST['id'] ?>&folio=" + gn_folioFactura;
                }
            });

            cargarCorreosDefault(<?php  echo $_POST['id'];  ?>);

            $(".alert").hide();
            $.post("php/getExpedicion.php", function (data) {
                var lj_response = JSON.parse(data);
                lj_response = lj_response.result;
                var len = lj_response.length; var i = 0; var buf = [];
                for (; i < len; i++)
                { buf.push("<option value='" + lj_response[i].idexpedicion + "'>" + lj_response[i].domicilio + "</option>"); }

                $("#i_selectLugares").html(buf.join(""));
            });
        });



        var gn_folioFactura = 0;
        function clear_forJson(s)
        {
            s = s.replace(/\\n/g, "\\n")
               .replace(/\\'/g, "\\'")
               .replace(/\\"/g, '\\"')
               .replace(/\\&/g, "\\&")
               .replace(/\\r/g, "\\r")
               .replace(/\\t/g, "\\t")
               .replace(/\\b/g, "\\b")
               .replace(/\\f/g, "\\f");
            // remove non-printable and other non-valid JSON chars
            s = s.replace(/[\u0000-\u0019]+/g, "");
            return s;
        }

        var j = 0;
        var is_timbrada = false;
        $("#i_btnContinuar").click(function () {
            if ($("#i_radioGyT").prop("checked"))
            {
                gn_ui = 1; //0:= Guardar 1:= Guardar y Timbrar
                var post = '<?php echo $_POST["id"];echo"%&";echo $_POST["factura_params"];echo"%&"; ?>';
                var la_post = post.split("%&");
                var idCliente = la_post[0];
                var factura_params = la_post[1];
                var idLugarExp = $("#i_selectLugares").val();
                
                //var folio = la_post[2];
                $("button").prop("disabled", true);
                var no_cuneta = "<?php echo $_POST['noCuenta']; ?>";

                        gd_modalReporteCorreos.modal('show');
                        gd_modalEnviarCorreosCubitoWaiting.show();
                        gd_modalContentCorreos.hide();
                        gd_modalEnviarCorreosTerminar.hide();
                        gd_modalEnviarCorreosEnviar.show();
                        gd_modalEnviarCorreosCancelar.show();
                        gd_modalEnviarCorreosTable.hide();
                        var fp = factura_params;

                        $.post("Facturacion/cfdi3.3/facturarCliente33.php", { noCuenta: no_cuneta, idExpedicion: idLugarExp, idCliente: idCliente, factura_params: fp }, function (data) {
                            console.log(data);
                            data = data.trim();
                            var lj_respuesta = JSON.parse(data);
                            $("#i_goOn").show();
                            $("#i_btnContinuar").remove();
                    gn_folioFactura = lj_respuesta.folio;
                    $("button").prop("disabled", false);

                    if (lj_respuesta.status == '0'){
                        gd_modalEnviarCorreosCubitoWaiting.hide();
                        gd_modalContentCorreos.show();
                        gd_modalEnviarCorreosMsj.text("Algo salió mal en el proceso de timbrado");
                        gd_modalEnviarCorreosMsj2.text("Mas información: " + lj_respuesta.respuesta);
                        gd_modalEnviarCorreosTerminar.show();
                        gd_modalEnviarCorreosEnviar.hide();
                        gd_modalEnviarCorreosCancelar.hide();
                    }
                    if (lj_respuesta.status == '1') {
                        is_timbrada = true;
                        var rows = gd_correosContainer.children();
                        if (rows.length === 0) {
                            gd_modalEnviarCorreosCubitoWaiting.hide();
                            gd_modalContentCorreos.show();
                            gd_modalEnviarCorreosEnviar.hide();
                            gd_modalEnviarCorreosCancelar.hide();
                            gd_modalEnviarCorreosTerminar.show();
                            gd_modalEnviarCorreosMsj.text("La factura ha sido timbrada con éxito.");
                            gd_modalEnviarCorreosMsj2.hide();
                            return;
                        }


                        gd_modalEnviarCorreosCubitoWaiting.hide();
                        gd_modalContentCorreos.show();
                        gd_modalEnviarCorreosMsj.text("La factura fue timbrada con éxito");

                        ga_correos = [];
                        var la_tableContents = [];
                        la_tableContents[0] = '<div class="table-responsive"><table class="table"><thead><tr><td>Correo</td><td>Status</td></tr></thead>';
                        var ln_iterator = 1;
                        rows.each(function () {
                            var la_individual = [];
                            var ld_children = $(this).children();

                            la_individual.push(ld_children.eq(0).html());
                            la_tableContents[ln_iterator] = '<tr id="Correo_' + (ln_iterator - 1) + '"><td>' + ld_children.eq(0).html() + '</td><td><i class="fa fa-question-circle" style="color:gray;"></i></td></tr>';
                            la_individual.push("");
                            la_individual.push(ld_children.eq(2).html());
                            la_individual.push(ld_children.eq(3).html());

                            ga_correos.push(la_individual);
                            ln_iterator++;
                        });
                        la_tableContents[parseInt(la_tableContents.size) - 1] = '</tbody></table>';
                        gd_modalEnviarCorreosTable.html(la_tableContents.join(""));
                        gd_modalEnviarCorreosTable.show();

                    }
                });


                /*
                var la_correos = [];
                gm_correos.forEach(function (value, key, gm_correos) {
                    var lj_params = JSON.parse(value);
                    var asunto = lj_params.asunto;
                    var cuerpo = lj_params.cuerpo;
                    la_correos.push({ mail: key, asunto: asunto, cuerpo: cuerpo });
                })*/
            }
            if ($("#i_radioGuardar").prop("checked")) {
                gn_ui = 0; //0:= Guardar 1:= Guardar y Timbrar
                gd_modalReporteCorreos.modal('show');
                gd_modalEnviarCorreosCubitoWaiting.show();
                gd_modalContentCorreos.hide();
                gd_modalEnviarCorreosTerminar.hide();
                gd_modalEnviarCorreosEnviar.show();
                gd_modalEnviarCorreosCancelar.show();
                gd_modalEnviarCorreosTable.hide();

                var post = '<?php echo $_POST["id"];echo"%&";echo $_POST["factura_params"]; ?>';

                var post_params = post.split("%&");
                var ls_idCliente = post_params[0];
                var ls_searchParams = post_params[1];
                var factura_params = '<?php echo $_POST["factura_params"]; ?>';

                $.post("php/guardarFacturaPendiente.php", { idCliente: ls_idCliente, factura_params: factura_params }, function (data, status) {
                 
                    gn_folioFactura = data;
                    //ENVIAR CORREOS JAGS
                    $("#i_radioGuardar").prop("disabled",true);
                    $("#i_goOn").show();
                    $("#i_btnContinuar").remove();
                    var rows = gd_correosContainer.children();
                    if (rows.length === 0) {
                        gd_modalEnviarCorreosCubitoWaiting.hide();
                        gd_modalContentCorreos.show();
                        gd_modalEnviarCorreosEnviar.hide();
                        gd_modalEnviarCorreosCancelar.hide();
                        gd_modalEnviarCorreosTerminar.show();
                        gd_modalEnviarCorreosMsj.text("La factura ha sido generada con éxito.");
                        gd_modalEnviarCorreosMsj2.hide();
                        return;
                    }

                    gn_newFolio = parseInt(data);
                    gd_modalEnviarCorreosCubitoWaiting.hide();
                    gd_modalContentCorreos.show();

                    gd_modalEnviarCorreosMsj.text("La factura ha sido generada con éxito.");
                    gd_modalEnviarCorreosMsj2.text("¿Esta seguro que desea enviar la factura a los siguientes correos?")
                    ga_correos = [];
                    var la_tableContents = [];
                    la_tableContents[0] = '<div class="table-responsive"><table class="table"><thead><tr><td>Correo</td><td>Status</td></tr></thead>';
                    var ln_iterator = 1;
                    rows.each(function () {
                        var la_individual = [];
                        var ld_children = $(this).children();

                        la_individual.push(ld_children.eq(0).html());
                        la_tableContents[ln_iterator] = '<tr id="Correo_' + (ln_iterator - 1) + '"><td>'+ ld_children.eq(0).html() +'</td><td><i class="fa fa-question-circle" style="color:gray;"></i></td></tr>';
                        la_individual.push("");
                        la_individual.push(ld_children.eq(2).html());
                        la_individual.push(ld_children.eq(3).html());

                        ga_correos.push(la_individual);
                        ln_iterator++;
                    });
                    la_tableContents[parseInt(la_tableContents.size) - 1] = '</tbody></table>';
                    gd_modalEnviarCorreosTable.html(la_tableContents.join(""));
                    gd_modalEnviarCorreosTable.show();
                });
            } // fin de radioGuardar
        });

    </script>
    
</body>
</html>