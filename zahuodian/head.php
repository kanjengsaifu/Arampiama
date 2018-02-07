<?php
include "config/function.php";
$result=Plugin_css_and_javascript();
try {
	echo $result['result'];
} catch (Exception $e) {
	echo $result['error'];
}



include "lib/input.php";
include "lib/fungsi_tanggal.php";

?>