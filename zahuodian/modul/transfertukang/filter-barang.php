<?php
 include "../../config/koneksi.php";
   $filter = $_GET['search'];
   $gdg=$_GET['gdg'];
    $no = 1;

if(isset($filter)){
  $sup= mysql_query("SELECT * FROM barang b, stok_tukang st, supplier s WHERE st.id_supplier = s.id_supplier AND b.id_barang = st.id_barang AND stok_tukang <> '0' AND st.id_supplier <> '$gdg' AND (  nama_barang LIKE '%$filter%' or kode_barang LIKE   '%$filter%' )");
   while($data = mysql_fetch_array($sup)){
  if ($data==''){
echo 'barang tidak ada';
  } 
  else {
        echo '
        <tr href="#" onclick="addMore(\''.$data['id_barang'].'\',\''.$data['id_supplier'].'\')">
            <td>'.$no.'</td>
            <td>'.$data['kode_barang'].'</td>
            <td>'.$data['nama_barang'].'</td>
            <td>'.$data['stok_tukang'].'</td>
            <td>'.$data['nama_supplier'].'</td>
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