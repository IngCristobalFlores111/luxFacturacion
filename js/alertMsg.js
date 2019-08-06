
var alertMsg = function (i_modalMsg, i_btnAceptar, i_btnCancelar, b_hideAceptarBtn, b_hideCancelarBtn) {
    /* EXAMPLE OF USAGE
    HTML
    <!--Modal MSG-->
    <div class="modal fade" id="i_modalMsg" role="dialog">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 text-center modal-box">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                    <h3 class="h3Modal titulo"></h3>

                    <p class="cuerpo1"></p>
                    <p class="cuerpo2"></p>

                    <button type="button" class="btn btn-default c_modalDismiss
                            c_Accept" data-dismiss="modal" id="i_aceptar" autofocus>
                        Aceptar
                    </button>
                    <button type="button" class="btn btn-default c_modalDismiss
                            c_Cancel" data-dismiss="modal" id="i_cancelar">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div><!--Modal MSG-->


    JS
    var g_alertMsg = new alertMsg(
    'i_modalMsg', //id del modal
    'i_aceptar', //id del boton aceptar
    'i_cancelar', // id del boton cancelar
    false, //ocultar boton aceptar
    true); //ocultar boton cancelar

    g_alertMsg.show(
   'Error en la cancelación', //TITULO 
   'La factura no pudo ser cancelado debido a lo siguiente: ', //CUERPO 1
     res["error"], //CUERPO 2
                                 {
                                     "function": function (params) {
                                         var folder = params[0];
                                         window.location.href = "https://www.luxline.com.mx/" + folder;
                                     },
                                     "params": [res["folder"]]
                                 }, //FUNCION QUE SE LLAMA AL HACER CLICK EN ACEPTAR!!!!
                                 {
                                     "function": function (params) {
                                         var folder = params[0];
                                         window.location.href = "https://www.luxline.com.mx/" + folder;
                                     },
                                     "params": [res["folder"]]
                                 } //FUNCION QUE SE LLAMA AL HACER CLICK EN CANCELAR!!!!
                                 );
    
    EXAMPLE OF USAGE  */
    var m_modalMsg = i_modalMsg;
    var m_modalAceptar = i_btnAceptar;
    var m_modalCancelar = i_btnCancelar;

    var m_titulo = null;
    var m_cuerpo = null;
    var m_cuerpo2 = null;

    var m_objFncAceptar = null;
    var m_objFncCancelar = null;

    var m_hideAceptarBtn = b_hideAceptarBtn;
    var m_hideCancelarBtn = b_hideCancelarBtn;

    this.show = function (titulo, cuerpo, cuerpo2,objFncAceptar,objFncCancelar) {
        m_titulo.text(titulo);
        m_cuerpo.text(cuerpo);
        m_cuerpo2.text(cuerpo2);

        m_objFncAceptar = objFncAceptar;
        m_objFncCancelar = objFncCancelar;

        m_modalMsg.modal('show');
    }

    $(document).ready(function () {
        m_modalMsg = $("#" + m_modalMsg);
        m_modalAceptar = $("#" + m_modalAceptar);
        m_modalCancelar = $("#" + m_modalCancelar);

        if (m_hideAceptarBtn === true)
            m_modalAceptar.hide();
        else
            m_modalAceptar.show();

        if (m_hideCancelarBtn === true)
            m_modalCancelar.hide();
        else
            m_modalCancelar.show();

        m_titulo = $(m_modalMsg.find('.titulo')[0]);
        m_cuerpo = $(m_modalMsg.find('.cuerpo1')[0]);
        m_cuerpo2 = $(m_modalMsg.find('.cuerpo2')[0]);

        m_modalAceptar.on('click', function (event) {
            m_objFncAceptar["function"](m_objFncAceptar["params"]);
        });
        m_modalCancelar.on('click', function (event) {
            m_objFncCancelar["function"](m_objFncCancelar["params"]);
        });
    });
}
