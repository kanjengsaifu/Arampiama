	<script>
	function add(idg,namag)
	{
		$('#gudang').val(idg);
		$('#nama_gudang').text(namag);
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
				<th>Nama Gudang</th>
			</tr>";
	$sql="SELECT * FROM gudang WHERE is_void=0";
	$sql.=" AND nama_gudang LIKE '%$_POST[txt_cari_gudang]%'";
	$sql.=" ORDER BY nama_gudang";
	$query=mysql_query($sql);
	$no=1;
	while($data=mysql_fetch_array($query))
	{
		echo "<tr>
				<td align='center'>$no</td>
				<td style='cursor:pointer' onclick=\"add($data[id_gudang],'$data[nama_gudang]')\">$data[nama_gudang]</td>
			</tr>";
		$no++;		
	}
	echo '</table>';
?>
