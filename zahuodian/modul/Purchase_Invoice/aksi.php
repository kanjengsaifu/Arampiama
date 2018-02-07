<?php
 include "../../config/koneksi.php";
include "../../lib/input.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
$module=$_GET['module'];
$act=$_GET['act'];

if ($module=='purchaseinvoice' AND $act=='delete'){
        $query="SELECT * FROM `trans_invoice` where id='$_GET[id]' ";
        $result=mysql_query($query);
        $r=mysql_fetch_array($result);
                    input_only_log("DELETE FROM jurnal_umum where kode_nota='$r[id_invoice]'");
                    input_only_log("DELETE FROM trans_invoice WHERE id_invoice='$r[id_invoice]'");
                    input_only_log("DELETE FROM trans_invoice_detail WHERE id_invoice='$r[id_invoice]'");
                    input_data("UPDATE trans_lpb SET status_trans='1' where id_lpb= '$r[id_lpb]'",$module);
}

elseif ($module=='purchaseinvoice' AND $act=='input'){
if (check_lpb($_POST[no_po])==0) {
  $querystatus="UPDATE trans_lpb SET status_trans='2' where id_lpb= '$_POST[no_po]'";
$noz= $_POST[noz];
input_only_log($querystatus,$module);
$id_invoice= kode_surat('PI','trans_invoice','id_invoice','id');
$query="INSERT INTO trans_invoice_detail(
                                                                id_invoice,
                                                                id_barang,
                                                                qty_po,
                                                                qty_po_satuan,
                                                                harga_po,
                                                                total_po,
                                                                qty_pi,
                                                                qty_pi_satuan,
                                                                qty_pi_convert,
                                                                harga_pi,
                                                                disc1,
                                                                disc2,
                                                                disc3,
                                                                disc4,
                                                                disc5,
                                                                total,
                                                                user,
                                                                tgl_update)
                                                                VALUES";   
for ($i=0;$i<$noz;$i++){
      $values=   "('".  $id_invoice."','".
                                $_POST[id_barang][$i]."','".
                                $_POST[qty_po][$i]."','".
                                $_POST[qty_po_satuan][$i]."','".
                                $_POST[harga_po][$i]."','".
                                $_POST[total_po][$i]."','".
                                $_POST[qty_pi][$i]."','".
                                $_POST[qty_pi_satuan][$i]."','".
                                $_POST[qty_pi_convert][$i]."','".
                                $_POST[harga_pi][$i]."','".
                                $_POST[disc1][$i]."','".
                                $_POST[disc2][$i]."','".
                                $_POST[disc3][$i]."','".
                                $_POST[disc4][$i]."','".
                                $_POST[disc5][$i]."','".
                                $_POST[total][$i]."','".
                                $_SESSION[namauser]."',
                                now())";
      $queryini  = $query.$values;
              input_only_log($queryini,$module);
      $data=mysql_query("SELECT * from barang where id_barang='".$_POST[id_barang][$i]."'");
      $data=mysql_fetch_array($data);
      input_jurnal_umum_tipe_1($data['id_akunkasperkiraan'],'','','1',$_POST[total][$i], $id_invoice,$_POST[tgl_pi],$_POST[supplier],'supplier','',$id_invoice);
      if ($_POST['hppId']==1){
      $query_update = "UPDATE barang 
                                    SET hpp = '".($_POST[total][$i]/$_POST[qty_pi_convert][$i])."' 
                                    WHERE id_barang = '".$_POST[id_barang][$i]."'";
      input_only_log($query_update, $module);
      }
}
$query = ("INSERT INTO trans_invoice(
                                                                id_invoice,
                                                                id_lpb,
                                                                id_supplier,
                                                                no_nota,
                                                                no_expedisi,
                                                                tgl_pi,
                                                                tgl,
                                                                alltotal,
                                                                alldiscpersen,
                                                                alldiscnominal,
                                                                allppnpersen,
                                                                allppnnominal,
                                                                grand_total,
                                                                user,
                                                                tgl_update)
                                                                VALUES(
                                                                                '$id_invoice',
                                                                                '$_POST[no_po]',
                                                                                '$_POST[supplier]',
                                                                                '$_POST[no_nota]',
                                                                                '$_POST[no_expedisi]',
                                                                                '$_POST[tgl_pi]',
                                                                                '$_POST[tgl_jt]',
                                                                                '$_POST[alltotal]',
                                                                                '$_POST[alldiscpersen]',
                                                                                '$_POST[alldiscnominal]',
                                                                                '$_POST[allppnpersen]',
                                                                                '$_POST[allppnnominal]',
                                                                                '$_POST[grandtotal]',
                                                                                '$_SESSION[namauser]',
                                                                                now())"
);
      input_jurnal_umum_tipe_1('','71','','1',$_POST[grandtotal],  $id_invoice,$_POST[tgl_pi],$_POST[supplier],'supplier','',$id_invoice);
      if ($_POST[allppnnominal]!='') {
        if ($_POST[allppnnominal]!=0) {
            input_jurnal_umum_tipe_1('55','','','1',$_POST[allppnnominal],  $id_invoice,$_POST[tgl_pi],$_POST[supplier],'supplier','',$id_invoice);
          }
      }
 if ($_POST[alldiscnominal]!='') {
   if ($_POST[alldiscnominal]!=0) {
   input_jurnal_umum_tipe_1('','94','','1',$_POST[alldiscnominal],  $id_invoice,$_POST[tgl_pi],$_POST[supplier],'supplier','',$id_invoice);
 }
 }
           
      
      input_and_print($query,$module,  $id_invoice);
}else{
  Echo "Transaksi Sudah Pernah di lakukan";
}

}


