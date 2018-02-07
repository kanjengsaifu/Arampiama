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
$awal=$_POST['rekapitulasipenjualanawal'];
$akhir=$_POST['rekapitulasipenjualanakhir'];
$id_customer = $_POST['id_customerrekapitulasi'];

$select=mysql_query("select * from customer where id_customer =".$id_customer);
$k= mysql_fetch_array($select);
date_default_timezone_set("Asia/Jakarta");

$select=mysql_query("SELECT * FROM `region`r,customer s WHERE r.id_region=s.id_region  and  id_customer =".$id_customer);
$k= mysql_fetch_array($select);
date_default_timezone_set("Asia/Jakarta");
echo '
<div style="text-align:Center" >
<h2>Rekapitulasi Penjualan Per Customer</h2>
</BR>UD. MELATI

</div>
<table style="width: 100%;">
<tr style="font-size: 26px;">
 <td>Nama customer</td>
 <td>:</td>
  <td colspan="2"><strong>'.$k['kode_customer'].'- '.$k['nama_customer'].' ('.$k['alamat_customer'].')</strong></td>
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
<th>No Nota</th>
<th>Tanggal</th>
<th>Kode Barang</th>
<th>Nama Barang</th>
<th>QTY-Satuan</th>
<th>Harga </th>
<th>Disc</th>
<th>Potonga (disc 5)</th>
<th>Total Netto</th>
<th>Total Harga</th>
<th>all PPN</th>
<th>all Disc</th>
<th> Grand Total</th>
</tr>
</thead>
<tbody>';
$query=mysql_query("SELECT * FROM `trans_sales_invoice` WHERE is_void=0 and tgl_update >='".$awal."' and tgl_update  <='".$akhir."' and id_customer= '".$id_customer."' order by id_invoice");


$satu=0;
$dua=0;
$tiga=0;
$empat=0;
$lima=0;

$no=1;
while ($tampil=mysql_fetch_array($query)) {
  $temp=1;
$squery="SELECT *,total as jumlah_netto FROM `trans_sales_invoice_detail` t,barang b WHERE b.id_barang=t.id_barang and id_invoice='".$tampil['id_invoice']."'";
$select=mysql_query($squery);
$count=mysql_num_rows($select);
while ($r=mysql_fetch_array($select)) {
  if (($temp==1) and ($temp==$count)){
 echo "
      <tr>
      <td>".$no."</td>
      <td>".$r['id_invoice']."</td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_update]))."</td>
      <td>".$r['kode_barang']."</td>
      <td>".$r['nama_barang']."</td>
       <td>".$r['qty_si']." - ".$r['qty_si_satuan']."</td>
      <td align=right>".format_jumlah($r['harga_si'])."</td>
      <td align=right>".$r['disc1'].'% '.$r['disc2'].'% '.$r['disc3'].'% '.$r['disc4']. "%</td>
        <td align=right>".$r['disc5']."</td>
      <td align=right>".format_jumlah($r['jumlah_netto'])."</td>
        <td align=right>".format_jumlah($tampil['alltotal'])."</td>
      <td align=right>".format_jumlah($tampil['allppnnominal'])."</td>
      <td align=right>".format_jumlah($tampil['alldiscnominal'])."</td>
      <td align=right>".format_jumlah($tampil['grand_total'])."</td>
      </tr>
    " ;
      $dua+=$tampil['alltotal'];
  $tiga+=$tampil['allppnnominal'];
  $empat+=$tampil['alldiscnominal'];
  $lima+=$tampil['grand_total'];
  }elseif ($temp==1) {
    $temp++;
    echo "
      <tr>
      <td>".$no."</td>
      <td>".$r['id_invoice']."</td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_update]))."</td>
      <td>".$r['kode_barang']."</td>
      <td>".$r['nama_barang']."</td>
       <td>".$r['qty_si']." - ".$r['qty_si_satuan']."</td>
      <td align=right>".format_jumlah($r['harga_si'])."</td>
      <td align=right>".$r['disc1'].'% '.$r['disc2'].'% '.$r['disc3'].'% '.$r['disc4']. "%</td>
        <td align=right>".$r['disc5']."</td>
      <td align=right>".format_jumlah($r['jumlah_netto'])."</td>
      <td align=right></td>
      <td align=right></td>
 <td align=right></td>
      <td align=right></td>
      </tr>
    " ;
  $satu+=$tampil['jumlah_netto'];

  }else  if ($temp==$count){
    $temp++;
    echo "
      <tr>
      <td>".$no."</td>
      <td></td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_update]))."</td>
      <td>".$r['kode_barang']."</td>
      <td>".$r['nama_barang']."</td>
       <td>".$r['qty_si']." - ".$r['qty_si_satuan']."</td>
      <td align=right>".format_jumlah($r['harga_si'])."</td>
      <td align=right>".$r['disc1'].'% '.$r['disc2'].'% '.$r['disc3'].'% '.$r['disc4']. "%</td>
        <td align=right>".$r['disc5']."</td>
      <td align=right>".format_jumlah($r['jumlah_netto'])."</td>
      <td align=right>".format_jumlah($tampil['alltotal'])."</td>
      <td align=right>".format_jumlah($tampil['allppnnominal'])."</td>
      <td align=right>".format_jumlah($tampil['alldiscnominal'])."</td>
      <td align=right>".format_jumlah($tampil['grand_total'])."</td>
      </tr>
    " ;
      $dua+=$tampil['alltotal'];
  $tiga+=$tampil['allppnnominal'];
  $empat+=$tampil['alldiscnominal'];
  $lima+=$tampil['grand_total'];
  }else{
    $temp++;
  echo "
      <tr>
      <td>".$no."</td>
      <td></td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_update]))."</td>
      <td>".$r['kode_barang']."</td>
      <td>".$r['nama_barang']."</td>
       <td>".$r['qty_si']." - ".$r['qty_si_satuan']."</td>
      <td align=right>".format_jumlah($r['harga_si'])."</td>
      <td align=right>".$r['disc1'].'% '.$r['disc2'].'% '.$r['disc3'].'% '.$r['disc4']. "%</td>
        <td align=right>".$r['disc5']."</td>
      <td align=right>".format_jumlah($r['jumlah_netto'])."</td>
      <td align=right></td>
      <td align=right></td>
      <td align=right></td>
      <td align=right></td>
      </tr>
    " ;
  }

  $no++;
}

}


echo"
</tbody>
<tfoot>
  <tr>
    <td colspan=9 align=right>JUMLAH : </td>
    <td align=right></td>
    <td align=right>".format_jumlah($dua)."</td>
    <td align=right>".format_jumlah($tiga)."</td>
    <td align=right>".format_jumlah($empat)."</td>
    <td align=right>".format_jumlah($lima)."</td>
  </tr>
</tfoot>

</table>

";
  
}
}
?>

