(function() {
    Date.prototype.toYMD = Date_toYMD;
    function Date_toYMD() {
        var year, month, day;
        year = String(this.getFullYear());
        month = String(this.getMonth() + 1);
        if (month.length == 1) {
            month = "0" + month;
        }
        day = String(this.getDate());
        if (day.length == 1) {
            day = "0" + day;
        }
        return year + "-" + month + "-" + day;
    }
})();

function ctrlFolios($scope,foliosService){
          var g_idCliente = idCliente;
var init_data = [];
    $scope.facturas = [];
    $scope.filtros = {fechaDesde:"",fechaHasta:"",montoDesde:0,montoHasta:0,q:""};
    foliosService.getInitFacturas(g_idCliente).then(function(resp){
        var data = resp.data;
$scope.facturas = data;
init_data = angular.copy(data);

    });
    $scope.search = function(){
      var filtros = angular.copy($scope.filtros);
       if(filtros.fechaDesde!=""&&filtros.fechaHasta!=""&&filtros.fechaDesde!=undefined&&filtros.fechaHasta!=undefined){
      filtros.fechaDesde = filtros.fechaDesde.toYMD();  
      filtros.fechaHasta = filtros.fechaHasta.toYMD();  
       }else{
        filtros.fechaHasta ="";
        filtros.fechaDesde ="";
       }
       if(filtros.montoDesde>=0 && filtros.montoHasta>=0){  
      foliosService.search(filtros,g_idCliente).then(function(resp){
     $scope.facturas = resp.data;
    });
    }else{
        toastr.info("montos incorrectos");
    }
      

    }
    $scope.mostratGuardadas = function(){

        foliosService.getPendings(g_idCliente).then(function(resp){

            $scope.facturas = resp.data;
            
        });
    }
$scope.reset = function(){
    $scope.facturas = init_data;
    
}
$scope.mostrarCanceladas = function(){
    var facturas = init_data; var len = facturas.length; var i = 0;
    var filtered = [];
    for(;i<len;i++){
        var f = facturas[i];
        var fecha = f.fecha_cancelada;
        if(fecha!="0"&&fecha!=null){
           filtered.push(f);
        }
    }
    $scope.facturas = filtered;
}
$scope.mostarTimbradas  = function(){
    var facturas = init_data; var len = facturas.length; var i = 0;
    var filtered = [];
    for(;i<len;i++){
        var f = facturas[i];
        var fecha = f.fecha_cancelada;
        if(fecha!="0"&&fecha==null){
           filtered.push(f);
        }
    }
    $scope.facturas = filtered;

}
$scope.excelExport = function(){
    foliosService.excelExport($scope.facturas).then(function(resp){
     window.open("PHPExcel/files/"+resp.data);
        
    });

}
$scope.exportacionMasiva = function(){
    var files = []; var facturas = $scope.facturas; var len = facturas.length; var i = 0;
    for(;i<len;i++){
        files.push(facturas[i].xml_file);
    }
    foliosService.massExport(files).then(function(resp){
        var data = resp.data;
        if(data=="failed"){
toastr.error("Upsss... ha ocurrido un error, contacta a soporte");
        }else{
        window.open("excelExport/"+resp.data);
        }
    });
}
$scope.showDoc = function(f){
    window.open("../" + g_clientAndSystemFilesFolder + "Facturacion/facturas/timbradas/pdf/" + f.pdf_file);

}
$scope.nextStep = function(f){
    if(f.fecha_cancelada==null){
     if(f.idSerie==null){
    window.open("opcionesTimbrado.html?id="+f.idcliente+"&folio="+f.folio+"&idSerie="+f.idSerie);
     }else{
        window.open("opcionesTimbrado33.html?id="+f.idcliente+"&folio="+f.folio+"&idSerie="+f.idSerie);
        
     }
}
    if(f.fecha_cancelada!=null&&f.fecha_cancelada!="0"){
        window.open("opcionesCancelado.html?id="+f.idcliente+"&folio="+f.folio);
        
    }
    if(f.fecha_cancelada=='0'){
        window.open("facturar-ng33.html?id="+f.idcliente+"&folio="+f.folio+"&tipo=pendiente&idPendiente="+f.id);
        
    }        
    
        

    //opcionesCancelado.html?
}
}
