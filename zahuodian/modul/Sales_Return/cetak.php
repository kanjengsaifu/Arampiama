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
$query="SELECT * ,if(jenis_retur='1','BS','Non BS') as jenis  FROM `trans_retur_penjualan` t,customer s  WHERE s.id_customer=t.id_customer and id = '$_GET[id]' and t.is_void='0' order by id desc limit 1";
 $tampil44=mysql_query($query);
  $r    = mysql_fetch_array($tampil44);

  echo "<h2><b>Cetak</b> Retur penjualan</h2>
      <table class='table table-hover' border=0 width='100%'>
<tr>
   <td>Nama customer</td> <td><strong>:</strong></td><td><strong>$r[kode_customer]  -$r[nama_customer] </strong></td>
</tr>
<tr>
  <td> Alamat </td><td><strong>:</strong><td>$r[alamat_customer]</td>
  <td>Tanggal Retur</td> <td><strong>:</strong><td>$r[tgl_rjb]</td>
</tr>
<tr>
  <td>No Nota Retur</td> <td><strong>:</strong><td>$r[kode_rjb]</td>
  <td>Jenis Retur</td> <td><strong>:</strong><td>$r[jenis]</td>
</tr>
<tr>
  <td>Alasan Retur</td> <td><strong>:</strong><td>$r[ket]</td>
</tr>
  </table> ";
  echo '
<!--h4>rules = "rows"</h4-->
<table id="header" class="table table-hover table-bordered" cellspacing="0" width="100%" border= 1px solid black>
  <thead>
    <tr style="background-color:#F5F5F5;">      
      <th>No Invoice</th>
      <th>No Nota</th>
      <th>Nama Barang</th>
       <th>Gudang</th>
      <th>Qty</th>
       <th>Harga</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody id="product">';
  $select="SELECT * FROM trans_retur_penjualan_detail od, barang bg,gudang g where g.id_gudang=od.id_gudang and od.id_barang=bg.id_barang and  od.Kode_rjb= '$r[kode_rjb]'";
$select=mysql_query($select);
  while ($data = mysql_fetch_array($select)) {
    echo "
    <tr  style='border-top:1px solid #000;border-bottom:1px solid #000'>  
     <td align='left'>$data[id_invoice]</td>
     <td align='left'>$data[no_nota]</td>
      <td align='left'>$data[kode_barang]   $data[nama_barang]</td>
       <td align='left'>$data[nama_gudang]</td>
       <td align='right'>".format_jumlah($data[qty_retur])."  $data[satuan]</td>
            <td align='right'>".format_jumlah($data[harga_per_satuan_terkecil])."</td>
                  <td align='right'>".format_jumlah($data[harga_retur])."</td>
    </tr>";
  }  
echo"
  </tbody>
  <tfoot>
    <tr>

      <td colspan=6  style=text-align:right;><p><b>Total All SUb </b></p></td>
      <td align=right>".format_jumlah($r[total_retur])."</td>
    </tr>
    <tr>
      <td colspan=6  style=text-align:right;><p> Disc  | $r[discpersen] (%)  </p></td>
      <td style=nowrap:nowrap; align=right>".format_jumlah($r[discnominal])."</td>
    </tr>
    <tr>
      <td colspan=6 style=text-align:right;><p> Ppn | $r[ppnpersen] (%) </p></td>
      <td style=nowrap:nowrap; align=right>".format_jumlah($r[ppnnominal])."</td>
    </tr>
    <tr>
      <td colspan=6  style=text-align:right;><b>Grand total</b></td>
      <td align=right><b>".format_jumlah($r[grandtotal_retur])."</b></td>
    </tr>
  </tfoot>
</table>
Tanggal Cetak : $tgl
";
  echo tanda_tangan("$r[nama_customer]");


}
}
?>
