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
echo '<link rel="stylesheet" href="asset/css/layout.css">';
 $tampil44=mysql_query("SELECT * FROM `trans_terima_tukang_header`tpr,supplier s   WHERE tpr.id_supplier=s.id_supplier and id_trans_terima_tukang_header = '$_GET[id]'");
  $r    = mysql_fetch_array($tampil44);

  echo "<h2><b>Cetak</b> Penerimaan Barang Jadi</h2>
      <table>
      <tr>
        <td>No PBJ</td> <td><strong>:</strong></td><td id='sup'><strong>$r[id_terima_tukang] </strong></td>
        <td>Tanggal</td> <td><strong>:</strong></td><td><strong>".tgl_indo($r['tgl_trans'])."</strong></td>
      </tr>
      <tr>
        <td>Nama Supplier</td> <td><strong>:</strong></td><td id='sup'><strong>$r[kode_supplier] - $r[nama_supplier] </strong></td>
        <td>No Nota</td> <td> <strong>:</strong></td><td id='sup'><strong>$r[nonota_terima_tukang] </strong></td>
  </tr>
  <tr id='txtHint'>
    <td> Alamat </td> <td><strong>:</strong></td><td id='sup'><strong>$r[alamat_supplier] </strong></td>
    <td> No tlp </td> <td><strong>:</strong></td><td id='sup'><strong>$r[telp1_supplier] </strong></td>
  </tr>
  </table> ";

echo '
<!--h4>rules = "rows"</h4-->
<table width="100%" id="header" class="table table-hover table-bordered" cellspacing="0" border= 1px solid black>
  <thead>
    <tr style="background-color:#F5F5F5;">
      <th width="15%">Nama barang</th>
      <th width="20%">Kode barang</th>
      <th width="15%">Ke Gudang</th>
      <th width="10%" >Satuan</th>
      <th width="10%">Harga</th> 
      <th width="10%">Qty</th>
      <th width="10%">Total</th>
    </tr>
  </thead>
  <tbody id="product">';
$select=mysql_query("SELECT * FROM `trans_terima_tukang_detail`   WHERE id_terima_tukang = '$r[id_terima_tukang]'");
  while ($data = mysql_fetch_array($select)) {
    $sql= mysql_query("SELECT * FROM barang WHERE id_barang = '$data[id_barang]' ");
    $brg = mysql_fetch_array($sql);
    echo "
    <tr  style='border-top:1px solid #000;border-bottom:1px solid #000'>  
      <td align='left'>$brg[nama_barang]</td>
      <td align='left'>$brg[kode_barang]</td>";
      $gudang=mysql_query("SELECT * FROM gudang where is_void=0 AND id_gudang='$data[id_gudang]'");
      $w=mysql_fetch_array($gudang);
      echo "
      <td align='left'>$w[nama_gudang]</td>
      <td align='center'>$data[satuan] (".($data['kali']*1).")</td>
      <td align='right'>".number_format($data[harga]*1)."</td>
      <td align='right'>".($data[jumlah]*1)."</td>
      <td align='right'>".number_format($data['total']*1)."</td>
    </tr>";
  }  
echo"
  </tbody>
  <tfoot>
    <tr>
      <td colspan=6 style=text-align:right;><b>Grand total</b></td>
      <td align=right><b>".format_rupiah($r[grandtotal])."</b></td>
    </tr>
  </tfoot>
</table>
";
  echo tanda_tangan("$r[nama_supplier]");
}
}
?>
