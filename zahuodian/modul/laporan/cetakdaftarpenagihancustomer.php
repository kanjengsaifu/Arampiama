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
$awal=$_POST['dpcawal'];
$akhir=$_POST['dpcakhir'];
$id_customerkh = $_POST['id_customerdpc'];
$ket = $_POST['ketdpc'];
$id_sales=$_POST['id_salesdpc'];
if ($_POST['id_salesdpc']!='') {
  $id_sales=' and tsi.id_sales ="'.$_POST['id_salesdpc'].'" ';
}
$select=mysql_query("SELECT * FROM `region`r right join customer s on (r.id_region=s.id_region)  where id_customer =".$id_customerkh);
$k= mysql_fetch_array($select);
date_default_timezone_set("Asia/Jakarta");
if ($ket==0) {
echo '

<table style="width: 100%;">
<tr>
<th colspan="4"  style="font-size: 26px;">Daftar Nota Tagihan Piutang</th>
</tr>
<tr >
<th  colspan="4" >UD. MELATI</th>
</tr>
<tr style="font-size: 26px;">
 <td>Nama customer</td>
 <td>:</td>
  <td colspan="2"><strong>'.$k['kode_customer'].'-'.$k['nama_customer'].' ('.$k['alamat_customer'].' )</td>
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
  <th>Nama Sales</th>
  <th>Piutang</th> 
  <th>Sisa Piutang</th>    
  <th>Surat Jalan</th>
  <th width="500px">keterangan</th>
  <th>Potongan</th>
  <th>Netto</th>
</tr>
</thead>
<tbody>';
$query = "SELECT  no_expedisi,tgl_si,tsi.id_invoice as nota ,nama_sales,tsi.grand_total,no_nota,if(sum(nominal_alokasi_detail_jual) is null,0,sum(nominal_alokasi_detail_jual) ) as diterima,
  tsi.id_customer,(tsi.grand_total-(if(sum(nominal_alokasi_detail_jual) is null,0,sum(nominal_alokasi_detail_jual) ) )) as sisa 
  FROM `trans_sales_invoice` tsi left join `trans_bayarjual_detail` tbd on (tsi.id_invoice=tbd.nota_invoice),sales s 
  WHERE   tsi.id_sales=s.id_sales and tgl_si >='".$awal."' and tgl_si <='".$akhir."' and tsi.id_customer= '".$id_customerkh."' ". $id_sales." group by id_invoice having sisa>0 ";
$query= mysql_query($query);
$satu=0;
$dua=0;
$tiga=0;
$no=1;
$tamp=1;
while ($tampil=mysql_fetch_array($query)) {
   echo "
      <tr>
      <td align=center>".$no."</td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_si]))."</td>
       <td>".$tampil['no_nota']." - ".$tampil['nota']."</td>
      <td>".$tampil['nama_sales']."</td>
      <td align=right>".format_jumlah($tampil['grand_total'])."</td>      
      <td align=right>".format_jumlah($tampil['sisa'])."</td>
      <td>".$tampil['no_expedisi']."</td>      
      <td>".$tampil['keterangan']."</td>
      <td align=right></td>
      <td align=right></td>      
      </tr>
    " ;
  $satu+=$tampil['sisa'];
  $dua +=$tampil['grand_total'];
  $no++;


  }


