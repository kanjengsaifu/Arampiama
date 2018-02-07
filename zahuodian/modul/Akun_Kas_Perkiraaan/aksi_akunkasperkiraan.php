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
if ($module=='akunkasperkiraan' AND $act=='hapus'){
                                                                      $query="UPDATE akun_kas_perkiraan SET is_void = '1' WHERE id_akunkasperkiraan='$_GET[id]'";

 input_data($query,$module);
}
// Input menu
elseif ($module=='akunkasperkiraan' AND $act=='input'){
    $kode=explode('-',$_POST[kode]);
    $kode_akun=cari_kode($_POST[kode]);
  // Input menu
                                                                        $query="INSERT INTO akun_kas_perkiraan (
                                                                        kode_akun_header,
                                                                        nama_akunkasperkiraan,
                                                                        kode_akun,
                                                                        ket,
                                                                        user,
                                                                        tgl_update) 
                                                                                VALUES(
                                                                                '$kode[0]',
                                                                                '$_POST[akun]',
                                                                                '$kode_akun',
                                                                                '$_POST[ket]',
                                                                                '$_SESSION[namauser]',
                                                                                now())";
                                                                                input_data($query,$module);
    }
    elseif ($module=='akunkasperkiraan' AND $act=='voucer'){

         $query="INSERT INTO jurnal_umum (
            id_akun_kas_perkiraan,
            tanggal,
            debet_kredit,
            nominal,
            kode_nota,
            user_update,
            tanggal_update) VALUES(
            '$_POST[akun_d_vaoucer]',
            now(),
             'D',
            '$_POST[qty_saldo_d]',
            '$_POST[nojurnal]',
            '$_SESSION[namauser]',
            now())";
input_only_log($query,$module);
 $query=mysql_query("select * from akun_kas_perkiraan where id_akunkasperkiraan='".$_POST['akun_d_vaoucer']."'");
        $r=mysql_fetch_array($query);
        if ($r['premier']==(1 or 7 or 8)) {
           $perjumlahan=abs($r['saldo']+$_POST['qty_saldo_d']);
        }else{
            $perjumlahan=abs($r['saldo']-$_POST['qty_saldo_d']);
        }
        input_only_log("UPDATE akun_kas_perkiraan set saldo=$perjumlahan where id_akunkasperkiraan='$_POST[akun_d_vaoucer]' ",$module);

   $query="INSERT INTO jurnal_umum (
            id_akun_kas_perkiraan,
            tanggal,
            debet_kredit,
            nominal,
            kode_nota,
            user_update,
            tanggal_update) VALUES(
            '$_POST[akun_k_vaoucer]',
            now(),
            'K',
            '$_POST[qty_saldo_d]',
            '$_POST[nojurnal]',
            '$_SESSION[namauser]',
            now())";
input_only_log($query,$module);
  $query=mysql_query("select * from akun_kas_perkiraan where id_akunkasperkiraan='$_POST[akun_k_vaoucer]'");
        $r=mysql_fetch_array($query);
        if ($r['premier']==(1 or 7 or 8) ){
           $perjumlahan=abs($r['saldo']-$_POST['qty_saldo_k']);
        }else{
            $perjumlahan=abs($r['saldo']+$_POST['qty_saldo_k']);
        }
        input_data("UPDATE akun_kas_perkiraan set saldo=$perjumlahan where id_akunkasperkiraan='$_POST[akun_k_vaoucer]' ",$module);
    }
// Update menu
elseif ($module=='akunkasperkiraan' AND $act=='update'){
                                                                        $query="UPDATE akun_kas_perkiraan SET 
                                                                        kode_akun_header = '$_POST[header]',
                                                                        kode_akunkasperkiraan = '$_POST[kode]',
                                                                        nama_akunkasperkiraan = '$_POST[akun]',
                                                                        kode_akun = '$_POST[kode]',
                                                                        ket = '$_POST[ket]',
                                                                        tgl_update = now(),
                                                                        user = '$_SESSION[namauser]'
                                                                                WHERE id_akunkasperkiraan = '$_POST[id_akunkasperkiraan]' ";
 input_data($query,$module);
  }
//input_data($query,$module);
}
function cari_kode($kode){
    $var = explode('-', $kode);
    $sql_cari="SELECT max(`kode_akun`) as kode FROM `akun_kas_perkiraan` WHERE `kode_akun` LIKE '$var[0]%' ";
    $result=mysql_query($sql_cari);
    $hasil=mysql_fetch_array($result);
    $kode =explode('-', $hasil['kode']);
    $kode_ururt=100+1+$kode[1];
   return $kode[0].'-'.substr($kode_ururt,1);
}
?>
