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
  $explode=explode('-', $_POST['tanggal_harian']);
  $kode_lap=$explode[0].$explode[1].$explode[2];
  $query= "select * from lap_rekap_kas_bank where kode_lap ='".$kode_lap."'";
  $temp=mysql_num_rows(mysql_query($query));
##########################<!-- ###################################### Mencari total Perhari  #####################################-->###########
$query =mysql_query("select sum(penerimaan_kas) as tkas,sum(pengeluaran_kas) as tkas2,sum(penerimaan_bank) as tbank,sum(pengeluaran_bank) as tbank2,sum(penerimaan_giro) as tgiro,sum(pengeluaran_giro) as tgiro2 from (SELECT bukti_bayar,concat(nama_supplier) as nama_akunkasperkiraan,concat(kode_akun_header,'-0',kode_akunkasperkiraan,'-',nama_akunkasperkiraan) as kode_akun,'' as penerimaan_kas,nominal_alokasi as pengeluaran_kas,'' as penerimaan_bank,'' as pengeluaran_bank,'' as penerimaan_giro,'' as pengeluaran_giro,t.tgl_update as tanggal FROM akun_kas_perkiraan akp ,`trans_bayarbeli_detail` t left join supplier c on (c.id_supplier=t.id_supplier) where t.id_akunkasperkiraan_detail=akp.id_akunkasperkiraan  and bukti_bayar like 'BKK%'
union all
SELECT bukti_bayar,concat(nama_supplier) as nama_akunkasperkiraan,concat(kode_akun_header,'-0',kode_akunkasperkiraan,'-',nama_akunkasperkiraan) as kode_akun,'' as penerimaan_kas,'' as pengeluaran_kas,'' as penerimaan_bank,nominal_alokasi as pengeluaran_bank,'' as penerimaan_giro,'' as pengeluaran_giro,t.tgl_update as tanggal FROM akun_kas_perkiraan akp ,`trans_bayarbeli_detail` t left join supplier c on (c.id_supplier=t.id_supplier) where t.id_akunkasperkiraan_detail=akp.id_akunkasperkiraan  and bukti_bayar like 'BBK%'
union all
SELECT bukti_bayar,concat(nama_supplier) as nama_akunkasperkiraan,concat(kode_akun_header,'-0',kode_akunkasperkiraan,'-',nama_akunkasperkiraan) as kode_akun,'' as penerimaan_kas,  '' as pengeluaran_kas,'' as penerimaan_bank,'' as pengeluaran_bank,'' as penerimaan_giro,nominal_alokasi as pengeluaran_giro,t.tgl_update as tanggal FROM akun_kas_perkiraan akp ,`trans_bayarbeli_detail` t left join supplier c on (c.id_supplier=t.id_supplier) where t.id_akunkasperkiraan_detail=akp.id_akunkasperkiraan  and bukti_bayar like 'BGK%'
union all
SELECT bukti_bayarjual,concat(nama_customer) as nama_akunkasperkiraan,concat(kode_akun_header,'-0',kode_akunkasperkiraan,'-',nama_akunkasperkiraan) as kode_akun,nominal_alokasi_detail_jual as penerimaan_kas, '' as pengeluaran_kas,'' as penerimaan_bank,'' as pengeluaran_bank,'' as penerimaan_giro,'' as pengeluaran_giro,t.tgl_update as tanggal FROM akun_kas_perkiraan akp ,`trans_bayarjual_detail` t left join customer c on (c.id_customer=t.id_customer) where t.id_akunkasperkiraan_detail=akp.id_akunkasperkiraan  and bukti_bayarjual like 'BkM%'
union all
SELECT bukti_bayarjual,concat(nama_customer) as nama_akunkasperkiraan,concat(kode_akun_header,'-0',kode_akunkasperkiraan,'-',nama_akunkasperkiraan) as kode_akun,'' as penerimaan_kas, '' as pengeluaran_kas,nominal_alokasi_detail_jual as penerimaan_bank,'' as pengeluaran_bank,'' as penerimaan_giro,'' as pengeluaran_giro,t.tgl_update as tanggal FROM akun_kas_perkiraan akp ,`trans_bayarjual_detail` t left join customer c on (c.id_customer=t.id_customer) where t.id_akunkasperkiraan_detail=akp.id_akunkasperkiraan  and bukti_bayarjual like 'BBM%'
union all
SELECT bukti_bayarjual,concat(nama_customer) as nama_akunkasperkiraan,concat(kode_akun_header,'-0',kode_akunkasperkiraan,'-',nama_akunkasperkiraan) as kode_akun,'' as penerimaan_kas, '' as pengeluaran_kas,'' as penerimaan_bank,'' as pengeluaran_bank,nominal_alokasi_detail_jual as penerimaan_giro,'' as pengeluaran_giro,t.tgl_update as tanggal FROM akun_kas_perkiraan akp ,`trans_bayarjual_detail` t left join customer c on (c.id_customer=t.id_customer) where t.id_akunkasperkiraan_detail=akp.id_akunkasperkiraan  and bukti_bayarjual like 'BGM%'
union all
SELECT kode_nota,' ' as nama_akunkasperkiraan,
concat(ah.kode_akun_header,'-0',kode_akunkasperkiraan,'-',nama_akunkasperkiraan) as kode_akun,
if(ah.kode_akun_header='111'and debet_kredit='D' ,nominal,'') as penerimaan_kas,
if(ah.kode_akun_header='111'and debet_kredit='K' ,nominal,'') as pengeluaran_kas,
if(ah.kode_akun_header='112'and debet_kredit='D' ,nominal,'') as penerimaan_bank,
if(ah.kode_akun_header='112'and debet_kredit='K' ,nominal,'') as pengeluaran_bank,
'' as penerimaan_giro,'' as pengeluaran_giro,
j.tanggal_update as tanggal
FROM jurnal_umum j,akun_kas_perkiraan a, akun_header ah where j.id_akun_kas_perkiraan=a.id_akunkasperkiraan and ah.kode_akun_header=a.kode_akun_header and kode_nota like 'JV%' and (ah.kode_akun_header='111' or ah.kode_akun_header='113') ) as tsum where tanggal like '".$_POST['tanggal_harian']."%';");
$r=mysql_fetch_array($query) ;
##########################<!-- ###################################### Mencari total Perhari  #####################################-->###########
##########################<!-- ###################################### Mencari saldo akhir  #####################################-->###########
$query= "select saldo_akhir_kas,saldo_akhir_bank,saldo_akhir_giro from lap_rekap_kas_bank where kode_lap <'".$explode[0].$explode[2].$explode[1]."' order by kode_lap desc limit 1";
  $s=mysql_fetch_array(mysql_query($query)) ;
