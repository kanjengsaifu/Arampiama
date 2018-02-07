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
$awal_tanggal=$_POST['awal_tanggal_harian'];
$akhir_tanggal=$_POST['akhir_tanggal_harian'];
date_default_timezone_set("Asia/Jakarta");
//$aksi="modul/purchaseorder/aksi_purchaseorder.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
echo '
<div style="text-align:Center" >
<h2>KAS-BANK HARIAN</h2>
</BR>UD. MELATI

</div>
 <p align=right>Tanggal Laporan : '.tgl_indo($awal_tanggal).' S/D '.tgl_indo($akhir_tanggal).' </p>
<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>
  <tr>
    <th>No. Bukti </th>
    <th>Kode Perk </th>
    <th>Keterangan</th>
    <th colspan="2">Kas</th>
    <th colspan="2">Giro</th>
    <th colspan="2">Bank</th>
  </tr>
  <tr>
    <th colspan="3">&nbsp;</th>
    <th>Penerimaan</th>
    <th>Pengeluaran</th>
    <th>Penerimaan</th>
    <th>Pengeluaran</th>
    <th>Penerimaan</th>
    <th>Pengeluaran</th>
  </tr>
</thead>
<tbody>';
$query="SELECT akp2.nama_akunkasperkiraan,`debet_kredit`,`nominal`,`kode_nota`,`header`,keterangan,akp2.kode_akun,akp.kode_akun_header FROM `jurnal_umum` ju,akun_kas_perkiraan akp,akun_kas_perkiraan akp2 WHERE akp.id_akunkasperkiraan=ju.`id_detail`and akp2.id_akunkasperkiraan=ju.`id_akun_kas_perkiraan`and(akp.kode_akun_header='111' or akp.kode_akun_header='113' or akp.kode_akun_header='312') and tanggal between '".$awal_tanggal."' and '".$akhir_tanggal."' 

    
";
echo $query;
$query=mysql_query($query);
$kas_d=0;
$kas_k=0;
$giro_d=0;
$giro_k=0;
$bank_d=0;
$bank_k=0;
while ($r=mysql_fetch_array($query)) {
echo "
<tr>
<td>$r[kode_nota]</td>
<td>$r[kode_akun] - $r[nama_akunkasperkiraan]</td>
<td>$r[keterangan]</td>";
if ($r['kode_akun_header']=='111') {
  if ($r['debet_kredit']=='D') {
       Echo "<td></td><td align='right'>".format_jumlah($r[nominal])."</td>";
       $kas_d +=$r['nominal'];
      }else{
     Echo "<td align='right'>".format_jumlah($r[nominal])."</td><td></td>";
      $kas_k +=$r['nominal'];
         }
}else{
  Echo "<td></td><td></td>";
}
if ($r['kode_akun_header']=='312') {
  if ($r['debet_kredit']=='D') {
       Echo "<td></td><td align='right'>".format_jumlah($r[nominal])."</td>";
        $giro_d +=$r['nominal'];
      }else{
     Echo "<td align='right'>".format_jumlah($r[nominal])."</td><td></td>";
        $giro_k +=$r['nominal'];
         }
}else{
  Echo "<td></td><td></td>";
}

if ($r['kode_akun_header']=='113') {
  if ($r['debet_kredit']=='D') {
       Echo "<td></td><td align='right'>".format_jumlah($r[nominal])."</td>";
        $bank_d +=$r['nominal'];
      }else{
     Echo "<td align='right'>".format_jumlah($r[nominal])."</td><td></td>";
        $bank_k +=$r['nominal'];
         }
}else{
  Echo "<td></td><td></td>";
}


echo "</tr>";
  
    
}
echo'
</tbody>
  <tfoot>';

