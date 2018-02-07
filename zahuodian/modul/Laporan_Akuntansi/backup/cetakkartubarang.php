<?php
  include "../../config/koneksi.php";
  include "../../lib/input.php";
    include "../../lib/fungsi_tanggal.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{

  $_ck = (array_search("1",$_SESSION['lvl'], true))?'true':'false';
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
//$aksi="modul/purchaseorder/aksi_purchaseorder.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
$awal=$_POST['awalkartubarang'];
$akhir=$_POST['akhirkartubarang'];
############################### Generate ############################################
$query="DELETE from lap_rekap_barang where (tgl_transaksi between '".$awal." ' and  '".$akhir." ') ";
input_only_log($query,$module);
$query=mysql_query("
select * from (select * from (
SELECT
tl.tgl_update as tgl_transaksi,
tl.id_lpb as nota,
nama_supplier as keterangan,
id_barang,
qty_pi_convert as masuk,
harga_pi as harga_masuk,
(qty_pi*harga_pi) as rupiah_masuk,
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
(qty_si*harga_si) as rupiah_keluar
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
 ) a where id_barang=6 ) as rekap
where (tgl_transaksi between '".$awal." ' and  '".$akhir." ') order by tgl_transaksi asc");
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
#####################################################################################


if ($_POST['id_barangkartubarang']==0) {
 date_default_timezone_set("Asia/Jakarta");
echo '

<table style="width: 100%;">
<tr>
<th colspan="4"  style="font-size: 26px;">Laporan Barang per Periode</th>
</tr>
<tr >
<th  colspan="4" >UD. MELATI</th>
</tr>
<tr>
 <td>Tanggal Laporan</td>
 <td>:</td>
  <td>'.tgl_indo($awal) .' s/d '. tgl_indo($akhir).'</td>
  <td>Rayon :  '.$k['region'].'</td>
</tr>


</table>


<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>
<tr>
  <th id="tablenumber">No</th>
      <th>Nama barang</th>
      <th>Satuan</th>
      <th>Saldo Awal Barang</th>
      <th>Barang Diterima</th>
      <th>Barang Dikeluarkan</th>
      <th>Saldo Akhir Barang</th>

</tr>
</thead>
<tbody>';
$query = mysql_query("SELECT l.id_barang,nama_barang,satuan1,saldo_awal,sum(masuk) as masuk ,sum(keluar) as keluar,
(saldo_awal+sum(masuk)-sum(keluar))as saldo_akhir
FROM lap_rekap_barang l,barang s where l.id_barang=s.id_barang
and date(tgl_transaksi) >= date('".$awal."') and date(tgl_transaksi) <= date('".$akhir."') group by l.id_barang");

$no=1;
$tamp=1;
while ($tampil=mysql_fetch_array($query)) {
                                            echo "
                                            <tr>
                                            <td align=center>".$no."</td>
                                            <td>".$tampil['nama_barang']."</td>
                                            <td>".$tampil['satuan1']."</td>
                                            <td  align=right>".format_jumlah($tampil['saldo_awal'])."</td>
                                            <td align=right>".format_jumlah($tampil['masuk'])."</td>
                                            <td align=right>".format_jumlah($tampil['keluar'])."</td>      
                                            <td align=right>".format_jumlah($tampil['saldo_akhir'])."</td>
                                            </tr>
                                            " ;
                                            $no++;}
echo "</tbody></table>";
echo 'Tanggal Dibuat '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .'';}
  



  else{
$barang=$_POST['id_barangkartubarang'];
$satuan= explode('#', $_POST['satuan']);

    date_default_timezone_set("Asia/Jakarta");
echo '

<table style="width: 100%;">
<tr>
<th colspan="4"  style="font-size: 26px;">Kartu Barang </th>
</tr>
<tr >
<th  colspan="4" >UD. MELATI</th>
</tr>
<tr>
 <td>Tanggal Laporan</td>
 <td>:</td>
  <td>'.tgl_indo($awal) .' s/d '. tgl_indo($akhir).'</td>
  <td>Satuan :  '.$satuan[1].'</td>
</tr>


</table>


<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
 <thead>
      <tr>
      <th id="tablenumber">No</th>
      <th>Tanggal Transaksi</th>
      <th>Nota</th>
      <th>Keterangan</th>
      <th>Harga </br> Jual</th> 
      <th colspan="3">Masuk</th>
      <th colspan="3">keluar</th>
      <th>Saldo Akhir Barang</th>

    </tr>
    <tr >
      <th colspan="5"></th>
      <th>Barang Masuk</th>
      <th>Harga</th>
      <th>Rupiah</th>
      <th>Barang keluar</th>
      <th>Harga</th>
      <th>Rupiah</th>
      <th>Saldo Akhir Barang</th>

    </tr>
        </thead>
<tbody>';
$query = mysql_query("SELECT * from(SELECT tgl_transaksi,b.id_barang,id_lap_rekap_barang,saldo_awal,saldo_akhir,rupiah_keluar,harga_keluar,keluar,rupiah_masuk,harga_masuk,masuk,harga_sat1,nota,keterangan FROM lap_rekap_barang l,barang b where b.id_barang=l.id_barang) as u where u.id_barang=$barang and date(tgl_transaksi) >= date('".$awal."') and date(tgl_transaksi) <= date('".$akhir."') order by id_lap_rekap_barang asc, tgl_transaksi asc");
$no=1;
$tamp=1;
$qty_m=0;
$qty_k=0;
$rup_m=0;
$rup_k=0;
while ($tampil=mysql_fetch_array($query)) {
  if ($no==1) {
     echo "
      <tr>
      <td align=center></td>
      <td></td>
      <td></td>
      <td>Saldo Awal</td>
      <td  align=right></td>
      <td align=right></td>
      <td align=right></td>      
      <td align=right></td>
       <td align=right></td>
      <td align=right></td>      
      <td align=right></td>
        <td align=right>".format_jumlah($tampil['saldo_awal']/$satuan[0])."</td>
      </tr>
    " ;
           echo "
      <tr>
      <td align=center>".$no."</td>
      <td>".$tampil['tgl_transaksi']."</td>
      <td>".$tampil['nota']."</td>
      <td>".$tampil['keterangan']."</td>
      <td  align=right>".format_jumlah($tampil['harga_sat1'])."</td>
      <td align=right>".$tampil['masuk']/$satuan[0]."</td>
      <td align=right>".format_jumlah($tampil['harga_masuk'])."</td>      
      <td align=right>".format_jumlah($tampil['rupiah_masuk'])."</td>
       <td align=right>".$tampil['keluar']/$satuan[0]."</td>
      <td align=right>".format_jumlah($tampil['harga_keluar'])."</td>      
      <td align=right>".format_jumlah($tampil['rupiah_keluar'])."</td>
        <td align=right>".$tampil['saldo_akhir']/$satuan[0]."</td>
      </tr>
    " ;
  $qty_m+=$tampil['masuk'];
$qty_k+=$tampil['keluar'];
$rup_m+=$tampil['rupiah_masuk'];
$rup_k+=$tampil['rupiah_keluar'];
  }else{

       echo "
      <tr>
      <td align=center>".$no."</td>
      <td>".$tampil['tgl_transaksi']."</td>
      <td>".$tampil['nota']."</td>
      <td>".$tampil['keterangan']."</td>
      <td  align=right>".format_jumlah($tampil['harga_sat1'])."</td>
      <td align=right>".$tampil['masuk']/$satuan[0]."</td>
      <td align=right>".format_jumlah($tampil['harga_masuk'])."</td>      
      <td align=right>".format_jumlah($tampil['rupiah_masuk'])."</td>
       <td align=right>".$tampil['keluar']/$satuan[0]."</td>
      <td align=right>".format_jumlah($tampil['harga_keluar'])."</td>      
      <td align=right>".format_jumlah($tampil['rupiah_keluar'])."</td>
        <td align=right>".$tampil['saldo_akhir']/$satuan[0]."</td>
      </tr>
    " ;
  $qty_m+=$tampil['masuk'];
$qty_k+=$tampil['keluar'];
$rup_m+=$tampil['rupiah_masuk'];
$rup_k+=$tampil['rupiah_keluar'];
$ssaldo=$tampil['saldo_akhir'];
  }

  $no++;

}
echo "</tbody>
<tfoot>
      <tr>
      <td align=center colspan='4'>Jumlah</td>

      <td  align=right></td>
      <td align=right>".format_jumlah($qty_m)."</td>
      <td align=right></td>      
      <td align=right>".format_jumlah($rup_m)."</td>
       <td align=right>".format_jumlah($qty_k)."</td>
      <td align=right></td>      
      <td align=right>".format_jumlah($rup_k)."</td>
        <td align=right>".format_jumlah($ssaldo)."</td>
      </tr>
</tfoot>
</table>";
echo 'Tanggal Dibuat '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .'';

  }





}
}

?>

