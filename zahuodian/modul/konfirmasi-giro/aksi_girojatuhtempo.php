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
   $query_akun_jurnal="INSERT  INTO jurnal_umum (id_akun_kas_perkiraan,debet_kredit,nominal,kode_nota,user_update,
                    tanggal,header) VALUES";
// Hapus modul
if ($module=='girojatuhtempo' AND $act=='terimagiro'){
$query="UPDATE trans_bayarbeli_header SET giro_ditolak = '2', tgl_giro_cair = '$_POST[tgl_giro_cair]', no_giro_cair = '$_POST[no_giro_cair]'  WHERE id_bayarbeli='$_POST[id_bgk]' ";
           input_jurnal_umum($_POST['akun_kas'],'32','1','',
                                    abs($_POST["nominal"]),
                                    ($_POST['nota'].'- Lunas'),
                                    $_POST['tgl_giro_cair']);


} elseif ($module=='girojatuhtempo' AND $act=='tolakgiro'){
$query="UPDATE trans_bayarbeli_header SET giro_ditolak = '1'  WHERE id_bayarbeli='$_POST[id_bgk]' ";
                               input_jurnal_umum($_POST['akun_kas'],'33','1','',
                                    abs($_POST["nominal"]),
                                    ($_POST['nota'].'- Ditolak'),
                                    $_POST['tgl_jt']);   

  } elseif ($module=='girojatuhtempo' AND $act=='terimagiromasuk'){
      $query="UPDATE trans_bayarjual_header SET giro_ditolak_jual = '2', 
       tgl_giro_cair = '$_POST[tgl_giro_cair]', no_giro_cair = '$_POST[no_giro_cair]' WHERE id_bayarjual='$_POST[id_bgk]' ";
           input_jurnal_umum('32',$_POST['akun_kas'],'1','',
                                    abs($_POST["nominal"]),
                                    ($_POST['nota'].'- Lunas'),
                                    $_POST['tgl_giro_cair']);
     
}elseif ($module=='girojatuhtempo' AND $act=='tolakgiromasuk'){
         $query="UPDATE trans_bayarjual_header SET giro_ditolak_jual = '1'  WHERE id_bayarjual='$_POST[id_bgk]' ";
                    input_jurnal_umum('33',$_POST['akun_kas'],'1','',
                                    abs($_POST["nominal"]),
                                    ($_POST['nota'].'- Ditolak'),
                                    $_POST['tgl_jt']);       
  }
input_data($query,$module);
}

?>
