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
date_default_timezone_set("Asia/Jakarta");
echo '
<table  style="width: 100%;">
<tr>
<th colspan="3"  style="font-size: 26px;">Laporan Purchase Order Sisa</th>
</tr>
<tr >
<th  colspan="3" >UD. MELATI</th>
</tr>
</table>

<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>
<th>Tanggal</th>
<th>No. PO</th>
<th>No. LBM</th>
<th>Nama Barang</th>
<th colspan=2>Order</th>
<th colspan=2>Diterima</th>
<th>Sisa</th>
</thead>
<tbody>';

$query="SELECT satuan,tpod.id as urut,tgl_po,tpo.id_pur_order,'' as id_lpb,nama_barang,jumlah,jumlah as qty,(jumlah*kali) as po_convert,'' as lpb_qty,'' as lpb_convert FROM `trans_pur_order_detail` tpod,`trans_pur_order` tpo,barang b where tpo.id_pur_order=tpod.id_pur_order and b.id_barang=tpod.id_barang and status_trans=1 order by id_pur_order,urut";
$query=mysql_query($query);
while ($r=mysql_fetch_array($query)) {
 echo "
 <tr>
<td>$r[tgl_po]</td>
<td>$r[id_pur_order]</td>
<td>-</td>
<td>$r[nama_barang]</td>
<td align='right'>$r[jumlah]</td>
<td align='right'>$r[satuan] </td>
<td colspan=2>-</td>
<td>$r[jumlah] - $r[satuan] </td>
</tr>
 "   ;
$temp=$r[qty];
 $query2 ="select *,concat(qty_diterima,'-',qty_diterima_satuan) as a from barang b, trans_lpb_detail td,trans_lpb t where t.id_lpb=td.id_lpb and b.id_barang=td.id_barang and td.id_pur_order='$r[id_pur_order]' and kode_barang_po='$r[urut]' order by td.id_lpb";
 $query2=mysql_query($query2);
while ($rr=mysql_fetch_array($query2)) {
  $temp = number_format( $temp,2)-number_format( $rr[qty_diterima],2);
   echo "
 <tr>
<td>$rr[tgl_lpb]</td>
<td>$rr[id_pur_order]</td>
<td>$rr[id_lpb]</td>
<td></td>
<td colspan = 2></td>
<td align='right'>$rr[qty_diterima]</td>
<td align='right'>$rr[qty_diterima_satuan] </td>
<td align='right'>".number_format( $temp,2)." - ".$r[satuan]."</td>
</tr>
 "   ;
}
}
echo '
</tbody>
</table>

  ';
  echo "<table>
<table>
  <tr>
    <td>Tanggal Cetak<td>
    <td>:</td>
    <td>".tgl_indo(date("Y/m/d"))." - ".date("h:i:s a") ." </td>
  </tr>
</table>";
}
}
?>

