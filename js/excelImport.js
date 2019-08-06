(function () {
    function fileUploadAjax(JQDom_input_TypeFile) {
        this.file = JQDom_input_TypeFile;
        this.res = null;
        this.send = function (phpFile, fileNames, directory_php, directory_html, rand) {
            //phpFile:= (string) directorio y nombre del archivo php que procesará la subida del archivo
            //fileName:= (arr) arreglo con los nombres que se le quieren dar a los archivos
            //directory_php:= (string) directorio (desde la perspectiva de la carperta donde están los php) donde se insertarán las imagenes
            //directory_html:= (string) directorio (desde la perspectiva de la carperta donde están los html) donde donde se leerán las imagenes
            //rand:= (boolean):= True:Nombre del/los archivos al azar
            //JQDom_dom:= (DOM):= Contenedor donde se mostrará la imagen al subirla el usuario.
            //old:= (string):= Nombre del archivo subido con anterioridad, si este parámetro esta vacio, el archivo se subirá sin afectar ningún otro
            var l_data = new FormData();

            l_data.append("rand", rand);
            l_data.append("names", fileNames);
            l_data.append("fileDir", directory_php);
            $.each(this.file[0].files, function (i, file) {
                l_data.append('file-' + i, file);
            });
            var me = this;

            $.ajax({
                url: phpFile,
                type: 'POST',
                data: l_data,
                processData: false,
                contentType: false,
                success: function (data) {
                  
                    data = JSON.parse(data);
                    var i = 0;
                    var len = data.length;
                    var dir = directory_html;
                    onUpload(data);
                   

                    me.res = data;
                },
                error: function (data) {
                    console.log(data);
                }
            });
        };
    };


    var btnImportClientes = null;
    var btnImportarProductos = null;
    var importInput = null;
    var ajaxUpload = null;
    var tipo = "";
    function onUpload(data) {
        $.ajax({
            url: "php/importExcelClientes.php",
            data: {
                filePath: "../excelImport/" + data[0].name,
                tipo:tipo
            },
            type: "POST"
        }).done(function (data) {
            var resp = JSON.parse(data);
            if (resp.exito) {
                toastr.success(resp.msg);

            } else {
                toastr.error("Upss... ha ocurrido un error, contactanos para ver este error <br>"+resp.msg);
            }

        });
    }
    function validarArchivo(file) {
        var f = file[0];
        var fail = false;
        if (f.type != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
            toastr.info("Solo archivos Excel son aceptados");
            fail = true;
        }
        if (f.size > 3145728) {
            toastr.info("Archivo demasiado grande, tiene que ser menos a 3 mb");
            fail = true;
        }
        if (!fail) {
               
            ajaxUpload.send("php/uploadFile2.php", "", "../excelImport/", "excelImport/", true);

        }

    }

    $(document).ready(function () {
        btnImportarProductos = $("#btnImportarProductos");
        importInput = $("#importInput");
        btnImportClientes = $("#btnImportarClientes");
        ajaxUpload = new fileUploadAjax(importInput);
        btnImportClientes.click(function () {
            importInput.trigger("click");
            tipo = "clientes";
        });
        btnImportarProductos.click(function () {
            importInput.trigger("click");
            tipo = "productos";

        });
        importInput.on("change", function () {
            validarArchivo(importInput[0].files);

        });



    });


})()