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
if ($module=='jurnalvoucer' AND $act=='hapus'){
                                                                      $query="DELETE FROM jurnal_umum WHERE kode_nota = '$_POST[nojurnal]'";

 input_data($query,$module);
}
// Input menu
    elseif ($module=='jurnalvoucer' AND $act=='voucer'){

         $query="INSERT INTO jurnal_umum (
            id_akun_kas_perkiraan,
            tanggal,
            debet_kredit,
            nominal,
            kode_nota,
            user_update,
            tanggal_update,
            header) VALUES(
            '$_POST[akun_d_vaoucer]',
            now(),
             'D',
            '$_POST[qty_saldo_d]',
            '$_POST[nojurnal]',
            '$_SESSION[namauser]',
            now(), 
             '$_POST[akun_k_vaoucer]')";
input_only_log($query,$module);

   $query="INSERT INTO jurnal_umum (
            id_akun_kas_perkiraan,
            tanggal,
            debet_kredit,
            nominal,
            kode_nota,
            user_update,
            tanggal_update,header) VALUES(
            '$_POST[akun_k_vaoucer]',
            now(),
            'K',
            '$_POST[qty_saldo_d]',
            '$_POST[nojurnal]',
            '$_SESSION[namauser]',
            now(),  '$_POST[akun_d_vaoucer]')";
input_data($query,$module);
    }
// Update menu
elseif ($module=='jurnalvoucer' AND $act=='update'){
    $del=mysql_query("DELETE FROM jurnal_umum WHERE kode_nota = '$_POST[nojurnal]'");

    $query="INSERT INTO jurnal_umum (
            id_akun_kas_perkiraan,
            tanggal,
            debet_kredit,
            nominal,
            kode_nota,
            user_update,
            tanggal_update,header) VALUES(
            '$_POST[akun_d_vaoucer]',
            now(),
             'D',
            '$_POST[edt_saldo_d]',
            '$_POST[nojurnal]',
            '$_SESSION[namauser]',
            now(),  '$_POST[akun_k_vaoucer]')";
    input_only_log($query,$module);

   $query="INSERT INTO jurnal_umum (
            id_akun_kas_perkiraan,
            tanggal,
            debet_kredit,
            nominal,
            kode_nota,
            user_update,
            tanggal_update,header) VALUES(
            '$_POST[akun_k_vaoucer]',
            now(),
            'K',
            '$_POST[edt_saldo_d]',
            '$_POST[nojurnal]',
            '$_SESSION[namauser]',
            now(),  '$_POST[akun_d_vaoucer]')";
    input_data($query,$module);    
  }
//input_data($query,$module);
}

?>