echo "
<!-- ###################################### Total  #####################################-->
   <tr>
     
    <td style='text-align:left' colspan='3'>Total</td>
    <td style='text-align:right' >".format_jumlah(intval($kas_k))."</td>
    <td style='text-align:right' >".format_jumlah(intval($kas_d))."</td>
    <td style='text-align:right' >".format_jumlah(intval($giro_k))."</td>
    <td style='text-align:right' >".format_jumlah(intval($giro_d))."</td>
    <td style='text-align:right' >".format_jumlah(intval($bank_k))."</td>
    <td style='text-align:right' >".format_jumlah(intval($bank_d))."</td>
  </tr>
<!-- ###################################### Total  #####################################-->
  ";
  $query= "SELECT id_akunkasperkiraan,kode_akun_header FROM akun_kas_perkiraan akp where (kode_akun_header='113' or kode_akun_header='115' or kode_akun_header='111')";
  $query=mysql_query($query);
  while ($id_akun=mysql_fetch_array($query)) {
     $query_bb= "SELECT saldo_akhir,kode_akun_header FROM `buku_besar` bb, akun_kas_perkiraan akp where bb.`id_akunkasperkiraan`=akp.`id_akunkasperkiraan` and bb.id_akunkasperkiraan='".$id_akun['id_akunkasperkiraan']."' and tanggal < '".$awal_tanggal."' order by id_buku_besar desc limit 1 ";
         $query_bb=mysql_query($query_bb);
          $query_bb=mysql_fetch_array($query_bb);
         if ($query_bb['kode_akun_header']=='111') {
          $awal_kas +=$query_bb['saldo_akhir'];
         }
           if ($query_bb['kode_akun_header']=='115') {
          $awal_giro +=$query_bb['saldo_akhir'];
         }
           if ($query_bb['kode_akun_header']=='113') {
          $awal_bank +=$query_bb['saldo_akhir'];
         }
  }

  echo "
<!-- ###################################### saldo awal  #####################################-->

   <tr>
    <th style='text-align:left' colspan='3'>Saldo Awal</th>
    <td style='text-align:right'>".format_jumlah($awal_kas)."</td>
    <td></td>
    <td style='text-align:right'>".format_jumlah($awal_giro)."</td>
    <td></td>
    <td style='text-align:right'>".format_jumlah($awal_bank)."</td>
    <td></td>
  </tr>
  <!-- ###################################### saldo awal  #####################################-->
  <!-- ###################################### saldo akhir  #####################################-->
   <tr>
    <th colspan='3' style='text-align:left' >Saldo Akhir</th>
    <td></td>
    <td style='text-align:right'>".format_jumlah($kas_k+$awal_kas-$kas_d)."</td>
    <td></td>
    <td style='text-align:right'>".format_jumlah($giro_k+$awal_giro-$giro_d)."</td>
    <td></td>
     <td style='text-align:right'>".format_jumlah($bank_k+$awal_bank-$bank_d)."</td>
  </tr>
  <!-- ###################################### saldo akhir  #####################################-->
  <!-- ###################################### Grand Total  #####################################-->
   <tr>
    <th colspan='3' style='text-align:left' >Grand Total</th>
    <td style='text-align:right' >".format_jumlah($kas_k+$awal_kas)."</td>
    <td style='text-align:right' >".format_jumlah($kas_k+$awal_kas-$kas_d+$kas_d)."</td>
    <td style='text-align:right' >".format_jumlah($giro_k+$awal_giro)."</td>
    <td style='text-align:right' >".format_jumlah($giro_k+$awal_giro-$kas_d+$kas_d)."</td>
    <td style='text-align:right' >".format_jumlah($bank_k+$awal_bank)."</td>
    <td style='text-align:right' >".format_jumlah($bank_k+$awal_bank-$kas_d+$kas_d)."</td>
  </tr>
  <!-- ###################################### Grand Total  #####################################-->
  </tfoot>
</table>
<p>Tanggal Dibuat : ".tgl_indo(date("Y/m/d"))." - ".date("h:i:s a") ." </p>
";
  
}
}
?>

