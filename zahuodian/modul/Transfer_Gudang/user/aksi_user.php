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
$pass=$_POST['password'];
$encrip= md5($pass);

// Hapus modul
if ($module=='user' AND $act=='hapus'){
                                                                    $query="UPDATE users SET is_void = '1' WHERE id_users='$_GET[id]'";
}
// Input menu
elseif ($module=='user' AND $act=='input'){
   if (!empty($_POST['id_mainmenu_array'])){
    $tag_seo = $_POST['id_mainmenu_array'];
    $tag=implode(',',$tag_seo);
  }
 
  // Input menu
  $query="INSERT INTO users(username,password,level,user_update,tgl_update)
                         VALUES('$_POST[username]',
                                '$encrip',
                                '$tag',
                                '$_SESSION[namauser]',
                                 now())";
                                 echo $query;

}

// Update menu
elseif ($module=='user' AND $act=='update'){
    if (!empty($_POST['id_mainmenu_array'])){
    $tag_seo = $_POST['id_mainmenu_array'];
    $tag=implode(',',$tag_seo);
  }
    //jika password tidak diubah dan gambar tidak diganti
 if (empty($_POST['password'])){
    $query="UPDATE users SET 
                                                                     username          ='$_POST[username]',
                                                                     level             ='$tag',
                                                                     user_update       ='$_SESSION[namauser]',
                                                                     tgl_update        =  now()       
                                                                      WHERE id_users   = '$_POST[id]' ";
    }
 // Apabila password diubah dan gambar tidak diganti
  else if (isset($_POST['password'])){
    $query="UPDATE users SET 
                                                                     username          ='$_POST[username]',
                                                                     level             ='$tag',
                                                                     password          ='$encrip',
                                                                     user_update       ='$_SESSION[namauser]',
                                                                     tgl_update        =  now()       
                                                                      WHERE id_users   = '$_POST[id]' ";
                                                                      
}



}
input_data($query,$module);
}
?>
