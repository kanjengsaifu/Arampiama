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
if ($module=='kodenota' AND $act=='hapus'){
  mysql_query("DELETE FROM kodenota WHERE id_kodenota='$_GET[id]'");
  header('location:../../media.php?module='.$module);
}

// Input menu
elseif ($module=='kodenota' AND $act=='input'){

  
  // Input menu
  mysql_query("INSERT INTO kodenota
                                (nama_kodenota,
                                 link_modul,
                                 user_update,
                                 tgl_update,
                                 ket) 
	                       VALUES('$_POST[nama_kodenota]',
                                '$_POST[link_modul]',
                                '$_SESSION[namauser]',
                                now(),
                                '$_POST[ket]')");
  header('location:../../media.php?module='.$module);
}

// Update menu
elseif ($module=='kodenota' AND $act=='update'){
mysql_query("UPDATE kodenota SET 
                                                                      nama_kodenota  = '$_POST[nama_kodenota]', 
                                                                      link_modul              = '$_POST[link_modul]', 
                                                                      user_update          = '$_SESSION[namauser]', 
                                                                      tgl_update              = now(),
                                                                      ket                             = '$_POST[ket]'
                                                                      WHERE id_kodenota = '$_POST[id_kodenota]' ");
header('location:../../media.php?module='.$module);
  }
}
?>
