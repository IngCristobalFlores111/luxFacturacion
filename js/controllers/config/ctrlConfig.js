function ctrlConfig($scope,facturarService){
    $scope.claveProd = {q:"",prods:[]};
    $scope.claveProdSelected = null;
    $scope.clavesProdActuales = [];
    $scope.unidadesActuales = [];
    $scope.unidades = {q:"",unidades:[]};
    facturarService.unidadesActuales().then(function(resp){
        $scope.unidadesActuales = resp.data;
    });

    facturarService.getProdsServActual().then(function(resp){
        $scope.clavesProdActuales = resp.data;        

    });
    $scope.searchClaveProd = function(){
        var q = $scope.claveProd.q.trim();
        if(q!=""){
            facturarService.searchClaveProd(q).then(function(resp){
        $scope.claveProd.prods = resp.data;
        });
    }
}
$scope.agregarClaveProd = function(p){
    $scope.claveProdSelected = p;
}
$scope.deselectClaveProd = function(){
    $scope.claveProdSelected = null;
    
}
$scope.confirmClaveProd = function(){
var item = $scope.claveProdSelected;
    facturarService.addClaveProdActual(item).then(function(resp){
if(resp.data.exito){
    toastr.success("Se ha agregado con exito");
    $scope.clavesProdActuales.push(angular.copy(item));
    $scope.claveProdSelected = null;

}else{
    toastr.error("Upsss... ha ocurrido error, contacta a soporte");
}

    });
}
$scope.eliminarClaveProd = function(c){
    bootbox.confirm({
        title: "¿Seguro que deseas eliminar esta claveProd?",
        message: "¿Realmente estás seguro de eliminar "+c.codigo+"?",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancelar'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirmar'
            }
        },
        callback: function (result) {
        if(result){
            facturarService.eliminarClaveProdActual(c.id).then(function(resp){
                if(resp.data.exito){
                    var prods = $scope.clavesProdActuales;
                    prods.splice(prods.indexOf(c),1);
                    toastr.success("Se ha eliminado correctamente");

                }else{
                    toastr.error("Upsss.... ha ocurrido un error, contacta a soporte");
                }
            });
        }
        }
    });
}
$scope.oculatrBusquedaProds = function(){
    $scope.claveProd.prods = [];

}
$scope.ocultarUnidades = function(){
    $scope.unidades.unidades =[];
}
$scope.searchUnidad = function(){
    var unidades = $scope.unidades;
    if(unidades.q!=""){
        facturarService.buscarUnidad(unidades.q).then(function(resp){
            unidades.unidades = resp.data;
        });
    }
}
$scope.unidadSelected = null;
$scope.agregarUnidad = function(u){

    $scope.unidadSelected = u;
    
}
$scope.confirmUnidad = function(){
    var unidad = angular.copy($scope.unidadSelected);
    facturarService.agregarUnidadActual( unidad.id).then(function(resp){
    if(resp.data.exito){
        toastr.success("Se ha agregado unidad exitosamente");
        $scope.unidadesActuales.push(unidad);
        $scope.unidadSelected = null;
        
    }else{
        toastr.error("Upsss... ha ocurrido un error, intentalo mas tarde");
    }
    });
}
$scope.eliminarUnidad = function(u){
    bootbox.confirm({
        title: "¿Seguro que deseas eliminar esta unidad?",
        message: "¿Realmente estás seguro de eliminar la unidad "+u.nombre+"?",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancelar'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirmar'
            }
        },
        callback: function (result) {
            if(result){
                facturarService.eliminarUnidadActual(u.id).then(function(resp){
               if(resp.data.exito){             
                    var unidades= $scope.unidadesActuales;
                    unidades.splice(unidades.indexOf(u),1);
                     toastr.success("Se ha eliminada unidad exitosamente");
               }else{
                   toastr.error("Upsss... ha ocurrido un error,contacta a soporte");

               }   

                });
            }

        }
    });

}
}