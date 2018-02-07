<?php
 include "../../config/koneksi.php";
   $id_customer= $_POST['id_customer'];
   $id_barang = $_POST['id_barang'];
   $query=" SELECT DATE_FORMAT(tgl_si, '%d %M %Y') as tanggal, a.`id_invoice`,no_nota,concat(qty_si,' ',qty_si_satuan) as Qty,round(total/qty_si_convert) as HargaPerUnit 
   		FROM `trans_sales_invoice_detail` a,`trans_sales_invoice` b 
   		WHERE a.`id_invoice`=b.`id_invoice` and id_customer='$id_customer' and id_barang='$id_barang'";
   		echo $query;
   $result=mysql_query($query);
   $no=0;
   while ($t=mysql_fetch_array($result)) :
   	$no++;?>
<tr>
	<td><?= $no ?></td>
	<td><?= $t['tanggal'] ?></td>
	<td><?= $t['id_invoice'] ?></td>
	<td><?= $t['no_nota'] ?></td>
	<td><?= $t['Qty'] ?></td>
	<td><?= $t['HargaPerUnit'] ?></td>
	<td><a   class='btn-sm btn-success' onclick="add_barang('<?= $id_barang ?>','<?= $t['id_invoice'] ?>')">+</a></td>
</tr>

<?php endwhile ?>
<tr>
	<td></td>
	<td>Tidak Ada Nota Tersisa</td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
</tr>