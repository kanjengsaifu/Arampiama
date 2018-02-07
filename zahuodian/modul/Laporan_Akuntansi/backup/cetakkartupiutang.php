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
$awal=$_POST['kartupiutangawal'];
$akhir=$_POST['kartupiutangakhir'];
$id_customerkp = $_POST['id_customerkartupiutang'];

$select=mysql_query("SELECT * FROM `region`r,customer s WHERE r.id_region=s.id_region  and id_customer =".$id_customerkp);
$k= mysql_fetch_array($select);
$query="DELETE from lap_rekap_piutang where (tgl_transaksi between '".$awal." ' and  '".$akhir." ') ";
input_only_log($query,$module);

$query=mysql_query("
select * from (SELECT id_invoice as nota,tgl_update as tgl_transaksi,id_customer,grand_total as pembelian,0 as pembayaran FROM trans_sales_invoice t
union
SELECT t.bukti_bayarjual as nota,t.tgl_update as tgl_transaksi ,t.id_customer,0 as pembelian,nominal_alokasi_detail_jual as Pembayaran
FROM trans_bayarjual_detail t,trans_bayarjual_header th where t.bukti_bayarjual=th.bukti_bayarjual  and th.status_titipan = 0 and t.is_void='0') as rekap
where (tgl_transaksi between '".$awal." ' and  '".$akhir." ') order by tgl_transaksi asc");
 
while ($r=mysql_fetch_array($query)) {
  $saldo_akhir=mysql_query("SELECT * from lap_rekap_piutang where id_customer =".$r['id_customer']." and date(tgl_transaksi) <= date('".$r['tgl_transaksi']."') order by id_lap_rekap_piutang desc ,date(tgl_transaksi)  limit 1");
  $s=mysql_fetch_array($saldo_akhir);
  if (empty($s['saldo_akhir'])){
    $qsaldo=0;
  }else{
     $qsaldo=$s['saldo_akhir'];
  }
  if ($r['pembayaran']=='0') {
   $qinsert=("Insert Into lap_rekap_piutang(nota,id_customer,tgl_transaksi,saldo_awal,pembelian,pembayaran,saldo_akhir,tgl_update) 
    values ('".$r['nota']."',".$r['id_customer'].",'".$r['tgl_transaksi']."',".$qsaldo.",".$r['pembelian'].",0,".($s['saldo_akhir']+$r['pembelian']).",now())");
  input_only_log($qinsert,$module);
  }else{
     $qinsert=("Insert Into lap_rekap_piutang(nota,id_customer,tgl_transaksi,saldo_awal,pembelian,pembayaran,saldo_akhir,tgl_update) 
    values ('".$r['nota']."',".$r['id_customer'].",'".$r['tgl_transaksi']."',".$qsaldo.",0,".$r['pembayaran'].",".($s['saldo_akhir']-$r['pembayaran']).",now())");
  input_only_log($qinsert,$module);
  }
  
}

date_default_timezone_set("Asia/Jakarta");
echo '
<table style="width: 100%;">
<tr>
<th colspan="4"  style="font-size: 26px;">Kartu Piutang Per customer</th>
</tr>
<tr >
<th  colspan="4" >UD. MELATI</th>
</tr>
<tr style="font-size: 26px;">
 <td>Nama Customer</td>
 <td>:</td>
  <td colspan="2"><strong>'.$k['kode_customer'].'-'.$k['nama_customer'].' ('.$k['alamat_customer'].' )</td>
</tr>
<tr>
 <td>Tanggal Laporan</td>
 <td>:</td>
  <td>'.tgl_indo($awal) .'s/d'. tgl_indo($akhir).'</td>
  <td>Rayon :  '.$k['region'].'</td>
</tr>


</table>
<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>
<tr>
  <th>No</th>
  <th>Tanggal</th>
  <th>No. Nota</th>
  <th>Keterangan</th>
  <th>Penjualan</th>
  <th>Pelunasan</th>
  <th>Sisa</th>
</tr>
</thead>
<tbody>';

$query = mysql_query("select *,if(nota like 'SI%','Penjualan','Pembayaran') as keterangan from lap_rekap_piutang where tgl_transaksi >='".$awal."' and tgl_transaksi <='".$akhir."' and id_customer= '".$id_customerkp."'");
$satu=0;
$dua=0;
$tiga=0;
$no=1;
while ($tampil=mysql_fetch_array($query)) {
  if ($no==1) {
          echo "
      <tr>
      <td align=center></td>
      <td></td>
      <td></td>
      <td>Saldo Awal</td>
      <td align=right></td>
      <td align=right></td>      
      <td align=right>".format_jumlah($tampil['saldo_awal'])."</td>
      </tr>
    " ;
     echo "

      <tr>
      <td align=center>".$no."</td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_transaksi]))."</td>
      <td>".$tampil['nota']."</td>
      <td>".$tampil['keterangan']."</td>
      <td align=right>".format_jumlah($tampil['pembelian'])."</td>
      <td align=right>".format_jumlah($tampil['pembayaran'])."</td>      
      <td align=right>".format_jumlah($tampil['saldo_akhir'])."</td>
      </tr>
    " ;
  }else{
        echo "
      <tr>
      <td align=center>".$no."</td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_transaksi]))."</td>
      <td>".$tampil['nota']."</td>
      <td>".$tampil['keterangan']."</td>
      <td align=right>".format_jumlah($tampil['pembelian'])."</td>
      <td align=right>".format_jumlah($tampil['pembayaran'])."</td>      
      <td align=right>".format_jumlah($tampil['saldo_akhir'])."</td>
      </tr>
    " ;

  }

  $satu+=$tampil['pembelian'];
  $dua+=$tampil['pembayaran'];
  $tiga=$tampil['saldo_akhir'];
  $no++;
}

echo"
</tbody>
<tfoot>
  <tr>
    <td colspan=4 align=right>JUMLAH : </td>
    <td align=right>".format_jumlah($satu)."</td>
    <td align=right>".format_jumlah($dua)."</td>
    <td align=right>".format_jumlah($satu-$dua)."</td>
  </tr>
</tfoot>

</table>

";
echo 'Tanggal Dibuat '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .'';
  
}
}
?>

