<style type="text/css">
  
</style>
<?php
  include "../../config/koneksi.php";
  include "../../lib/input.php";
    include "../../lib/fungsi_tanggal.php";
 error_reporting(E_ALL ^ E_NOTICE);
 error_reporting(0);
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
echo '<link rel="stylesheet" href="../../asset/css/cetak.css">';
$awal=$_POST['rekapitulasipembelianawal'];
$akhir=$_POST['rekapitulasipembelianakhir'];
$id_supplier = $_POST['id_supplierrekapitulasi'];

$select=mysql_query("SELECT * FROM `region`r right join supplier s on (r.id_region=s.id_region)  where  id_supplier =".$id_supplier);
$k= mysql_fetch_array($select);
date_default_timezone_set("Asia/Jakarta");
echo '
<div style="text-align:Center" >
<h2>Rekapitulasi Pembelian Per Supplier</h2>
</BR>UD. MELATI

</div>
<table style="width: 100%;">
<tr style="font-size: 26px;">
 <td>Nama Supplier</td>
 <td>:</td>
  <td colspan="2"><strong>'.$k['kode_supplier'].'- '.$k['nama_supplier'].' ('.$k['alamat_supplier'].')</strong></td>
</tr>
<tr>
 <td>Tanggal Laporan</td>
 <td>:</td>
  <td>'.tgl_indo($awal) .'s/d'. tgl_indo($akhir).'</td>
  <td>Rayon :  '.$k['region'].'</td>
</tr>


</table>
<table width="100%" class="cetak" cellspacing=0 border= 1px solid black>
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
<th>Pembulatan</th>
<th>Total Netto</th>
<th>Total Harga</th>
<th>all PPN</th>
<th>all Disc</th>
<th> Grand Total</th>
</tr>
</thead>
<tbody>';
$query=mysql_query("SELECT id_invoice, tgl_update, alltotal, allppnnominal, alldiscnominal, grand_total FROM trans_invoice WHERE is_void=0 and tgl_update >='".$awal."' and tgl_update  <='".$akhir."' and id_supplier= '".$id_supplier."' UNION SELECT id_terima_tukang, tgl_update, grandtotal, '', '', grandtotal FROM trans_terima_tukang_header WHERE is_void=0 and tgl_update >='".$awal."' and tgl_update  <='".$akhir."' and id_supplier= '".$id_supplier."' order by id_invoice");

/*$query = mysql_query("select * from (SELECT  b.id_barang,id_supplier,td.id_invoice,t.tgl as tanggal,kode_barang,nama_barang,qty_pi_convert,harga_pi,disc1,disc2
  ,disc3,disc4,disc5,total as jumlah_netto,alltotal,alldiscnominal,allppnnominal,grand_total
FROM trans_invoice t,trans_invoice_detail td,barang b
where t.id_invoice=td.id_invoice and td.id_barang=b.id_barang) as ttransaksi where tanggal >='".$awal."' and tanggal <='".$akhir."' and id_supplier= '".$id_supplier."' order by id_invoice");
#################*/ 
$satu=0;
$dua=0;
$tiga=0;
$empat=0;
$lima=0;

$no=1;
while ($tampil=mysql_fetch_array($query)) {
  $temp=1;
$squery="SELECT t.`id_invoice`, b.kode_barang, b.nama_barang, t.`qty_pi`, t.`qty_pi_satuan`, t.`harga_pi`, `disc1`, `disc2`, `disc3`,`disc4`,`disc5`, total as jumlah_netto FROM `trans_invoice_detail` t, barang b WHERE b.id_barang=t.id_barang and id_invoice='".$tampil['id_invoice']."' UNION SELECT `id_terima_tukang`, b.kode_barang, b.nama_barang, `jumlah`, t.`satuan`, t.`harga`, '0,00', '0,00', '0,00', '0,00', '0,00', total as jumlah_netto FROM `trans_terima_tukang_detail` t, barang b WHERE b.id_barang=t.id_barang and id_terima_tukang='".$tampil['id_invoice']."'";
$select=mysql_query($squery);
$count=mysql_num_rows($select);
while ($r=mysql_fetch_array($select)) {
  if (($temp==1) and ($temp==$count)) {
        echo "
      <tr>
      <td>".$no."</td>
      <td>".$r['id_invoice']."</td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_update]))."</td>
      <td>".$r['kode_barang']."</td>
      <td>".$r['nama_barang']."</td>
      <td>".$r['qty_pi']." - ".$r['qty_pi_satuan']."</td>
      <td align=right>".format_jumlah($r['harga_pi'])."</td>
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
  }else if ($temp==1) {
    $temp++;
    echo "
      <tr>
      <td>".$no."</td>
      <td>".$r['id_invoice']."</td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_update]))."</td>
      <td>".$r['kode_barang']."</td>
      <td>".$r['nama_barang']."</td>
      <td>".$r['qty_pi']." - ".$r['qty_pi_satuan']."</td>
      <td align=right>".format_jumlah($r['harga_pi'])."</td>
      <td align=right>".$r['disc1'].'% '.$r['disc2'].'% '.$r['disc3'].'% '.$r['disc4']. "%</td>
        <td align=right>".$r['disc5']."</td>
      <td align=right>".format_jumlah($r['jumlah_netto'])."</td>
      <td align=right></td>
      <td align=right></td>
      <td align=right></td>
      <td align=right></td>
      </tr>
    " ;
  $satu+=$r['jumlah_netto'];

  }else  if ($temp==$count){
    $temp++;
    echo "
      <tr>
      <td>".$no."</td>
      <td></td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_update]))."</td>
      <td>".$r['kode_barang']."</td>
      <td>".$r['nama_barang']."</td>
      <td>".$r['qty_pi']." - ".$r['qty_pi_satuan']."</td>
      <td align=right>".format_jumlah($r['harga_pi'])."</td>
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
      <td>".$r['qty_pi']." - ".$r['qty_pi_satuan']."</td>
      <td align=right>".format_jumlah($r['harga_pi'])."</td>
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
    <td align=right>".format_jumlah($satu)."</td>
    <td align=right>".format_jumlah($dua)."</td>
    <td align=right>".format_jumlah($tiga)."</td>
    <td align=right>".format_jumlah($empat)."</td>
    <td align=right>".format_jumlah($lima)."</td>
  </tr>
</tfoot>

</table>

";
  echo 'Tanggal Dibuat  :   '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .' ';
}
}
?>

