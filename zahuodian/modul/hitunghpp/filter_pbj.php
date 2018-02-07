<?php
 include "../../config/koneksi.php";
 $text=$_GET['text'];

if (empty($_GET['text'])){
	 echo "<tr> <td colspan='3'> <p>Tidak Ada barang</p></td></tr>";
	} else {
$tampil=mysql_query("SELECT * FROM trans_terima_tukang_detail tt, barang b, trans_terima_tukang_header th, supplier s WHERE tt.id_barang = b.id_barang AND tt.id_terima_tukang = th.id_terima_tukang AND s.id_supplier = th.id_supplier AND tt.status = 0 AND th.id_terima_tukang = '$text'") ;
$no=1;
if ((mysql_num_rows($tampil)) > 0) {
	 while ($r=mysql_fetch_array($tampil)){
		echo '
		 <tr onclick="nilaipo(\''.$r['id_trans_terima_tukang_detail'].'#'.$r['nama_barang'].'#'.$r['nama_supplier'].'#'.$r['jumlah'].'#'.$r['biaya_tukang'].'#'.$r['id_barang'].'#'.$r['id_supplier'].'\')">
		<td>
		<span>'.$no.'</span></td>
		<td >'.$r['nama_barang'].'</td>
		<td>'.$r['jumlah'].'</td>
		</tr>';
		$no++;}
	} else { echo "<tr> <td colspan='3'> <p>Tidak Ada barang</p></td></tr>";}
}
    	
?>
