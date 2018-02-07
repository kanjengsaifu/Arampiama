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
$awal=$_POST['awalkartubarang'];
$akhir=$_POST['akhirkartubarang'];
$jenis_laporan=$_POST['jenis_laporan'];
$merk_utama=explode('@',$_POST['merk_kartu_barang']);
$kategori_utama=explode('@',$_POST['kategori_kartu_barang']);
$kode_supplier_utama=explode('@',$_POST['kode_supplier_kartu_barang']);
$barang=$_POST['id_barang'];
$satuan= explode('@', $_POST['satuan']);
generate_barang($awal,$akhir);
$filter="";
if ($merk_utama[0]!='') {
	$merk =" and id_merk='".$merk_utama[0]."' ";
	$filter.="Merk : ".$merk_utama[1]."<br>";
}else{
	$merk='';
}
if ($kategori_utama[0]!='') {
	$ketegori =" and id_kategori='".$kategori_utama[0]."' ";
	$filter.="Kategori : ".$kategori_utama[1]."<br>";
}else{
	$ketegori='';
}
if ($kode_supplier_utama[0]!='') {
	$kode_supplier =" and kode_barang like '".$kode_supplier_utama[0]."%' ";
	$filter.="Supplier : ".$kode_supplier_utama[1]."<br>";
}else{
	$kode_supplier='';
}
$filter.="Satuan : ".$satuan[1]."<br>";
if ($jenis_laporan=='general') {
  include 'cetakkartubarang_general.php';
}else if ($jenis_laporan=='barang_kantor') {
   include 'cetakkartubarang_kantor.php';
}else if ($jenis_laporan=='barang_gudang') {
 include 'cetakkartubarang_gudang.php';
}
}
}

?>

