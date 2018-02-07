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
if ($module=='region' AND $act=='hapus'){
                                                                   
                                                                      $query="UPDATE region SET is_void = '1' WHERE id_region='$_GET[id]'";
                                                                      input_data($query,$module);


}
// Input menu
elseif ($module=='region' AND $act=='input'){
  $cek_database=mysql_num_rows(mysql_query
                                                                      ("SELECT *FROM region
                                                                        WHERE region='$_POST[region]'"));
   $cek_void=mysql_num_rows(mysql_query
                                                                      ("SELECT *FROM region
                                                                        WHERE region='$_POST[region]'AND is_void='0'"));
  if ($cek_void > 0){
    echo "data sudah ada"; }
  elseif ($cek_database>0) {
      echo"data pernah ada";}
  else{
  // Input menu
                                              $query="INSERT INTO region(region,user_update,tgl_update) 
                                                      VALUES('$_POST[region]','$_SESSION[namauser]',now())";
                                              input_data($query,$module);


      }
    }
// Update menu
elseif ($module=='region' AND $act=='update'){ 
                                              $query="UPDATE region SET region = '$_POST[region]'
                                                      WHERE id_region = '$_POST[id]' ";                              
                                              input_data($query,$module);
 
  }

}

?>
