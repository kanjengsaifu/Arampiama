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
$awal=$_POST['awalgirodibuka'];
$akhir=$_POST['akhirgirodibuka'];
$ket=$_POST['ketgirodibuka'];
$id_customer=$_POST['id_customergirodibuka'];
$tampung="";
$tampungtitle="";
if ($ket!=0) {
  if ($ket==1) {
  $tampung=' and  status_giro=1 and Giro_ditolak=0  ';
   $tampungtitle='yang Belum Dicairkan';
        }elseif ($ket==2) {
    $tampung=' and  status_giro=0 and Giro_ditolak=0 ';
    $tampungtitle='yang Telah DI Cairkan';
        }elseif ($ket==3) {
  $tampung=' and  status_giro=1 and Giro_ditolak=1 ';
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
<th colspan="3"  style="font-size: 26px;">Rincian Giro Dibuka '.$tampungtitle.'</th>
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
    <th>Dari Bank</th>
  <th>No. Giro</th>
  <th>Nama Supplier</th>
  <th>Kota</th>
  <th>Tgl. J-T</th>
  <th>Jumlah</th>
  <th>Tgl. Cair</th>
</tr>
</thead>
<tbody>'; 

$query="SELECT t.tgl_update,bukti_bayar,no_giro,nama_supplier,region,jatuh_tempo,nominal,tgl_giro_cair,nama_akunkasperkiraan,Giro_ditolak FROM supplier c left join region r on (r.id_region=c.id_region),trans_bayarbeli_header t left join akun_kas_perkiraan akp on (akp.id_akunkasperkiraan=t.id_akunkasperkiraan) where t.id_supplier=c.id_supplier and bukti_bayar like 'BGk%'  and jatuh_tempo>= '".$awal."' and jatuh_tempo<='".$akhir."'  ".$tampung."   order by nama_supplier asc";

$query = mysql_query($query);

$no=1;
$temp=0;
while ($tampil=mysql_fetch_array($query)) {
  echo "
  <tr>
    <td align=center>".$no."</td>
    <td style='text-align:left' >".date("d-m-Y", strtotime($tampil[tgl_update]))."</td>
     <td style='text-align:left' >".$tampil['bukti_bayar']."</td>
    <td>$tampil[nama_akunkasperkiraan]</td>
    <td>$tampil[no_giro]</td>
    <td>$tampil[nama_supplier]</td>
    <td>$tampil[region]</td>
    <td style='text-align:left' >".date("d-m-Y", strtotime($tampil[jatuh_tempo]))."</td>
    <td style='text-align:right' >".format_jumlah(intval($tampil['nominal']))."</td>


";
$temp+=$tampil['nominal'];
if ($tampil[Giro_ditolak]!=1) {
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
<td colspan='7'></td>
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