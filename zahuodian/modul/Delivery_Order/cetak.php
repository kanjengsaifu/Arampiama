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

  $query=mysql_query("SELECT * FROM `trans_lkb` t, customer s   WHERE t.id_customer=s.id_customer and t.id = '$_GET[id]' order by t.id desc limit 1 ");
 $r=mysql_fetch_array($query);
echo '<link rel="stylesheet" href="asset/css/layout.css">';
  echo "<h2><b>Cetak</b> Laporan Barang Keluar</h2>
      <table class='table table-hover' border=0 id=tambah>
        <tr>
   <td>Customer</td>  <td><strong> :</strong> </td>
     <td>$r[nama_customer]</td>
 </tr>
  <tr><td > Alamat </td> <td><strong> :</strong> </td>
    <td>$r[alamat_customer]</td>
       <td>Tanggal Barang Dikirim</td> <td><strong> :</strong> </td>
    <td>".date("d/m/Y", strtotime($r[tgl_lkb]))."</td>
    </tr>
  <tr>
     <td>No SO</td> <td><strong> :</strong> </td>
<td>$r[id_sales_order]</td>
    <td>No LKB</td> <td><strong> :</strong> </td>
    <td>$r[id_lkb]</td>

  </tr>
  <tr>
    <td>No Expedisi </td> <td><strong> :</strong> </td>
    <td>$r[no_expedisi]</td>
   <td> No Nota Customer </td> <td><strong> :</strong> </td>
    <td>$r[no_nota_customer]</td>
  </tr>
</table>
<table id=header class=table table-hover table-bordered cellspacing=0 border= 1px solid black>
        <thead>
  <tr style=background-color:#F5F5F5;>
      <th>No</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Jumlah dalam SO</th>
      <th>Jumlah diterima</th>
      <th>Gudang</th>
      </tr>
        </thead>
 
        <tbody>";
$noz= 100;
$tampiltable=mysql_query("SELECT *, concat(qty,'-',qty_satuan) as jumlah_dlm_so, CONCAT (qty_diterima,'-',qty_diterima_satuan) AS terima FROM `trans_lkb_detail` d,gudang g WHERE d.id_gudang=g.id_gudang and d.id_lkb = '$r[id_lkb]' order by d.kode_barang_so, d.id");
 $no=1;
while ($rst = mysql_fetch_array($tampiltable)){

  echo "
 <tr>
      <td  align=right>
       $no
    </td>
  <td  align=right>";
  $tampiltablebarang=mysql_query("SELECT * FROM `barang` WHERE id_barang = '$rst[id_barang]' ");
   $rst1 = mysql_fetch_array($tampiltablebarang);
  echo"
       $rst1[kode_barang]
    </td>
    <td  align=right>
       $rst1[nama_barang]
    </td>
   <td  align=right>
       $rst[jumlah_dlm_so]
    </td>       
      <td  align=right>$rst[terima]
      </td>
   <td  align=right>
   $rst[nama_gudang]</td>
</tr>";
$no++;
$noz++;
}        echo "
        </tbody>
        <tfoot>
                </tfoot>
          </table>
  </div> 
  </form>";
     echo tanda_tangan("$r[nama_customer]");
}
}
?>