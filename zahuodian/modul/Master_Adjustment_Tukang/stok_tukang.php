<?php
 include "../../config/koneksi.php";
 $text=$_GET['text'];

if (empty($_GET['text'])){
	 echo "<tr> <td colspan='2'> <p>Tidak Ada Stok</p></td></tr>";
	} else {
$tampil=mysql_query("SELECT * FROM stok_tukang st, barang b WHERE st.id_barang = b.id_barang AND st.id_supplier =$text") ;
$no=1;
if ((mysql_num_rows($tampil)) > 0) {
	 while ($r=mysql_fetch_array($tampil)){
		echo '
		 <tr onclick="nilaipo(\''.$r['nama_barang'].'#'.$r['id_barang'].'\')">
		<td>
		<span>'.$no.'</span></td>
		<td >'.$r['nama_barang'].'</td>
		<td>'.$r['stok_tukang'].'</td>
		</tr>';
		$no++;}
	} else { echo "<tr> <td colspan='2'> <p>Tidak Ada Stok</p></td></tr>";}
}
    	
?>
