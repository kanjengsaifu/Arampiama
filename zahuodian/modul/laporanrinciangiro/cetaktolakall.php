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
$awal=$_POST['tolakawal'];
$akhir=$_POST['tolakakhir'];

date_default_timezone_set("Asia/Jakarta");
echo '
<div style="text-align:Center" >
<h2>Rincian Giro Dibuka Yang Belum Cair</h2>
</BR>UD. MELATI

</div>
<table>
  <tr>
    <td>Tanggal Periode</td> 
    <td>:</td> 
    <td>'.tgl_indo($awal) .' Sd. '.tgl_indo($akhir).' </td>    
  </tr>
</table>
<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>
<tr>
  <td>No.</td>
  <td>Tanggal Terima</td>
  <td>Bank</td>
  <td>No. Giro</td>
  <td>Nama Customer</td>
  <td>Kota</td>
  <td>Tgl. J-T</td>
  <td>Jumlah</td>
  <!--td>Atas Nama</td-->
</tr>
</thead>
<tbody>';


$query = mysql_query("SELECT tgl_pembayaranjual as tanggal ,nama_akunkasperkiraan,no_giro_jual,nama_customer,region,jatuh_tempo_jual,nominaljual,ac_asal FROM (
SELECT t.*,t.jatuh_tempo_jual as tanggal,s.nama_customer,r.region FROM trans_bayarjual_header t, trans_bayarjual_detail d, customer s, region r where t.bukti_bayarjual=d.bukti_bayarjual AND d.id_customer=s.id_customer AND s.id_region=r.id_region AND t.is_void='0' and t.bukti_bayarjual like 'BGM%' GROUP BY d.bukti_bayarjual) as tg,akun_kas_perkiraan akp where akp.id_akunkasperkiraan=tg.id_akunkasperkiraan and status_giro_jual=1 and giro_ditolak_jual=1 AND tanggal >='".$awal."' and tanggal <='".$akhir."'");

$no=1;
while ($tampil=mysql_fetch_array($query)) {
  echo "
  <tr>
    <td align=center>".$no."</td>
    <td style='text-align:left' >".date("d-m-Y", strtotime($tampil[tanggal]))."</td>
    <td style='text-align:left' >".$tampil['nama_akunkasperkiraan']."</td>
    <td>$tampil[no_giro_jual]</td>
    <td>$tampil[nama_customer]</td>
    <td>$tampil[region]</td>
    <td style='text-align:left' >".date("d-m-Y", strtotime($tampil[jatuh_tempo_jual]))."</td>
    <td style='text-align:right' >".format_jumlah(intval($tampil['nominaljual']))."</td>
    <!--td style='text-align:left' >".$tampil['ac_asal']."</td-->
  </tr>
  ";
  $no++;
}
echo"
</tbody>
</table>
<table>
  <tr>
    <td>Tanggal Cetak<td>
    <td>:</td>
    <td>".tgl_indo(date("Y/m/d"))." - ".date("h:i:s a") ." </td>
  </tr>
</table>
";
  
}
}
?>

