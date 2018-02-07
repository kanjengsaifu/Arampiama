  <?php
 include "../../config/koneksi.php";
 $filter = $_GET['kd'];

 //$sup= mysql_query("SELECT * FROM stok WHERE id_barang = '$filter' ");

 //$sup= mysql_query("SELECT * FROM stok ");
 $test = mysql_query("SELECT  SUM(IF( nama_barang LIKE '$filter%', stok_sekarang, 0))  AS stok_now,
        FROM stok join barang on (barang.id_barang=stok.id_barang)");
  $data = mysql_fetch_array($test);
   ?>
  <tr>
    <td colspan="4">Jumlah Stok Total = <?php echo $data['stok_now']?></td>
    <td>limit ....</td>
  </tr>
