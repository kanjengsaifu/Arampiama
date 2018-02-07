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
if ($module=='tukanganpenotaan' AND $act=='delete'){
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
elseif ($module=='tukanganpenotaan' AND $act=='input'){
$kode_penotaan=kode_surat('NTT','trans_totalan_tukang','no_totalan_tukang','id');



    $query_header = "INSERT INTO `trans_totalan_tukang_detail`(
                                                               `no_totalan_tukang`,
                                                                `id_barang`,
                                                                `jumlah`,
                                                                `satuan`,
                                                                `kali`,
                                                                `convert`,
                                                                `harga`,
                                                                `total`) VALUES ";
 $no=1;
 foreach ($_POST['id_barang'] as $i => $value) {
   $split_satuan=explode('-', $_POST["jenis_satuan"][$i]);
        $querydetail .= ",";
$querydetail .= "('" . $kode_penotaan . "', '" . $_POST["id_barang"][$i] . "','" . $_POST["jumlah"][$i] . "','" . $split_satuan[1]  . "','" . $split_satuan[2]  . "', '" .$_POST["convert"][$i]. "', '" . $_POST["harga"][$i] . "', '" . $_POST["total"][$i]   . "')";



      ################################### Stok Start ##############################
  $t =mysql_fetch_array(mysql_query("SELECT `stok_tukang` FROM `stok_tukang` 
                                  WHERE `id_barang`='".$_POST["id_barang"][$i]."' and `id_supplier`='". $_POST['id_supplier']."'"));
 input_only_log("update stok_tukang set stok_tukang='". ($t["stok_tukang"]-$_POST["convert"][$i])."' 
                                  WHERE id_barang = '".$_POST["id_barang"][$i]."' AND id_supplier =  '". $_POST["id_supplier"]."'",$module);
//       ################################### Stok End ##############################
//   ################################### Maslah Jurnal ##############################
// $akun_barang="Select id_akunkasperkiraan from barang where id_barang='".$_POST["id_barang"][$i]."'";
// $akun_barang=mysql_query($akun_barang);
// $data=mysql_fetch_array($akun_barang);
// input_jurnal_umum($data['id_akunkasperkiraan'],'93','','1',($_POST["harga_si"][$i] *$_POST["qty_convert"][$i] ),$kode_rjb,$_POST['tgl_rjb'],$_POST['customer'],'customer');
############################################################################
 }

$query_header.=substr($querydetail, 1);
if (mysql_query($query_header)) {
  input_only_log("UPDATE `trans_terima_tukang_header` SET `status`='1' where `id_terima_tukang`='$_POST[id_terima_tukang]'");
  //input_jurnal_umum('91','36','','1',$_POST["returgrandtotal"],$kode_rjb,$_POST['tgl_rjb'],$_POST['customer'],'customer');
  input_data("INSERT INTO `trans_totalan_tukang`(
                                                 `no_totalan_tukang`,
                                                 `id_terima_tukang`,
                                                 `nota_tukang`,
                                                 `id_supplier`,
                                                 `tgl_totalan`,
                                                 `nominal_hutang`,
                                                 `total`,
                                                 `hasiltotal`,
                                                 `discper`,
                                                 `disc`,
                                                 `ppnper`,
                                                 `ppn`,
                                                 `nominal_totalan`,
                                                 `ket`,
                                                 `user_update`,
                                                 `tgl_update`) VALUES (  
                                                  '$kode_penotaan',  
                                                  '$_POST[id_terima_tukang]', 
                                                  '$_POST[nota_tukang]', 
                                                  '$_POST[id_supplier]',
                                                  '$_POST[tgl_NTT]',
                                                  '$_POST[nominal_hutang]',
                                                  '$_POST[penotaantotal]',
                                                  '$_POST[hasiltotal]',
                                                  '$_POST[penotaandiscpersen]',
                                                  '$_POST[penotaandiscnominal]',
                                                  '$_POST[returppnpersen]',
                                                  '$_POST[penotaanppnnominal]',
                                                  '$_POST[grandtotalpenotaan]',
                                                  '$_POST[ket]',
                                                  '$_SESSION[namauser]',
                                                  now())",
                                                $module);
}
}



}

?>