##########################<!-- ###################################### Mencari saldo akhir  #####################################-->###########
  if ($temp==0) {
    if ($kode_lap==""){
        header('location:../../media.php?module='.$module);
    }
    $query="INSERT INTO lap_rekap_kas_bank(kode_lap,saldo_awal_kas,saldo_akhir_kas,saldo_awal_bank,saldo_akhir_bank,saldo_awal_giro,saldo_akhir_giro,user_update,tgl_update) 
    values ('$kode_lap','".intval($s['saldo_akhir_kas'])."','".(intval($s['saldo_akhir_kas'])+intval($r['tkas'])-intval($r['tkas2']))."',
      '".intval($s['saldo_akhir_bank'])."','".(intval($s['saldo_akhir_bank'])+intval($r['tbank'])-intval($r['tbank2']))."',
      '".intval($s['saldo_akhir_giro'])."','".(intval($s['saldo_akhir_giro'])+intval($r['tgiro'])-intval($r['tgiro2']))."',
      '$_SESSION[namauser]',now())";
    input_only_log($query,$module);
  }else{
    if ($kode_lap==""){
        header('location:../../media.php?module='.$module);
    }
    $query="UPDATE `lap_rekap_kas_bank` 
    SET `saldo_awal_kas`='".intval($s['saldo_akhir_kas'])."',`saldo_akhir_kas`='".(intval($s['saldo_akhir_kas'])+intval($r['tkas'])-intval($r['tkas2']))."',
    `saldo_awal_bank`='".intval($s['saldo_akhir_bank'])."',`saldo_akhir_bank`='".(intval($s['saldo_akhir_bank'])+intval($r['tbank'])-intval($r['tbank2']))."',
    `saldo_awal_giro`='".intval($s['saldo_akhir_giro'])."',
    `saldo_akhir_giro`='".(intval($s['saldo_akhir_giro'])+intval($r['tgiro'])-intval($r['tgiro2']))."'
    ,`tgl_update`= now(),`user_update`='$_SESSION[namauser]' WHERE `kode_lap`='$kode_lap'";
  input_only_log($query,$module);

  }
