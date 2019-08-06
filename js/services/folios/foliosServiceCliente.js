function foliosService($http){
this.getInitFacturas = function(idCliente){
return $http.get("php/new/folios/fetchData.php",{params:{accion:"initFacturasClientes",idCliente:idCliente}});

}
this.search = function(filtros,idCliente){
    var getParams = $.extend({accion:"filtrarClientes",idCliente:idCliente},filtros);
    return $http.get("php/new/folios/fetchData.php",{params:getParams});
    
    }
    this.getPendings = function(idCliente){
        return $http.get("php/new/folios/fetchData.php",{params:{accion:"obtenerPendientes",idCliente:idCliente}});
        
    }
    this.excelExport = function(folios){
      return  $http.post("php/xlsGen2.php",{folios:folios});
    }
    this.massExport = function(files){
        return  $http.post("php/new/exportacionMasiva2.php",{xml_files:files});
    }

}