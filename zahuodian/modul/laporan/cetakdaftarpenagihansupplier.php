<?php
  include "../../config/koneksi.php";
  include "../../lib/input.php";
    include "../../lib/fungsi_tanggal.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{

  $_ck = (array_search("1",$_SESSION['lvl'], true))?'true':'false';
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
//$aksi="modul/purchaseorder/aksi_purchaseorder.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
$awal=$_POST['dpsawal'];
$akhir=$_POST['dpsakhir'];
$id_supplierkh = $_POST['id_supplierdps'];
$ket = $_POST['ketdps'];
$select=mysql_query("SELECT * FROM `region`r right join supplier s on (r.id_region=s.id_region)  where id_supplier =".$id_supplierkh);
$k= mysql_fetch_array($select);
date_default_timezone_set("Asia/Jakarta");
if ($ket==0) {
echo '

<table style="width: 100%;">
<tr>
<th colspan="4"  style="font-size: 26px;">Daftar Nota Tagihan Hutang</th>
</tr>
<tr >
<th  colspan="4" >UD. MELATI</th>
</tr>
<tr style="font-size: 26px;">
 <td>Nama supplier</td>
 <td>:</td>
  <td colspan="2"><strong>'.$k['kode_supplier'].'-'.$k['nama_supplier'].' ('.$k['alamat_supplier'].' )</td>
</tr>
<tr>
 <td>Tanggal Laporan</td>
 <td>:</td>
  <td>'.tgl_indo($awal) .' s/d '. tgl_indo($akhir).'</td>
  <td>Rayon :  '.$k['region'].'</td>
</tr>
</table>';

  
echo'
<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>
<tr>
  <th>No</th>
  <th>Tanggal</th>
  <th>No. Nota</th>
  <th>Hutang</th> 
  <th>Sisa Hutang</th>    
  <th>Surat Jalan</th>
  <th>keterangan</th>
  <th>Potongan</th>
  <th>Netto</th>
</tr>
</thead>
<tbody>';
$query = "

SELECT no_nota,tgl_pi,tsi.id_invoice as nota ,tsi.grand_total,no_nota_supplier,if(sum(tbd.nominal_alokasi) is null,0,sum(tbd.nominal_alokasi)) as diterima, tsi.id_supplier,(tsi.grand_total-(if(sum(tbd.nominal_alokasi) is null,0,sum(tbd.nominal_alokasi)))) as sisa FROM `trans_invoice` tsi left join `trans_bayarbeli_detail` tbd on (tsi.id_invoice=tbd.nota_invoice),trans_lpb tl,trans_pur_order tso 
WHERE tl.id_lpb=tsi.id_lpb and tso.id_pur_order=tl.id_pur_order 
and tgl_pi >='".$awal."' and tgl_pi <='".$akhir."' and tsi.id_supplier= '".$id_supplierkh."' group by id_invoice having sisa>0 ";
$query = mysql_query($query);
$satu=0;
$dua=0;
$tiga=0;
$no=1;
$tamp=1;
while ($tampil=mysql_fetch_array($query)) {
   echo "
      <tr>
      <td align=center>".$no."</td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_pi]))."</td>
       <td>".$tampil['no_nota']." - ".$tampil['nota']."</td>
      <td align=right>".format_jumlah($tampil['grand_total'])."</td>      
      <td align=right>".format_jumlah($tampil['sisa'])."</td>
      <td>".$tampil['no_expedisi']."</td>      
      <td>".$tampil['keterangan']."</td>
      <td align=right></td>
      <td align=right></td>      
      </tr>
    " ;
  $satu+=$tampil['sisa'];
  $dua +=$tampil['diterima'];
  $no++;


  }


