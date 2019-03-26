<?php
//~ require($entity_path.'EAN13.php');
require("../common/src/Entity/EAN13.php");
$barcode= @$_GET['ean'];
if(!$barcode)return;
$ean = new Debora($barcode);
$ean->makeImage("gif");

echo($ean);


exit;
