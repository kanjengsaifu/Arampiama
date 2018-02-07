<?php
date_default_timezone_set("Asia/Jakarta");
echo '

<table style="width: 100%;">
<tr>
<th colspan="4"  style="font-size: 26px;">Laporan Barang per Periode</th>
</tr>
<tr >
<th  colspan="4" >UD. MELATI</th>
</tr>
<tr>
 <td>Tanggal Laporan</td>
 <td>:</td>
  <td>'.tgl_indo($awal) .' s/d '. tgl_indo($akhir).'</td>
  <td>Rayon :  '.$k['region'].'</td>
</tr>
<tr>
 <td>Filter</td>
 <td>:</td>
  <td colspan="2">'.$filter.'</td>
</tr>


</table>


<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>
<tr>
  <th id="tablenumber">No</th>
      <th>Nama barang</th>
      <th>Saldo Awal Barang</th>
      <th>Barang Diterima</th>
      <th>Barang Dikeluarkan</th>
      <th>Saldo Akhir Barang</th>

</tr>
</thead>
<tbody>';
$query="

select b.id_barang,nama_barang,saldo_awal,masuk,keluar,saldo_akhir from (SELECT id_lap_rekap_barang,saldo_akhir as saldo_awal,id_barang FROM `lap_rekap_barang` WHERE `id_lap_rekap_barang` in (SELECT max(`id_lap_rekap_barang`) FROM `lap_rekap_barang` where `tgl_transaksi`< date('".$awal."') group by id_barang)) saldo_awal right join barang b on ( b.id_barang=saldo_awal.id_barang) left join (SELECT l.id_barang,sum(masuk) as masuk ,sum(keluar) as keluar, (saldo_awal+sum(masuk)-sum(keluar))as saldo_akhir FROM lap_rekap_barang l where date(tgl_transaksi) >=  date('".$awal."') and date(tgl_transaksi) <= date('".$akhir."') group by l.id_barang) saldo_akhir on (b.id_barang=saldo_akhir.id_barang) where 1 ".$merk." ".$kategori." ".$kode_supplier." ";
$query = mysql_query($query);

$no=1;
$tamp=1;
while ($tampil=mysql_fetch_array($query)) {
                                            echo "
                                            <tr>
                                            <td align=center>".$no."</td>
                                            <td>".$tampil['nama_barang']."</td>
                                            <td  align=right>".format_jumlah($tampil['saldo_awal']/$satuan[0])."</td>
                                            <td align=right>".format_jumlah($tampil['masuk']/$satuan[0])."</td>
                                            <td align=right>".format_jumlah($tampil['keluar']/$satuan[0])."</td>      
                                            <td align=right>".format_jumlah($tampil['saldo_akhir']/$satuan[0])."</td>
                                            </tr>
                                            " ;
                                            $no++;}
echo "</tbody></table>";
echo 'Tanggal Dibuat '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .'';
  
?>