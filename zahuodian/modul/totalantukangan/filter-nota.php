<?php
 include "../../config/koneksi.php";
 include "../../lib/fungsi_tanggal.php";
   //$filter = $_GET['search'];
   $gdg=$_GET['gdg'];
    $no = 1;

//if(isset($filter)){UNION SELECT no_bon_tukang AS no_trans, tgl_trans AS tgl, no_bukti AS nama, nominal AS total FROM bon_tukang WHERE is_void = 0 AND status_terbayar = 0 AND id_supplier = '$gdg'
  $sup= mysql_query("SELECT th.no_hitung_hpp AS no_trans, th.tgl_trans AS tgl, b.nama_barang AS nama, th.total AS total FROM hitung_hpp_header th, barang b, trans_terima_tukang_header tt WHERE th.id_barang_header = b.id_barang AND th.id_trans_terima_tukang_header = tt.id_terima_tukang AND tt.status_totalan = 0 AND tt.is_void = 0 AND th.id_supplier = '$gdg'

ORDER BY tgl");
   while($data = mysql_fetch_array($sup)){
  if ($data==''){
echo 'barang tidak ada';
  }
  else {
        echo '
        <tr href="#" onclick="addMore(\''.$data['no_trans'].'\')" style="white-space:nowrap;">
            <td>'.$no.'</td>
            <td>'.$data['no_trans'].'</td>
            <td>'.tgl_indo($data['tgl']).'</td>
            <td>'.$data['nama'].'</td>
            <td>'.$data['total'].'</td>
        </tr>';
  $no++;
}
     }
// },\''.$data['total'].'\'
// else{
//         echo '
//        maaf salah input
//         ';
//      }
?>