date_default_timezone_set("Asia/Jakarta");
//$aksi="modul/purchaseorder/aksi_purchaseorder.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
echo '
<div style="text-align:Center" >
<h2>KAS-BANK HARIAN</h2>
</BR>UD. MELATI

</div>
 <p align=right>Tanggal Laporan : '.tgl_indo($_POST['tanggal_harian']).' </p>
<table width="100%" class="table table-hover" cellspacing=0 border= 1px solid black>
<thead>
  <tr>
    <th>No. Bukti </th>
    <th>Kode Perk </th>
    <th>Keterangan</th>
    <th colspan="2">Kas</th>
    <th colspan="2">Giro</th>
    <th colspan="2">Bank</th>
  </tr>
  <tr>
    <th colspan="3">&nbsp;</th>
    <th>Penerimaan</th>
    <th>Pengeluaran</th>
    <th>Penerimaan</th>
    <th>Pengeluaran</th>
    <th>Penerimaan</th>
    <th>Pengeluaran</th>
  </tr>
</thead>

<tbody>';
$query =mysql_query("select * from (SELECT bukti_bayar,concat(nama_supplier) as nama_akunkasperkiraan,concat(kode_akun_header,'-0',kode_akunkasperkiraan,'-',nama_akunkasperkiraan) as kode_akun,'' as penerimaan_kas,nominal_alokasi as pengeluaran_kas,'' as penerimaan_bank,'' as pengeluaran_bank,'' as penerimaan_giro,'' as pengeluaran_giro,t.tgl_update as tanggal FROM akun_kas_perkiraan akp ,`trans_bayarbeli_detail` t left join supplier c on (c.id_supplier=t.id_supplier) where t.id_akunkasperkiraan_detail=akp.id_akunkasperkiraan  and bukti_bayar like 'BKK%'
union all
SELECT bukti_bayar,concat(nama_supplier) as nama_akunkasperkiraan,concat(kode_akun_header,'-0',kode_akunkasperkiraan,'-',nama_akunkasperkiraan) as kode_akun,'' as penerimaan_kas,'' as pengeluaran_kas,'' as penerimaan_bank,nominal_alokasi as pengeluaran_bank,'' as penerimaan_giro,'' as pengeluaran_giro,t.tgl_update as tanggal FROM akun_kas_perkiraan akp ,`trans_bayarbeli_detail` t left join supplier c on (c.id_supplier=t.id_supplier) where t.id_akunkasperkiraan_detail=akp.id_akunkasperkiraan  and bukti_bayar like 'BBK%'
union all
SELECT bukti_bayar,concat(nama_supplier) as nama_akunkasperkiraan,concat(kode_akun_header,'-0',kode_akunkasperkiraan,'-',nama_akunkasperkiraan) as kode_akun,'' as penerimaan_kas,  '' as pengeluaran_kas,'' as penerimaan_bank,'' as pengeluaran_bank,'' as penerimaan_giro,nominal_alokasi as pengeluaran_giro,t.tgl_update as tanggal FROM akun_kas_perkiraan akp ,`trans_bayarbeli_detail` t left join supplier c on (c.id_supplier=t.id_supplier) where t.id_akunkasperkiraan_detail=akp.id_akunkasperkiraan  and bukti_bayar like 'BGK%'
union all
SELECT bukti_bayarjual,concat(nama_customer) as nama_akunkasperkiraan,concat(kode_akun_header,'-0',kode_akunkasperkiraan,'-',nama_akunkasperkiraan) as kode_akun,nominal_alokasi_detail_jual as penerimaan_kas, '' as pengeluaran_kas,'' as penerimaan_bank,'' as pengeluaran_bank,'' as penerimaan_giro,'' as pengeluaran_giro,t.tgl_update as tanggal FROM akun_kas_perkiraan akp ,`trans_bayarjual_detail` t left join customer c on (c.id_customer=t.id_customer) where t.id_akunkasperkiraan_detail=akp.id_akunkasperkiraan  and bukti_bayarjual like 'BkM%'
union all
SELECT bukti_bayarjual,concat(nama_customer) as nama_akunkasperkiraan,concat(kode_akun_header,'-0',kode_akunkasperkiraan,'-',nama_akunkasperkiraan) as kode_akun,'' as penerimaan_kas, '' as pengeluaran_kas,nominal_alokasi_detail_jual as penerimaan_bank,'' as pengeluaran_bank,'' as penerimaan_giro,'' as pengeluaran_giro,t.tgl_update as tanggal FROM akun_kas_perkiraan akp ,`trans_bayarjual_detail` t left join customer c on (c.id_customer=t.id_customer) where t.id_akunkasperkiraan_detail=akp.id_akunkasperkiraan  and bukti_bayarjual like 'BBM%'
union all
SELECT bukti_bayarjual,concat(nama_customer) as nama_akunkasperkiraan,concat(kode_akun_header,'-0',kode_akunkasperkiraan,'-',nama_akunkasperkiraan) as kode_akun,'' as penerimaan_kas, '' as pengeluaran_kas,'' as penerimaan_bank,'' as pengeluaran_bank,nominal_alokasi_detail_jual as penerimaan_giro,'' as pengeluaran_giro,t.tgl_update as tanggal FROM akun_kas_perkiraan akp ,`trans_bayarjual_detail` t left join customer c on (c.id_customer=t.id_customer) where t.id_akunkasperkiraan_detail=akp.id_akunkasperkiraan  and bukti_bayarjual like 'BGM%'
union all
SELECT kode_nota,' ' as nama_akunkasperkiraan,
concat(ah.kode_akun_header,'-0',kode_akunkasperkiraan,'-',nama_akunkasperkiraan) as kode_akun,
if(ah.kode_akun_header='111' and debet_kredit='D' ,nominal,'') as penerimaan_kas,
if(ah.kode_akun_header='111' and debet_kredit='K' ,nominal,'') as pengeluaran_kas,
if(ah.kode_akun_header='113' and debet_kredit='D' ,nominal,'') as penerimaan_bank,
if(ah.kode_akun_header='113' and debet_kredit='K' ,nominal,'') as pengeluaran_bank,
'' as penerimaan_giro,'' as pengeluaran_giro,
j.tanggal_update as tanggal
FROM jurnal_umum j,akun_kas_perkiraan a, akun_header ah where j.id_akun_kas_perkiraan=a.id_akunkasperkiraan and ah.kode_akun_header=a.kode_akun_header and kode_nota like 'JV%' and (ah.kode_akun_header='111' or ah.kode_akun_header='113')) as dtrans where tanggal like '".$_POST['tanggal_harian']."%'");
while ($rk=mysql_fetch_array($query)) {
   echo "<tr>
    <td>$rk[bukti_bayar]</td>
    <td>$rk[kode_akun]</td>
    <td>$rk[nama_akunkasperkiraan]</td>
    <td style='text-align:right'>".format_jumlah(intval($rk['penerimaan_kas']))."</td>
    <td style='text-align:right'>".format_jumlah(intval($rk['pengeluaran_kas']))."</td>
    <td style='text-align:right'>".format_jumlah(intval($rk['penerimaan_giro']))."</td>
    <td style='text-align:right'>".format_jumlah(intval($rk['pengeluaran_giro']))."</td>
    <td style='text-align:right'>".format_jumlah(intval($rk['penerimaan_bank']))."</td>
    <td style='text-align:right'>".format_jumlah(intval($rk['pengeluaran_bank']))."</td>
  </tr>";
}

