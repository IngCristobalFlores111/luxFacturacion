// JavaScript source code
/*Ejemplo de uso
function cargarContenido(params) {
    if (gd_inputSearch.val().trim() != "") {
        nombreCliente_buscarCliente();
    } else {
        body_loadClientes();
    }
}

Ejemplo de uso
var rszWatcher = new resizeWatcher(
    {
        'function': cargarContenido,
        'params': []
    });

*/

var resizeWatcher = function (obj_fncDoThis) {
    var finished = true;
    var setWatcher = false;

    var objFnc = obj_fncDoThis;

    var reactivateRefresh = function () {
        if (!finished) {
            finished = true;
            setTimeout(function () { reactivateRefresh(); }, 100);
        }
        else {
            objFnc['function'](objFnc['params']);
            setWatcher = false;
        }
    }

    $(window).resize(function () {
        finished = false;
        if (!setWatcher) {
            setWatcher = true;
            reactivateRefresh();
        }
    });
}