echo"
</tbody>
<tfoot>
  <tr>
      <td colspan=3  align=center>Total</td>

      <td align=right>".format_jumlah($dua)."</td>      
      <td align=right>".format_jumlah($satu)."</td>
      <td></td>      
      <td></td>
      <td align=right></td>
      <td align=right></td>  
  </tr>";
  $tiga= ($satu-$dua);
  if ($tiga>0) {   
    echo  " <tr>
      <td colspan=3  align=center>Sisa</td>
         <td></td>
      <td align=right>".format_jumlah(($tiga))."</td>      
      <td></td>      
      <td></td>
      <td align=right></td>
      <td align=right></td>  
  </tr>";

  }else{
     echo  " <tr>
      <td colspan=3  align=center>Sisa</td>
      <td align=right>".format_jumlah($tiga)."</td>      
      <td></td>
      <td></td>      
      <td></td>
      <td align=right></td>
      <td align=right></td>  
  </tr>";
  }
  echo "
</tfoot>
</table>
";
echo 'Tanggal Dibuat '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .'';
}else if ($ket==1) {
  echo '

<table style="width: 100%;">
<tr>
<th colspan="4"  style="font-size: 26px;">Daftar Nota Tagihan Titipan</th>
</tr>
<tr >
<th  colspan="4" >UD. MELATI</th>
</tr>
<tr style="font-size: 26px;">
 <td>Nama supplier</td>
 <td>:</td>
  <td colspan="2"><strong>'.$k['kode_supplier'].'-'.$k['nama_supplier'].' ('.$k['alamat_supplier'].' )</td>
</tr>
<tr>
 <td>Tanggal Laporan</td>
 <td>:</td>
  <td>'.tgl_indo($awal) .' s/d '. tgl_indo($akhir).'</td>
  <td>Rayon :  '.$k['region'].'</td>
</tr>
</table>';

echo'
<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>
<tr>
  <th>No</th>
  <th>Tanggal</th>
  <th>No. Nota</th>
  <th>Jumlah</th>
  <th>Terpakai</th>
  <th colspan=2>keterangan</th>
</tr>
</thead>
<tbody>
  ';
  $query="SELECT tgl_pembayaran,tbh.bukti_bayar,nominal,tbh.id_supplier,tbh.nominal_alokasi FROM `trans_bayarbeli_header` tbh left join `trans_bayarbeli_detail` tbd on (tbh.bukti_bayar=tbd.bukti_bayar) 
  where  status_titipan='T' and tbh.id_supplier=$id_supplierkh group by tbh.bukti_bayar";
$no=1;
$satu=0;
$dua=0;
$query=mysql_query($query);
while ($r=mysql_fetch_array($query)) {
  echo "     <tr> 
      <td>$no</td>
      <td>$r[tgl_pembayaran]</td>      
      <td>$r[bukti_bayar]</td>
      <td align=right>".format_jumlah($r[nominal])."</td>
      <td align=right>".format_jumlah($r[nominal_alokasi])."</td>      
      <td colspan =2></td></tr>";
$no++;
$satu+=$r[nominal];
$dua +=$r[nominal_alokasi];
}
echo "</tbody>
<tfoot>
<tr>
  <td colspan=3> Jumlah </td>
  <td align=right>".format_jumlah($satu)."</td>
  <td align=right>".format_jumlah($dua)."</td>
  <td colspan 2></td></tr>
</tfoot>
";
}else if ($ket==2) {
echo '
<table style="width: 100%;">
<tr>
<th colspan="4"  style="font-size: 26px;">Daftar Nota Tagihan Retur</th>
</tr>
<tr >
<th  colspan="4" >UD. MELATI</th>
</tr>
<tr style="font-size: 26px;">
 <td>Nama supplier</td>
 <td>:</td>
  <td colspan="2"><strong>'.$k['kode_supplier'].'-'.$k['nama_supplier'].' ('.$k['alamat_supplier'].' )</td>
</tr>
<tr>
 <td>Tanggal Laporan</td>
 <td>:</td>
  <td>'.tgl_indo($awal) .' s/d '. tgl_indo($akhir).'</td>
  <td>Rayon :  '.$k['region'].'</td>
</tr>
</table>';

  
echo'
<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>
<tr>
  <th>No</th>
  <th>Tanggal</th>
  <th>No. Nota</th>
  <th>Jumlah</th>    
  <th>keterangan</th>
  <th>Potongan</th>
  <th>Netto</th>
</tr>
</thead>
<tbody>';
$query = "SELECT tgl_rbb as tgl,kode_rbb as nota,grandtotal_retur FROM `trans_retur_pembelian` trp where kode_rbb not in 
(SELECT `nota_invoice` as kode_rbb  FROM `trans_bayarbeli_detail` 
union all
SELECT `nota_invoice`  as kode_rbb FROM `trans_bayarjual_detail` ) and trp.is_void=0 and tgl_rbb >='".$awal."' and tgl_rbb <='".$akhir."' and
  id_supplier= '".$id_supplierkh."'";
$query=mysql_query($query);
$satu=0;
$dua=0;
$tiga=0;
$no=1;
$tamp=1;
while ($tampil=mysql_fetch_array($query)) {
   echo "
      <tr>
      <td align=center>".$no."</td>
      <td>".date('d-m-Y', strtotime($tampil[tgl]))."</td>
      <td>".$tampil['nota']."</td>
      <td align=right>".format_jumlah($tampil['grandtotal_retur'])."</td>          
      <td>".$tampil['keterangan']."</td>
      <td align=right></td>
      <td align=right></td>      
      </tr>
    " ;
  $satu+=$tampil['grandtotal_retur'];
  $dua +=0;
  $no++;


  }


echo"
</tbody>
<tfoot>
  <tr>
      <td colspan=3  align=center>Total</td>      
      <td align=right>".format_jumlah($satu)."</td>
      <td align=right></td>
      <td align=right></td>  
        <td align=right></td>  
  </tr>";
  echo "
</tfoot>
</table>
";
echo 'Tanggal Dibuat '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .'';
}else if ($ket==3) {
echo '
<table style="width: 100%;">
<tr>
<th colspan="4"  style="font-size: 26px;">Daftar Nota Tagihan</th>
</tr>
<tr >
<th  colspan="4" >UD. MELATI</th>
</tr>
<tr style="font-size: 26px;">
 <td>Nama supplier</td>
 <td>:</td>
  <td colspan="2"><strong>'.$k['kode_supplier'].'-'.$k['nama_supplier'].' ('.$k['alamat_supplier'].' )</td>
</tr>
<tr>
 <td>Tanggal Laporan</td>
 <td>:</td>
  <td>'.tgl_indo($awal) .' s/d '. tgl_indo($akhir).'</td>
  <td>Rayon :  '.$k['region'].'</td>
</tr>
</table>';

  
echo'
<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>
<tr>
  <th>No</th>
  <th>Tanggal</th>
  <th>No. Nota</th>
  <th>Hutang</th> 
  <th>Sisa Hutang</th>    
  <th>Surat Jalan</th>
  <th>Keterangan</th>
  <th>Potongan</th>
  <th>Netto</th>
</tr>
</thead>
<tbody>';
$satu=0;
$dua=0;
$tiga=0;
$no=1;
$tamp=1;
$query =  "SELECT no_nota,tgl_pi,tsi.no_expedisi,tsi.id_invoice as nota ,tsi.grand_total,no_nota_supplier,if(sum(tbd.nominal_alokasi) is null,0,sum(tbd.nominal_alokasi)) as diterima, tsi.id_supplier,(tsi.grand_total-(if(sum(tbd.nominal_alokasi) is null,0,sum(tbd.nominal_alokasi)))) as sisa FROM `trans_invoice` tsi left join `trans_bayarbeli_detail` tbd on (tsi.id_invoice=tbd.nota_invoice),trans_lpb tl,trans_pur_order tso 
WHERE tl.id_lpb=tsi.id_lpb and tso.id_pur_order=tl.id_pur_order 
and tgl_pi >='".$awal."' and tgl_pi <='".$akhir."' and tsi.id_supplier= '".$id_supplierkh."' group by id_invoice having sisa>0"
;

$query = mysql_query($query);
while ($tampil=mysql_fetch_array($query)) {
   echo "
      <tr>
      <td align=center>".$no."</td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_pi]))."</td>
      <td>".$tampil['nota']." - ".$tampil['no_nota']."</td>
      <td align=right>".format_jumlah($tampil['grand_total'])."</td>      
      <td align=right>".format_jumlah($tampil['sisa'])."</td>
      <td>".$tampil['no_expedisi']."</td>      
      <td>".$tampil['keterangan']."</td>
      <td align=right></td>
      <td align=right></td>      
      </tr>
    " ;
  $satu+=$tampil['grand_total'];
  $dua +=$tampil['sisa'];
  $no++;
  }

  $query="SELECT tgl_pembayaran,tbh.bukti_bayar,nominal,tbh.id_supplier,tbh.nominal_alokasi FROM `trans_bayarbeli_header` tbh left join `trans_bayarbeli_detail` tbd on (tbh.bukti_bayar=tbd.bukti_bayar) 
  where  status_titipan='T' and tbh.id_supplier=$id_supplierkh group by tbh.bukti_bayar";
