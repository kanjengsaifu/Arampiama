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
//$aksi="modul/purchaseorder/aksi_purchaseorder.php";
//echo '<link rel="stylesheet" href="asset/css/layout.css">';

echo "<h2><b>Cetak</b> Sales Order</h2>
    
   ";
 $tampil44=mysql_query("SELECT * FROM trans_sales_order tso LEFT JOIN customer ctr ON tso.id_customer = ctr.id_customer LEFT JOIN sales sls  ON sls.id_sales = tso.id_sales WHERE tso.id = '$_GET[id]' order by tso.id desc limit 1 ");
  $r    = mysql_fetch_array($tampil44);

  echo "
<div class='table-responsive'>
<table class='table table-hover' border=0 id=tambah>
   <tr>
   <td>Customer</td> <td><strong>:</strong></td>
   <td><strong>$r[nama_customer]</strong></td>
  </tr>
    <tr>
    <td> Alamat </td><td><strong>:</strong></td>
    <td>$r[alamat_customer]</td>
    <td> Tlp/Hp Customer </td><td><strong>:</strong></td>
    <td>$r[telp1_customer]</td>
  </tr>
  <tr>
    <td> Sales</td> <td><strong>:</strong></td>
  <td>$r[nama_sales]</td>  
    <td> Tlp/Hp Sales </td><td><strong>:</strong></td>
    <td>$r[telp1_sales]</td>
      </tr>
      <tr>
    <td>No So</td><td><strong>:</strong></td>
    <td>$r[id_sales_order]</td>
    <td>Tanggal SO</td><td><strong>:</strong></td>
    <td>".date("d/m/Y", strtotime($r[tgl_so]))."</td>
  </tr>


  </table> 
<table id=header class=table table-hover table-bordered cellspacing=0 border= 1px solid black>
  <thead>
  <tr style=background-color:#F5F5F5;>
      <th>Kode barang</th>
      <th>Nama barang</th>
      <th>Harga</th>
      <th>Qty</th>
      <th>satuan</th>
      <th>Disc 1 (%)</th>
      <th>Disc 2 (%)</th>
      <th>Disc 3 (%)</th>
      <th>Disc 4 (%)</th>
      <th>Disc 5 (Rp.)</th>
      <th>Total</th>
  </tr>
  </thead> 
  <tbody id=product>";
        $tampiltable=mysql_query("SELECT * FROM trans_sales_order_detail tso LEFT JOIN barang brg ON tso.id_barang=brg.id_barang   WHERE tso.id_sales_order = '$r[id_sales_order]'  ");
while ($rst = mysql_fetch_array($tampiltable)){
  echo "
    <tr>
      <td  align=right>$rst[kode_barang]</td>
      <td  align=right>$rst[nama_barang]</td>
      <td  align=right>".format_rupiah($rst['harga'])."</td>
      <td  align=right>$rst[jumlah]</td>
      <td  align=right>$rst[satuan]</td>
      <td  align=right>$rst[disc1]</td>
      <td  align=right>$rst[disc2]</td>
      <td  align=right>$rst[disc3]</td>
      <td  align=right>$rst[disc4]</td>
      <td  align=right>$rst[disc5]</td>
      <td  align=right>".format_rupiah($rst['total'])."</td>
    </tr>";
  $noz++;
}
echo"
  </tbody>
  <tfoot>
    <tr>
      <td  align=right colspan=7 rowspan=4>&nbsp;</td>
      <td  align=right colspan=3 style=text-align:right; ><p><b>ToTal All SUb </b></p></td>
      <td  align=right colspan=2  >".format_rupiah($r['alltotal'])."</td>
    </tr>
    <tr>
      <td  align=right colspan=3 style=text-align:right;><p> Disc (%) $r[discper] | (Rp) </p></td>
      <td  align=right colspan=2 style=nowrap:nowrap;>$r[disc]</td>
    </tr>
    <tr>
      <td  align=right colspan=3 style=text-align:right;><p> Ppn (%) $r[ppnper] | (Rp) </p></td>
      <td  align=right colspan=2  style=nowrap:nowrap;>$r[ppn]</td>
    </tr>
    <tr>
      <td  align=right colspan=3 style=text-align:right;><b>Grand Total</b></td>
      <td  align=right colspan=2><b>".format_rupiah($r['grand_total'])."</b></td>
    </tr>
  </tfoot>
</table>
</div>";
    echo tanda_tangan("$r[nama_customer]");
}
}

?>
