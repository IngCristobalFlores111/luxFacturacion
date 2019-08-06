function ctrlOpcionesTimbrados($scope,opcionesUsuarioService){
   $scope.regimenes = [];
    opcionesUsuarioService.obtenerRegimenes().then(function(resp){
        $scope.regimenes = resp.data;
        $scope.params.regimen = resp.data[0].id;
    });
    $scope.params = {};
    $scope.regimenActual = "";
    opcionesUsuarioService.obtenerConfigUsr().then(function(resp){
        $scope.params = resp.data;

        $scope.regimenActual = angular.copy(resp.data.regimen_nombre); 
    });
    $scope.actualizarRegimen = function(){
        var params = $scope.params;
        console.log(params.regimen);
        if(params.regimen==""||params.regimen==undefined ||params.tipo_factura==undefined){
            toastr.info("Debes de establecer un regimen para "+params.regimenActual);
        
        }else{
            opcionesUsuarioService.modificarTipoFactura(params).then(function(resp){
                   if(resp.data.exito){
                       toastr.success("Se ha actualizado su regimen fiscal exitosamente");
                       $scope.regimenActual = angular.copy(params.regimen_nombre); 
                       
                    }else{
                       toastr.error("Upsss... ha ocurrido un error,contacta a soporpte");

                   }

            });
        }
    }

}