echo'
</tbody>
  <tfoot>';

echo "
<!-- ###################################### Total  #####################################-->
   <tr>
     
    <td style='text-align:left' colspan='3'>Total</td>
    <td style='text-align:right' >".format_jumlah(intval($r['tkas']))."</td>
    <td style='text-align:right' >".format_jumlah(intval($r['tkas2']))."</td>
    <td style='text-align:right' >".format_jumlah(intval($r['tgiro']))."</td>
    <td style='text-align:right' >".format_jumlah(intval($r['tgiro2']))."</td>
    <td style='text-align:right' >".format_jumlah(intval($r['tbank']))."</td>
    <td style='text-align:right' >".format_jumlah(intval($r['tbank2']))."</td>
  </tr>
<!-- ###################################### Total  #####################################-->
  ";
  $query= "select * from lap_rekap_kas_bank where kode_lap ='".$kode_lap."'";
  $saldo=mysql_fetch_array(mysql_query($query));
  echo "
<!-- ###################################### saldo awal  #####################################-->

   <tr>
    <th style='text-align:left' colspan='3'>Saldo Awal</th>
    <td style='text-align:right'>".format_jumlah($saldo['saldo_awal_kas'])."</td>
    <td></td>
    <td style='text-align:right'>".format_jumlah($saldo['saldo_awal_giro'])."</td>
    <td></td>
    <td style='text-align:right'>".format_jumlah($saldo['saldo_awal_bank'])."</td>
    <td></td>
  </tr>
  <!-- ###################################### saldo awal  #####################################-->
  <!-- ###################################### saldo akhir  #####################################-->
   <tr>
    <th colspan='3' style='text-align:left' >Saldo Akhir</th>
    <td></td>
    <td style='text-align:right'>".format_jumlah($saldo['saldo_akhir_kas'])."</td>
    <td></td>
    <td style='text-align:right'>".format_jumlah($saldo['saldo_akhir_giro'])."</td>
    <td></td>
     <td style='text-align:right'>".format_jumlah($saldo['saldo_akhir_bank'])."</td>
  </tr>
  <!-- ###################################### saldo akhir  #####################################-->
  <!-- ###################################### Grand Total  #####################################-->
   <tr>
    <th colspan='3' style='text-align:left' >Grand Total</th>
    <td style='text-align:right' >".format_jumlah((intval($r['tkas'])+intval($saldo['saldo_awal_kas'])))."</td>
    <td style='text-align:right' >".format_jumlah((intval($r['tkas2'])+intval($saldo['saldo_akhir_kas'])))."</td>
    <td style='text-align:right' >".format_jumlah((intval($r['tgiro'])+intval($saldo['saldo_awal_giro'])))."</td>
    <td style='text-align:right' >".format_jumlah((intval($r['tgiro2'])+intval($saldo['saldo_akhir_giro'])))."</td>
    <td style='text-align:right' >".format_jumlah((intval($r['tbank'])+intval($saldo['saldo_awal_bank'])))."</td>
    <td style='text-align:right' >".format_jumlah((intval($r['tbank2'])+intval($saldo['saldo_akhir_bank'])))."</td>
  </tr>
  <!-- ###################################### Grand Total  #####################################-->
  </tfoot>
</table>
<p>Tanggal Dibuat : ".tgl_indo(date("Y/m/d"))." - ".date("h:i:s a") ." </p>
";
  
}
}
?>