$query=mysql_query($query);
while ($r=mysql_fetch_array($query)) {
  echo "     <tr> 
      <td align=center>$no</td>
      <td>".date('d-m-Y', strtotime($r[tgl_pembayaran]))."</td>      
      <td>$r[bukti_bayar]</td>
 <td align=right>(".format_jumlah($r[nominal]).")</td>
      <td align=right>(".format_jumlah($r[nominal]).")</td>
      <td></td>
       <td></td>
        <td></td>
         <td></td></tr>";
$no++;
$dua -=$r[nominal];
$satu -=$r[nominal];
}
$query = "SELECT tgl_rbb as tgl,kode_rbb as nota,grandtotal_retur FROM `trans_retur_pembelian` trp where kode_rbb not in 
(SELECT `nota_invoice` as kode_rbb  FROM `trans_bayarbeli_detail` 
union all
SELECT `nota_invoice`  as kode_rbb FROM `trans_bayarjual_detail` ) and trp.is_void=0 and tgl_rbb >='".$awal."' and tgl_rbb <='".$akhir."' and
  id_supplier= '".$id_supplierkh."'";
$query=mysql_query($query);
while ($tampil=mysql_fetch_array($query)) {
   echo "
      <tr>
      <td align=center>".$no."</td>
      <td>".date('d-m-Y', strtotime($tampil[tgl]))."</td>
      <td>".$tampil['nota']."</td>
      <td align=right>(".format_jumlah($tampil['grandtotal_retur']).")</td>     
      <td align=right>(".format_jumlah($tampil['grandtotal_retur']).")</td>          
      <td>".$tampil['keterangan']."</td>
      <td align=right></td>
      <td align=right></td>  
      <td align=right></td>
    
      </tr>
    " ;
  $dua -=$tampil['grandtotal_retur'];
  $satu -=$tampil['grandtotal_retur'];
  $no++;


  }


echo"
</tbody>
<tfoot>
  <tr>
      <td colspan=3  align=center>Total</td>      
      <td align=right>".format_jumlah($satu)."</td>
      <td align=right>".format_jumlah($dua)."</td>
      <td align=right></td>  
        <td align=right></td>  
              <td align=right></td>
      <td align=right></td>   
  </tr>";
  echo "
</tfoot>
</table>
";
echo 'Tanggal Dibuat '.tgl_indo(date("Y/m/d")).' - '.date("h:i:s a") .'';
}
}}
?>

