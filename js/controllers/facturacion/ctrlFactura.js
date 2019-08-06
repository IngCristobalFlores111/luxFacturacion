function ctrFactura($scope,facturaService){
    $scope.conceptos =[];
    $scope.atajo = {q:"",atajos:[]};
    $scope.unidades = [];
    $scope.cliente = {};
    $scope.config = {};
    $scope.params = { serie: "A", folio: "0", importe: 0, descuento: 0, metodosPago: "", formasPago: "", numCuenta: "" };

    if (metodoDePago) //Global variable with the GET['metodoPago'] value
        $scope.params.metodosPago = metodoDePago;
    
    if (formaDePago) //Global variable with the GET['formaPago'] value
        $scope.params.formasPago = formaDePagp;
    

    $scope.metodosPago = [];
    $scope.formasPago = [];
    $scope.pedimentos = [];
    var accion=0; // 0->agregar , 1->editar
    $scope.monedas = [];
    $scope.series = [];
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
    facturaService.getCurrencies().then(function(resp){
        $scope.monedas = resp.data;
        $scope.config.moneda= $scope.monedas[0].idMoneda.toString();
        
        setTimeout(function() {
            $scope.config.moneda= $scope.monedas[0].idMoneda.toString();
            
        }, 1000);
     
    });
    $scope.nuevoConcepto = {importe:0,precio:0,cantidad:0,unidad:"",descripcion:"",noSerie:"",isImportado:false,pedimento:null};
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
    if(data==0){
        $scope.config = {tipo:"ingreso",retencion_ISR:15,tipo_factura:0,retener_ISR:false,retencion_IVA:false}
    }else{    
    $scope.config= data;
    $scope.config.tipo ="ingreso";

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
    facturaService.getParams().then(function(resp){
var data = resp.data;
$scope.metodosPago =data.metodos;
$scope.formasPago = data.formas;
$scope.pedimentos = data.pedimentos;
$scope.unidades = data.unidades;

    });
    $scope.setPedimento = function(p){
$scope.nuevoConcepto.pedimento = p;
    }
    $scope.unselectPedimento = function(){
        $scope.nuevoConcepto.pedimento = null;
    }
    $scope.buscarAtajo = function(){
var q = $scope.atajo.q;
facturaService.searchShortCut(q).then(function(resp){
$scope.atajo.atajos = resp.data;

});

    }
    $scope.agregarAtajo = function(atajo){
        $scope.nuevoConcepto = atajo;
        var m = atajo.medida;
        if($scope.unidades.indexOf(m)==-1){
            $scope.unidades.push({nombre:m,id:0});
        }
        $scope.nuevoConcepto.unidad = m;
    }
    $scope.calcularConcepto = function(){
var c = $scope.nuevoConcepto;
        var importe = parseFloat(c.precio)*parseFloat(c.cantidad);
        c.importe = Math.round(importe * 100) / 100;
    }
    $scope.agregarConcepto = function(){
        if(accion==0){
    var concepto = $scope.nuevoConcepto;
    if(concepto.isImportado){
       setTimeout(function() {
        $('[data-toggle="popover"]').popover();
       }, 1000);    
        
    }
   
    if(concepto.descripcion==""||concepto.noSerie==""||concepto.unidad==""||concepto.cantidad<0||concepto.precio<0||concepto.importe<0){
    toastr.info("Concepto con información invalida");
    }else{
    $scope.conceptos.push(angular.copy(concepto));
    facturaService.saveSessionConcepts($scope.conceptos).then(function(resp){
      
        $scope.nuevoConcepto = {precio:0,cantidad:0,unidad:"",descripcion:"",noSerie:"",isImportado:false,pedimento:null};
      
    });

}
        }else{
            facturaService.saveSessionConcepts($scope.conceptos).then(function(resp){
                
                  $scope.nuevoConcepto = {precio:0,cantidad:0,unidad:"",descripcion:"",noSerie:"",isImportado:false,pedimento:null};
                accion = 0;
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
        $scope.total = $scope.subtotal+$scope.iva;
        return  $scope.total;
    }
    $scope.getRetencionIva= function(){
        return $scope.iva*(2/3);
    }
    $scope.getRetencionIsr = function(){

        return ($scope.subtotal - $scope.params.descuento)*($scope.config.retencion_ISR/100);
    }

    $scope.modConcepto = function(c){
        $scope.nuevoConcepto = c;
        accion= 1;
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
        $scope.nuevoConcepto = {importe:0,precio:0,cantidad:0,unidad:"",descripcion:"",noSerie:"",isImportado:false,pedimento:null};
        
    }
$scope.mostrarVistPrevia = function(){

    modalVistaPrevia.modal("show");
    //vistaPreviaEmbed
    facturaService.generatePreview($scope.cliente.idcliente).then(function(resp){
     
        vistaPreviaEmbed.attr("src","https://www.luxline.com.mx/sanAngelprod/LuxFacturacion/php/new/facturar/previe_newbie.pdf");
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
    if($scope.conceptos.length>0&& params.metodosPago!=""&&params.formasPago!="")
    {
    location.href="GuardarTimbra-ng.html?id="+idCliente+"&"+$.param(params)+"&"+$.param($scope.config);
}else{
    toastr.info("Valores invalidos o Sin conceptos");
}
}
}