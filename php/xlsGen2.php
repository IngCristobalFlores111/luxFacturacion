<?php

//error_reporting(0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Europe/London');
include_once('Functions.php');

 $res = luxlineWebLogin::getClientAndSystemFilesFolder();
     $response = $res->getResponse();
     if($response['status'] < 0)
        $res->printResponseJson();

 $clientAndSystemFilesFolder = $response['data'];

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../PHPExcel/Classes/PHPExcel.php';



$xslName = 'export_'.date("Y-m-d");

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("LuxFacturacion")
                             ->setLastModifiedBy("LuxFacturacion")
                             ->setTitle("Facturas")
                             ->setSubject("Facturas")
                             ->setDescription("Lista de Facturas")
                             ->setKeywords("LDF")
                             ->setCategory("Facturacion");


// Add some data
$sheet = $objPHPExcel->setActiveSheetIndex(0);

$sheet->mergeCells('A1:I1');
$sheet->setCellValue('A1', 'LuxLine Facturación Electrónica');
$sheet->getStyle("A1")->getFont()->setBold(true);
$sheet->getStyle('A1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$sheet->getStyle('A1:I1')->getFill()->getStartColor()->setARGB('e67e22');


$sheet->getStyle('A2:I2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$sheet->getStyle('A2:I2')->getFill()->getStartColor()->setARGB('e67e22');

$sheet->getStyle("A2:I2")->getFont()->setBold(true);
$sheet->getStyle('A2:I2')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

$sheet->setCellValue('A2', 'Folio Impreso');
$sheet->setCellValue('B2', 'Nombre del Cliente');
$sheet->setCellValue('C2', 'Monto');
$sheet->setCellValue('D2', 'UUID');
$sheet->setCellValue('E2', 'Fecha de Timbrado');
$sheet->setCellValue('F2', 'Fecha de Cancelación');
$sheet->setCellValue('G2', 'Tipo de Factura');
$sheet->setCellValue('H2', 'Ver PDF');
$sheet->setCellValue('I2', 'Ver XML');


$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(50);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(41);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(20);
$sheet->getColumnDimension('H')->setWidth(20);
$postdata = file_get_contents("php://input");
$request = json_decode($postdata,true);
$index = 0;
$key = 0;
foreach($request['folios'] as $factura){
    $index =  $key + 3;
    $sheet->getStyle("A$index:G$index")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    $sheet->setCellValue('A'.$index, $factura['folio']);
    $sheet->setCellValue('B'.$index, $factura['nombre']);
    $sheet->setCellValue('C'.$index, $factura['montoTotal']);
    $sheet->setCellValue('D'.$index, $factura['uuid']);
    $sheet->setCellValue('E'.$index, $factura['fecha_timbrado']);
    $sheet->setCellValue('F'.$index, $factura['fecha_cancelada']);
    $sheet->setCellValue('G'.$index, $factura['tipo']);
    $xml = $factura['xml_file'];
    $pdf = $factura['pdf_file'];
    $sheet->setCellValue('H'.$index, '=HYPERLINK("'.'https://www.luxline.com.mx/LuxFacturacion/printPDF.php?pdfFile='.$pdf.'&type=1")');
    $sheet->setCellValue('I'.$index, '=HYPERLINK("'.'https://www.luxline.com.mx/'.$clientAndSystemFilesFolder.'Facturacion/facturas/timbradas/xml/'.$xml.'")');
    $key++;
}

// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$dir = dirname(__FILE__) ."/../PHPExcel/files/".$xslName.".xlsx";
$objWriter->save($dir);

print_r($xslName.".xlsx");


// Save Excel 95 file
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//$objWriter->save($dir);


?>