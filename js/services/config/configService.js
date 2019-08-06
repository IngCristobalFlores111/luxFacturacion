function configService($http){
this.buscarClaveProd=function(q){
return $http.get("php/new/config/fetchData.php",{params:{accion:"buscarClaveProdServ",q:q}});

}
this.obtenerUnidadesActuales = function(){
    return $http.get("php/new/config/fetchData.php",{params:{accion:"unidadesActuales"}});   
}
this.agregarAtajo = function(atajo){
    return $http.post("php/new/config/accionConfig.php?accion=agregarAtajo",atajo);
    
}
this.obtenerAtajos = function(){
    return $http.get("php/new/config/fetchData.php",{params:{accion:"atajos"}});
    
}
this.modificarAtajo = function(atajo){
    return $http.post("php/new/config/accionConfig.php?accion=modificarAtajo",atajo);
    
}
this.eliminarAtajo = function(idAtajo){
    return $http.post("php/new/config/accionConfig.php?accion=eliminarAtajo",{idAtajo:idAtajo});
    
}

}