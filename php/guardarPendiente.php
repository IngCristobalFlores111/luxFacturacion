<?php
include("functions.php");
$facturaParams = $_POST['facturaParams'];

$folio =$_POST['folio'];
$lj_conceptos =$_POST['j_conceptos'];

$lj_conceptos = json_decode($lj_conceptos,true);

$objSQL = new SQLConnection("localhost", "factura_user", "56A59K_04?", "dblux_facturacion");
$total = $_POST['total'];
$total = $objSQL->filter_input($total);

$lj_conceptos = $objSQL->filter_json($lj_conceptos);

$folio = $objSQL->filter_input($folio);
$lj_facturaParamas = json_decode($facturaParams);

$lj_facturaParamas = $objSQL->filter_json($lj_facturaParamas);
$metodo_pago = $lj_facturaParamas->metodo_pago;
$forma_pago =$lj_facturaParamas->forma_pago;
$descuento = $lj_facturaParamas->descuento;
$fecha =date("Y-m-d");
$query ="UPDATE `factura_pendiente` SET total='$total',`forma_pago`='$forma_pago',`metodo_pago`='$metodo_pago',`descuento`='$descuento',`fecha` ='$fecha' WHERE `folio_factura`='$folio';";
$objSQL->executeCommand($query);
$objSQL->executeCommand("DELETE FROM `folio_conceptos` WHERE `folio_factura`='$folio'");

//var lj_Concepto = { "descripcion": descripcion, "unidad": unidad, "cantidad": cantidad, "precio_unitario": precio.toFixed(3), "importe": importe.toFixed(3) };
$fileds = '';$values ='';
// formando querys...
$query ='';
foreach($lj_conceptos as $c){
    $fields = '';$values = '';
    foreach($c as $key=>$value){
        $fields= $fields."$key,";
        $values = $values."'$value',";
    }
    $fields = rtrim($fields, ",");
    $values = rtrim($values, ",");
    $query = $query."INSERT INTO `folio_conceptos`(folio_factura,$fields) VALUES ('$folio',$values);";

}
$objSQL->executeCommand($query);
?>