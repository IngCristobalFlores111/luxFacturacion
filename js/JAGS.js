// JavaScript source code
var g_atajoIDModificar = -1;
var g_eliminarAtajoState = false; // BAENA bandera para eliminar atajo


function body_loadClientes() {
    gd_tableClientes.load("php/body_loadClientes.php");
}

function nombreCliente_buscarCliente() {
    var s_nombreCliente = $("#i_nombreCliente").val();
    gd_tableClientes.load("php/body_searchCliente.php", { NombreCliente: s_nombreCliente });
}

function nombreCliente_AutoSearch(event) {
    if (parseInt(event.keyCode) === parseInt(13)) {
        var s_nombreCliente = $("#i_nombreCliente").val();
        gd_tableClientes.load("php/body_searchCliente.php", { NombreCliente: s_nombreCliente });
    }
}

function table_loadAtajos() {
    gd_tableClientes.load("php/table_loadAtajos.php", {});
}

function atajoEliminar(id) {
    
    $("#i_modalEliminarAtajo").modal();
    $("#i_btngoElimianr").val(id);
    
}



function confirmarEliminar(id){

   g_eliminarAtajoState = true;



}

function atajoModificar(id) {
    var ln_idatajoanterior = 0;

    if (parseInt(g_atajoIDModificar) < parseInt(0)) {
        g_atajoIDModificar = id;
        ln_idatajoanterior = -1
    }
    else {
        ln_idatajoanterior = g_atajoIDModificar;
        g_atajoIDModificar = id;
    }
    $.post("php/table_modificarAtajo.php", { IDATAJO: id, IDATAJOANTERIOR: ln_idatajoanterior }, function (data, status) {
        var Atajos = JSON.parse(data);

        if (parseInt(ln_idatajoanterior) < parseInt(0)) {
            var ld_atajoActual = $("#i_Atajo_" + id);
            var AppendActual = "";

            ld_atajoActual.empty();

            AppendActual = "<td>" + "<input type='text' class='form-control' id='i_nuevoAtajo' value='" + Atajos.Obj[0]["atajo"] + "' />" + "</td>" +
            "<td>" + "<input type='text' class='form-control' id='i_nuevaDescripcion' value='" + Atajos.Obj[0]["descripcion"] + "' />" + "</td>" +
            "<td>" + "<input type='text' class='form-control' id='i_nuevaMedida' value='" + Atajos.Obj[0]["medida"] + "' />" + "</td>" +
            "<td>" + "<input type='text' class='form-control' id='i_nuevoPrecio' value='" + Atajos.Obj[0]["precio"] + "' />" + "</td>" +
            "<td>" +
            "<div class='btn-group-vertical'>" +
            '<button type="button" class="btn btn-default c_AcceptModal" onclick="atajoAceptar(' + Atajos.Obj[0]["idatajo"] + ')"><i class="fa fa-check" style = "font-size:18px;" aria-hidden="true"></i></button>' +
            "</div>" +
            "</td>";
            ld_atajoActual.append(AppendActual);
        }

        else {
            var ld_atajoActual = $("#i_Atajo_" + id);
            var ld_atajoAnterior = $("#i_Atajo_" + ln_idatajoanterior);
            var AppendActual = "";
            var AppendAnterior = "";

            ld_atajoActual.empty();
            ld_atajoAnterior.empty();

            AppendActual = "<td>" + "<input type='text' class='form-control' id='i_nuevoAtajo' value=" + Atajos.Obj[0]["atajo"] + ">" + "</td>" +
            "<td>" + "<input type='text' class='form-control' id='i_nuevaDescripcion' value=" + Atajos.Obj[0]["descripcion"] + ">" + "</td>" +
            "<td>" + "<input type='text' class='form-control' id='i_nuevaMedida' value=" + Atajos.Obj[0]["medida"] + ">" + "</td>" +
            "<td>" + "<input type='text' class='form-control' id='i_nuevoPrecio' value=" + Atajos.Obj[0]["precio"] + ">" + "</td>" +
            "<td>" +
            "<div class='btn-group-horizontal'>" +
            '<button  type="button" class="btn btn-default c_AcceptModal" onclick="atajoAceptar(' + Atajos.Obj[0]["idatajo"] + ')"><i class="fa fa-check" style = "font-size:18px;" aria-hidden="true"></i></button>' +
            "</div>" +
            "</td>";
            ld_atajoActual.append(AppendActual);

            AppendAnterior = "<td>" + Atajos.Obj[1]["atajo"] + "</td>" +
            "<td>" + Atajos.Obj[1]["descripcion"] + "</td>" +
            "<td>" + Atajos.Obj[1]["medida"] + "</td>" +
            "<td>$" + Atajos.Obj[1]["precio"] + "</td>" +
            "<td>" +
            "<div class='btn-group-horizontal'>" +
            '<button type="button" class="btn btn-default c_Ajustes" onclick="atajoModificar(' + Atajos.Obj[1]["idatajo"] + ')"><i class="fa fa-cog" style = "font-size:20px; " aria-hidden="true"></i></button>' +
            '<button type="button" style = "margin-left: 7%;" class="btn btn-default c_Cancel" onclick="atajoEliminar(' + Atajos.Obj[1]["idatajo"] + ')"><i class="fa fa-trash"  style = "font-size:20px; " aria-hidden="true"></i></button>' +
            "</div>" +
            "</td>";
            ld_atajoAnterior.append(AppendAnterior);
        }
    });
}

