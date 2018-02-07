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
if ($module=='pembayaranpembelian' AND $act=='hapus'){
      $query="SELECT * ,a.bukti_bayar as bukti FROM `trans_bayarbeli_header` a left join `trans_bayarbeli_detail` b on(a.`bukti_bayar`=b.`bukti_bayar`) where id_bayarbeli='$_GET[id]' and  b.bukti_bayar is null group by a.bukti_bayar  ";
      $result=mysql_query($query);
      $r=mysql_fetch_array($result);
        input_only_log("delete from  jurnal_umum where kode_nota='".$r['bukti']."'",$module);
           input_only_log("delete from  jurnal_umum where kode_nota='".$r['bukti']." - Titip'",$module);
       input_data("delete from  trans_bayarbeli_header where bukti_bayar='".$r['bukti']."'",$module);
} 
}
?>
