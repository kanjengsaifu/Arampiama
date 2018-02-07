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
if ($module=='pembayaranpenjualan' AND $act=='hapus'){
      $query="SELECT * ,a.bukti_bayarjual as bukti FROM `trans_bayarjual_header` a left join `trans_bayarjual_detail` b on(a.`bukti_bayarjual`=b.`bukti_bayarjual`) where id_bayarjual='$_GET[id]' and b.bukti_bayarjual is null group by a.bukti_bayarjual  ";
      $result=mysql_query($query);
      $r=mysql_fetch_array($result);
        input_only_log("delete from  jurnal_umum where kode_nota='".$r['bukti']."'",$module);
           input_only_log("delete from  jurnal_umum where kode_nota='".$r['bukti']." - Titip'",$module);
       input_data("delete from  trans_bayarjual_header where bukti_bayarjual='".$r['bukti']."'",$module);
} 
}
?>
