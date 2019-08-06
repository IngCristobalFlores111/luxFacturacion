function ctrlFormIndex($scope,indexService,fileUploadService){
$scope.clientes = [];  // arreglo de tabla de clientes
$scope.search = {qCliente:""};
indexService.obtenerInitData().then(function(resp){
    $scope.clientes = resp.data;
});
$scope.buscarCliente = function(){
var q = $scope.search.qCliente.trim();
if(q!=""){
indexService.buscarCliente(q).then(function(resp){
var data = resp.data;
if(data.length==0){
    toastr.info("No se han encontrado resultados que coencidan con '"+q+"'");
}else{
    $scope.clientes = data;
}
},function(error){
    toastr.error("Ha ocurrido un error fatal,contacta a soporte");
    console.log(error);
});
}else{
    toastr.info("Tu busqueda no puede ser vacia");
}
}
$scope.opcionImportacion = 0; //0->importar clientes, 1->importar productos
$scope.importarClientes = function(){
    $scope.opcionImportacion = 0;
    importInput.trigger("click");
}
$scope.importarProductos = function(){
    $scope.opcionImportacion = 1;
    importInput.trigger("click");
    
}

$scope.inputExcelFile = function(f){
    var file = f.files[0];
    var tipo = ($scope.opcionImportacion==0)?'clientes':'productos';
    var promise = fileUploadService.excelImportFacturacion(file, "php/new/index/accionIndex.php?accion=importFacturacion",tipo);
   if(promise!=null){
       promise.then(function(resp){
           var data = resp.data;
           console.log(data);
           if(data.exito){
               toastr.success(data.msg);
           }else{
            toastr.error(data.msg);
            
           }
       })
   }
}


}