<?php
header('Content-Type: text/html; charset=utf-8');
include('o_to_a.php');
include('e_to_i.php');
include('yo_to_e.php');
$handle = @fopen("file.txt", "r");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
        echo $buffer; echo '--->';
        echo yoToE(eToI(oToA($buffer))) . "\n";
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
} else {
  echo "Error: g-fail\n";
}


 ?>
