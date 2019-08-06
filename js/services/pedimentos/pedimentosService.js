function pedimentosService($http){
    this.initData=function(q){
        return $http.get("php/new/pedimentos/fetchData.php",{params:{accion:"initData"}});
   
    }   
 this.buscarAduanas=function(q){
     return $http.get("php/new/pedimentos/fetchData.php",{params:{accion:"aduanas",q:q}});

 }   
 this.obtenerPedimentos = function(){
    return $http.get("php/new/pedimentos/fetchData.php",{params:{accion:"pedimentos"}});
    
 }


this.alterPedimento = function(pedimento,accion){
    return $http.post("php/new/pedimentos/accionPedimento.php?accion=alterPedimento",{accion:accion,pedimento:pedimento});

}
 this.altaPedimento = function(idAduana,patente,ano){
    return $http.post("php/new/pedimentos/accionPedimento.php?accion=altaPedimento",{idAduana:idAduana,patente:patente,ano:ano});

 }
 this.modPedimento = function(idPedimento,idAduana,patente,ano){
    return $http.post("php/new/pedimentos/accionPedimento.php?accion=modificarPedimento",{idPedimento:idPedimento,idAduana:idAduana,patente:patente,ano:ano});
    
 }
 this.eliminarPedimento= function(idPedimento){
    return $http.post("php/new/pedimentos/accionPedimento.php?accion=eliminar",{idPedimento:idPedimento});
    
 }
}