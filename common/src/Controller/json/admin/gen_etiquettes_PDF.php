<?php

//~ require($entity_path."fpdf181/".'fpdf.php');
require("../common/src/Entity/fpdf181/fpdf.php");

//~ require($entity_path.'EAN13.php');
require("../common/src/Entity/EAN13.php");
//~ $barcode= @$_GET['ean'];
//~ if(!$barcode)return;
//~ $barcodeList= array('8715946388625', "0039564506680", "0033200024095", "0029744916019", "0000346382020", "7613024130115",'8715946388625', "0039564506680", "0033200024095");
$barcodeList=explode(';',$_GET["ean"]);
if(!count($barcodeList))return array($_GET, $barcodeList);

$currentX = null;
$currentY = null;
$imageSize = 54;
$where_refArt = "";
foreach ($barcodeList as $barcode ) {
	if ( strlen($barcode) < 13 ) continue;
	$where_refArt .= ($where_refArt ? " or " : "") . "AR_Ref = '$barcode'";
}

$statement = $bdd->prepare(
"select AR_Ref, AR_Design
from ARTICLE 
where AR_Sommeil = 0
and ($where_refArt)");
$statement->execute();

$barcodeList2 = $statement->fetchAll();

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);

//~ return $barcodeList2;

foreach($barcodeList2 as $index=>$barcode_obj){
	//~ return $barcode_obj["AR_Ref"];
	$ean = new Debora($barcode_obj["AR_Ref"]);
	$imgPath= "/srv/www/files/" . $barcode_obj["AR_Ref"] . ".png";
	$ean->makeImage("png", $imgPath);
	//~ return $imgPath;
	if ( $index%3 == 0 ) {
		$currentY = $pdf->GetY() + 5;
		$pdf->SetXY(15, $currentY);
		
	} else {
		$pdf->SetXY( 15+ ( 5+ $imageSize) * ($index%3), $currentY );
	}
	
	$pdf->Image($imgPath, null, null, $imageSize);
	$pdf->Cell(0,0, utf8_decode($barcode_obj["AR_Design"]));
	
	//~ break;
	
}

$pdf->Output();


exit;



