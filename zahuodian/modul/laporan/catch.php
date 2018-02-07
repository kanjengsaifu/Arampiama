<?php
 include "../../config/koneksi.php"; 
if(isset($_GET['test'])){
	$kode = $_GET['test']; 
	$query = mysql_query("SELECT id_barang, nama_barang FROM barang WHERE id_barang = '$kode'");
	while ($ss = mysql_fetch_array($query)) {
		echo $ss['id_barang']." ## ".$ss['nama_barang'];
	}
	
} else {
	echo "codene g muasok mas";
}
 ?>
 