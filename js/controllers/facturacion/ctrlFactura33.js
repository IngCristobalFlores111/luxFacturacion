function ctrFactura($scope, facturaService) {

    $scope.preselectedMetodoPago = '';
    if (metodoDePago) //Global variable with the GET['metodoPago'] value
        $scope.preselectedMetodoPago = metodoDePago;

    $scope.preselectedFormaPago = '';
    if (formaDePago) //Global variable with the GET['formaPago'] value
        $scope.preselectedFormaPago = formaDePago;

    $scope.conceptos =[];
    $scope.atajo = {q:"",atajos:[]};
    $scope.unidades = [];
    $scope.cliente = {};
    $scope.config = {};
    $scope.params = {importe:0,descuento:0,metodosPago:"",formasPago:"",numCuenta:""};
    $scope.metodosPago = [];
    $scope.formasPago = [];
    $scope.pedimentos = [];
    $scope.accion=0; // 0->agregar , 1->editar
    $scope.series = [];
    $scope.modalInfoMedimentos = function(){
        bootbox.alert({
            title: "Concepto con información de aduana CFDI 3.3",
            message: `Se debe registrar el número del pedimento correspondiente a la
            importación del bien, el cual se integra de izquierda a derecha de la
            siguiente manera:
            Últimos 2 dígitos del año de validación seguidos por dos espacios, 2
            dígitos de la aduana de despacho seguidos por dos espacios, 4
            dígitos del número de la patente seguidos por dos espacios, 1 dígito
            que corresponde al último dígito del año en curso, salvo que se trate
            de un pedimento consolidado, iniciado en el año inmediato anterior
            o del pedimento original de una rectificación, seguido de 6 dígitos de
            la numeración progresiva por aduana.
            <ul>
            <li>Se debe registrar la información en este campo cuando el
            CFDI no contenga el complemento de comercio exterior (es
            una venta de primera mano nacional).</li>
            <li>Para validar la estructura de este campo puede consultar la
            documentación técnica publicada en el Portal del SAT.
            Ejemplo:
            NumeroPedimento= 10 47 3807 8003832. </li>
            </ul>
            `
            
        })
        
    }

$scope.controlRangoIsr = function(){
    var isr = parseFloat($scope.config.retencion_ISR);
    if(!isNaN(isr)){
        if(isr<0){
            $scope.config.retencion_ISR = 0;
        }
    }
    
}

    $scope.cambiarFolio = function(){
        var serie = $scope.params.serie;
        var series = $scope.series; var len = series.length;var i = 0;
        for(;i<len;i++){
           var s= series[i];
           if(s.serie==serie){
               $scope.params.folio = s.folio;
           }
        }

    }
    facturaService.obtenerSeries().then(function(resp){
        $scope.series = resp.data;
        $scope.params.serie = resp.data[0].serie;
        $scope.params.folio = resp.data[0].folio;
    });


    $scope.monedas = [];
    facturaService.getCurrencies().then(function(resp){
        $scope.monedas = resp.data;
        $scope.config.moneda= $scope.monedas[0].idMoneda.toString();
        
        setTimeout(function() {
            $scope.config.moneda= $scope.monedas[0].idMoneda.toString();
            
        }, 1000);
     
    });
    $scope.nuevoConcepto = {claveProdServ:null,importe:0,precio:0,cantidad:0,unidad:"",descripcion:"",noSerie:"",isImportado:false,pedimento:null};
    facturaService.getConceptsSession().then(function(resp){
        var data = resp.data;

        if(data!=0){
            setTimeout(function() {
                $('[data-toggle="popover"]').popover();
               }, 1000);   
$scope.conceptos = resp.data;

        }
    });
    facturaService.getConfig().then(function(resp){
    var data = resp.data;
    console.log(data);
    if(data==0){
        $scope.config = {tipo:"I",retencion_ISR:15,tipo_factura:0,retener_ISR:false,retencion_IVA:false}
    }else{    
    $scope.config= data;
    $scope.config.tipo ="I";

        if(data.retener_ISR==1){
            $scope.config.retener_ISR = true;
        }else{
            $scope.config.retener_ISR = false;
            
        }
        if(data.retencion_IVA==1){
            $scope.config.retencion_IVA = true;
        }else{
            $scope.config.retencion_IVA = false;
            
        }
    }
    });

    facturaService.getClient(idCliente).then(function(resp){
     if(resp.data==0){
       //  location.href="index.html";
     }else{
         var data = resp.data;
         $scope.cliente = data;
         $scope.cliente.domicilio = data.calle + " "+data.noExterior+" "+data.noInterior+" "+data.colonia+" "+data.municipio+","+data.estado;

     }
        
    });
    facturaService.unidadesActuales().then(function(resp){
        $scope.unidades = resp.data;
    });


    facturaService.getParams33().then(function(resp){
        var data = resp.data;

        $scope.metodosPago = data.metodos;
        $scope.formasPago = data.formas;
        $scope.pedimentos = data.pedimentos;
        //$scope.unidades = data.unidades;
        
    });


    $scope.setPedimento = function(p){
$scope.nuevoConcepto.pedimento = p;
$scope.nuevoConcepto.isImportado = true;
    }
    $scope.unselectPedimento = function(){
        $scope.nuevoConcepto.pedimento = null;
    }
    $scope.buscarAtajo = function(){
        var q = $scope.atajo.q;
        if(q === '')
            $scope.atajo.atajos = [];
        else{
            facturaService.searchShortCut(q).then(function(resp){
                    $scope.atajo.atajos = resp.data;
            });
        }
    }

    function validarConcepto(concepto){
        var msg="";
        var success=true;

        
        if(!concepto.claveProdServ || concepto.claveProdServ === undefined || concepto.claveProdServ === null){
            msg+="Debes de elegir una clave producto servicio<br>";
            success = false;
        }
        if(concepto.descripcion === "" || concepto.descripcion === undefined || concepto.descripcion === null) //||||concepto.importe<0){
        {
            msg+="Descripción no puede ser vacia<br>";
            success = false;
        }
        if(concepto.unidad === "" || concepto.unidad === undefined || concepto.unidad === null){
            msg+="Unidad no puede ser vacia<br>";
            success = false;
        }
        if(parseInt(concepto.cantidad) <= 0 || concepto.cantidad === undefined || concepto.cantidad === null){
            msg+="Cantidad tiene que se mayor a 0<br>";
            success = false; 
        }
        if(parseInt(concepto.precio) <= 0 || concepto.precio === undefined || concepto.precio === null){
            msg+="Precio tiene que se mayor a 0<br>";
            success = false; 
        }
        if(parseInt(concepto.importe) <= 0 || concepto.importe === undefined || concepto.importe === null){
            msg+="Importe tiene que se mayor a 0<br>";
            success = false; 
        }
      return {
          success:success,
          msg:msg
        };

    }
    $scope.agregarAtajo = function(atajo){
        $scope.nuevoConcepto =angular.copy(atajo);
        var m = atajo.medida;
        if($scope.unidades.indexOf(m)==-1){
            $scope.unidades.push({nombre:m,id:0});
        }
        $scope.nuevoConcepto.unidad = m;
        $scope.nuevoConcepto.claveProdServ = {codigo:atajo.claveProdServ,descripcion:atajo.nombreProdServ};
        
    }

    
    $scope.truncarCantidad = function(ctd){
        return Math.round(ctd * 1000000) / 1000000;
    }

    $scope.calcularConcepto = function(){
        var c = $scope.nuevoConcepto;
        var importe = parseFloat(c.precio)*parseFloat(c.cantidad);
        c.importe =  $scope.truncarCantidad(importe); 
    }

    $scope.agregarConcepto = function(){
        if($scope.accion==0){
                    var concepto = $scope.nuevoConcepto;
                    var validacion = validarConcepto(concepto);
                    
                        if(validacion.success){
                                if(concepto.isImportado){
                                        setTimeout(function() {
                                            $('[data-toggle="popover"]').popover();
                                        }, 1000);    
                                }

                                concepto.precio = $scope.truncarCantidad(concepto.precio);
                                
                                $scope.conceptos.push(angular.copy(concepto));
                                
                                facturaService.saveSessionConcepts($scope.conceptos).then(function(resp){
                                    $scope.prodServ.prods = [];
                                    $scope.nuevoConcepto = {claveProdServ:null,precio:0,cantidad:0,unidad:"",descripcion:"",noSerie:"",isImportado:false,pedimento:null};
                                });
                        }else{
                            toastr.info(validacion.msg);
                        }

        }
        else{
            var index = $scope.nuevoConcepto.index;
            $scope.conceptos[index] = $scope.nuevoConcepto;
            facturaService.saveSessionConcepts($scope.conceptos).then(function(resp){  
              
                $scope.nuevoConcepto = {precio:0,cantidad:0,unidad:"",descripcion:"",noSerie:"",isImportado:false,pedimento:null};
                $scope.accion = 0;
              });
        }

    }
    $scope.subtotal = 0;
    $scope.iva = 0;
    $scope.total = 0;
    $scope.getSubtotal = function(){
    var conceptos = $scope.conceptos; var len = conceptos.length;
    var i = 0;var subtotal = 0;
    for(;i<len;i++){
subtotal+=conceptos[i].importe;
    }
    $scope.subtotal = subtotal;
    return subtotal;

    }
    $scope.getSubtotalConDescuento = function(){
  return $scope.subtotal - $scope.params.descuento;

    }
    $scope.getIva= function(){
$scope.iva = ($scope.subtotal-$scope.params.descuento)*0.16;
        return $scope.iva;
    }
    $scope.getTotal= function(){
        var iva = $scope.iva;
        var total =  $scope.subtotal+iva;
        var retenido = 0;
      if($scope.config.retencion_IVA){
          retenido +=$scope.iva*(2/3);
      }
      if($scope.config.retener_ISR){
          var isrRetenido = $scope.getRetencionIsr();
          retenido+=isrRetenido;
      }
      total = total-retenido;
      $scope.total = total;
        return  total;
    }
    $scope.getRetencionIva= function(){
        return $scope.iva*(2/3);
    }
    $scope.getRetencionIsr = function(){
        var isr = $scope.config.retencion_ISR;
        return ($scope.subtotal - $scope.params.descuento)*(isr/100);
    }

    $scope.undoConcepto = function(){
     
        $scope.nuevoConcepto = {claveProdServ:null,importe:0,precio:0,cantidad:0,unidad:"",descripcion:"",noSerie:"",isImportado:false,pedimento:null};

    }

    $scope.modConcepto = function($index){  /// modificar concepto
        $scope.nuevoConcepto = angular.copy($scope.conceptos[$index]);
        $scope.nuevoConcepto.index = $index;
        $scope.accion= 1;
    }
    $scope.eliminarConcepto = function(c){
        bootbox.confirm({
            title: "¿Eliminar Concepto=",
            message: "¿Seguro que deseas eliminar este concepto?, el proceso es irreversible",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancelar'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirmar'
                }
            },
            callback: function (result) {
                if(result){
                    $scope.conceptos.splice($scope.conceptos.indexOf(c),1);
                    facturaService.saveSessionConcepts($scope.conceptos).then(function(resp){
                        
                 });
                }
            }
        });
       
    }
    $scope.deshacerNuevo = function(){
        $scope.nuevoConcepto = {claveProdServ:null,importe:0,precio:0,cantidad:0,unidad:"",descripcion:"",noSerie:"",isImportado:false,pedimento:null};
        
    }
