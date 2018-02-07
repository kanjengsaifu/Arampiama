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

$select=mysql_query("SELECT * FROM `region`r right join customer s on (r.id_region=s.id_region)  where id_customer =".$id_customerkh);
$k= mysql_fetch_array($select);
date_default_timezone_set("Asia/Jakarta");
echo '

<table style="width: 100%;">
<tr>
<th colspan="4"  style="font-size: 26px;">Daftar Penagihan Piutang</th>
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
</table>


<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>
<tr>
  <th>No</th>
  <th>Tanggal</th>
  <th>No. Nota</th>
  <th>Nama Sales</th>
  <th>Telah Diterima</th> 
  <th>Sisa Piutang</th>    
  <th>Surat Jalan</th>
  <th>keterangan</th>
  <th>Potongan</th>
  <th>Netto</th>
</tr>
</thead>
<tbody>';
$query = mysql_query("SELECT  tgl,id_invoice as nota ,nama_sales,tsi.grand_total,no_nota_customer,sum(nominal_alokasi_detail_jual) as diterima,
  tsi.id_customer,(tsi.grand_total-sum(nominal_alokasi_detail_jual)) as sisa 
  FROM `trans_sales_invoice` tsi,`trans_bayarjual_detail` tbd,trans_lkb tl,trans_sales_order tso,sales s 
  WHERE tsi.id_invoice=tbd.nota_invoice and tl.id_lkb=tsi.id_lkb and tso.id_sales_order=tl.id_sales_order and tso.id_sales=s.id_sales and tgl >='".$awal."' and tgl <='".$akhir."' and tsi.id_customer= '".$id_customerkh."' group by id_invoice having sisa>0 ");
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
      <td>".$tampil['nama_sales']."</td>
      <td align=right>".format_jumlah($tampil['diterima'])."</td>      
      <td align=right>".format_jumlah($tampil['sisa'])."</td>
      <td>".$tampil['no_nota_customer']."</td>      
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
      <td colspan=4  align=center>Sisa</td>
         <td></td>
      <td align=right>".format_jumlah($tiga)."</td>      
      <td></td>      
      <td></td>
      <td align=right></td>
      <td align=right></td>  
  </tr>";
  }else{
     echo  " <tr>
      <td colspan=4  align=center>Sisa</td>
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
  
}
}
?>

