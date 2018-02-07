<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
include "../../config/koneksi.php";
include "../../lib/input.php";

$module=$_GET['module'];
$act=$_GET['act'];
// Hapus modul
if ($module=='setting_akun' AND $act=='update'){
  $tag_seo = $_POST['id_akunkasperkiraan_array'];
  $tag=implode(',',$tag_seo);
        $query="UPDATE setting_akun SET akses = '$tag' WHERE id='$_POST[id]'";
        input_data($query,$module);
}
}
?>
