var gn_idCliente = 0;
var gd_trAtajos = $("#i_trinputAtajos");
var gd_tableConceptos = $("#i_tableConceptos");
var gd_totales = $("#trTotales");
var gd_chechCiVA = $("#i_checkConceptosIVA");
var gd_tableConceptosAgregados = $("#i_tableConceptosAgregados");
var gf_iva = 0;
var gf_subtotal = 0;
var gf_total = 0;
var ga_conceptos = [];
var gn_folioFactura = 0;

$(window).on('beforeunload', function () {
    return 'Seguro que deseas salir?';
});

$(window).on('unload', function () {

    if (ga_conceptos.length == 0) {
        $.post("PHP/deleteNullConceptos.php", { folio: gn_folioFactura, idCliente: gn_idCliente });
    }
});






$("#trigger_next").click(function () {
    if (ga_conceptos.length > 0) {
        $("form").submit();
    }
});

$("#i_btnContinuar").click(function () {

    var metodo_pago = $("#i_selectMetodoPago").val();
    var forma_pago = $("#i_selectFormaPago").val();
    var descuento = $("#i_inputDescuento").val();
    $("#idCliente").val(gn_idCliente);
    var f = { forma_pago: forma_pago, metodo_pago: metodo_pago, descuento: descuento };
    $("#facturaParams").val(JSON.stringify(f));
    $("#folioFactura").val(gn_folioFactura);
    // $("form").submit();
});


$(document).on("input", "#i_tableConceptos input[type=text]", function () {
    if ($(this).attr("id") != 'i_inputUnidad')  // input en bootstrap-select
    {
        $(".table-responsive").animate({ scrollTop: $(document).height() }, 1000);

        $.post("PHP/atajosPicker.php", { query: $(this).val() }, function (data) {

            var lj_results = JSON.parse(data);

            var len = lj_results.result.length; var i = 0; var buf = [];

            //     buf.push("<select id='i_select' class='form-control c_input'>");

            for (; i < len; i++) {

                buf.push("<option value='" + lj_results.result[i].idatajo + "'>" + lj_results.result[i].descripcion.slice(0, 25) + "...</option>");



            }



            //   buf.push("</select>");




            if (len == 1)

                update_concepto_data(lj_results.result[0].idatajo);



            gd_trAtajos.html(buf.join("")).selectpicker('refresh');

            gd_trAtajos.show();

        });
    }
});

$("#i_btnUnDo").click(function () {


    $("#i_inputAtajos").val("");
    $("#i_inputDesc").val("");
    $("#i_inputUnidad").val("");
    $("#i_inputCantidad").val("");
    $("#i_inputPrecio").val("");
    $("#i_inputImporte").val("");

});


$("#i_btnVistaPrevia").click(function () {

    $.post("Facturacion/vistaPrevia.php", { folio: gn_folioFactura, idUsuario: 11, idCliente: gn_idCliente }, function (data) {
        //  window.open("printPDF.php?pdfFile=preview" + gn_folioFactura + ".pdf");

        $("#i_previewSec").attr("src", "printPDF.php?pdfFile=preview" + gn_folioFactura + ".pdf");

    });
});


$("#i_inputDescuento").on("input", function () {





    var num = parseFloat($(this).val());
    if (num <= 0) { $(this).val(0); }
    if (num >= 100) { $(this).val(100); }

});

$("#i_btnDescuento").click(function () {

    recalculate_totals();

    var descuento = $("#i_inputDescuento").val();
    var porcentaje = descuento;

    descuento = parseInt(descuento) / 100;
    descuento = gf_subtotal * descuento;
    gf_subtotal = gf_subtotal - descuento;
    gf_iva = gf_subtotal * 0.16;
    gf_total = gf_subtotal + gf_iva;
    gd_totales.html("<td>$" + gf_subtotal.toFixed(3) + "</td><td>$" + gf_iva.toFixed(3) + "</td><td>$" + gf_total.toFixed(3) + "</td>");

    $("#i_thSubtotal").html("Subtotal(Descuento " + porcentaje + "%)");


});

