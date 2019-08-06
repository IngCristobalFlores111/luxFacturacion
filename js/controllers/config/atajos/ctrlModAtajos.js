function ctrlModAtajos($scope,configService){
    $scope.atajos = [];
    $scope.qProdServ= "";
    
    $scope.clavesProd = [];
    configService.obtenerAtajos().then(function(resp){
        $scope.atajos = resp.data;
    });
    var tmpMod = {};
    $scope.modBlock = false;
    $scope.modificar = function(atajo){
        if(!$scope.modBlock){
atajo.active = true;
tmpMod = angular.copy(atajo);
$scope.modBlock= true;
        }else{
            toastr.info("Debes de terminar de editar el actual antes de editar otro");

        }
    }
    $scope.deshacerMod = function(index){
           tmpMod.active = false;
           $scope.atajos[index] =tmpMod;
           $scope.modBlock = false;
    }
    function buscarClaveProd(id){
        id = parseInt(id);
        var claves =$scope.clavesProd;
        var len = claves.length; var i = 0;
        for(;i<len;i++){
            var c = claves[i];
            if(c.id==id){
                return c; 
            }
        }
        return null;
    }
    $scope.confirmarMod = function(a){
       if(a.atajo=="" || a.descripcion=="" ||a.noSerie=="" || a.medida=="" || a.precio<0){
           toastr.info("Revisa los datos, son erroneos");
       }else{
         
           if(a.claveProdSelected){
           var c = buscarClaveProd(a.claveProdSelected);
            a.claveProdServ = c.codigo;
            a.nombreProdServ =c.descripcion;
           }
           configService.modificarAtajo(a).then(function(resp){
         if(resp.data.exito){
              toastr.success("Se ha modificar atajo con exito");
        $scope.modBlock = false;
        a.active = false;
        $scope.clavesProd = [];
         }else{
             toastr.error("Ha ocurrido un error,intentalo mas tarde");

         }
           });

       }

      
       

        
    }
    $scope.eliminar = function(atajo){
        bootbox.confirm({
            title: "¿Seguro que deseas eliminar este atajo?",
            message: "El atajo "+atajo.atajo+"sera eliminado del sistema",
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
                configService.eliminarAtajo(atajo.idatajo).then(function(resp){
                if(resp.data.exito){
                    toastr.success("El atajo ha diso eliminado exitosamente");
                    var index = $scope.atajos.indexOf(atajo);
                    $scope.atajos.splice(index,1);
                }else{
                    toastr.error("Upsss... ha ocurrdo un error,contacta a soporte");
                    console.log(resp);
                }

                });
              }
            }
        });
    }
    $scope.undoBusqueda = function(a){
       delete a.claveProdSelected;
    }
    $scope.buscarClaveProd = function(a){
        var q = a.qProdServ;
  
        if(q!=""){
        configService.buscarClaveProd(q).then(function(resp){
            $scope.clavesProd = resp.data;
            a.claveProdSelected = resp.data[0].id.toString();

        });
    }
    }
}