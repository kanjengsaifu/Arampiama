<?php
 include "../../config/koneksi.php";
 include "../../lib/fungsi_tanggal.php";
   //$filter = $_GET['search'];
   $gdg=$_GET['gdg'];
    $no = 1;

//if(isset($filter)){
  $sup= mysql_query("SELECT * FROM stok_tukang s, barang b WHERE s.id_barang = b.id_barang AND s.id_supplier = '$gdg'");
   while($data = mysql_fetch_array($sup)){
  if ($data==''){
echo 'barang tidak ada';
  }
  else {
        echo '
        <tr href="#" onclick="addMore(\''.$data['id_barang'].'\',\''.$data['nama_barang'].'\')" style="white-space:nowrap;">
            <td>'.$no.'</td>
            <td>'.$data['nama_barang'].'</td>
            <td>'.$data['stok_tukang'].'</td>
        </tr>';
  $no++;
}
     }
// }
// else{
//         echo '
//        maaf salah input
//         ';
//      }
?>