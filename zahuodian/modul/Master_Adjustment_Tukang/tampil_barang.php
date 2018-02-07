	<script>
	function add(idg,namag)
	{
		$('#barang').val(idg);
		$('#nama_barang').text(namag);
		$('#browse_gudang').dialog('close');
	}
	</script>
<?php
	include '../../config/koneksi.php';
	error_reporting(E_ALL & ~E_NOTICE);
	echo '<br><br>';
	echo "<table class='table'>
			<tr>
				<th width='5%'>No</th>
				<th>Nama Barang</th>
				<th>Jumlah Stok</th>
			</tr>";
	$sql="SELECT * FROM stok_tukang s, barang b WHERE s.id_barang = b.id_barang";
	$sql.=" AND s.id_supplier = '$_POST[tukang]'";
	$sql.=" AND nama_barang LIKE '%$_POST[txt_cari_gudang]%'";
	$sql.=" ORDER BY nama_gudang";
	$query=mysql_query($sql);
	$no=1;
	while($data=mysql_fetch_array($query))
	{
		echo "<tr>
				<td align='center'>$no</td>
				<td style='cursor:pointer' onclick=\"add($data[id_barang],'$data[nama_barang]')\">$data[nama_barang]</td>
				<td style='cursor:pointer' onclick=\"add($data[id_barang],'$data[nama_barang]')\">$data[stok_tukang]</td>
			</tr>";
		$no++;		
	}
	echo '</table>';
?>
