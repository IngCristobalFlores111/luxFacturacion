// LuxRestaurant® modals


function Invalid() 
{ 
   $("#modalInvalido").modal("show");  


    $(".sk-cube-grid").fadeOut(3000,function(){
     
      $( "#modal-info-6" ).fadeIn(1800);
       $( "#modal-info-5" ).fadeIn(1000);
        $( "#modal-info-4" ).fadeIn(500);
        
    });
   
}

function Admit()
{
   $("#modalBienvenido").modal("show");
   
     $(".sk-cube-grid").fadeOut(2000,function(){
     
      $( "#modal-info-3" ).fadeIn(1800);
       $( "#modal-info-2" ).fadeIn(1000);
        $( "#modal-info-1" ).fadeIn(500);
         $( "#modal-info-0" ).fadeIn(400);
     
     
     
     });
   
}

