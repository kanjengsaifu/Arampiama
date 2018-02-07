<?php
  include "../../config/koneksi.php";
  include "../../lib/input.php";
    include "../../lib/fungsi_tanggal.php";
$awal=$_POST['buku_besar_awal'];
$akhir=$_POST['buku_besar_akhir'];
$id=$_POST['id_pegawai'];
 session_start();
generate_buku_besar($awal,$akhir,$_SESSION['username']);
 echo '<div style="text-align:Center" >
<h2>Laporan Buku Besar</h2>
</BR>UD. MELATI

</div>
<table style="width: 100%;">
<tr>
 <td>Periode</td>
 <td>:</td>
  <td>'.tgl_indo($awal) .'s/d'. tgl_indo($akhir).'</td>
</tr>
</table>';

for ($i=0; $i <count($id) ; $i++) { 
	$q=mysql_query("SELECT nama_akunkasperkiraan,kode_akun from akun_kas_perkiraan where id_akunkasperkiraan='".$id[$i]."'");
	$q=mysql_fetch_array($q);
echo '
</br>
</br>
</br>
<table class="table"  cellspacing=0 border= 1px solid black style="width: 100%;">
<thead>
<tr>
<th  align="left" colspan=4 width="10%">Nama Akun :'.$q['nama_akunkasperkiraan'].'</th>
<th align="left"  colspan=3 width="10%">Kode Akun :'.$q['kode_akun'].'</th>
</tr>
<tr>
<th  width="10%">Tanggal Transaksi</th>
<th  width="10%">Keterangan</th>
<th  width="10%">Ref</th>
<th  width="10%">Debet</th>
<th  width="10%">Kredit</th>
<th   width="10%" colspan=2>Saldo</th>
</tr>
<tr>
<th colspan=5></th>
<th  width="10%">Debet</th>
<th  width="10%">Kredit</th>
</tr>
</thead>
<tbody>';
$query ="SELECT * FROM `buku_besar` WHERE `id_akunkasperkiraan`='".$id[$i]."' and Tanggal between '".$awal."' and '".$akhir."'  ";
$result=mysql_query($query);
while ($r=mysql_fetch_array($result)) {
	echo "<tr>
	<td>$r[tanggal]</td>
	<td>$r[keterangan]</td>
	<td>$r[no_nota]</td>
	<td align='right'>".format_jumlah($r['debet'])."</td>
	<td align='right'>".format_jumlah($r['kredit'])."</td>
	";
	if ($r["saldo_akhir"]>=0) {
	 echo "	<td align='right'>".format_jumlah($r['saldo_akhir'])."</td>
	<td></td>";	
	}else{
			 echo "	<td></td>
	<td align='right'>".format_jumlah($r['saldo_akhir'])."</td>";	
	}
echo"
	</tr>";}
	echo'</tbody></table>';
}
