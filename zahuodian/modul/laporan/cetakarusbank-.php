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


$select=mysql_query("SELECT id_akunkasperkiraan,nama_akunkasperkiraan FROM akun_kas_perkiraan a, akun_header ah where a.kode_akun_header=ah.kode_akun_header and ah.kode_akun_header=113");
while ($id= mysql_fetch_array($select)) {
  $debet ="debet".$id['id_akunkasperkiraan'];
  $kredit ="kredit".$id['id_akunkasperkiraan'];
  $tname .= $id['nama_akunkasperkiraan'].'@';
  $ttamp .= $debet.'#'.$kredit.'#';
  $kas_keluar.= "if(id_akunkasperkiraan=".$id['id_akunkasperkiraan'].",'','') as ".$debet.",if(id_akunkasperkiraan=".$id['id_akunkasperkiraan'].",nominal,'') as ".$kredit.",";
  $kas_masuk .="if(id_akunkasperkiraan=".$id['id_akunkasperkiraan'].",nominaljual,'') as ".$debet.",if(id_akunkasperkiraan=".$id['id_akunkasperkiraan'].",'','') as ".$kredit.",";
}
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

</tr>
  <tr>
    <th>No.</th>
    <th>No. Bukti </th>
    <th>Kode Perk </th>';
$tname=explode('@', $tname);
for ($i=0; $i < (count($tname)-1) ; $i++) { 
 echo '<th colspan="2">'.$tname[$i].'</th>';
}
    echo '
  </tr>
  <tr>
    <th colspan="3">&nbsp;</th>';
for ($i=0; $i < (count($tname)-1) ; $i++) { 
 echo '<th>Penerimaan</th>
    <th>Pengeluaran</th>';
}
echo '
  </tr>
</thead>
<tbody>';


$query = mysql_query("select * from (
SELECT tgl_pembayaran as tanggal,".$kas_keluar."bukti_bayar
FROM trans_bayarbeli_header t where  bukti_bayar like 'BBK%'
union all
SELECT tgl_giro_cair as tanggal,".$kas_keluar."bukti_bayar
FROM trans_bayarbeli_header t where  bukti_bayar like 'BGK%' and tgl_giro_cair !='0000-00-00'
union all
SELECT tgl_pembayaranjual as tanggal,".$kas_masuk."bukti_bayarjual
FROM trans_bayarjual_header t where bukti_bayarjual like 'BBM%'
union all
SELECT tgl_giro_cair as tanggal,".$kas_masuk."bukti_bayarjual
FROM trans_bayarjual_header t where  bukti_bayarjual like 'BGM%' and tgl_giro_cair !='0000-00-00' 
 ) as ttransaksi where tanggal >='".$awal."' and tanggal <='".$akhir."'");

$ttamp=explode('#', $ttamp);
$no=1;
while ($tampil=mysql_fetch_array($query)) {
  echo "
  <tr>
  <td align=center>$no</td>
  <td style='text-align:left' >".date('d-m-Y', strtotime($tampil[tanggal]))."</td>
  <td style='text-align:left' >".$tampil['bukti_bayar']."</td>";
  for ($i=0; $i < (count($ttamp)-1) ; $i++) { 
  echo " <td style='text-align:right' >".format_jumlah(intval($tampil[$ttamp[$i]]))."</td>" ;

  }
  echo "
  </tr>";
  $no++;
}
 for ($i=0; $i < (count($ttamp)-1) ; $i++) { 
  $tampung .='sum('.$ttamp[$i].'),';
 }
 $query = mysql_query("select ".$tampung."tanggal from (
SELECT tgl_pembayaran as tanggal,".$kas_keluar."bukti_bayar
FROM trans_bayarbeli_header t where  bukti_bayar like 'BBK%'
union all
SELECT tgl_giro_cair as tanggal,".$kas_keluar."bukti_bayar
FROM trans_bayarbeli_header t where  bukti_bayar like 'BGK%' and tgl_giro_cair !='0000-00-00'
union all
SELECT tgl_pembayaranjual as tanggal,".$kas_masuk."bukti_bayarjual
FROM trans_bayarjual_header t where bukti_bayarjual like 'BBM%'
union all
SELECT tgl_giro_cair as tanggal,".$kas_masuk."bukti_bayarjual
FROM trans_bayarjual_header t where  bukti_bayarjual like 'BGM%' and tgl_giro_cair !='0000-00-00'
 ) as ttransaksi where tanggal >='".$awal."' and tanggal <='".$akhir."'");
 $k=mysql_fetch_array($query);
echo"
</tbody>
<tfoot>
<tr>
  <td>&nbsp;</td>
  <td colspan='2'>Total :</td>";
  $ttampung=explode(',', $tampung);
  for ($i=0; $i < (count($ttampung)-1) ; $i++) { 
  echo " <td style='text-align:right' >".format_jumlah(intval($k[$ttampung[$i]]))."</td>" ;
  }
echo "</tr>
</tfoot>
</table>
";
echo 'Tanggal Dibuat : '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .'';
  
}
}
?>

