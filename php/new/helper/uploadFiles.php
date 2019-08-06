<?php

//uploadFilesAjax($archivos,$nombres,$sobreescribir,$tama�oMaximoDeArchivo,$formatosAceptados);
function uploadFilesAjax($files,$names,$overwrite,$maxFileSizeInBytes,$targetDir,$random){
    $uploadStatus = [];
    $report = [];
    $uploadQty = sizeof($_FILES);
    $i = 0;
    for($i; $i < $uploadQty; $i++){
        $target_dir = $targetDir;
        $imageFileType = pathinfo(basename($_FILES["file-".$i]["name"]),PATHINFO_EXTENSION);
        $uploadOk = 1;

        $target_file = "";

        $file_name = "";

        if($random === "true"){
            $file_name = mt_rand();
            $target_file = $target_dir . $file_name .".".$imageFileType;
        }
        else{
            $file_name = $names[$i];
            $target_file = $target_dir . $file_name .".".$imageFileType;
        }

        $error_it = 0;


        // Check if file already exists
        if (file_exists($target_file) && !$overwrite) {
            $uploadOk = 0;
            $uploadStatus[$i][$error_it] = "El archivo ya existe " . $names[$i] .".".$imageFileType.".";
            $error_it++;
        }

        // Check file size
        if ($_FILES["file-".$i]["size"] > $maxFileSizeInBytes) {
            $uploadOk = 0;
            $uploadStatus[$i][$error_it] = "El archivo ".$names[$i] .".".$imageFileType." es demasiado grande.";
            $error_it++;
        }

  

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0){
            $report[$i]["ok"] = -1;
            $report[$i]["name"] = "";
            $report[$i]["errors"] = $uploadStatus; //return errors
        }
        else {
            // if everything is ok, try to upload file
            $report[$i]["ok"] = 0;
            $report[$i]["name"] = $file_name . "." . $imageFileType;
            $report[$i]["errors"] = null;
            move_uploaded_file($_FILES["file-".$i]["tmp_name"], $target_file);
        }

    }
    return $report;
}

?>