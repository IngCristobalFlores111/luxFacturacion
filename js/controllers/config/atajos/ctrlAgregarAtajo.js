function ctrlAgregarAtajo($scope,configService){
  $scope.clavesProd = [];
  $scope.unidades = [];
  $scope.search = {claveProd:""}
  $scope.atajo = {
  claveProd:null,
  atajo:"",
  descripcion:"",
  medida:null,
  precio:0,
  noSerie:"N/A"
  };
  configService.obtenerUnidadesActuales().then(function(resp){
    $scope.unidades = resp.data;    

  });
$scope.buscarClaveProd = function(){
    var q = $scope.search.claveProd;
    if(q!=""){
configService.buscarClaveProd(q).then(function(resp){
$scope.clavesProd = resp.data;
$scope.atajo.claveProd = resp.data[0];
});

}
} 
$scope.agregarAtajo = function(){
var atajo = $scope.atajo;
var mesg="";var fail =false;
if(atajo.atajo=="" || atajo.descripcion==""|| atajo.noSerie==""||atajo.precio<0){
    msg+="Valores invalidos<br>";
    fail =true;
}
if(atajo.claveProd==null){
    msg+="Debes de seleccionar una clave producto Servicio<br>";
    fail =true;
}
if(atajo.medida==null){
    msg+="Debes de seleccionar una medida<br>";
    fail =true;
}

if(!fail){
var postData ={
    descripcion:atajo.descripcion,
    medida:atajo.medida.clave,
    precio:atajo.precio,
    atajo:atajo.atajo,
    noSerie:atajo.noSerie,
    claveProdServ:atajo.claveProd.codigo,
    nombreProdServ:atajo.claveProd.descripcion
};
configService.agregarAtajo(postData).then(function(resp){
    if(resp.data.exito){
        toastr.success("Se ha agregado atajo exitosamente");
        $scope.atajo = {
            claveProd:null,
            atajo:"",
            descripcion:"",
            medida:null,
            precio:0,
            noSerie:"N/A"
            };
    }else{
        toastr.error("Upsss... ha ocurrido un error,contactaa soporte");
        console.log(resp);
    }
});

}
}
}