function facturarService($http){
    this.getClient = function(id){

        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"obtenerCliente",id:id}});
    }
    this.getParams = function(){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"params"}});
        
    }
    this.getParams33 = function(){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"params33"}});
        
    }
    this.getConfig = function(){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"config"}});
    }
    this.searchShortCut = function(q){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"buscarAtajo",q:q}});
        
    }
    this.saveSessionConcepts = function(conceptos){
        return $http.post("php/new/facturar/accionFactura.php?accion=guardarConceptos",{conceptos:conceptos});
    }
    this.getConceptsSession = function(){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"getSessionConceptos"}});
    }
    this.generatePreview = function(idCliente){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"generarVistaPrevia",idCliente:idCliente}});
        

    }
    this.generatePreview33 = function(idCliente){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"generarVistaPrevia33",idCliente:idCliente}});
        

    }
    this.getCorreosDefault =function(){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"getCorreosDefault"}});
        
    }
    this.getLugares = function(){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"lugaresExpedicionUsr"}});
    }
    this.facturar = function(idCliente,lugarExpedicion,facturaParams){

        return $http.post("Facturacion/facturarCliente-ng.php",{idCliente:idCliente,lugarExpedicion:lugarExpedicion,facturaParams:facturaParams})
    }
    this.facturar33 = function(ambientePruebas,idCliente,lugarExpedicion,facturaParams){
        
                return $http.post("Facturacion/facturarCliente-ng33.php",{ambientePruebas:ambientePruebas,idCliente:idCliente,lugarExpedicion:lugarExpedicion,facturaParams:facturaParams})
            }
    this.getCurrencies = function(){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"monedas"}});
        
    }
    this.sendMails = function(mails,xml,pdf,tipo){

        return $http.post("php/new/facturar/accionFactura.php?accion=enviarCorreos",{emails:mails,xml:xml,pdf:pdf,tipo:tipo});
    }
    this.saveBill = function(facturaParams){

        return $http.post("php/new/facturar/accionFactura.php?accion=guardarFactura",facturaParams);
        
    }
    this.searchClaveProd = function(q){
            return $http.get("php/new/facturar/fetchData.php",{params:{accion:"buscarClaveProd",q:q}});
        

    }
    this.searchClaveProdActual=function(q){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"buscarClaveProdActuales",q:q}});
        

    }
    this.getProdsServActual = function(){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"clavesProdActuales"}});
        
    }
    this.addClaveProdActual =function(item){
        return $http.post("php/new/facturar/accionFactura.php?accion=agregarClaveProdActual",item);
        
    }
    this.eliminarClaveProdActual =function(id){
        return $http.post("php/new/facturar/accionFactura.php?accion=eliminarClaveProdActual",{id:id});
        
    }
    this.unidadesActuales = function(){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"unidadesActuales"}});
        
    }
    this.buscarUnidad = function(q){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"buscarUnidades",q:q}});
    }
    this.agregarUnidadActual = function(id){
        return $http.post("php/new/facturar/accionFactura.php?accion=agregarUnidadActual",{id:id});
        
    }
    this.eliminarUnidadActual = function(id){
        return $http.post("php/new/facturar/accionFactura.php?accion=eliminarUnidadActual",{id:id});
        
    }
    this.usosCFDI = function(){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"usosCFDI"}});
        

    }
    this.obtenerSeries = function(){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"series"}});
        
    }
    this.obtenerSeriesOriginales = function(){
        return $http.get("php/new/facturar/fetchData.php",{params:{accion:"seriesOriginal"}});
        
    }
    this.altaSerie = function(serie){
        return $http.post("php/new/facturar/accionFactura.php?accion=altaSerie",{serie:serie});
        
    }
    this.eliminarSerie = function(idSerie){
        return $http.post("php/new/facturar/accionFactura.php?accion=eliminarSerie",{idSerie:idSerie});
        
    }
    this.modificarSerie = function(idSerie,serie){
        return $http.post("php/new/facturar/accionFactura.php?accion=modSerie",{idSerie:idSerie,serie:serie});
        
    }

}