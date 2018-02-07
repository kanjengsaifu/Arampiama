<?php
 include "../../config/koneksi.php";
   $filter = $_GET['search'];
   $gdg=$_GET['gdg'];
    $no = 1;

if(isset($filter)){
  $sup= mysql_query("SELECT * FROM barang b,stok s,gudang g WHERE g.id_gudang=s.id_gudang and b.id_barang=s.id_barang and stok_sekarang <> '0' and s.id_gudang<>'$gdg' and(  nama_barang LIKE '%$filter%' or kode_barang LIKE   '%$filter%' )");
   while($data = mysql_fetch_array($sup)){
  if ($data==''){
echo 'barang tidak ada';
  }
  else {
        echo '
        <tr href="#" onclick="addMore(\''.$data['id_barang'].'\',\''.$data['id_gudang'].'\')">
            <td>'.$no.'</td>
            <td>'.$data['kode_barang'].'</td>
            <td>'.$data['nama_barang'].'</td>
            <td>'.$data['stok_sekarang'].'</td>
            <td>'.$data['nama_gudang'].'</td>
        </tr>';
  $no++;
}
     }
}
else{
        echo '
       maaf salah input
        ';
     }
?>