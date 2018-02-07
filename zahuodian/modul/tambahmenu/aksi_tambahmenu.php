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
if ($module=='tambahmenu' AND $act=='hapus'){
  mysql_query("DELETE FROM mainmenu WHERE id_mainmenu='$_GET[id]'");
  header('location:../../media.php?module='.$module);
}

// Input menu
elseif ($module=='tambahmenu' AND $act=='input'){
  // Cari angka urutan terakhir
  $u=mysql_query("SELECT no FROM mainmenu ORDER by no DESC");
  $d=mysql_fetch_array($u);
  $urutan=$d[urutan]+1;
  
  // Input menu
  mysql_query("INSERT INTO mainmenu(no,
                                 nama_mainmenu,
                                 root,
                                 aktif,
                                 link_modul) 
	                       VALUES('$_POST[no_urut]',
                                '$_POST[nama_mainmenu]',
                                '$_POST[root]',
                                '$_POST[aktif]',
                                '$_POST[link]')");
  header('location:../../media.php?module='.$module);
}

// Update menu
elseif ($module=='tambahmenu' AND $act=='update'){
mysql_query("UPDATE mainmenu SET 
                                                                      nama_mainmenu = '$_POST[nama_mainmenu]', 
                                                                      no                             = '$_POST[no_urut]', 
                                                                      root                           = '$_POST[root]', 
                                                                      aktif                           = '$_POST[aktif]',
                                                                      link_modul              = '$_POST[link]'
                                                                      WHERE id_mainmenu = '$_POST[id]' ");
header('location:../../media.php?module='.$module);
  }
}
?>
