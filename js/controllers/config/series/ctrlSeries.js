function ctrlSeries($scope,facturarService){
    $scope.nueva = {serie:""};
     $scope.series = [];
    facturarService.obtenerSeriesOriginales().then(function(resp){
        $scope.series = resp.data;

    });
    function setNuevoNombre(id,nombre){
        var series = $scope.series; var len= series.length; var i =0;
        for(;i<len;i++){
            var s = series[i];
            if(s.id==id){
                s.serie = nombre;
                break;
            }
        }
    }
    $scope.agregarSerie = function(){
        var serie=$scope.nueva.serie;
        if($scope.opcion==0){
        if(serie!=""){
            facturarService.altaSerie(serie).then(function(resp){
                if(resp.data.exito){
                    toastr.success("Se ha agregado la serie exitosamente");
                    $scope.series.push({folio:0,serie:serie,id:resp.data.id});
                    
                }else{
                    toastr.error("Upsss... no se puedo agregar serie, se debe a que ya existe esta serie o algun otro problema");

                }
            });
        }
    }else{
        var s= $scope.nueva;
        facturarService.modificarSerie(s.id,s.serie).then(function(resp){
      if(resp.data.exito){
          toastr.success("Se ha modificar la serie exitosamente");
          setNuevoNombre(s.id,s.serie);
          $scope.nueva.serie="";
          $scope.opcion = 0;
      }else{
        $scope.opcion = 0;
        $scope.nueva.serie="";
        
          toastr.error("Upsss... ha ocurrido un error, contacta a soporte");
      }

        });
    }
    }
    $scope.opcion = 0; //0 ->insertar,1->modificar
$scope.modSerie = function(s){
 $scope.nueva = angular.copy(s);
 $scope.opcion = 1;
}

$scope.eliminarSerie = function(s){
    bootbox.confirm({
        title: "¿Seguro que deseas eliminar esta serie",
        message: "La serie "+s.serie+" se eliminará del sistema",
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
            facturarService.eliminarSerie(s.id).then(function(resp){
                if(resp.data.exito){
                    toastr.success("Se ha eliminada serie exitosamente");
                    $scope.series.splice($scope.series.indexOf(s),1);

                }else{
                    toastr.error("No se ha podido eliminar serie, intentalo mas tarde");
                }
            });
          }
        }
    });
}
}