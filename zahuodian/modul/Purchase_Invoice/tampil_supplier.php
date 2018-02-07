	<script>
	$(function() {
		$("#theTable1 tr:even").addClass("stripe1");
		$("#theTable1 tr:odd").addClass("stripe2");
		$("#theTable1 tr").hover(
			function() {
				$(this).toggleClass("highlight");
			},
			function() {
				$(this).toggleClass("highlight");
			}
		);
	});
	function add(ida,namaa)
	{
		$('#id_supplier').val(ida);
		$('#nama_supplier').text(namaa);
		$('#browse_supplier').dialog('close');
	}
	</script>
<?php
	include '../../inc/inc.koneksi.php';
	echo '<br><br>';
	echo "<table id='theTable1' width='100%'>
			<tr>
				<th width='5%'>No</th>
				<th>Kode Supplier</th>
				<th>Nama Supplier</th>
			</tr>";
	$sql="SELECT * FROM supplier WHERE is_void=0";
	$sql.=" AND (nama_supplier LIKE '%$_POST[txt_cari_supplier]%' OR kode_supplier LIKE '%$_POST[txt_cari_supplier]%')";
	$sql.=" ORDER BY kode_supplier";
	$query=mysql_query($sql);
	$no=1;
	while($data=mysql_fetch_array($query))
	{
		echo "<tr>
				<td align='center'>$no</td>
				<td style='cursor:pointer' onclick=\"add($data[id_supplier],'$data[kode_supplier] - $data[nama_supplier]')\">$data[kode_supplier]</td>
				<td style='cursor:pointer' onclick=\"add($data[id_supplier],'$data[kode_supplier] - $data[nama_supplier]')\">$data[nama_supplier]</td>
			</tr>";
		$no++;
	}
	echo '</table>';
?>
