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
$tgl=date('d/m/Y');
  $edit = mysql_query("select * from trans_sales_invoice ti,trans_sales_invoice_detail tid,customer s,barang b WHERE ti.id_invoice=tid.id_invoice and s.id_customer=ti.id_customer and b.id_barang=tid.id_barang  and ti.id_invoice='$_GET[id]'");
    $r    = mysql_fetch_array($edit);
  echo "
  <h2>
    <b>
      Nota Penjualan
    </b> 
  </h2>
  <form method='post' action='$aksi?module=salesinvoice&act=update'>
    <div class='table-responsive'>
      <table class='table table-hover' border=0 width='100%'>
              <tr>
          <td>Customer</td>
          <td><strong>:</strong></td>
          <td><strong> $r[nama_customer] </strong> </td>

        </tr>
        <tr>
          <td> Alamat </td>
          <td><strong>:</strong></td>
          <td>$r[alamat_customer]</td>
            <td>Tanggal Transaksi</td>
    <td><strong>:</strong></td>
      <td>".date("d/m/Y", strtotime($r[tgl_si]))."</td> </td>
        </tr>
          <tr>
            <td>No LKB</td> 
          <td><strong>:</strong></td>
          <td>$r[id_lkb]</td>
      <td>Tanggal Jatuh Tempo</td>
    <td><strong>:</strong></td>
    <td>".date("d/m/Y", strtotime($r[tgl]))."</td> 
        </tr>
        <tr>
           <td>No Nota Customer</td>
           <td><strong>:</strong></td>
           <td>$r[no_nota]</td>
          <td>No Expedisi</td>
          <td><strong>:</strong></td>
          <td>$r[no_expedisi]</td>
        </tr>
  

      </table>";
echo '

<table id="header" width="100%" class="table table-hover table-bordered" cellspacing="0" border= 1px solid black>
        <thead>
  <tr>
      <th id="tablenumber">No</th>
      <th>Nama Barang</th>
      <th>Qty Diterima</th>
      <th>Harga</th->
      <th>Disc 1 (%)</th>
      <th>Disc 2 (%)</th>
      <th>Disc 3 (%)</th>
      <th>Disc 4 (%)</th>
      <th>Pembulatan (Rp.)</th>
      <th>Total</th>
        </tr>
        </thead>
 
        <tbody id="product">';
$tampiltable=mysql_query("select *,CONCAT(qty_so,'-',qty_so_satuan) AS minta from trans_sales_invoice ti,trans_sales_invoice_detail tid,customer s,barang b where ti.id_invoice=tid.id_invoice and s.id_customer=ti.id_customer and b.id_barang=tid.id_barang and ti.id_invoice='$_GET[id]'");

$noz = 0;
$no=1;
$rst_jumlah = mysql_num_rows($tampiltable);

while ($rst = mysql_fetch_array($tampiltable)){
  echo "
  <tr>
    <td  align=right>
       $no
    </td>
    <td>
     $rst[nama_barang]
    </td>

     <td  align=right>
     ".$rst['qty_si']." - ".$rst['qty_si_satuan']."
     </td>
     <td  align=right>
       ".format_rupiah($rst['harga_si'])."
    </td->
    <td  align=right>$rst[disc1]</td>
    <td  align=right>$rst[disc2]</td>
    <td  align=right>$rst[disc3]</td>
    <td  align=right>$rst[disc4]</td>
    <td  align=right>$rst[disc5]</td>
    <td  align=right>".format_rupiah($rst['total'])."</td>
</tr>
";
$noz++;
$no++;
}
$tampiltable=mysql_query("SELECT * FROM trans_lkb t, trans_sales_order tt where tt.id_sales_order=t.id_sales_order; ");

echo"
        </tbody>
        <tfoot>
        <tr>
    <td  align=right colspan=6 rowspan=4> <br>
          </td>

    <td  align=right colspan=3 style=text-align:right; ><p><b>ToTal All SUb </b></p></td>
    <td  align=right colspan=2  >".format_rupiah($r['alltotal'])."</td>
  </tr>

  <tr>
    <td  align=right colspan=3 style=text-align:right;><p> Disc (%) $r[alldiscpersen] | (Rp) </p></td>
    <td  align=right colspan=2 style=nowrap:nowrap;>".format_rupiah($r[alldiscnominal])."</td>
  </tr>
  <tr>
    <td  align=right colspan=3 style=text-align:right;><p> Ppn (%)$r[allppnpersen] | (Rp) </p></td>
    <td  align=right colspan=2  style=nowrap:nowrap;>".format_rupiah($r[allppnnominal])."</td>
  </tr>
  <tr>
    <td  align=right colspan=3 style=text-align:right;><b>Grand total</b></td>
    <td  align=right colspan=2><b>".format_rupiah($r['grand_total'])."</b></td>
  </tr>
                </tfoot>
          </table>
          Tanggal Cetak : $tgl
  </div> 
  </form>";
     echo tanda_tangan("$r[nama_customer]");
}
}
?>