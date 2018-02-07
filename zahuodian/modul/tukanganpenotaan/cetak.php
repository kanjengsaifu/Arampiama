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
$id=$_GET['id'];
 $tampil44=mysql_query("SELECT *,date_format(`tgl_totalan`,'%d %M %Y') as tanggal FROM `trans_totalan_tukang` a, supplier c WHERE a.`id_supplier`=c.`id_supplier` and a.is_void='0'and a.id='$id' order by `id` desc");
  $r    = mysql_fetch_array($tampil44);

  echo "<h2><b>Cetak</b> Penotaan Tukang</h2>
      <table class='table table-hover' border=0>
   <td>Nama Supplier</td> <td><strong>:</strong></td><td id='sup'><strong>$r[kode_supplier] - $r[nama_supplier] </strong>
  </tr>
<td> Alamat </td> <td><strong>:</strong> <td>$r[alamat_supplier]</td><td> No tlp </td> <td><strong>:</strong><td>$r[telp1_supplier]</td></tr>
<tr><td>No NTT</td> <td><strong>:</strong><td>$r[no_totalan_tukang]</td><td>Tanggal PO</td> <td><strong>:</strong><td>$r[tanggal]</td></tr>
<tr><td>No PBJ</td> <td><strong>:</strong><td>$r[id_terima_tukang]</td><td>Nota Tukang</td> <td><strong>:</strong><td>$r[nota_tukang]</td></tr>
  </table> ";

echo '
<!--h4>rules = "rows"</h4-->
<table width="100%" id="header" class="table table-hover table-bordered" cellspacing="0" border= 1px solid black>
  <thead>
    <tr style="background-color:#F5F5F5;">      
      <th>Nama barang - Kode barang</th>
      <th>Qty</th>
      <th>Harga <br> Bahan</th>

      <th colspan="3">Total</th>
    </tr>
  </thead>
  <tbody id="product">';
 $query=mysql_query("SELECT * FROM `trans_totalan_tukang` a, `trans_totalan_tukang_detail` b, barang c WHERE a.`no_totalan_tukang`=b.`no_totalan_tukang` and b.`id_barang`=c.`id_barang` and a.is_void='0'and a.id='$id' order by `id` desc");
  while ($data = mysql_fetch_array($query)) {
    echo "
    <tr  style='border-top:1px solid #000;border-bottom:1px solid #000'>  
      <td align='left'>$data[kode_barang] - $data[nama_barang]</td>
      <td align='right'>$data[jumlah] </td>
      <td align='right'>$data[harga]/$data[satuan]</td>
      <td align='right'>".format_rupiah($data[total])."</td>
    </tr>";
  }  
echo"
  </tbody>
  <tfoot>
    <tr>

      <td colspan=2 style=text-align:right;><p><b>Total  :</b></p></td>
      <td></td>
      <td align=right>".format_rupiah($r[total])."</td>
    </tr>
    <tr>
      <td colspan=2 style=text-align:right;><p><b>Total  :</b></p></td>
      <td align=right>".format_rupiah($r[nominal_hutang])."</td>
      <td align=right>".format_rupiah($r[hasiltotal])."</td>
    </tr>
    <tr>
      <td colspan=3 style=text-align:right;><p> Disc (%) $r[discper] | (Rp) </p></td>
      <td style=nowrap:nowrap; align=right>".format_rupiah($r[disc])."</td>
    </tr>
    <tr>
      <td colspan=3 style=text-align:right;><p> Ppn (%) $r[ppnper] | (Rp) </p></td>
      <td style=nowrap:nowrap; align=right>".format_rupiah($r[ppn])."</td>
    </tr>
    <tr>
      <td colspan=3 style=text-align:right;><b>Grand total</b></td>
      <td align=right><b>".format_rupiah($r[nominal_totalan])."</b></td>
    </tr>
  </tfoot>
</table>
";
  echo tanda_tangan("$r[nama_supplier]");
}
}
?>