function agregar_concepto(concepto) {



    $.post("PHP/agregarConcepto.PHP", { concepto: JSON.stringify(concepto), idCliente: gn_idCliente, folio: gn_folioFactura, total_importe: gf_total }, function (data) {


    });



}
function is_concepto_repeated(descripcion) {

    var len = ga_conceptos.length; var i = 0;

    for (; i < len; i++) {

        if (ga_conceptos[i].descripcion == descripcion.trim()) {

            return true;



        }

    }

    return false;

}
$("#i_btnAgregar").click(function () {


    var descripcion = $("#i_inputDesc").val();
    var unidad = $("#i_inputUnidad").val();
    var cantidad = $("#i_inputCantidad").val();
    var precio = $("#i_inputPrecio").val();
    var importe = $("#i_inputImporte").val();

    if (!isNaN(unidad) || is_concepto_repeated(descripcion) || descripcion.trim() == '' || unidad.trim() == '' || cantidad.trim() == '' || precio.trim() == '' || importe.trim() == '') {

        $("#i_modalAlertError").modal("show");


    }
    else {

        // checar si es importe con iva o sin iva

        importe = parseFloat(importe);

        cantidad = parseFloat(cantidad);

        precio = parseFloat(precio);

        gf_subtotal += importe

        gf_iva += importe * 0.16;

        gf_total = parseFloat(gf_subtotal) + parseFloat(gf_iva);

        gd_totales.html("<td>$" + gf_subtotal.toFixed(3) + "</td><td>$" + gf_iva.toFixed(3) + "</td><td>$" + gf_total.toFixed(3) + "</td>");

        var lj_Concepto = { "descripcion": descripcion, "unidad": unidad, "cantidad": cantidad, "precio_unitario": precio, "importe": importe };
        agregar_concepto(lj_Concepto);
        ga_conceptos.push(lj_Concepto);
        //gd_tableConceptos.append("<tr><td></td> <td>" +descripcion + "</td><td>" +unidad + "</td><td>" +cantidad.toFixed(3) + "</td><td>" +precio.toFixed(3) + "</td><td>" +importe.toFixed(3) + "</td><td><button id='btn_eliminar_concepto' class='btn btn-block btn-primary c_btnEliminarConcepto'>Eliminar</button></td></tr>");
        update_table_conceptos();
        $("#i_inputDesc").val("");

        $("#i_inputUnidad").val("");

        $("#i_inputCantidad").val("");

        $("#i_inputPrecio").val("");

        $("#i_inputImporte").val("");


    }
});

function eliminar_conepto(index) {



    // actualizar totales 

    gf_subtotal = gf_subtotal - ga_conceptos[index].importe;

    gf_iva = gf_subtotal * 0.16;

    gf_total = gf_iva + gf_subtotal;





    $.post("PHP/eliminarConcepto.php", { desc: ga_conceptos[index].descripcion, folio: gn_folioFactura, cantidad: ga_conceptos[index].cantidad }, function (data) {

        console.log(data);

        ga_conceptos.splice(index, 1);

        update_table_conceptos();



        gd_totales.html("<td>$" + gf_subtotal.toFixed(3) + "</td><td>$" + gf_iva.toFixed(3) + "</td><td>$" + gf_total.toFixed(3) + "</td>");





    });





}
function recalculate_totals() {

    gf_subtotal = 0; gf_total = 0; gf_iva = 0;

    var len = ga_conceptos.length; var i = 0;

    for (; i < len; i++) {

        gf_subtotal += ga_conceptos[i].importe;



    }

    gf_iva = gf_subtotal * 0.16;

    gf_total = gf_iva + gf_subtotal;

}

function update_table_conceptos() {

    var len = ga_conceptos.length; var i = 0; var buff = [];

    for (; i < len; i++) {

        buff.push("<tr><td></td> <td>" + ga_conceptos[i].descripcion + "</td><td>" + ga_conceptos[i].unidad + "</td><td>" + ga_conceptos[i].cantidad.toFixed(3) + "</td><td>$" + ga_conceptos[i].precio_unitario.toFixed(3) + "</td><td>$" + ga_conceptos[i].importe.toFixed(3) + "</td><td><button onclick ='eliminar_conepto(" + i + ")' class='btn btn-block btn-primary'>Eliminar</button></td></tr>");



    }



    gd_tableConceptosAgregados.html(buff.join(""));



}
$("#i_inputPrecio").on("input", function () {

    var precio = parseFloat($(this).val());
    var cantidad = parseFloat($("#i_inputCantidad").val());
    var importe = precio * cantidad;
    if (isNaN(importe)) { $("#i_inputImporte").val("*"); }
    else { $("#i_inputImporte").val(importe); }


});


