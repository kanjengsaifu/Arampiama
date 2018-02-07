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
$awal=$_POST['min'];
$akhir=$_POST['max'];

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
  $no++;

  
}
echo "</tbody></table>";
  }



echo 'Tanggal Dibuat '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .'';
  
}

?>

