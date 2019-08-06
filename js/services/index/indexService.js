function indexService($http){
    this.obtenerInitData = function(){
      return  $http.get("php/new/index/fetchData.php?accion=initData");

    }
    this.buscarCliente = function(q){
        return  $http.get("php/new/index/fetchData.php",{params:{accion:"buscarCliente",q:q}});
        
        
    }
}