elseif ($module=='purchaseinvoice' AND $act=='update'){
$noz= $_POST[noz];
echo $noz;
  mysql_query("Delete from jurnal_umum where kode_nota='".$_POST[id_invoice]."'");
 for ($i=0; $i < $noz; $i++) { 
$sqlini2 = "UPDATE trans_invoice_detail SET harga_pi='". $_POST[harga_pi][$i]."',disc1='". $_POST[disc1][$i]."',disc2='". $_POST[disc2][$i]."',disc3='". $_POST[disc3][$i]."',disc4='". $_POST[disc4][$i]."',disc5='". $_POST[disc5][$i]."',total='". $_POST[total][$i]."',user='". $_SESSION[namauser]."',tgl_update=now() where id='".$_POST[id][$i]."'";
      
      $data=mysql_query("SELECT * from barang where id_barang='".$_POST[id_barang][$i]."'");
      $data=mysql_fetch_array($data);
input_jurnal_umum_tipe_1($data['id_akunkasperkiraan'],'','','1',$_POST[total][$i],$_POST[id_invoice],$_POST[tgl_pi],$_POST[id_supplier],'supplier','',$_POST[id_invoice]);

            if ($_POST['hppId']==1){
            $query_update = "UPDATE barang SET hpp = '".$_POST[harga_pi][$i]."' WHERE id_barang = '".$_POST[id_barang][$i]."'";
            input_only_log($query_update, $module);
    }

   input_only_log($sqlini2,$module);
 
 }

   $sqlini = "UPDATE trans_invoice SET 
                                  tgl_pi = '$_POST[tgl_pi]',
                                  tgl = '$_POST[tgl_jt]',
                                  alltotal = '$_POST[alltotal]',
                                  alldiscpersen = '$_POST[alldiscpersen]',
                                  alldiscnominal = '$_POST[alldiscnominal]',
                                  allppnpersen = '$_POST[allppnpersen]',
                                  allppnnominal = '$_POST[allppnnominal]',
                                  grand_total = '$_POST[grandtotal]',
                                  user = '$_SESSION[namauser]',
                                  tgl_update = now()
                                  where id_invoice='$_POST[id_invoice]'";
input_jurnal_umum_tipe_1('','71','','1',$_POST[grandtotal],$_POST[id_invoice],$_POST[tgl_pi],$_POST[id_supplier],'supplier','',$_POST[id_invoice]);
      if ($_POST[allppnnominal]!='') {
        if ($_POST[allppnnominal]!=0) {
  input_jurnal_umum_tipe_1('55','','','1',$_POST[allppnnominal],$_POST[id_invoice],$_POST[tgl_pi],$_POST[id_supplier],'supplier','',$_POST[id_invoice]);
}
}
 if ($_POST[alldiscnominal]!='') {
   if ($_POST[alldiscnominal]!=0) {
  input_jurnal_umum_tipe_1('','94','','1',$_POST[alldiscnominal],$_POST[id_invoice],$_POST[tgl_pi],$_POST[id_supplier],'supplier','',$_POST[id_invoice]);
}
}
input_data($sqlini,$module);

}


}
function cari_kode(){
    $var = explode('-', date("Y-m-d"));
    $sql_cari="SELECT max(`id_invoice`) as kode FROM `trans_invoice` WHERE `id_invoice` LIKE 'PI/$var[0]$var[1]%' ";
    $result=mysql_query($sql_cari);
    $hasil=mysql_fetch_array($result);
    $kode = explode('/',$hasil["kode"]);
    $kode_ururt=100001+$kode[2];
   return 'PI/'.implode('', $var).'/'.substr($kode_ururt,1);
}
function check_lpb($kode_lpb){
    $sql_cari="SELECT * FROM `trans_lpb` WHERE `id_lpb`='$kode_lpb' and `status_trans`='2' ";
    $result=mysql_query($sql_cari);
    $hasil =mysql_num_rows($result);
    return $hasil;
}
?>
