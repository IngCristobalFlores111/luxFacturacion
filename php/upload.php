<?php
include ("Functions.php");

$new_filename =$_FILES['fileToUpload']["name"];

$res = luxlineWebLogin::getClientAndSystemFilesFolder();
$response = $res->getResponse();
if($response['status'] < 0){
	echo "Sorry, there was an error uploading your file.";
	exit;
}

$clientAndSystemFolder = $response['data'];
$target_dir =  "../../". $clientAndSystemFolder ."Facturacion/pruebas/";

$target_file = $target_dir . basename($_FILES['fileToUpload']["name"]);


$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
	$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	if($check !== false) {
		echo "File  - " . $check["mime"] . ".";
		$uploadOk = 1;
	} else {
		$uploadOk = 0;
	}
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
	echo "Sorry, your file is too large.";
	$uploadOk = 0;
}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}

	$tipo = $_POST['tipo'];
    $query = "UPDATE usuario_facturacion SET archivo_$tipo='$new_filename'";

$objSQL = F_sqlConn();
$objSQL->executeCommand($query);

echo "<script>close();</script>";
?>