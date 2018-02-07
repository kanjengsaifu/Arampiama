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
$awal=$_POST['kartuhutangawal'];
$akhir=$_POST['kartuhutangakhir'];
$id_supplierkh = $_POST['id_supplierkartuhutang'];

$select=mysql_query("SELECT * FROM `region`r right join supplier s on (r.id_region=s.id_region)  where id_supplier =".$id_supplierkh);
$k= mysql_fetch_array($select);

 session_start();
generate_hutang($awal,$akhir,$_SESSION['username']);
date_default_timezone_set("Asia/Jakarta");
echo '

<table style="width: 100%;">
<tr>
<th colspan="4"  style="font-size: 26px;">Kartu Hutang Per Supplier</th>
</tr>
<tr >
<th  colspan="4" >UD. MELATI</th>
</tr>
<tr style="font-size: 26px;">
 <td>Nama Supplier</td>
 <td>:</td>
  <td colspan="2"><strong>'.$k['kode_supplier'].'-'.$k['nama_supplier'].' ('.$k['alamat_supplier'].' )</td>
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
   <th>Pembelian</th>
  <th>Pembayaran</th>
  <th>Sisa</th>
</tr>
</thead>
<tbody>';

$query = mysql_query("select * from lap_rekap_hutang l left join trans_invoice t on (t.id_invoice=l.nota) left join trans_bayarbeli_header a on (a.bukti_bayar=l.nota),akun_kas_perkiraan k  where  k.id_akunkasperkiraan=l.id_akun_kas_perkiraan and tgl_transaksi >='".$awal."' and tgl_transaksi <='".$akhir."' and l.id_supplier= '".$id_supplierkh."' ORDER BY id_lap_rekap_hutang");

$satu=0;
$dua=0;
$tiga=0;
$no=1;
$tamp=1;

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
      <td>".$tampil[nota]." - "
           .$tampil['no_nota'].$tampil['no_giro'].$tampil['rek_tujuan']." - "
           .$tampil['no_expedisi'].$tampil['ket']."</td>
      <td>".$tampil['nama_akunkasperkiraan']."</td>
      <td align=right>".format_jumlah($tampil['pembelian'])."</td>
      <td align=right>".format_jumlah($tampil['pembayaran'])."</td>      
      <td align=right>".format_jumlah($tampil['saldo_akhir'])."</td>
      </tr>
    " ;
  $satu+=$tampil['pembelian'];
  $dua+=$tampil['pembayaran'];
  $tiga=$tampil['saldo_akhir'];
  $no++;

  }else{

        echo "
      <tr>
      <td align=center>".$no."</td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_transaksi]))."</td>
      <td>".$tampil[nota]." - "
           .$tampil['no_nota'].$tampil['no_giro'].$tampil['rek_tujuan']." - "
           .$tampil['no_expedisi'].$tampil['ket']."</td>
      <td>".$tampil['nama_akunkasperkiraan']."</td>
      <td align=right>".format_jumlah($tampil['pembelian'])."</td>
      <td align=right>".format_jumlah($tampil['pembayaran'])."</td>      
      <td align=right>".format_jumlah($tampil['saldo_akhir'])."</td>
      </tr>
    " ;
  $satu+=$tampil['pembelian'];
  $dua+=$tampil['pembayaran'];
  $tiga=$tampil['saldo_akhir'];
  $no++;
}

  }


echo"
</tbody>
<tfoot>
  <tr>
    <td colspan=4 align=right>JUMLAH : </td>
    <td align=right>".format_jumlah($satu)."</td>
    <td align=right>".format_jumlah($dua)."</td>
    <td align=right>".format_jumlah($tiga)."</td>
  </tr>
</tfoot>

</table>

";
echo 'Tanggal Dibuat '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .'';
  
}
}
?>