function atajoAceptar(id) {

    var ld_inputs = $("#menu2 input");
    var len = ld_inputs.length; var i = 0; var fail = false;
    for (; i < len; i++) {
        var element = $(ld_inputs[i]);

        if (i == 3) {
            if (isNaN(ld_inputs[i].value.trim())) {
                element.css("background-color", "rgba(254, 110, 0, 0.498039)");
                element.attr("placeholder", "Campo debe de ser numerico");
                element.val("");
                element.css("color", "white");
                fail = true;
            }
        } else {

            if (ld_inputs[i].value.trim() == "") {
                element.css("background-color", "rgba(254, 110, 0, 0.498039)");
                element.attr("placeholder", "Campo no puede ser vacio");
                element.val("");
                element.css("color", "white");

                fail = true;

            } else {
                element.css("background-color", "");
                element.attr("placeholder", "");
            }
        }
        
    }
    if (!fail) {
        $.post("php/guardarModAtajo.php", { ID: id, NA: ld_inputs[0].value, ND: ld_inputs[1].value, NM: ld_inputs[2].value, NP: ld_inputs[3].value }, function (data, status) {
           // alert(data);
            $("#i_tableTBody").load("php/table_loadAtajos.php", {});
            g_atajoIDModificar = -1;
        });
    }
}


function agregarNuevoAtajo() {
    
    var ld_inputs = gd_form_inputs;
        var len = ld_inputs.length; var i = 0; var fail = false;
        for (; i < len; i++) {
            var input = ld_inputs[i];
            if (input.value == "") {
                var element = $(input);
                element.css("background-color", "rgba(254, 110, 0, 0.498039)");
                element.attr("placeholder", "Campo no puede ser vacio");

                fail = true;
            }
            if (input.type == "number") {
                var val = input.value;
                if (isNaN(val)) {
                    var element = $(input);
                    element.css("background-color", "rgba(254, 110, 0, 0.498039)");
                    element.attr("placeholder", "Campo debe de ser numerico");
                    fail = true;
                } else {
                    var n = parseFloat(val);
                    if (n <= 0) {
                        element.css("background-color", "rgba(254, 110, 0, 0.498039)");
                        element.attr("placeholder", "Campo debe de ser un precio valido");
                        fail = true;
                    }
                }
            }

        
              }
   
    if (!fail) {
        $.post("php/agregarNuevoAtajo.php", { NA: ld_inputs[0].value, ND: ld_inputs[1].value, NM: ld_inputs[2].value, NP: ld_inputs[3].value }, function (data, status) {
            ga_modalDoms.header.html("Se ha agregado Atajo");
            ga_modalDoms.info.html("");
            ld_inputs.val("");
            $("#i_modalSeguroAgregar").modal("show");

        });
    } else {
        ga_modalDoms.header.html("No se puede agregar");
        ga_modalDoms.info.html("Debe de corregir los errores en los campos indicados");
        $("#i_modalSeguroAgregar").modal("show");

    }
}

