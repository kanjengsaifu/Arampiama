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
if ($module=='laporantitipansupplier' AND $act=='generate'){
$query="DELETE from lap_rekap_titipan_supplier where (tgl_transaksi between '".$_POST['periode_awal']." ' and  '".$_POST['periode_akhir']." ') ";
input_only_log($query,$module);

$query=mysql_query("
select * from (SELECT id_invoice as nota, tgl_update as tgl_transaksi,id_supplier,grand_total as pembelian,0 as pembayaran FROM trans_invoice t where is_void=0
union
SELECT bukti_bayar as nota, tgl_update as tgl_transaksi ,id_supplier,0 as pembelian,nominal_alokasi as Pembayaran FROM trans_bayarbeli_detail t RIGHT JOIN trans_bayarbeli_header tb ON(tb.bukti_bayar=t.bukti_bayar) where t.is_void=0 AND tb.status_titipan='T') as rekap
where (tgl_transaksi between '".$_POST['periode_awal']." ' and  '".$_POST['periode_akhir']." ') order by tgl_transaksi asc");
  echo $query;
while ($r=mysql_fetch_array($query)) {
  $saldo_akhir=mysql_query("SELECT * from  laporantitipansupplier where id_supplier =".$r['id_supplier']." and date(tgl_transaksi) <= date('".$r['tgl_transaksi']."') order by id_lap_rekap_hutang desc ,date(tgl_transaksi)  limit 1");
  $s=mysql_fetch_array($saldo_akhir);
  echo "SELECT * from  laporantitipansupplier where id_supplier =".$r['id_supplier']." and date(tgl_transaksi) <= date('".$r['tgl_transaksi']."') order by id_lap_rekap_hutang desc ,date(tgl_transaksi)  limit 1</br>";
  if (empty($s['saldo_akhir'])){
    $qsaldo=0;
  }else{
     $qsaldo=$s['saldo_akhir'];
  }
  if ($r['pembayaran']=='0') {
   $qinsert=("Insert Into  laporantitipansupplier(nota,id_supplier,tgl_transaksi,saldo_awal,pembelian,pembayaran,saldo_akhir,tgl_update) 
    values ('".$r['nota']."',".$r['id_supplier'].",'".$r['tgl_transaksi']."',".$qsaldo.",".$r['pembelian'].",0,".($s['saldo_akhir']+$r['pembelian']).",now())");
  input_only_log($qinsert,$module);
  }else{
     $qinsert=("Insert Into laporantitipansupplier(nota,id_supplier,tgl_transaksi,saldo_awal,pembelian,pembayaran,saldo_akhir,tgl_update) 
    values ('".$r['nota']."',".$r['id_supplier'].",'".$r['tgl_transaksi']."',".$qsaldo.",0,".$r['pembayaran'].",".($s['saldo_akhir']-$r['pembayaran']).",now())");
  input_only_log($qinsert,$module);
  }
  
}
}
elseif ($module=='kreditmemopenjualan' AND $act=='input'){
  $query="update trans_retur_penjualan set no_invoice_terretur='$_POST[no_nota]', grandtotal_sebelum_terretur='".$_POST['no_nota_jumlah']."'  where kode_rbb='$_POST[no_nota_retur]'";
  input_only_log($query,$module);
  $query="update trans_sales_invoice set grand_total='$_POST[total]',status_lunas='1' where id_invoice='$_POST[no_nota]'";
  input_data($query,$module);
}
}
?>
