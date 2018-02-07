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
$awal=$_POST['tglawal1'];
$akhir=$_POST['tglakhir1'];
$ket=$_POST['ket2'];
$id_customer=$_POST['id_customer'];
$tampung="";
$tampungtitle="";
if ($ket!=0) {
  if ($ket==1) {
  $tampung=' and  status_giro_jual=1 and Giro_ditolak_jual=0 ';
   $tampungtitle='yang Belum Dicairkan';
        }elseif ($ket==2) {
    $tampung=' and  status_giro_jual=0 and Giro_ditolak_jual=0 ';
    $tampungtitle='yang Telah DI Cairkan';
        }elseif ($ket==3) {
  $tampung=' and  status_giro_jual=1 and Giro_ditolak_jual=1 ';
  $tampungtitle='yang Ditolak';
}
}
else{
    $tampungtitle=' Semua';
}
if ($id_customer!=0) {
 $tampung.="and t.id_customer=".$id_customer."  ";
}
date_default_timezone_set("Asia/Jakarta");
echo '
<table  style="width: 100%;">
<tr>
<th colspan="3"  style="font-size: 26px;">Rincian Giro Diterima '.$tampungtitle.'</th>
</tr>
<tr >
<th  colspan="3" >UD. MELATI</th>
</tr>
<tr>
 <td>Periode Giro Jatuh Tempo : '.tgl_indo($awal) .' s/d '. tgl_indo($akhir).'</td>
 <td></td>
  <td></td>

</tr>
</table>
<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>
<tr>
  <th>No.</th>
  <th>Tgl. Bayar</th>
  <th>Nomor Pembayran</th>
  <th>Bank</th>
  <th>No. Giro</th>
  <th>Nama Supplier</th>
  <th>Kota</th>
  <th>Tgl. J-T</th>
  <th>Jumlah</th>
  <th>Cair Ke</th>
  <th>Tgl. Cair</th>
</tr>
</thead>
<tbody>'; 

$query="SELECT t.tgl_update,nama_bank,no_giro_jual,bukti_bayarjual,nama_customer,region,jatuh_tempo_jual,nominaljual,tgl_giro_cair,nama_akunkasperkiraan,Giro_ditolak_jual FROM customer c,region r,trans_bayarjual_header t left join akun_kas_perkiraan akp on (akp.id_akunkasperkiraan=t.id_akunkasperkiraan) where r.id_region=c.id_region and t.id_customer=c.id_customer and bukti_bayarjual like 'BGM%'  and jatuh_tempo_jual>= '".$awal."' and jatuh_tempo_jual<='".$akhir."'  ".$tampung."   order by nama_customer asc";
echo $query;
$query = mysql_query($query);

$no=1;
$temp=0;
while ($tampil=mysql_fetch_array($query)) {
  echo "
  <tr>
    <td align=center>".$no."</td>
    <td style='text-align:left' >".date("d-m-Y", strtotime($tampil[tgl_update]))."</td>
        <td style='text-align:left' >".$tampil['bukti_bayarjual']."</td>
    <td style='text-align:left' >".$tampil['nama_bank']."</td>
    <td>$tampil[no_giro_jual]</td>
    <td>$tampil[nama_customer]</td>
    <td>$tampil[region]</td>
    <td style='text-align:left' >".date("d-m-Y", strtotime($tampil[jatuh_tempo_jual]))."</td>
    <td style='text-align:right' >".format_jumlah(intval($tampil['nominaljual']))."</td>
     <td style='text-align:right' >".$tampil['nama_akunkasperkiraan']."</td>

";
$temp+=$tampil['nominaljual'];
if ($tampil[Giro_ditolak_jual]!=1) {
      if ($tampil[tgl_giro_cair]=='0000-00-00') {
      echo "<td></td>"; 
    }else{
      echo "<td>".date('d-m-Y', strtotime($tampil[tgl_giro_cair]))."</td>";
    }
}else{
        echo "<td>Ditolak</td>"; 
}
  echo "
  </tr>
  ";
  $no++;
}
echo"
</tbody>
<tfoot>
<td colspan='8'></td>
<td style='text-align:right'>".format_jumlah($temp)."</td>
<td colspan='2'></td>
</tfoot>
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