<?php
$tampil_barang=mysql_query("select * from barang where id_barang=$barang");
$tampil_barang=mysql_fetch_array($tampil_barang);
    date_default_timezone_set("Asia/Jakarta");
echo '

<table style="width: 100%;">
<tr>
<th colspan="4"  style="font-size: 26px;">Kartu Barang Kantor</th>
</tr>
<tr >
<th  colspan="4" >UD. MELATI</th>
</tr>
<tr>
 <td>Nama Barang</td>
 <td><strong>:<strong></td>
  <td><strong>('.$tampil_barang['kode_barang'] .')-'. $tampil_barang['nama_barang'].'<strong></td>
  <td></td>
</tr>
<tr>
 <td>Tanggal Laporan</td>
 <td><strong>:<strong></td>
  <td>'.tgl_indo($awal) .' s/d '. tgl_indo($akhir).'</td>
  <td>Satuan :  '.$satuan[1].'</td>
</tr>


</table>


<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
 <thead>
      <tr>
      <th id="tablenumber">No</th>
      <th>Tanggal Transaksi</th>
      <th>Nota</th>
      <th>Keterangan</th>
      <th colspan="3">Masuk</th>
      <th colspan="3">keluar</th>
      <th colspan="3">Saldo Akhir Barang</th>
    </tr>
    <tr >
      <th colspan="4"></th>
      <th>Barang Masuk</th>
      <th>Harga</th>
      <th>Rupiah</th>
      <th>Barang keluar</th>
      <th>Harga</th>
      <th>Rupiah</th>
      <th>Saldo Akhir Barang</th>
      <th>Harga</th>
      <th>Rupiah</th>

    </tr>
        </thead>
<tbody>';
$query = mysql_query("SELECT * from(SELECT tgl_transaksi,b.id_barang,id_lap_rekap_barang,saldo_awal,saldo_akhir,rupiah_keluar,harga_keluar,keluar,rupiah_masuk,harga_masuk,masuk,harga_sat1,nota,keterangan FROM lap_rekap_barang l,barang b where b.id_barang=l.id_barang) as u where u.id_barang=$barang and date(tgl_transaksi) >= date('".$awal."') and date(tgl_transaksi) <= date('".$akhir."') order by id_lap_rekap_barang asc, tgl_transaksi asc");
$no=1;
$tamp=1;
$qty_m=0;
$qty_k=0;
$rup_m=0;
$rup_k=0;
while ($tampil=mysql_fetch_array($query)) {
  if ($tampil['harga_masuk']!=0) {
     $harga_masuk_temp=$tampil['harga_masuk'];
  }
    
  if ($no==1) {
     echo "
      <tr>
      <td align=center></td>
      <td></td>
      <td></td>
      <td>Saldo Awal</td>
      <td  align=right></td>
      <td align=right></td>
      <td align=right></td>      
      <td align=right></td>
       <td align=right></td>
      <td align=right></td>      
      <!--td align=right></td-->
        <td align=right>".format_jumlah($tampil['saldo_awal']/$satuan[0])."</td>
         <td align=right>".format_jumlah($harga_masuk_temp)."</td>
          <td align=right>".format_jumlah(($tampil['saldo_awal']/$satuan[0])*$harga_masuk_temp)."</td>
      </tr>
    " ;
 
           echo "
      <tr>
      <td align=center>".$no."</td>
      <td>".$tampil['tgl_transaksi']."</td>
      <td>".$tampil['nota']."</td>
      <td>".$tampil['keterangan']."</td>
      <!--td  align=right>".format_jumlah($tampil['harga_sat1'])."</td-->
      <td align=right>".$tampil['masuk']/$satuan[0]."</td>
      <td align=right>".format_jumlah($tampil['harga_masuk'])."</td>      
      <td align=right>".format_jumlah($tampil['rupiah_masuk'])."</td>
       <td align=right>".$tampil['keluar']/$satuan[0]."</td>
      <td align=right>".format_jumlah($tampil['harga_keluar'])."</td>      
      <td align=right>".format_jumlah($tampil['rupiah_keluar'])."</td>
      <td align=right>".$tampil['saldo_akhir']/$satuan[0]."</td>
      <td align=right>".format_jumlah($harga_masuk_temp)."</td> 
      <td align=right>".format_jumlah(($tampil['saldo_akhir']/$satuan[0])*$harga_masuk_temp)."</td>          
      </tr>
    " ;
  $qty_m+=$tampil['masuk'];
$qty_k+=$tampil['keluar'];
$rup_m+=$tampil['rupiah_masuk'];
$rup_k+=$tampil['rupiah_keluar'];
  }else{

       echo "
      <tr>
      <td align=center>".$no."</td>
      <td>".$tampil['tgl_transaksi']."</td>
      <td>".$tampil['nota']."</td>
      <td>".$tampil['keterangan']."</td>
      <!--td  align=right>".format_jumlah($tampil['harga_sat1'])."</td-->
      <td align=right>".$tampil['masuk']/$satuan[0]."</td>
      <td align=right>".format_jumlah($tampil['harga_masuk'])."</td>      
      <td align=right>".format_jumlah($tampil['rupiah_masuk'])."</td>
       <td align=right>".$tampil['keluar']/$satuan[0]."</td>
      <td align=right>".format_jumlah($tampil['harga_keluar'])."</td>      
      <td align=right>".format_jumlah($tampil['rupiah_keluar'])."</td>
        <td align=right>".$tampil['saldo_akhir']/$satuan[0]."</td>
              <td align=right>".format_jumlah($harga_masuk_temp)."</td> 
      <td align=right>".format_jumlah(($tampil['saldo_akhir']/$satuan[0])*$harga_masuk_temp)."</td>   
      </tr>
    " ;
  $qty_m+=$tampil['masuk'];
$qty_k+=$tampil['keluar'];
$rup_m+=$tampil['rupiah_masuk'];
$rup_k+=$tampil['rupiah_keluar'];
$ssaldo=$tampil['saldo_akhir'];
  }

  $no++;

}
echo "</tbody>
<tfoot>
      <tr>
      <td align=center colspan='3>Jumlah</td>
      <td  align=right></td>
      <td align=right>".format_jumlah($qty_m)."</td>
      <td align=right></td>      
      <td align=right>".format_jumlah($rup_m)."</td>
       <td align=right>".format_jumlah($qty_k)."</td>
      <td align=right></td>      
      <td align=right>".format_jumlah($rup_k)."</td>
        <td align=right>".format_jumlah($ssaldo)."</td>
      </tr>
</tfoot>
</table>";
echo 'Tanggal Dibuat '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .'';
?>