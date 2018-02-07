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
if ($module=='kartubarang' AND $act=='generate'){
$query="DELETE from lap_rekap_barang where (tgl_transaksi between '".$_POST['periode_awal']." ' and  '".$_POST['periode_akhir']." ') ";
input_only_log($query,$module);
echo "aaa";
$query=mysql_query("
select * from (
SELECT
tl.tgl_update as tgl_transaksi,
tl.id_lpb as nota,
nama_supplier as keterangan,
id_barang,
qty_pi_convert as masuk,
harga_pi as harga_masuk,
(qty_pi_convert*harga_pi) as rupiah_masuk,
'0' as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM supplier s,trans_invoice_detail td, trans_invoice t, trans_lpb tl
where td.id_invoice=t.id_invoice and s.id_supplier=t.id_supplier and tl.id_lpb=t.id_lpb and td.is_void=0 and t.is_void=0
union all
SELECT
t2.tgl_update as tgl_transaksi,
t.id_lpb as nota,
nama_supplier as keterangan,
id_barang,
qty_diterima_convert as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
'0' as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM trans_lpb_detail t,trans_lpb t2,supplier s where t2.is_void=0 and t.id_lpb=t2.id_lpb and s.id_supplier=t2.id_supplier and t.id_lpb not in(SELECT t3.id_lpb FROM trans_invoice t3)
union all
SELECT
tgl_update as tgl_transaksi,
'Adjustment' as nota,
'Adjustment' as keterangan,
id_barang ,
'0' as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
abs(plusminus_barang) as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM adjustment_stok where (plusminus_barang like '-%')
union all
SELECT
tgl_update as tgl_transaksi,
'Adjustment' as nota,
'Adjustment' as keterangan,
id_barang ,
abs(plusminus_barang) as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
'0' as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM adjustment_stok where (plusminus_barang not like '-%')
union all
SELECT
tl.tgl_update as tgl_transaksi,
tl.id_lkb as nota,
nama_customer as keterangan,
id_barang,
'0' as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
qty_si_convert as keluar,
harga_si as harga_keluar,
(qty_si_convert*harga_si) as rupiah_keluar
FROM customer s,trans_sales_invoice_detail td, trans_sales_invoice t, trans_lkb tl
where td.id_invoice=t.id_invoice and s.id_customer=t.id_customer and tl.id_lkb=t.id_lkb and td.is_void=0 and t.is_void=0
union all
SELECT
tl.tgl_update as tgl_transaksi,
tl.id_lkb as nota,
nama_customer as keterangan,
id_barang,
'0' as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
qty_si_convert as keluar,
harga_si as harga_keluar,
(qty_si_convert*harga_si) as rupiah_keluar
FROM customer s,trans_sales_invoice_detail td, trans_sales_invoice t, trans_lkb tl
where td.id_invoice=t.id_invoice and s.id_customer=t.id_customer and tl.id_lkb=t.id_lkb and td.is_void=0 and t.is_void=0
union all
SELECT
t2.tgl_update as tgl_transaksi,
t.id_lkb as nota,
nama_customer as keterangan,
id_barang,
'0' as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
qty_diterima_convert as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM trans_lkb_detail t,trans_lkb t2,customer s where t2.is_void=0 and t.id_lkb=t2.id_lkb and s.id_customer=t2.id_customer and t.id_lkb not in(SELECT t3.id_lkb FROM trans_sales_invoice t3)
union all
SELECT
tl.tgl_update  as tgl_transaksi,
tld.kode_rjb as nota,
nama_customer as keterangan,
id_barang,
'0' as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
qty_convert as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM trans_retur_penjualan_detail tld,trans_retur_penjualan tl,customer c WHERE c.id_customer=tl.id_customer and tl.kode_rjb=tld.kode_rjb and  tl.is_void=0
union all
SELECT
tl.tgl_update  as tgl_transaksi,
tl.kode_rjb as nota,
nama_customer as keterangan,
1 as id_barang,
qty_convert as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
'0' as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM trans_retur_penjualan_detail tld,trans_retur_penjualan tl,customer c
WHERE c.id_customer=tl.id_customer and tl.kode_rjb=tld.kode_rjb and  tl.is_void=0 and tl.is_void=0 and id_gudang = 1
union all
SELECT
tl.tgl_update  as tgl_transaksi,
tl.kode_rbb as nota,
nama_supplier as keterangan,
id_barang as id_barang,
'0' as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
qty_convert as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM trans_retur_pembelian_detail tld,trans_retur_pembelian tl,supplier s WHERE s.id_supplier=tl.id_supplier and tl.kode_rbb=tld.kode_rbb and  tl.is_void=0
  ) as rekap
where (tgl_transaksi between '".$_POST['periode_awal']." ' and  '".$_POST['periode_akhir']." ') order by tgl_transaksi asc");
  echo $query;
while ($r=mysql_fetch_array($query)) {
  $saldo_akhir=mysql_query("SELECT * from lap_rekap_barang where id_barang =".$r['id_barang']." and date(tgl_transaksi) <= date('".$r['tgl_transaksi']."') order by id_lap_rekap_barang desc ,date(tgl_transaksi)  limit 1");
  $s=mysql_fetch_array($saldo_akhir);
 if (empty($s['saldo_akhir'])){
    $qsaldo=0;
  }else{
     $qsaldo=$s['saldo_akhir'];
  }
   if ($r['keluar']=='0' && $r['masuk']=='0') {}else{
  if ($r['keluar']=='0') {
   $qinsert=("Insert Into lap_rekap_barang(nota,id_barang,tgl_transaksi,saldo_awal,masuk,keluar,saldo_akhir,tgl_update,harga_masuk,rupiah_masuk,keterangan) 
    values ('".$r['nota']."',".$r['id_barang'].",'".$r['tgl_transaksi']."',".$qsaldo.",".$r['masuk'].",0,".($s['saldo_akhir']+$r['masuk']).",now(),".$r['harga_masuk'].",".$r['rupiah_masuk'].",'".$r['keterangan']."')");
  input_only_log($qinsert,$module);
}else{  
     $qinsert=("Insert Into lap_rekap_barang(nota,id_barang,tgl_transaksi,saldo_awal,masuk,keluar,saldo_akhir,tgl_update,harga_keluar,rupiah_keluar,keterangan) 
    values ('".$r['nota']."',".$r['id_barang'].",'".$r['tgl_transaksi']."',".$qsaldo.",0,".$r['keluar'].",".($s['saldo_akhir']-$r['keluar']).",now(),".$r['harga_keluar'].",".$r['rupiah_keluar'].",'".$r['keterangan']."')");
  input_only_log($qinsert,$module);
  }
  }
}
 // header('location:../../media.php?module='.$module);
}
elseif ($module=='kreditmemopenjualan' AND $act=='input'){
  $query="update trans_retur_penjualan set no_invoice_terretur='$_POST[no_nota]', grandtotal_sebelum_terretur='".$_POST['no_nota_jumlah']."'  where kode_rbb='$_POST[no_nota_retur]'";
  input_only_log($query,$module);
  $query="update trans_sales_invoice set grand_total='$_POST[total]',status_lunas='1' where id_invoice='$_POST[no_nota]'";
  input_data($query,$module);
}
}
?>
