<?php

$barcode_ts = sprintf("%s", round(microtime(true)*1000));
$barcode = strrev( $barcode_ts );
printf("%s\n", $barcode );
