<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
$query="SELECT * FROM trans_lpb where is_void = 0 AND id_lpb='$_POST[kode]'";
$result=mysql_query($query);
echo mysql_num_rows($result);
 ?>