$("#i_inputCantidad").on("input", function () {

    var precio = parseFloat($("#i_inputPrecio").val());
    var importe = parseFloat($(this).val()) * precio;
    if (isNaN(importe)) { $("#i_inputImporte").val("*"); }
    else
        $("#i_inputImporte").val(importe);

});

function update_concepto_data(id) {

    $.post("PHP/searchAtajoId.php", { idAtajo: id }, function (data) {

        var concepto = JSON.parse(data);
        concepto = concepto.result[0];
        $("#i_inputDesc").val(concepto.descripcion);
        $("#i_inputUnidad").val(concepto.medida);
        $("#i_inputPrecio").val(concepto.precio);




    });


}


gd_trAtajos.on("change", function () {

    var idatajo = $(this).val();
    update_concepto_data(idatajo);

});




$("#i_selectFormaPago").on("change", function () {


    if ($(this).prop("selectedIndex") == 2) {

        $("#divFormaPago").html("<input class='c_input form-control' type='text' id='i_selectFormaPago'  />");

    }
});


$(document).ready(function () {



    gd_trAtajos.hide();



    var ls_options = $("#i_dDom").val();

    var arr_options = ls_options.split(":");

    gn_idCliente = arr_options[0];





    $.post("php/getClient.php", { id: gn_idCliente }, function (data) {
        
       
        var obj = JSON.parse(data);
        obj = obj.result[0];
        var domicilio = obj.calle + " " + obj.noExterior + " " + obj.noInterior + " " + obj.colonia + " " + obj.municipio + " " + obj.estado;
        var table_html = "<tr><td>" + obj.RFC + "</td><td>" + obj.email + "</td><td>" + domicilio + "</td><td>" + obj.telefono + "</td></tr>";
        $("#i_tableCliente").html(table_html);
        $("#i_nombreCliente").html(obj.nombre);

    });
    if (arr_options.length == 1) {

        // ga_conceptos.push({ "descripcion": lj_result[i].descripcion, "unidad": lj_result[i].unidad, "cantidad": parseFloat(lj_result[i].cantidad), "precio_unitario": parseFloat(lj_result[i].precio_unitario), "importe": parseFloat(lj_result[i].importe) });
        $.post("PHP/asignNewFolio.php", { idCliente: gn_idCliente }, function (data) {

            gn_folioFactura = data;
        });



    }
    else  // cargar conceptos de db
    {



        gn_folioFactura = arr_options[1];

        $.post("PHP/CargarConceptosFacturaPendiente.php", { folio: arr_options[1], idCliente: arr_options[0] }, function (data) {



            var lj_result = JSON.parse(data);

            lj_result = lj_result.result;

            var len = lj_result.length; var i = 0;

            for (; i < len; i++) {



                ga_conceptos.push({ "descripcion": lj_result[i].descripcion, "unidad": lj_result[i].unidad, "cantidad": parseFloat(lj_result[i].cantidad), "precio_unitario": parseFloat(lj_result[i].precio_unitario), "importe": parseFloat(lj_result[i].importe) });
                gf_subtotal += parseFloat(lj_result[i].importe);

            }

            gf_subtotal = parseFloat(gf_subtotal);

            gf_iva = gf_subtotal * 0.16;

            gf_total = gf_subtotal + gf_iva;

            gd_totales.html("<td>$" + gf_subtotal.toFixed(3) + "</td><td>$" + gf_iva.toFixed(3) + "</td><td>$" + gf_total.toFixed(3) + "</td>");



            update_table_conceptos();



        });



    }

});

$("#i_inputAtajos").keydown(function (e) {

    if (e.keyCode == 40) {

        var ld_inputAtajos = $("#i_trinputAtajos");

        var lj_inputAtajos = ld_inputAtajos[0];

        var ln_index = lj_inputAtajos.selectedIndex;

        ln_index++;

        if (ln_index == ld_inputAtajos.children().length)

            ln_index = 0;



        lj_inputAtajos.selectedIndex = ln_index;









    }
    if (e.keyCode == 38) {

        var ld_inputAtajos = $("#i_trinputAtajos");

        var lj_inputAtajos = ld_inputAtajos[0];

        var ln_index = lj_inputAtajos.selectedIndex;

        ln_index--;

        if (ln_index < 0)

            ln_index = ld_inputAtajos.children().length - 1;



        lj_inputAtajos.selectedIndex = ln_index;













    }

});

