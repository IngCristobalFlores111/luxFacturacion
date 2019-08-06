<?php
/**
 * PHPExcel
 *
 * Copyright (c) 2006 - 2015 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2015 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */

/** Error reporting */
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
//ini_set('display_startup_errors', TRUE);
error_reporting(0);

date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../PHPExcel/Classes/PHPExcel.php';
require_once "Functions.php";

session_start();

MysqlLink::m_filterArray($_POST['FOLIOS']);
$folios = $_POST['FOLIOS'];

$link = F_linkDb();
$linkLux = F_LinkDbLuxLine();
$query = "
SELECT factura_generada.xml_file,folio_impreso,montoTotal,uuid,fecha_timbrado,fecha_cancelada,tipos_factura.nombre as tipoFactura, clientes_facturacion.nombre as nombreCliente
FROM factura_generada
INNER JOIN tipos_factura ON tipos_factura.id = factura_generada.tipo
INNER JOIN clientes_facturacion ON clientes_facturacion.idcliente = factura_generada.idcliente
WHERE folio_impreso IN(";
$i = 0;
$size = sizeof($folios);
for($i; $i < $size; $i++){
    if($i === $size - 1)
        $query = $query . (string)($folios[$i]);
    else
        $query = $query . (string)($folios[$i]) . ",";
}
$query = $query. ");";

$facturas = $link->m_SendCommand($query,MysqlLink::NAMED,MysqlLink::RET_YES);

$xslName = hash('sha256',$_SESSION['idUsuario']);

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
$index = 0;
$key = 0;
foreach($facturas as $key => $factura){
    $index =  $key + 3;
    $sheet->getStyle("A$index:G$index")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    $sheet->setCellValue('A'.$index, $factura['folio_impreso']);
    $sheet->setCellValue('B'.$index, $factura['nombreCliente']);
    $sheet->setCellValue('C'.$index, $factura['montoTotal']);
    $sheet->setCellValue('D'.$index, $factura['uuid']);
    $sheet->setCellValue('E'.$index, $factura['fecha_timbrado']);
    $sheet->setCellValue('F'.$index, $factura['fecha_cancelada']);
    $sheet->setCellValue('G'.$index, $factura['tipoFactura']);
    $xml = $factura['xml_file'];
    $tmp = explode(".",$xml);
    $pdf = $tmp[0].".pdf";
    $sheet->setCellValue('H'.$index, '=HYPERLINK("'.'https://www.luxline.com.mx/SanAngel/LuxFacturacion/printPDF.php?pdfFile='.$pdf.'&type=1")');
    $sheet->setCellValue('I'.$index, '=HYPERLINK("'.'luxline.com.mx/SanAngel/LuxFacturacion/Facturacion/facturas/timbradas/xml/'.$xml.'")');

}

// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$dir = dirname(__FILE__) ."/../PHPExcel/files/".$xslName.".xlsx";
$objWriter->save($dir);

print_r($xslName.".xlsx");


// Save Excel 95 file
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//$objWriter->save($dir);
