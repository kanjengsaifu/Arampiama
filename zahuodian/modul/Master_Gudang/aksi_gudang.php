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
if ($module=='gudang' AND $act=='hapus'){
                                                                   
                                                                      $query="UPDATE gudang SET is_void = '1' WHERE id_gudang='$_GET[id]'";
                                                                      input_data($query,$module);


}
// Input menu
elseif ($module=='gudang' AND $act=='input'){
  $cek_database=mysql_num_rows(mysql_query
                                                                      ("SELECT *FROM gudang
                                                                        WHERE nama_gudang='$_POST[gudang]'"));
   $cek_void=mysql_num_rows(mysql_query
                                                                      ("SELECT *FROM gudang
                                                                        WHERE nama_gudang='$_POST[gudang]'AND is_void='0'"));
  if ($cek_void > 0){
    echo "data sudah ada"; }
  elseif ($cek_database>0) {
      echo"data pernah ada";}
  else{
  // Input menu
                                                            $query="INSERT INTO gudang(nama_gudang,status_gudang,user_update,tgl_update) 
                                                                    VALUES('$_POST[gudang]','$_POST[gudang_luar]','$_SESSION[namauser]',now())";
                                                            input_only_log($query,$module);
                                                            $a=mysql_fetch_array(mysql_query("SELECT id_gudang from gudang order by id_GUDANG desc"));

                                                            $query=mysql_query("SELECT id_barang from barang where is_void=0");
                                                            $count=mysql_num_rows($query);                                         
                                                          while ($r=mysql_fetch_array($query)) { 
                                                                          $INSERTstart="INSERT INTO `stok`( `id_barang`, `id_gudang`, `stok_sekarang`, `user_update`, `tgl_update`) VALUES ('".($r["id_barang"])."','".$a["id_gudang"][0]."','0','".$_SESSION["namauser"]."',now())"; 
                                                                          mysql_query($INSERTstart);                                               
                                                            }                                                                                                                               
                                                        ;


      }
    }
// Update menu
elseif ($module=='gudang' AND $act=='update'){
  if (!empty($_POST['gudang_luar'])) {
        $status="1";
      }
      else{
         $status="0";
      }
 
                                                                        $query="UPDATE gudang SET nama_gudang = '$_POST[gudang]',status_gudang='$status'
                                                                                WHERE id_gudang = '$_POST[id]' ";
                                                                            
                                                                        input_data($query,$module);
 
  }

}

?>
