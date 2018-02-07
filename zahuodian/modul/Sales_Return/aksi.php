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

// Hapus modul
if ($module=='returpenjualan' AND $act=='delete'){
 $query=mysql_query("SELECT * FROM `trans_retur_penjualan_detail` trpd, trans_retur_penjualan trp WHERE trp.kode_rjb=trpd.kode_rjb and trp.kode_rjb='$_GET[kode]' and trp.is_void=0 ");
 while ( $r=mysql_fetch_array($query)){
  $t =mysql_fetch_array(mysql_query("SELECT `stok_sekarang` FROM `stok` WHERE `id_barang`='$r[id_barang]' and `id_gudang`='$r[id_gudang]'"));
  if ($_GET['jenisretur']=='2') {
    input_only_log("update stok set stok_sekarang=". ($t["stok_sekarang"] -$r['qty_convert'])." WHERE id_barang = '".$r["id_barang"]."' AND id_gudang =  '".$r['id_gudang']."'",$module);
  }elseif ($_GET['jenisretur']=='1') {
    BS($r["qty_convert"],$module,'kurang');
  }
  $no_invoice=$r[id_invoice];
    mysql_query("UPDATE `trans_sales_invoice` SET `status_retur`='0' WHERE `id_invoice`='$no_invoice'");
 }
  $delete= "delete from  jurnal_umum where kode_nota='".$_GET['kode']."' ";
  mysql_query($delete);

input_data("update `trans_retur_penjualan` set is_void='1' WHERE kode_rjb='$_GET[kode]'",$module);
}
// Input menu
elseif ($module=='returpenjualan' AND $act=='input'){
$kode_rjb=kode_surat('RJB','trans_retur_penjualan','kode_rjb','id');



    $query_header = "INSERT INTO trans_retur_penjualan_detail(
                                                kode_rjb,
                                                id_invoice,
                                                no_nota,
                                                id_barang,
                                                id_gudang,
                                                qty_si,
                                                qty_si_satuan,
                                                qty_si_convert,
                                                qty_retur,
                                                satuan,
                                                qty_convert,
                                                harga_retur,
                                                harga_per_satuan_terkecil,
                                                user_update,
                                                tgl_update)
                                                VALUES ";
 $no=1;
 foreach ($_POST['id_barang'] as $i => $value) {
   $split_satuan=explode('-', $_POST["jenis_satuan"][$i]);
        $querydetail .= ",";
$querydetail .= "('" . $kode_rjb . "', '" . $_POST["id_invoice"][$i] . "','" . $_POST["no_nota"][$i] . "','" . $_POST["id_barang"][$i] . "','" . $_POST["id_gudang"][$i] . "', '" . $_POST["qty_si"][$i]. "', '" . $_POST["qty_si_satuan"][$i] . "', '" . $_POST["qty_si_convert"][$i]   . "', '" . $_POST["jml-rjb"][$i] . "', '" . $split_satuan[1] . "', '" .$_POST["qty_convert"][$i] . "', '" .$_POST["total-rjb"][$i] . "', '" .$_POST["harga-rjb"][$i] . "', '" . $_SESSION["namauser"]. "', now() )";
input_only_log("UPDATE trans_sales_invoice set status_retur=1 where id_invoice='".$_POST["id_invoice"][$i]."' ",$module);


      ################################### Stok Start ##############################
                  if ($_POST['jenis_retur']=='2') {
                   $t =mysql_fetch_array(mysql_query("SELECT `stok_sekarang` FROM `stok` 
                                                                           WHERE `id_barang`='".$_POST["id_barang"][$i]."' and `id_gudang`='". $_POST["id_gudang"][$i]."'"));
 input_only_log("update stok set stok_sekarang=". ($t["stok_sekarang"] +$_POST["qty_convert"][$i])." 
                                  WHERE id_barang = '".$_POST["id_barang"][$i]."' AND id_gudang =  '". $_POST["id_gudang"][$i]."'",$module);
                }else  if ($_POST['jenis_retur']=='1') {
 BS($_POST["qty_convert"][$i],$module,'tambah');
                }
      ################################### Stok End ##############################
  ################################### Maslah Jurnal ##############################
$akun_barang="Select id_akunkasperkiraan from barang where id_barang='".$_POST["id_barang"][$i]."'";
$akun_barang=mysql_query($akun_barang);
$data=mysql_fetch_array($akun_barang);
input_jurnal_umum($data['id_akunkasperkiraan'],'93','','1',($_POST["harga_si"][$i] *$_POST["qty_convert"][$i] ),$kode_rjb,$_POST['tgl_rjb'],$_POST['customer'],'customer');
############################################################################


 }

$query_header.=substr($querydetail, 1);
echo $query_header;
if (mysql_query($query_header)) {
  input_jurnal_umum_tipe_1('89','','1','',$_POST[returtotal],$kode_rjb,$_POST['tgl_rjb'],$_POST['customer'],'customer');
  input_jurnal_umum_tipe_1('','36','1','',$_POST[returgrandtotal],$kode_rjb,$_POST['tgl_rjb'],$_POST['customer'],'customer');
  input_jurnal_umum_tipe_1('','91','1','',$_POST[returdiscnominal],$kode_rjb,$_POST['tgl_rjb'],$_POST['customer'],'customer');
  input_data("INSERT INTO trans_retur_penjualan(
                                                kode_rjb, 
                                                id_customer, 
                                                jenis_retur, 
                                                tgl_rjb,
                                                ket, 
                                                total_retur,
                                                discpersen,
                                                discnominal,
                                                ppnpersen,
                                                ppnnominal,
                                                grandtotal_retur,
                                                user_update, 
                                                tgl_update)
                                                VALUES (
                                                '$kode_rjb', 
                                                '$_POST[id_customer]', 
                                                '$_POST[jenis_retur]', 
                                                '$_POST[tgl_rjb]',
                                                 '$_POST[ket]',
                                                '$_POST[returtotal]', 
                                                '$_POST[returdiscpersen]', 
                                                '$_POST[returdiscnominal]', 
                                                '$_POST[returppnpersen]',
                                                 '$_POST[returppnnominal]',  
                                                '$_POST[returgrandtotal]',
                                                '$_SESSION[namauser]',
                                                now())",
                                                $module);
}
}



}
 function cari_kode(){
    $var = explode('-', date("Y-m-d"));
    $sql_cari="SELECT max(`kode_rjb`) as kode FROM `trans_retur_penjualan` WHERE `kode_rjb` LIKE 'RJB/$var[0]$var[1]%' ";
    $result=mysql_query($sql_cari);
    $hasil=mysql_fetch_array($result);
    $kode = explode('/',$hasil["kode"]);
    $kode_ururt=100001+$kode[2];
   return 'RJB/'.implode('', $var).'/'.substr($kode_ururt,1);
}
function check_si($kode_si){
    $sql_cari="SELECT * FROM trans_sales_invoice WHERE `id_invoice`='$kode_si' and `status_retur`='1' ";
    $result=mysql_query($sql_cari);
    $hasil =mysql_num_rows($result);
    return $hasil;
}
?>