<?php
  include "../../config/koneksi.php";
  include "../../lib/input.php";
    include "../../lib/fungsi_tanggal.php";
$awal=$_POST['tgl_ju_awal'];
$akhir=$_POST['tgl_ju_akhir'];
 echo '<div style="text-align:Center" >
<h2>Laporan Jurnal Umum</h2>
</BR>UD. MELATI

</div>
<table style="width: 100%;">
<tr>
 <td>Periode</td>
 <td>:</td>
  <td>'.tgl_indo($awal) .' s/d '. tgl_indo($akhir).'</td>
</tr>
</table>';

echo '<table class="table" cellspacing=0 border= 1px solid black style="width: 100%;">
<thead>
<th>No</th>
<th>Tanggal Transaksi</th>
<th>Nama Akun</th>
<th>Akun Debet</th>
<th>Akun Kredit</th>
<th>Debet</th>
<th>Kredit</th>
<th>Nota</th>
</thead>
<tbody>';
$query ="SELECT tanggal,nama_akunkasperkiraan,kode_akun,debet_kredit,`kode_nota`, sum(nominal) as nominal FROM `jurnal_umum` j, akun_kas_perkiraan a where a.id_akunkasperkiraan=j.`id_akun_kas_perkiraan` and tanggal between '$awal' and '$akhir' group by id_akun_kas_perkiraan ,kode_nota,debet_kredit order by tanggal,kode_nota,debet_kredit   ";
$result=mysql_query($query);
$no=1;
$temp="";
while ($r=mysql_fetch_array($result)) {
	if ($temp==$r['kode_nota']) {
	echo "<tr><td></td><td></td>";
	if ($r['debet_kredit']=='D') {
	echo "<td>$r[nama_akunkasperkiraan]</td><td></td>";} else{
	echo "<td></td><td>$r[nama_akunkasperkiraan]</td>";}
	echo"<td>$r[kode_akun]</td>";
	if ($r['debet_kredit']=='D') {
	echo "<td align='right'>".format_jumlah($r["nominal"])."</td><td align='right'></td>";	} else{
	echo "<td align='right'></td><td align='right'>".format_jumlah($r["nominal"])."</td>";}	
	echo "<td></td></tr>";}else{
    	echo "<tr><td>$no</td><td>$r[tanggal]</td>";
	if ($r['debet_kredit']=='D') {
	echo "<td>$r[nama_akunkasperkiraan]</td><td></td>";} else{
	echo "<td></td><td>$r[nama_akunkasperkiraan]</td>";}
	echo"<td>$r[kode_akun]</td>";
	if ($r['debet_kredit']=='D') {
	echo "<td align='right'>".format_jumlah($r["nominal"])."</td><td align='right'></td>";	} else{
	echo "<td align='right'></td><td align='right'>".format_jumlah($r["nominal"])."</td>";}	
	echo "<td>$r[kode_nota]</td></tr>";$temp=$r['kode_nota'];
$no++;}}
date_default_timezone_set("Asia/Jakarta");
	echo'</tbody></table>';
	echo 'Tanggal Dibuat : '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .'';