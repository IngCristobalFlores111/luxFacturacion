function tiposFacturasService($http){
    this.obtenerTipoFacturaActual = function(){
        return $http.get("php/new/config/fetchData.php",{params:{accion:"tipoFacturaActual"}});

    }
this.cambiarISR = function(ISR){
return $http.post("php/new/config/accionConfig.php?accion=actualizarISR",{ISR:ISR});
}
this.cambiarTipoFactura = function(tipoFactura){
    return $http.post("php/new/config/accionConfig.php?accion=actualizarTipoFactura",{tipoFactura:tipoFactura});
    
}

}