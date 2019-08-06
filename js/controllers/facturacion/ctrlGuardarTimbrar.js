function ctrlGuardarTimbrar($scope,facturaService){
    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
$scope.cliente = {};

$scope.facturaParams = facturaParams;
    facturaService.getClient(idCliente).then(function(resp){
var data = resp.data;
if(data==0){
    location.href="index.html";
}else{
        $scope.cliente = resp.data; 
        $scope.cliente.idCliente = idCliente;   
        $scope.cliente.domicilio = data.calle + " "+data.noExterior+" "+data.noInterior+" "+data.colonia+" "+data.municipio+","+data.estado;
        $scope.correos.push({email:data.email,isCliente:true});
}
    });

$scope.correos = [];
$scope.lugares = [];
$scope.lugar = "";
$scope.correoNuevo = "";
$scope.correoEdit ={asunto:"",cuerpo:""};
$scope.opcion = "guardarytimbrar";
$scope.show={btnEnviarCorreos:true,btnContinuar:true,btnSalir:false,successTimbrado:false,failTimbrado:false};
$scope.timbrado = {mensaje:""};    
facturaService.getCorreosDefault().then(function(resp){
var data = resp.data;
$scope.correos =  $scope.correos.concat(data);
    });
    facturaService.getLugares().then(function(resp){
        $scope.lugares = resp.data;
        $scope.lugar = resp.data[0].idexpedicion;

    });
$scope.agregarCorreo = function(){
    var emial = $scope.correoNuevo;
if(validateEmail(emial)){
$scope.correos.push({email:emial});
}else{
toastr.info("Debes de ingresar un correo valido");
}

}

$scope.eliminarCorreo =function(c){
$scope.correos.splice($scope.correos.indexOf(c),1);
}
$scope.setCorreo = function(c){
    modalCorreo.modal("show");
        $scope.correoEdit = c;
 
}
$scope.continuar = function(){
var opcion =$scope.opcion;
if(opcion=="guardarytimbrar"){
    var lugar = $scope.lugar;
    $scope.show.btnContinuar = false;
    if(lugar==""||lugar==undefined){
        toastr.info("Debes de especificar el lugar de expedicion");
    }else{
        modalWaiting.modal("show");
    facturaService.facturar(idCliente,$scope.lugar,$scope.facturaParams).then(function(resp){
      var data = resp.data;
     
      modalWaiting.modal("hide");
      $scope.show.btnSalir = true;
      setTimeout(function() {
        modalposTimbrar.modal("show");
        
      }, (1000));
      if(data.timbrado){
        $scope.timbrado.mensaje = data.res.codigo_mf_texto;
        var xml = data.res.archivo_xml.split("/").pop();
        var tmp = xml.split(".");
        var pdf = tmp[0]+".pdf";
  
        $scope.timbrado.pdf ="Facturacion/facturas/timbradas/pdf/"+pdf;
        $scope.timbrado.xml = "Facturacion/facturas/timbradas/xml/"+xml; 
    $scope.show.successTimbrado = true;
      }else{
          $scope.show.failTimbrado = true;
          $scope.timbrado.mensaje = data.res.codigo_mf_texto;
          
      }
    });
}
}
if(opcion=="guardar"){
    $scope.facturaParams.idCliente =  $scope.cliente.idCliente 
    facturaService.saveBill($scope.facturaParams).then(function(resp){
     if(resp.data.dbErrors.length==0){
         toastr.success("Tu factura ha sido guardada para timbrar después");
     }else{
         toastr.error("Upsss... ha ocurrido un error, contacta a soporte");
     }
   });
}

}
$scope.salir = function(){
    location.href="index.html";
}
$scope.irCorreos = function(){
    var len =$scope.correos.length; var i = 0;
    for(;i<len;i++){
        $scope.correos[i].status = -1;
    }

    modalposTimbrar.modal("hide");
    setTimeout(function() {
        modalCorreosEnvio.modal("show");
    }, 500);



}
function checkCorreos(correos){
var len = correos.length; var i = 0;
for(;i<len;i++){
    var c = correos[i];
    if(c.email.email==$scope.correos[i].email){
        if(c.error){
            $scope.correos[i].status = 0;
        }else{
            $scope.correos[i].status= 1;
            
            
        }
    }
}
}
$scope.enviarCorreos = function(){
var correos = $scope.correos;
var pdf = $scope.timbrado.pdf.split("/").pop();
modalCorreosEnvio.modal("hide");
modalWaiting.modal("show");
var xml =$scope.timbrado.xml.split("/").pop();
   facturaService.sendMails(correos,xml,pdf,"timbrado").then(function(resp){
    checkCorreos(resp.data);
    $scope.show.btnEnviarCorreos = false;
    modalWaiting.modal("hide");
    setTimeout(function() {
        modalCorreosEnvio.modal("show");
        
    }, 500);
    
});

}
}