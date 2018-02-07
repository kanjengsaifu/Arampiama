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
 }

$query_header.=substr($querydetail, 1);
if (mysql_query($query_header)) {
  if ($_POST[penotaanppnnominal]!='') {
        if ($_POST[penotaanppnnominal]!=0) {
  input_jurnal_umum_tipe_1('55','','','1',$_POST[penotaanppnnominal],$kode_penotaan,$_POST[tgl_NTT],$_POST[id_supplier],'supplier','',$kode_penotaan);
}
}
 if ($_POST[penotaandiscnominal]!='') {
   if ($_POST[penotaandiscnominal]!=0) {
  input_jurnal_umum_tipe_1('','94','','1',$_POST[penotaandiscnominal],$kode_penotaan,$_POST[tgl_NTT],$_POST[id_supplier],'supplier','',$kode_penotaan);
}}
      $nominal_hutang_terbayar=($_POST[nominal_hutang]-$_POST[grandtotalpenotaan]);
  input_jurnal_umum_tipe_1('','154','1','',$_POST[penotaantotal],  $kode_penotaan,$_POST[tgl_NTT],$_POST[id_supplier],'supplier','',$kode_penotaan);
  input_jurnal_umum_tipe_1('71','','1','',$nominal_hutang_terbayar,  $kode_penotaan,$_POST[tgl_NTT],$_POST[id_supplier],'supplier','',$kode_penotaan);
  input_only_log("UPDATE `trans_terima_tukang_header` SET `status`='1' where `id_terima_tukang`='$_POST[id_terima_tukang]'");
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






}elseif ($module=='tukanganpenotaan' AND $act=='edit'){
$kode_penotaan=$_POST[no_totalan_tukang];
$querydetail="SELECT * FROM `trans_totalan_tukang_detail` WHERE `no_totalan_tukang`='$kode_penotaan'";
$result=mysql_query($querydetail);
while ($r=mysql_fetch_array($result)) {
      ################################### Stok Start ##############################
  $t =mysql_fetch_array(mysql_query("SELECT `stok_tukang` FROM `stok_tukang` 
                                  WHERE `id_barang`='".$r["id_barang"]."' and `id_supplier`='". $_POST['id_supplier']."'"));
 input_only_log("update stok_tukang set stok_tukang='". ($t["stok_tukang"]+$r["convert"])."' 
                                  WHERE id_barang = '".$r["id_barang"]."' AND id_supplier =  '". $_POST["id_supplier"]."'",$module);
       ################################### Stok End ##############################
}
    mysql_query("DELETE FROM jurnal_umum WHERE kode_nota = '".$kode_penotaan."'");
 input_only_log("delete from trans_totalan_tukang_detail where no_totalan_tukang='".$kode_penotaan."'");
$querydetail='';
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
  $query_header = "INSERT INTO `trans_totalan_tukang_detail`(
                                                               `no_totalan_tukang`,
                                                                `id_barang`,
                                                                `jumlah`,
                                                                `satuan`,
                                                                `kali`,
                                                                `convert`,
                                                                `harga`,
                                                                `total`) VALUES "; 
$query_header.=substr($querydetail, 1);

if (mysql_query($query_header)) {
   $nominal_hutang_terbayar=($_POST[nominal_hutang]-$_POST[grandtotalpenotaan]);
    input_jurnal_umum_tipe_1('','154','1','',$_POST[penotaantotal],  $kode_penotaan,$_POST[tgl_NTT],$_POST[id_supplier],'supplier','',$kode_penotaan);
  input_jurnal_umum_tipe_1('71','','1','',$nominal_hutang_terbayar,  $kode_penotaan,$_POST[tgl_NTT],$_POST[id_supplier],'supplier','',$kode_penotaan);
  if ($_POST[penotaanppnnominal]!='') {
        if ($_POST[penotaanppnnominal]!=0) {
  input_jurnal_umum_tipe_1('55','','','1',$_POST[penotaanppnnominal],$kode_penotaan,$_POST[tgl_NTT],$_POST[id_supplier],'supplier','',$kode_penotaan);
}
}
 if ($_POST[penotaandiscnominal]!='') {
   if ($_POST[penotaandiscnominal]!=0) {
  input_jurnal_umum_tipe_1('','94','','1',$_POST[penotaandiscnominal],$kode_penotaan,$_POST[tgl_NTT],$_POST[id_supplier],'supplier','',$kode_penotaan);
}
}
  $update= "UPDATE `trans_totalan_tukang` SET 
                                          `nota_tukang`='$_POST[nota_tukang]',
                                          `id_supplier`='$_POST[id_supplier]',
                                          `tgl_totalan`='$_POST[tgl_NTT]',
                                          `nominal_hutang`='$_POST[nominal_hutang]',
                                          `total`='$_POST[penotaantotal]',
                                          `hasiltotal`='$_POST[hasiltotal]',
                                          `discper`='$_POST[penotaandiscpersen]',
                                          `disc`='$_POST[penotaandiscnominal]',
                                          `ppnper`='$_POST[returppnpersen]',
                                          `ppn`='$_POST[penotaanppnnominal]',
                                          `nominal_totalan`='$_POST[grandtotalpenotaan]',
                                          `ket`='$_POST[ket]',
                                          `user_update`='$_SESSION[namauser]',
                                          `tgl_update`=now() WHERE `no_totalan_tukang`='$kode_penotaan' ";
                                          input_data($update,$module);
}
}



}

?>