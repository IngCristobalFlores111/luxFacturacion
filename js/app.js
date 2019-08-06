(function(){


    var app = angular.module("app",[]);
    app.controller("ctrlFactura",ctrFactura);
    app.controller("ctrlGuardarTimbrar",ctrlGuardarTimbrar);
    app.service("facturaService",facturarService);

})()