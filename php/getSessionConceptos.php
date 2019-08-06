<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$conceptos = null;
if(isset($_SESSION['conceptos'])){

	$conceptos =$_SESSION['conceptos'];

}
if(is_null($conceptos)){
    echo "[]";
}else{
    echo json_encode($conceptos,JSON_HEX_APOS|JSON_UNESCAPED_UNICODE|JSON_HEX_QUOT);
}


?>