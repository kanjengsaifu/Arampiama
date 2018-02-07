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
if($_POST['status']=='status_giro='){
  $temp= $_POST['status'] . "'0'";
}else{
   $temp= $_POST['status'] . "'1'";
}
if ($module=='pencairangiro' AND $act=='input'){

      $query="UPDATE trans_pembayaran set ".$temp.",ket='".$_POST['ket']."',tgl_pencairan='".$_POST['tgl']."' where id='".$_POST['id_pembayaran']."'";
     
    input_data($query,$module);
}
}

?>
