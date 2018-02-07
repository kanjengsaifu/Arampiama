<?php
session_start();

 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
include "../../config/koneksi.php";
include "../../lib/input.php";

$module=$_GET['module'];
$act=$_GET['act'];


// Hapus modul
if ($module=='kategori' AND $act=='hapus'){
                                                                      $query="UPDATE kategori SET is_void = '1' WHERE id_kategori='$_GET[id]'";

}
// Input menu
elseif ($module=='kategori' AND $act=='input'){
  $cek_database=mysql_num_rows(mysql_query
                                                                      ("SELECT *FROM kategori
                                                                        WHERE kategori='$_POST[kategori]'"));
   $cek_void=mysql_num_rows(mysql_query
                                                                      ("SELECT *FROM kategori
                                                                        WHERE kategori='$_POST[kategori]'AND is_void='0'"));
  if ($cek_void > 0){
    echo "data sudah ada"; }
  elseif ($cek_database>0) {
      echo"data pernah ada";}
  else{
  // Input menu
                                                                        $query="INSERT INTO kategori(
                                                                        kategori,
                                                                        user_update,
                                                                        root,
                                                                        tgl_update) 
                                                                                VALUES(
                                                                                '$_POST[kategori]',
                                                                                '$_SESSION[namauser]',
                                                                                '$_POST[subkategori]',
                                                                                now())";

      }
    }
// Update menu
elseif ($module=='kategori' AND $act=='update'){
                                                                        $query="UPDATE kategori SET kategori = '$_POST[kategori]'
                                                                                WHERE id_kategori = '$_POST[id]' ";
 
  }
input_data($query,$module);
}

?>
