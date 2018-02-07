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
if ($module=='kreditmemopenjualan' AND $act=='hapus'){
    $query=mysql_query("select * from trans_retur_penjualan where kode_rbb='".$_GET['id']."' ");
    echo $query;
    $r=mysql_fetch_array($query);
  $query="update trans_retur_penjualan set no_invoice_terretur='0',grandtotal_sebelum_terretur='0' where kode_rbb='".$_GET['id']."'";
  input_only_log($query,$module);
  $query="update trans_invoice set grand_total='".$r['grandtotal_sebelum_terretur']."',status_lunas='1' where id_invoice='".$r['no_invoice_terretur']."'";
  input_data($query,$module);
}
elseif ($module=='kreditmemopenjualan' AND $act=='input'){
  $query="update trans_retur_penjualan set no_invoice_terretur='$_POST[no_nota]', grandtotal_sebelum_terretur='".$_POST['no_nota_jumlah']."'  where kode_rjb='$_POST[no_nota_retur]'";
  input_only_log($query,$module);
  $query="update trans_sales_invoice set grand_total='$_POST[total]',status_lunas='1' where id_invoice='$_POST[no_nota]'";
  input_data($query,$module);
}
}
?>
