<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
include "../../config/koneksi.php";

$module=$_GET['module'];
$act=$_GET['act'];

// Hapus modul
if ($module=='sales' AND $act=='hapus'){
  mysql_query("DELETE FROM sales WHERE id_sales='$_GET[id]'");
  header('location:../../media.php?module='.$module);
}

// Input menu
elseif ($module=='sales' AND $act=='input'){

// Input menu
  mysql_query("INSERT INTO sales
                                (nama_sales,
                                Telp1_sales,
                                Telp2_sales,
                                perolehan_bulan,
                                ket)
                          VALUES('$_POST[nama_sales]',
                                '$_POST[Telp1_sales]',
                                '$_POST[Telp2_sales]',
                                '$_POST[perolehan]',
                                '$_POST[ket]')"); 
  header('location:../../media.php?module='.$module);
}

// Update menu
elseif ($module=='sales' AND $act=='update'){
  mysql_query("UPDATE sales SET
                                nama_sales = '$_POST[nama_sales]',
                                Telp1_sales = '$_POST[Telp1_sales]',
                                Telp2_sales = '$_POST[Telp2_sales]',
                                perolehan_bulan = '$_POST[perolehan]',
                                ket = '$_POST[ket]'
                          WHERE id_sales = '$_POST[id_sales]'"); 
header('location:../../media.php?module='.$module);
  }
}
?>
