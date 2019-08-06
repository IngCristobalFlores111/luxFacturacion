function opcionesTimbradoService($http){
    
  this.obtenerRegimenes = function(){
      return $http.get("php/new/opcionesUsuario/fetchData.php",{params:{accion:"regimenes"}});
  }
  this.obtenerConfigUsr = function(){
    return $http.get("php/new/opcionesUsuario/fetchData.php",{params:{accion:"config"}});
    
  }
  this.modificarTipoFactura =function(params){
    return $http.post("php/new/opcionesUsuario/accionOpcionesUsuario.php?accion=modificarTipoFactura",params);

  }
}