$scope.mostrarVistPrevia = function(){

    modalVistaPrevia.modal("show");
    //vistaPreviaEmbed
    facturaService.generatePreview33($scope.cliente.idcliente).then(function(resp){
        vistaPreviaEmbed.attr("src","https://www.luxline.com.mx/joseO/LuxFacturacion/php/new/facturar/previe_newbie.pdf");
    });
}
$scope.undoFactura = function(){
    bootbox.confirm({
        message: "Seguro que deseas deshacer todo en esta factura?",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
if(result){
    $scope.conceptos =[];
}

        }
    });

}

$scope.nextStep = function(){

    var params = $scope.params;
    var error=[];
    if(params.metodosPago==""){
     error.push("<label>Debes de elegir un método de pago</label>");
    }
    if(params.formasPago==""){
        error.push("<label>Debes de elegir una forma de pago</label>");
    }
    if($scope.conceptos.length==0){
        error.push("<label>Debes de establecer al menos un concepto</label>");
    }

    if(error.length==0)
    {
        bootbox.confirm({
            title: "¿Deseas Continuar con la factura con los datos establecidos?",
            message:"Se continuará la factura con los datos introducidos",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancelar'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> OK'
                }
            },
            callback: function (result) {
                location.href="GuardarTimbra-ng33.html?id="+idCliente+"&"+$.param(params)+"&"+$.param($scope.config);

            }
        });
}else{
    bootbox.alert({message:error.join("<br>"),title:"Error"});

}
}

//new 3.3
$scope.usosCFDI = [];
facturaService.usosCFDI().then(function(resp){
    $scope.usosCFDI = resp.data;
    $scope.params.usoCFDI = "G01";    

});
$scope.prodServ = {q:"",prods:[]};
$scope.buscarProdServ = function(){
var q = $scope.prodServ.q;
q = q.trim();
if(q!=""){
    facturaService.searchClaveProdActual(q).then(function(resp){

    $scope.prodServ.prods = resp.data;
});

}else{
    $scope.prodServ.prods =[];
}

}
$scope.agregarProdServ = function(c){
$scope.nuevoConcepto.claveProdServ =c;
}
$scope.deselectClaveProd = function(){
    $scope.nuevoConcepto.claveProdServ =null;
    
}
}