echo"
</tbody>
<tfoot>
  <tr>
      <td colspan=4  align=center>Total</td>

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
      <td colspan=4  align=center>Terbayar</td>
         <td></td>
      <td align=right>".format_jumlah(($tiga))."</td>      
      <td></td>      
      <td></td>
      <td align=right></td>
      <td align=right></td>  
  </tr>";

  }else{
     echo  " <tr>
      <td colspan=4  align=center>Terbayar</td>
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
 <td>Nama customer</td>
 <td>:</td>
  <td colspan="2"><strong>'.$k['kode_customer'].'-'.$k['nama_customer'].' ('.$k['alamat_customer'].' )</td>
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
  $query="SELECT tgl_pembayaranjual,tbh.bukti_bayarjual,nominaljual,tbh.id_customer,nominal_alokasi_jual FROM 
`trans_bayarjual_header`  tbh where status_bayar_jual!='klop' and status_titipan='T' and tbh.id_customer=$id_customerkh group by tbh.bukti_bayarjual";
$no=1;
$satu=0;
$dua=0;
$query=mysql_query($query);
while ($r=mysql_fetch_array($query)) {
  echo "     <tr> 
      <td>$no</td>
      <td>$r[tgl_pembayaranjual]</td>      
      <td>$r[bukti_bayarjual]</td>
      <td>".format_jumlah($r[nominaljual])."</td>
      <td>".format_jumlah($r[nominal_alokasi_jual])."</td>      
      <td colspan =2></td></tr>";
$no++;
$satu+=$r[nominaljual];
$dua +=$r[nominal_alokasi_jual];
}
echo "</tbody>
<tfoot>
<tr>
  <td colspan=3> Jumlah </td>
  <td>".format_jumlah($satu)."</td>
  <td>".format_jumlah($dua)."</td>
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
 <td>Nama customer</td>
 <td>:</td>
  <td colspan="2"><strong>'.$k['kode_customer'].'-'.$k['nama_customer'].' ('.$k['alamat_customer'].' )</td>
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
  <th>Nama Sales</th>
  <th>Jumlah</th>    
  <th>keterangan</th>
  <th>Potongan</th>
  <th>Netto</th>
</tr>
</thead>
<tbody>';
$query="SELECT * FROM `trans_retur_penjualan` trp,trans_sales_invoice tsi ,sales s 
                WHERE tsi.id_sales=s.id_sales 
                and trp.no_invoice=tsi.id_invoice
                and tgl_rjb >='".$awal."' 
                and tgl_rjb <='".$akhir."' 
                and trp.id_customer= '".$id_customerkh."' 
                and jenis_retur='2' 
                and trp.status= '0' 
                ". $id_sales." 
                and trp.is_void='0'";
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
      <td>".date('d-m-Y', strtotime($tampil[tgl_rjb]))."</td>
      <td>".$tampil['kode_rjb']."</td>
      <td>".$tampil['nama_sales']."</td>
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
      <td colspan=4  align=center>Total</td>      
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
<th colspan="4"  style="font-size: 26px;">Daftar Nota Tagihan </th>
</tr>
<tr >
<th  colspan="4" >UD. MELATI</th>
</tr>
<tr style="font-size: 26px;">
 <td>Nama customer</td>
 <td>:</td>
  <td colspan="2"><strong>'.$k['kode_customer'].'-'.$k['nama_customer'].' ('.$k['alamat_customer'].' )</td>
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
  <th width="15%">No. Nota</th>
  <th>Nama Sales</th>
  <th>Jumlah</th>   
  <th>Sisa</th>  
  <th>Surat Jalan</th> 
  <th>Keterangan</th>
  <th>Potongan</th>
  <th width="15%">Netto</th>
</tr>
</thead>
<tbody>';


$satu=0;
$dua=0;
$tiga=0;
$no=1;
$tamp=1;


$query = "SELECT  tgl_si,id_lkb as nota ,no_expedisi,nama_sales,tsi.grand_total,no_nota,if(sum(nominal_alokasi_detail_jual) is null,0,sum(nominal_alokasi_detail_jual) ) as diterima,
  tsi.id_customer,(tsi.grand_total-(if(sum(nominal_alokasi_detail_jual) is null,0,sum(nominal_alokasi_detail_jual) ) )) as sisa 
  FROM `trans_sales_invoice` tsi left join `trans_bayarjual_detail` tbd on (tsi.id_invoice=tbd.nota_invoice),sales s 
  WHERE   tsi.id_sales=s.id_sales and tgl_si >='".$awal."' and tgl_si <='".$akhir."' and tsi.id_customer= '".$id_customerkh."' ". $id_sales." group by id_invoice having sisa>0 ";
$query= mysql_query($query);
while ($tampil=mysql_fetch_array($query)) {
   echo "
      <tr>
      <td align=center>".$no."</td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_si]))."</td>
      <td>".$tampil['nota']." - ".$tampil['no_nota']."</td>
      <td>".$tampil['nama_sales']."</td>
      <td align=right>".format_jumlah($tampil['grand_total'])."</td>      
      <td align=right>".format_jumlah($tampil['sisa'])."</td>
      <td align=right>".$tampil['no_expedisi']."</td>
      <td></td>      
      <td>".$tampil['keterangan']."</td>
      <td align=right></td>
      </tr>
    " ;
  $satu+=$tampil['grand_total'];
  $dua +=$tampil['sisa'];
  $no++;


  }

$query="SELECT * FROM `trans_retur_penjualan` trp,trans_sales_invoice tsi ,sales s 
                WHERE tsi.id_sales=s.id_sales 
                and trp.no_invoice=tsi.id_invoice
                and tgl_rjb >='".$awal."' 
                and tgl_rjb <='".$akhir."' 
                and trp.id_customer= '".$id_customerkh."' 
                and jenis_retur='2' 
                and trp.status= '0' 
                  ". $id_sales." 
                and trp.is_void='0'";
$query = mysql_query($query);
while ($tampil=mysql_fetch_array($query)) {
   echo "
      <tr>
      <td align=center>".$no."</td>
      <td>".date('d-m-Y', strtotime($tampil[tgl_rjb]))."</td>
      <td>".$tampil['kode_rjb']."</td>
      <td>".$tampil['nama_sales']."</td>
       <td align=right>(".format_jumlah($tampil['grandtotal_retur']).")</td>        
      <td align=right>(".format_jumlah($tampil['grandtotal_retur']).")</td>        
      <td>".$tampil['keterangan']."</td>
      <td align=right></td>     
      <td align=right></td>      
      </tr>
    " ;
  $satu -=$tampil['grandtotal_retur'];
  $dua -=$tampil['grandtotal_retur'];
  $no++;
  }
  $query="SELECT tgl_pembayaranjual,tbh.bukti_bayarjual,nominaljual,tbh.id_customer,nominal_alokasi_jual FROM 
`trans_bayarjual_header`  tbh where status_bayar_jual!='klop' and status_titipan='T' and tbh.id_customer=$id_customerkh group by tbh.bukti_bayarjual";
$query=mysql_query($query);
while ($r=mysql_fetch_array($query)) {
  echo "     <tr> 
      <td>$no</td>
      <td>$r[tgl_pembayaranjual]</td>      
      <td>$r[bukti_bayarjual]</td>
      <td></td>
       <td align=right>(".format_jumlah($r[nominaljual]).")</td>      
      <td align=right>(".format_jumlah($r[nominaljual]).")</td>      
      <td colspan =3></td></tr>";

$dua -=$r[nominaljual];
$satu -=$r[nominaljual];
}
echo"
</tbody>
<tfoot>
  <tr>
      <td colspan=4  align=center>Total</td>      
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

