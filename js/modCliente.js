$("#i_btnUnDo").click(function () {

    location.reload();
});

function goPendientes(folio, idCliente)
{
    window.location.href = "opcionesPendiente.html?id=" + idCliente + "&folio=" + folio;
}



function goActualizar() {
    var la_doms = $("#i_menuModificar input[type=text]");
    var ls_idCliente = gn_idCliente;
    var lj_data = { nombre: la_doms[0].value, RFC: la_doms[1].value, telefono: la_doms[2].value, celular: la_doms[3].value, email: la_doms[4].value, calle: la_doms[5].value, colonia: la_doms[6].value, CodigoPostal: la_doms[7].value, noExterior: la_doms[8].value, noInterior: la_doms[9].value, localidad: la_doms[10].value, municipio: la_doms[11].value, estado: la_doms[12].value };
    $.post("php/actualizarCliente.php", { idCliente: ls_idCliente, json_datos: JSON.stringify(lj_data) }, function (resp) {
       var data = JSON.parse(resp);
       if(data.exito){       
        toastr.success(data.msg);
       }else{
           toastr.error(data.msg);
           console.log(data.errors);
       }


    });
}
function validateTel(str)
{
    //str = str.trim();
    for(var i of str) {
        if(isNaN(i))
        {
            if (i != "-") {
                return false;
            }

        }
    }
    return true;


}

function validateNombre(str)
{
    str = str.trim();
    str = str.toLowerCase();
    var pattern = /[^a-z|^A-Z|^\s]/;
    if (pattern.test(str)) {
        //Your logice will be here.
        return false;
    }
    else {
        return true;
    }
}









