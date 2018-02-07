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
$edit = mysql_query("select * from trans_invoice ti, supplier s where s.id_supplier=ti.id_supplier and ti.id_invoice='$_GET[id]'");
    $r    = mysql_fetch_array($edit);
  echo "<h2><b> Nota Pembelian </b></h2>
     <div class='table-responsive'>
      <table class='table table-hover' border=0  width='100%''>
       <tr>
    <td>Supplier</td>
    <td>:</td>
    <td colspan =3 ><strong>$r[kode_supplier] - $r[nama_supplier] </strong> </td>

  </tr>
  <tr>
    <td> Alamat </td>
    <td>:</td>
    <td>$r[alamat_supplier]</td>
    <td>Tanggal </td>
    <td>:</td>
     <td>".date("d/m/Y", strtotime($r[tgl_pi]))."</td> 
 </tr>
  <tr>
      <td>No LBM</td> 
    <td>:</td>
    <td>$r[id_lpb]</td>  
      <td>Tanggal Jatuh Tempo</td>
    <td>:</td>
    <td>".date("d/m/Y", strtotime($r[tgl]))."</td> 
  </tr>
  <tr>
     <td>No Nota Supplier</td>
     <td>:</td>
     <td>$r[no_nota]</td>
    <td>No. Expedis</td>
    <td>:</td>
    <td>$r[no_expedisi]</td>
  </tr>
 
  
 </table>";
echo '
<FORM name="frmProduct" method="post" action="">
<DIV class="btn-action float-clear">
</DIV>
<table id="header" class="table table-hover table-bordered" cellspacing="0" width="100%" border= 1px solid black>
        <thead>
  <tr >
      <th id="tablenumber">No</th>
      <th>Nama Barang</th>
      <th>Qty diterima</th>
      <th>Harga</th>
      <th>Disc 1 (%)</th>
      <th>Disc 2 (%)</th>
      <th>Disc 3 (%)</th>
      <th>Disc 4 (%)</th>
      <th>Pembulatan  (Rp.)</th>
      <th>Total</th>
        </tr>
        </thead>
 
        <tbody id="product">';
$tampiltable=mysql_query("select *,CONCAT(qty_po,'-',qty_po_satuan) AS minta from trans_invoice_detail tid, barang b where b.id_barang=tid.id_barang  and tid.id_invoice='$r[id_invoice]'");

$noz = 0;
$no=1;
$rst_jumlah = mysql_num_rows($tampiltable);

while ($rst = mysql_fetch_array($tampiltable)){
  echo "
  <tr>
    <td  align=center>
     $no
    </td>
    <td>
     $rst[nama_barang]
    </td>
     <td  align=right>
     ".$rst['qty_pi']." - ".$rst['qty_pi_satuan']."
    </td>
     <td  align=right>
       ".format_rupiah($rst['harga_pi'])."
    </td>
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
$tampiltable=mysql_query("SELECT * FROM trans_lpb t, trans_pur_order tt where tt.id_pur_order=t.id_pur_order; ");

echo"
        </tbody>
        <tfoot>
        <tr>
          <td  align=right colspan=6 rowspan=4> <br>
          </td>
    <td  align=right colspan=3 style=text-align:right; ><p><b>ToTal All SUB </b></p></td>
    <td  align=right colspan=2>".format_rupiah($r['alltotal'])."</td>
  </tr>
  <tr>
    <td  align=right colspan=3 style=text-align:right;><p> Disc (%) $r[alldiscpersen] | </p></td>
    <td  align=right colspan=2 style=nowrap:nowrap;>".format_rupiah($r['alldiscnominal'])."</td>
  </tr>
  <tr>
    <td  align=right colspan=3 style=text-align:right;><p> Ppn (%) $r[allppnpersen] | </p></td>
    <td  align=right colspan=2 style=nowrap:norwap;>".format_rupiah($r['allppnnominal'])."</td>
  </tr>  
  <tr>
    <td  align=right colspan=3 style=text-align:right;><b>Grand total</b></td>
    <td  align=right colspan=2><b>".format_rupiah($r['grand_total'])."</b></td>
  </tr>
                </tfoot>
          </table>
             Tanggal Cetak : $tgl
  </div> 
  </form>
  ";
  
}
}
?>

