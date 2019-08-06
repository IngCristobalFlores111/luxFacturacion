function foliosService($http){
this.getInitFacturas = function(){
return $http.get("php/new/folios/fetchData.php",{params:{accion:"initFacturas"}});

}
this.search = function(filtros){
    var getParams = $.extend({accion:"filtrar"},filtros);
    return $http.get("php/new/folios/fetchData.php",{params:getParams});
    
    }
    this.getPendings = function(){
        return $http.get("php/new/folios/fetchData.php",{params:{accion:"obtenerPendientes"}});
        
    }
    this.excelExport = function(folios){
      return  $http.post("php/xlsGen2.php",{folios:folios});
    }
    this.massExport = function(files){
        return  $http.post("php/new/exportacionMasiva2.php",{xml_files:files});
    }

}