<?php

$file = $_GET['pdfFile'];


  $file = "Facturacion/timbrados/$file";
  $filename = $file;
  header('Content-type: application/pdf');
  header('Content-Disposition: inline; filename="' . $filename . '"');
  header('Content-Transfer-Encoding: binary');
  header('Accept-Ranges: bytes');
  @readfile($file);



?>