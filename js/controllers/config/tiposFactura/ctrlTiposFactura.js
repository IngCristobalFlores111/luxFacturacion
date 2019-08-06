function ctrlTiposFactura($scope,tiposFacturasService){
$scope.params = {tipoFactura:0,ISR:15};
$scope.cambio = {ISR:false,tipoFactura:false};

$scope.onChangeTipoFactura = function(){
    $scope.cambio.tipoFactura = true;
}
$scope.onChangeISR = function(){
    $scope.cambio.ISR = true;
}
$scope.actualizar = function(){
    var cambio = $scope.cambio;
    if(cambio.ISR){
        var ISR = $scope.params.ISR;
        if(ISR>0){
        tiposFacturasService.cambiarISR(ISR).then(function(resp){
            var data = resp.data;
            if(data.exito){
                toastr.success("El % de ISR se ha actualizado con exito");
                $scope.cambio = {ISR:false,tipoFactura:false};
            }else{
                toastr.error(data.errores);
            }
        });
    }else{
    toastr.info("Tasa de ISR tiene que ser mayor que cero");
    }
    }
    if(cambio.tipoFactura){
        var tipoFactura = $scope.params.tipoFactura;
        tiposFacturasService.cambiarTipoFactura(tipoFactura).then(function(resp){
            var data = resp.data;
            if(data.exito){
                toastr.success("Se ha actualizado el tipo de factura exitosamente");
                $scope.cambio = {ISR:false,tipoFactura:false};
                
            }else{
                toastr.error(data.errores);
                
            }
        });
    }

    

}


tiposFacturasService.obtenerTipoFacturaActual().then(function(resp){
   var data = resp.data;
    if(data.exito){
        var data = data.data;
       $scope.params.tipoFactura = data.tipoFactura;
       $scope.params.ISR = data.retencionISR;
       
   }

});


}