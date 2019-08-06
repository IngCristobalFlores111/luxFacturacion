<?php 

// initiate FPDI 
include('fpdf.php'); 
include('fpdi.php');
function addLabelCanceled($filePath){
 
$pdf = new FPDI(); 
// add a page 
$pdf->AddPage(); 
// set the sourcefile 
$pdf->setSourceFile($filePath); 
// import page 1 
$tplIdx = $pdf->importPage(1); 
// use the imported page as the template 
$pdf->useTemplate($tplIdx, 0, 0); 

// now write some text above the imported page 
$pdf->SetFont('Arial','B',55);

$pdf->SetTextColor(255,0,0); 
$pdf->SetXY(50, 111); 
$pdf->Write(0, "Cancelada"); 

$pdf->Output($filePath); 


}

?>