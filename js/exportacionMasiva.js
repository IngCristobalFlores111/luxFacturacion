(function () {
    var btnExportarMas = null;
    $(document).ready(function () {
        btnExportarMas =$("#btnExportarMas");
        btnExportarMas.click(function () {
            var btns = gd_tableFacturas.find("tr td button.c_buttonTable1")
            var xml_files = [];
            btns.each(function (i, element) {
                var tmp = $(element).attr("onclick");
                var a = tmp.split('"');
                xml_files.push(a[1]);

            });
            console.log(xml_files);
            $.ajax({
                url: "php/new/exportacionMasiva.php",
                data: { xml_files: xml_files },
                type: "POST"
            }).done(function (data) {
                console.log(data);
                window.open("excelExport/" + data);

            });



        });


    });

})()