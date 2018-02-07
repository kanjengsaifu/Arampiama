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
$awal=$_POST['tanggal_awalsales'];
$akhir=$_POST['tanggal_akhirsales'];
$id_sales =$_POST['id_sales'];
date_default_timezone_set("Asia/Jakarta");
$query=mysql_query("SELECT * from Sales Where id_sales= '$id_sales'");
$k=mysql_fetch_array($query);
if ($id_sales==0) {
  echo '
<table style="width: 100%;">
<tr>
<th colspan="4"  style="font-size: 26px;">LAPORAN PENJUALAN NOTA SALES</th>
</tr>
<tr >
<th  colspan="4" >UD. MELATI</th>
</tr>
<tr style="font-size: 26px;">
 <td>Nama Sales</td>
 <td>:</td>
  <td colspan="2"><strong> Semua Sales</td>
</tr>
<tr>
 <td>Tanggal Laporan</td>
 <td>:</td>
  <td>'.tgl_indo($awal) .'s/d'. tgl_indo($akhir).'</td>

</tr>
</table>';
$tquery=mysql_query("SELECT nama_sales,id_invoice,tsi.tgl_update as tanggal,nama_customer,tsi.no_nota
FROM trans_sales_order t,sales s,trans_lkb tl,trans_sales_invoice tsi,customer c
where s.id_sales=t.id_sales and tl.id_sales_order=t.id_sales_order and tl.id_lkb=tsi.id_lkb and c.id_customer=t.id_customer and 
tsi.tgl_update >= '".$awal."'  and tsi.tgl_update <='".$akhir."'  order by nama_sales ");
}else{
  echo '
<table style="width: 100%;">
<tr>
<th colspan="4"  style="font-size: 26px;">LAPORAN PENJUALAN NOTA SALES</th>
</tr>
<tr >
<th  colspan="4" >UD. MELATI</th>
</tr>
<tr style="font-size: 26px;">
 <td>Nama Sales</td>
 <td>:</td>
  <td colspan="2"><strong>'.$k['id_sales'].'-'.$k['nama_sales'].' ('.$k['telp1_sales'].' )</td>
</tr>
<tr>
 <td>Tanggal Laporan</td>
 <td>:</td>
  <td>'.tgl_indo($awal) .'s/d'. tgl_indo($akhir).'</td>

</tr>
</table>';
$tquery=mysql_query("SELECT nama_sales,id_invoice,tsi.tgl_update as tanggal,nama_customer,tsi.no_nota
FROM trans_sales_order t,sales s,trans_lkb tl,trans_sales_invoice tsi,customer c
where s.id_sales=t.id_sales and tl.id_sales_order=t.id_sales_order and tl.id_lkb=tsi.id_lkb and c.id_customer=t.id_customer and 
tsi.tgl_update >= '".$awal."'  and tsi.tgl_update <='".$akhir."' and s.id_sales='".$id_sales."' order by nama_sales
 ");
}

while ($t=mysql_fetch_array($tquery)) {
 ECHO '
 <table  width="100%" class="table table-hover" cellspacing=0>
 <tr>
<th align=right >Nama Sales :</th>
<th align=left colspan="1">'.$t['nama_sales'].'</th>
<th align=right >Tanggal Invoice</th>
<th align=left colspan="1">'.$t['tanggal'].'</th>
<th align=right >Nomor Invoice</th>
<th align=left colspan="1">'.$t['id_invoice'].'</th>
<th align=right >Nama Customer</th>
<th align=left colspan="1">'.$t['nama_customer'].'</th>
</tr>
 </table>
<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>

<tr>
  <th>No</th>
  <th>No. Nota</th>
  <th>Urian Barang</th>
  <th colspan ="2">Qty</th>
  <th>Harga</br>Satuan</th>
  <th>Disc </br> %</th>
  <th>Disc </br> Rp.</th>
  <th>Jumlah Netto </br> Rp.</th>
  <th>Disc </br> Potongan</th>
  <th>Jumlah </br> Total</th>
  <th>PPN</th>
  <th>Jumlah </br> Nota</th>
</tr>
</thead>
<tbody>';

$query = mysql_query("SELECT id_invoice,nama_barang,b.id_barang,qty_si_convert,harga_si,disc1,disc2,disc3,disc4,disc5,total
FROM trans_sales_invoice_detail t,barang b where t.id_barang=b.id_barang and id_invoice='$t[id_invoice]'");
$no=1;
while ($h=mysql_fetch_array($query)) {
 echo " <tr>
    <td >".$no."</td>
    <td >".$t['no_nota']."</td>
    <td >".$h['nama_barang']."</td>
    <td align=right >".convt_satuan($h['qty_si_convert'],$h['id_barang'])."</td>
    <td align=right > [".$h['qty_si_convert']."]</td>
    <td align=right >".format_jumlah($h['harga_si'])."</td>
    <td align=right >".$h['disc1'].'% '.$h['disc2'].'% '.$h['disc3'].'% '.$h['disc4'].'% '."</td>
    <td align=right >".format_jumlah($h['disc5'])."</td>
    <td align=right >".format_jumlah($h['total'])."</td>
    <td ></td>
    <td ></td>
    <td ></td>
    <td ></td>

  </tr>";$no++;
}
$query = mysql_query("SELECT  * FROM trans_sales_invoice  where  id_invoice='$t[id_invoice]'");
$o=mysql_fetch_array($query);
echo"
</tbody>
<tfoot>
  <tr>
    <td colspan=8 align=right>JUMLAH : </td>
    <td align=right>".format_jumlah($o['alltotal'])."</td>
    <td align=right>".format_jumlah($o['alldiscnominal'])."</td>
    <td align=right>".format_jumlah($o['alltotal']-$o['alldiscnominal'])."</td>
     <td align=right>".format_jumlah($o['allppnnominal'])."</td>
      <td align=right>".format_jumlah($o['grand_total'])."</td>
  </tr>
</tfoot>

</table>
</br>
";
}



echo 'Tanggal Dibuat : '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .'';
  
}
}
?>

