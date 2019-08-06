function fileUploadService($http){
    this.uploadFileToUrl = function (files, phpUploadFile, uploadDir) {
        var fd = new FormData();
        $.each(files, function (i, file) {
            fd.append('file-' + i, file);
            fd.append("nombre",file.nombre);
              
        });
       fd.append("uploadDir",uploadDir);
     
        return $http.post(phpUploadFile, fd, {
            transformRequest: angular.identity,
            headers: { 'Content-Type': undefined }
        });


    }
    this.excelImportFacturacion = function(file, phpUploadFile,tipo){
        var tipo = file.type;
        var size = file.size;
        var nombreArchivo = file.name;
        var msg ="";var fail = false;
        if(nombreArchivo.indexOf(".xlsx")==-1){
        msg+="Solamente archivos de excel son validos<br>";
        fail = true;
        }
        if(size>2097152){
            msg+="Archivo demasiado grande,solo archivos menores a 2mb";
            fail= true;
        }
        if(!fail){
    
        var fd = new FormData();
        fd.append("file",file);
        fd.append("tipo",tipo);
     
        return $http.post(phpUploadFile, fd, {
            transformRequest: angular.identity,
            headers: { 'Content-Type': undefined }
        });
    }else{
        toastr.info(msg);
        return null;
    }

    }
}