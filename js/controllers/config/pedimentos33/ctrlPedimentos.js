function ctrlPedimentos($scope,pedimentosService){
    $scope.accion = 0; // 0->agregar pedimento; 1->modificar
    $scope.search={aduana:""};
    $scope.pedimento = {num_progresiva:"",ano_num_progresiva:"",patente:"",aduana:null,ano_validacion:""};
   $scope.aduanas = [];
   $scope.pedimentos = [];
   function isLetter(c) {
    return c.toLowerCase() != c.toUpperCase();
  }
   $scope.limitPatente = function(){
       var str =   $scope.pedimento.patente;
       var last_pos = str.length -1
       var last_char = str.charAt(last_pos);
       if(isLetter(last_char)){ // limitar solo numeros
    
        $scope.pedimento.patente =str.substr(0,last_pos).substr(0,4);
       }else{

    $scope.pedimento.patente = str.substr(0,4);
   }
}
$scope.limitAnoValidacion = function(){
    var str =   $scope.pedimento.ano_validacion;
       var last_pos = str.length -1
       var last_char = str.charAt(last_pos);
       if(isLetter(last_char)){ // limitar solo numeros
    
        $scope.pedimento.ano_validacion =str.substr(0,last_pos).substr(0,2);
       }else{

    $scope.pedimento.ano_validacion = str.substr(0,2);
   }

}
$scope.limitNumProgresiva = function(){
   // $scope.pedimento.num_progresiva = $scope.pedimento.num_progresiva.substr(0,6);
    var str =   $scope.pedimento.num_progresiva;
    var last_pos = str.length -1
    var last_char = str.charAt(last_pos);
    if(isLetter(last_char)){ // limitar solo numeros
 
     $scope.pedimento.num_progresiva =str.substr(0,last_pos).substr(0,6);
    }else{

 $scope.pedimento.num_progresiva = str.substr(0,6);
}
}
$scope.limitAnoNumProgresiva = function(){
    var num_prog =  $scope.pedimento.ano_num_progresiva;
    if(num_prog.length!=0){
        $scope.pedimento.ano_num_progresiva = num_prog.substr(0,1);

    }
}
   $scope.modalPedimento33 = function(){
    bootbox.alert({
        title:"Pedimento CFDI 3.3",
        message: `De acuerdo a la nuva guía de llenado del CFDI 3.3, el numero de pedimento consta de lo siguiente <br>
        <ul>
        <li>Año de validación del pedimento (2 digitos)</li>
        <li>Número de indentificación de la aduana (2 digitos)</li>
        <li>Número de patente (4 digitos)</li>
        <li>1 digito que corresponde al año en curso, salvo que se trate
        de un pedimento consolidado, iniciado en el año inmediato anterior
        o del pedimento original de una rectificación
        </li>
        </ul>
        
        `,
        callback: function () {
        }
    });
   }
   $scope.modalAnoPedimiento = function(){
    bootbox.alert({
        title:"Año del pedimento CFDI 3.3",
        message: ` 1 dígito
        que corresponde al último dígito del año en curso, salvo que se trate
        de un pedimento consolidado, iniciado en el año inmediato anterior
        o del pedimento original de una rectificación`,
        callback: function () {
        }
    });
   }

   pedimentosService.initData().then(function(resp){
       var data = resp.data;
$scope.pedimentos = data.pedimentos;
$scope.aduanas = data.aduanas;
   });
   $scope.buscarAduanas = function(){
       var q = $scope.search.aduana;
       if(q!=""){
    pedimentosService.buscarAduanas(q).then(function(resp){
        $scope.aduanas = resp.data;
        $scope.pedimento.aduana =null;
    });
}else{
    toastr.info("Aduana no puede ser vacia");
}
   }
   $scope.resetSearchAduana = function(){
    $scope.pedimento.aduana = null;
   }
   $scope.selectAduana = function(a){
    $scope.pedimento.aduana = angular.copy(a);
   }

   function validarPedimento (pedimento){
   var pedimento = $scope.pedimento;
   var error =[];
   if(pedimento.num_progresiva.length<6){
    error.push("La numeración progresiva de la aduana es de 6 digitos");
   }
   if(pedimento.ano_num_progresiva.length!=1){
    error.push("el año de numeración progresiva de la aduana es de 1 solo digito");

   }
   if(pedimento.patente.length!=4){
    error.push("El numero de patente tiene que tener 4 digitos");

   }
   if(pedimento.aduana==null){
    error.push("Tienes que seleccionar una aduana");

   }
   return {success:(error.length==0),msg:error};
    

   }
   $scope.agregarPedimento = function(){
//    $scope.pedimento = {num_progresiva:"",ano_num_progresiva:"",
//patente:"",aduana:null,ano_validacion:""};

       var pedimento = $scope.pedimento;
         var validar_pedimento = validarPedimento(pedimento);

//$scope.accion==0
       if(validar_pedimento.success){
           var accion = $scope.accion;
           var index= pedimento.index;
           pedimentosService.alterPedimento(pedimento,accion).then(function(resp){
           if(resp.data.exito){
               
               if(accion==0){
               msg ="Pedido dado de alta exitosamente";
               $scope.pedimentos.push(pedimento);
               }else{
               msg ="Pedimento actualizado exitosamente";
               $scope.pedimentos[index] = pedimento;
               }
               toastr.success(msg);
               $scope.accion= 0;
               $scope.search.aduana="";
               $scope.pedimento = {num_progresiva:"",ano_num_progresiva:"",patente:"",aduana:null,ano_validacion:""};

            }else{
                toastr.error("Upsss... ha ocurrido un error,contacta a soporte");
                console.log(resp.data);

            }

           });


        }else{
            bootbox.alert({title:"Error",message:validar_pedimento.msg.join("<br>")});

        }
}
  function actualizarTablaPedimento(pedimento){
      var pedimentos = $scope.pedimentos; var len = pedimentos.length;
      var i = 0;
      for(;i<len;i++){
          if(pedimentos[i].id==pedimento.id){
              $scope.pedimentos[i] = {
                ano:pedimento.ano,
                codigoAduana:pedimento.aduana,
                id:pedimento.id,

                idAduana:pedimento.aduana.id,
                patente:pedimento.patente,
                nombreAduana:pedimento.aduana.nombre
              };
              break;
          }
      }
  }
   $scope.modPedimento = function(index){

    $scope.accion = 1;
var pedimento = $scope.pedimentos[index];
pedimento.index= index;
    $scope.pedimento = angular.copy(pedimento);
   }
   $scope.eliminarPedimento = function(pedimento){
    bootbox.confirm({
        title: "Seguro que deseas eliminar este pedimento?",
        message: "Será eliminado del sistema",
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
    pedimentosService.eliminarPedimento(pedimento.id).then(function(resp){
        if(resp.data.exito){
            toastr.success("Se ha eliminado pedimento exitosamente");
         $scope.pedimentos.splice($scope.pedimentos.indexOf(pedimento),1);
        }else{
            toastr.error("Ocurro un error, contacta a soporte");
        console.log(res.data);

        }
    });
}   
     }
    });
   }


}