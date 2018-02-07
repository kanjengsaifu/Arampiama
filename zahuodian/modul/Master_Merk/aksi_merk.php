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
if ($module=='merk' AND $act=='hapus'){
                                                                      $query="UPDATE merk SET is_void = '1' WHERE id_merk='$_GET[id]'";

}
// Input menu
elseif ($module=='merk' AND $act=='input'){
  $cek_database=mysql_num_rows(mysql_query
                                                                      ("SELECT *FROM merk
                                                                        WHERE merk='$_POST[merk]'"));
   $cek_void=mysql_num_rows(mysql_query
                                                                      ("SELECT *FROM merk
                                                                        WHERE merk='$_POST[merk]'AND is_void='0'"));
  if ($cek_void > 0){
    echo "data sudah ada"; }
  elseif ($cek_database>0) {
      echo"data pernah ada";}
  else{
  // Input menu
                                                                        $query="INSERT INTO merk(merk,user_update,tgl_update) 
                                                                                VALUES('$_POST[merk]','$_SESSION[namauser]',now())";

      }
    }
// Update menu
elseif ($module=='merk' AND $act=='update'){
                                                                        $query="UPDATE merk SET merk = '$_POST[merk]'
                                                                                WHERE id_merk = '$_POST[id]' ";
 
  }
input_data($query,$module);
}



?>
