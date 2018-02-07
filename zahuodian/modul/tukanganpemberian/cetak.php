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
echo '<link rel="stylesheet" href="asset/css/layout.css">';
 $tampil44=mysql_query("SELECT * FROM `trans_pur_order` t,supplier s  WHERE s.id_supplier=t.id_supplier and id_pur_order = '$_GET[id]' order by id desc limit 1 ");
  $r    = mysql_fetch_array($tampil44);

  echo "<h2><b>Cetak</b> Purchase Order</h2>
      <table class='table table-hover' border=0>
   <td>Nama Supplier</td> <td><strong>:</strong></td><td id='sup'><strong>$r[kode_supplier] - $r[nama_supplier] </strong>
  </tr>
<td> Alamat </td> <td><strong>:</strong> <td>$r[alamat_supplier]</td><td> No tlp </td> <td><strong>:</strong><td>$r[telp1_supplier]</td></tr>
<tr><td>No PO</td> <td><strong>:</strong><td>$r[id_pur_order]</td><td>Tanggal PO</td> <td><strong>:</strong><td>$r[tgl_po]</td></tr>
  </table> ";

echo '
<!--h4>rules = "rows"</h4-->
<table width="100%" id="header" class="table table-hover table-bordered" cellspacing="0" border= 1px solid black>
  <thead>
    <tr style="background-color:#F5F5F5;">      
      <th>Nama barang</th>
      <th>Harga</th>
      <th>Qty</th>
      <th>Disc 1 (%)</th>
      <th>Disc 2 (%)</th>
      <th>Disc 3 (%)</th>
      <th>Disc 4 (%)</th>
      <th>Pembuatan (Rp.)</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody id="product">';
$select=mysql_query("SELECT * FROM trans_pur_order_detail od LEFT JOIN barang bg ON od.id_barang=bg.id_barang where od.id_pur_order= '$_GET[id]'");
  while ($data = mysql_fetch_array($select)) {
    echo "
    <tr  style='border-top:1px solid #000;border-bottom:1px solid #000'>  
      <td align='left'>$data[nama_barang]</td>
      <td align='right'>".format_rupiah($data[harga])."</td>
      <td align='right'>$data[jumlah] (".$data[satuan].")</td>
      <td align='right'>$data[disc1]</td>
      <td align='right'>$data[disc2]</td>
      <td align='right'>$data[disc3]</td>
      <td align='right'>$data[disc4]</td>
      <td align='right'>$data[disc5]</td>
      <td align='right'>".format_rupiah($data[total])."</td>
    </tr>";
  }  
echo"
  </tbody>
  <tfoot>
    <tr>

      <td colspan=8 style=text-align:right;><p><b>Total All SUb </b></p></td>
      <td align=right>".format_rupiah($r[alltotal])."</td>
    </tr>
    <tr>
      <td colspan=8 style=text-align:right;><p> Disc (%) $r[discper] | (Rp) </p></td>
      <td style=nowrap:nowrap; align=right>".format_rupiah($r[disc])."</td>
    </tr>
    <tr>
      <td colspan=8 style=text-align:right;><p> Ppn (%) $r[ppnper] | (Rp) </p></td>
      <td style=nowrap:nowrap; align=right>".format_rupiah($r[ppn])."</td>
    </tr>
    <tr>
      <td colspan=8 style=text-align:right;><b>Grand total</b></td>
      <td align=right><b>".format_rupiah($r[grand_total])."</b></td>
    </tr>
  </tfoot>
</table>
";
  echo tanda_tangan("$r[nama_supplier]");
}
}
?>
