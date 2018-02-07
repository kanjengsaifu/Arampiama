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
$awal=$_POST['arusbankawal'];
$akhir=$_POST['arusbankakhir'];
generate_buku_besar($awal,$akhir,$_SESSION['username']);

date_default_timezone_set("Asia/Jakarta");
echo '
<div style="text-align:Center" >
<h2>Laporan Bank Perperiode</h2>
</BR>UD. MELATI
</div>
<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>
<tr>
<th colspan="9">Tanggal : '.tgl_indo($awal) .' Sd. '.tgl_indo($akhir).' </th>
';
$select=mysql_query("select * from buku_besar bb,akun_kas_perkiraan  akp where akp.id_akunkasperkiraan=bb.id_akunkasperkiraan and kode_akun_header=113 and tanggal between '".$awal."' and '".$akhir."'  order by id_buku_besar");
$tamp[] ="";
$id_tmp[] ="";
while ($th=mysql_fetch_array($select)) {
    if ((array_search($th['nama_akunkasperkiraan'],$tamp)==false)) {
      $tamp[]=$th['nama_akunkasperkiraan'];
      $id_tmp[]=$th['id_akunkasperkiraan'];
    }
  }
echo '
</tr>
  <tr>
    <th>No.</th>
    <th>No. Bukti </th>
    <th>Kode Perk </th>';
for ($i=1; $i < count($tamp) ; $i++) { 
 echo '<th colspan="2">'.$tamp[$i].'</th>';
}
    echo '
  </tr>
  <tr>
    <th colspan="3">&nbsp;</th>';
for ($i=1; $i < count($tamp) ; $i++) { 
 echo '<th>Penerimaan</th>
    <th>Pengeluaran</th>';
}
echo '
  </tr>
</thead>
<tbody>';
$no=1;
for ($i=1; $i < count($id_tmp) ; $i++) { 
$select=mysql_query("select * from buku_besar bb,akun_kas_perkiraan  akp where akp.id_akunkasperkiraan=bb.id_akunkasperkiraan and bb.id_akunkasperkiraan='".$id_tmp[$i]."' and tanggal between '".$awal."' and '".$akhir."' order by id_buku_besar");
while ($tampil=mysql_fetch_array($select)) {
  echo "
  <tr>
  <td align=center>$no</td>
  <td style='text-align:left' >".date('d-m-Y', strtotime($tampil[tanggal]))."</td>
  <td style='text-align:left' >".$tampil['no_nota']."</td>";
for ($k=1; $k < count($id_tmp); $k++) { 
  if ($k==$i) {
       echo "<td align='right'>".format_jumlah($tampil[debet])."</td><td align='right'>".format_jumlah($tampil[kredit])."</td>";
  }else{
     echo "<td></td><td></td>";
  }

}
  echo "
  </tr>";
  $no++;
  $s_akhir=$tampil['saldo_akhir'];
}
$saldo_akhir[]=$s_akhir;
}
echo"
</tbody>
<tr>
<td colspan=3>Total</td>
";
for ($k=0; $k < count($saldo_akhir); $k++) { 
  if ($saldo_akhir[$k]>=0) {
    echo "<td align='right'>".format_jumlah($saldo_akhir[$k])."</td><td></td>";
  }else{
    echo "<td></td><td align='right'>".format_jumlah($saldo_akhir[$k])."</td>";
  }
     
}
echo"
</tr>
</table>
";
echo 'Tanggal Dibuat : '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .'';
  
}
}
?>

