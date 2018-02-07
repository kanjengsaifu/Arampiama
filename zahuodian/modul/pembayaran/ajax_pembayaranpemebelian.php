<?php
 include "../../config/koneksi.php";
  include "../../lib/input.php";
$id=$_POST['id'];
$noz=0;
/*$tampiltable=mysql_query("SELECT *,sum(qty_diterima) as diterima FROM `trans_lpb_detail` n,barang b,trans_pur_order_detail po,trans_pur_order p WHERE p.id_pur_order=po.id_pur_order and po.id=n.kode_barang_po and n.id_barang=b.id_barang and po.id_pur_order = '$nmr_po'  group by kode_barang_po order by kode_barang_po
");
$r = mysql_fetch_array($tampiltable);*/
echo "<table id='modalnoinvoice' class='table table-hover' style='width: 100%;'>
	<thead>
		<tr>
			<th id='tablenumber'>No</th>
			<th>No. Invoice</th>
			<th>No. PO</th>
			<th>Nama Supplier</th>
			<th>Tanggal</th>
			<th>Grand Total</th>
			<th id='tablenumber'>Detail</th>
		</tr>
	</thead>
	</tbody>
";
$total=0;
for ($i=0; $i <count($id) ; $i++) { 
$tampil = mysql_query("SELECT * FROM trans_invoice ti join supplier s on ti.id_supplier=s.id_supplier where id = $id[$i]");
$r = mysql_fetch_array($tampil);
$no = $i + "1";

$tanggal = date("d M Y", strtotime("$r[tgl]"));
	echo "<tr>
			<td id='tablenumber'> 
				<input type='hidden' name='id[$noz]' value='$r[id]' id='id-$noz'>
				$no
			</td>
			<td><input type='hidden' name='id_invoice[$noz]' value='$r[id_invoice]' id='id_invoice-$noz'>
				$r[id_invoice]
			</td>
			<td><input type='hidden' name='id_pur_order[$noz]' value='$r[id_pur_order]' id='id_pur_order-$noz'>
				$r[id_pur_order]
			</td>
			<td><input type='hidden' name='nama_supplier[$noz]' value='$r[nama_supplier]' id='nama_supplier-$noz'>
				$r[nama_supplier]
			</td>
			<td><input type='hidden' name='tgl[$noz]' value='$r[tgl]' id='tgl-$noz'>
				$tanggal
			</td>
			<td><input type='hidden' name='total[$noz]' value='$r[grand_total]' id='total-$noz' class='hitung'>
				". format_rupiah("$r[grand_total]")."
			</td>
			<td>
				<div class='btn btn-warning' name='detail' id ='detail' onclick='detail(\"$r[id_invoice]\")'><span class='glyphicon glyphicon-th-list'></span></div>
			</td>
		</tr>";
		$total += $r['grand_total'];
		$noz++;
		 
}
echo "
		<tr id='productall' style='border-top: 2px solid #030303;'>
			  <td colspan='2'></td>
			    <td colspan='3' style='text-align:right;' ><p><b>ToTal All SUb </b></p></td>
			    <td colspan='2'  ><input name='alltotal' type='hidden' class='hitung2 form-control' id='total' value='$total' readonly>". format_rupiah("$total")."</td>
			  </tr>

			  <tr>
			  <td colspan='2'></td>
			    <td colspan='3' style='text-align:right;'><p> Disc (%) <input name='alldiscpersen' type='text' id='persendisc' style='width:4em;padding: 0px 0px;text-align:center;' class='form-control'> | (Rp) </p></td>
			    <td colspan='2' style='nowrap:nowrap;'><input name='alldiscnominal' value=''  type='text' id='totaldisc' class='hitung2 form-control' ></td>
			  </tr>
			  <tr>
			  <td colspan='2'></td>
			    <td colspan='3' style='text-align:right;'><p> Ppn (%) <input name='allppnpersen' type='text' id='persenppn' style='width:4em;padding: 0px 0px;text-align:center;' class='form-control'> | (Rp) </p></td>
			    <td colspan='2'  style='nowrap:nowrap;'><input name='allppnnominal' type='text' id='totalppn'   value='' class='hitung2 form-control' ></td>
			  </tr>
			  <tr>
			  <td colspan='2'></td>
			    <td colspan='3' style='text-align:right;'><b>Grand total </b></td>
			    <td colspan='2'><b><input name='grandtotal' type='hidden' id='grandtotal'  class='form-control'  value='' readonly><input id='grandtotal1' type='' value='". format_rupiah("$total")."'></b></td>
		</tr>
	</tbody>
	</table>
		 <input class='btn btn-success' type=submit value=Simpan>
                       <input class='btn btn-warning' type=button value=Batal onclick=self.history.back()>
             ";

?>