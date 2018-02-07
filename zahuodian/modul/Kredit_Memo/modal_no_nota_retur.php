<?php
 include "../../config/koneksi.php";
 $text=$_GET['text'];
    $tampil=mysql_query("SELECT * FROM `trans_retur_pembelian` where id_supplier=$text and status='0' and is_void='0' ") ;
    $no=1;
    echo $text;
    if (isset($tampil)){
       while ($r=mysql_fetch_array($tampil)){
      echo '
      echo $text;
      <tr onclick="nilairetur(\''.$r['kode_rbb'].'-'.$r['grandtotal_retur'].'\')">
      <td>
      <span>'.$no.'</span></td>
      <td >'.$r['kode_rbb'].'</td>
      <td >'.$r['grandtotal_retur'].'</td></tr>';
      $no++;}
    }
        echo "<p>Tidak Ada Purchase Order</p>";
?>
