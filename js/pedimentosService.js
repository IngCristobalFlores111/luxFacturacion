function pedimentosService($http){
 this.buscarAduanas=function(q){
     return $http.get("php/new/pedimentos/fetchData.php",{params:{accion:"aduanas",q:q}});

 }   
 this.obtenerPedimentos = function(){
    return $http.get("php/new/pedimentos/fetchData.php",{params:{accion:"pedimentos"}});
    
 }
 this.altaPedimento = function(idAduana,patente,ano){
    return $http.post("php/new/pedimentos/accionPedimento.php?accion=altaPedimento",{idAduana:idAduana,patente:patente,ano:ano});

 }
}