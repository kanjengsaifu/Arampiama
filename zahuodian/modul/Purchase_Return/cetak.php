<?php
  include "../../config/koneksi.php";
  include "../../lib/input.php";
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
  $tgl=date('d/m/Y');
echo '<link rel="stylesheet" href="asset/css/layout.css">';
 $tampil44=mysql_query("SELECT * FROM `trans_retur_pembelian` t,supplier s  WHERE s.id_supplier=t.id_supplier and id = '$_GET[id]' order by id desc limit 1 ");
  $r    = mysql_fetch_array($tampil44);

  echo "<h2><b>Cetak</b> Retur Pembelian</h2>
  <table class='table table-hover' border=0>
  <tr>
   <td>Nama Supplier</td><td><strong>:</strong></td><td><strong>$r[kode_supplier] -$r[nama_supplier]</strong (%)></td>
  </tr>
  <tr>
    <td>Alamat</td><td><strong>:</strong> <td>$r[alamat_supplier]</td>
    <td> No tlp </td> <td><strong>:</strong><td>$r[telp1_supplier]</td>
  </tr>
 <tr>
  <td>No Nota Retur</td> <td><strong>:</strong><td>$r[kode_rbb]</td>
  <td>Tanggal Retur</td> <td><strong>:</strong><td>$r[tgl_rbb]</td>
</tr>
<tr>
  <td>Jenis Retur</td> <td><strong>:</strong><td>Deposit</td>
</tr>
  </table> ";
echo '
<!--h4>rules = "rows"</h4-->
<table id="header" class="table table-hover table-bordered" cellspacing="0" width="100%" border= 1px solid black>
  <thead>
    <tr style="background-color:#F5F5F5;"> 
          <th>QTY</th>     
      <th>Kode barang - Nama Barang</th>
             <th>Harga</th>
      <th>disc1 (%)</th>
      <th>disc2 (%)</th>
      <th>disc3 (%)</th>
      <th>disc4 (%)</th>
      <th>disc5 (Rp)</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody id="product">';
  $select="SELECT * FROM trans_retur_pembelian_detail od LEFT JOIN barang bg ON od.id_barang=bg.id_barang where od.Kode_rbb= '$r[kode_rbb]'";
$select=mysql_query($select);
  while ($data = mysql_fetch_array($select)) {
    echo "
    <tr  style='border-top:1px solid #000;border-bottom:1px solid #000'>  
     <td align='right'>".format_jumlah($data[qty_retur])." $data[satuan]</td>
     <td align='left'>$data[kode_barang] - $data[nama_barang]</td>
    <td align='right'>".format_jumlah($data[harga_per_satuan_terkecil])."</td>
    <td align='right'>".$data[disc1]."</td>
    <td align='right'>".$data[disc2]."</td>
    <td align='right'>".$data[disc3]."</td>
    <td align='right'>".$data[disc4]."</td>
    <td align='right'>".$data[disc5]."</td>
                  <td align='right'>".format_jumlah($data[harga_retur])."</td>
    </tr>";
  }  
echo"
  </tbody>
  <tfoot>
    <tr>

      <td colspan=8 style=text-align:right;><p><b>Total All SUb </b></p></td>
      <td align=right>".format_jumlah($r[total_retur])."</td>
    </tr>
    <tr>
      <td colspan=8 style=text-align:right;><p> Disc  | $r[discpersen] (%)  </p></td>
      <td style=nowrap:nowrap; align=right>".format_jumlah($r[discnominal])."</td>
    </tr>
    <tr>
      <td colspan=8 style=text-align:right;><p> Ppn | $r[ppnpersen] (%) </p></td>
      <td style=nowrap:nowrap; align=right>".format_jumlah($r[ppnnominal])."</td>
    </tr>
    <tr>
      <td colspan=8 style=text-align:right;><b>Grand total</b></td>
      <td align=right><b>".format_jumlah($r[grandtotal_retur])."</b></td>
    </tr>
  </tfoot>
</table>
Tanggal Cetak : $tgl
";
  echo tanda_tangan("$r[nama_supplier]");
}
}
?>
