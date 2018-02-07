<?php
 include "../../config/koneksi.php";
 $text=$_GET['text'];
    $tampil=mysql_query("SELECT * FROM `trans_sales_invoice` where id_customer=$text and status_lunas=0 ") ;
    $no=1;
    if (isset($tampil)){
       while ($r=mysql_fetch_array($tampil)){
      echo '
      <tr onclick="nilaipo(\''.$r['id_invoice'].'-'.$r['grand_total'].'\')">
      <td>
      <span>'.$no.'</span></td>
      <td >'.$r['id_invoice'].'</td>
      <td >'.$r['grand_total'].'</td></tr>';
      $no++;}
    }
        echo "<p>Tidak Ada Purchase Order</p>";
?>
