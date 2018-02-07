<?php 
 include "../../config/koneksi.php";
 include "../../lib/input.php";
  session_start();
$query="SELECT * FROM `trans_bayarjual_header` WHERE `bukti_bayarjual`='$_POST[no_bukti]'";
$result=mysql_query($query);
$check_header=mysql_num_rows($result);
if ($check_header == '0') {

if(isset($_POST['id_masterbank'])){ $id_masterbank =$_POST['id_masterbank'];}else{$id_masterbank = '';}
if(isset($_POST['rek_tujuan'])){ $rek_tujuan = $_POST['rek_tujuan'];}else {$rek_tujuan = ''; }
if(isset($_POST['ac_tujuan'])){ $ac_tujuan =  $_POST['ac_tujuan'];}else {$ac_tujuan = '';}
if(isset($_POST['no_giro'])){$no_giro =   $_POST['no_giro'];}else {$no_giro = ''; }
if(isset($_POST['jatuh_tempo'])){ $jatuh_tempo =   $_POST['jatuh_tempo'];}else {$jatuh_tempo = '';}
if(isset($_POST['status_giro'])){ $status_giro =   $_POST['status_giro'];}else {$status_giro = '';}
if(isset($_POST['id_customer'])){ $id_customer =   $_POST['id_customer'];}else {$id_customer = '';}
if(isset($_POST['titipancheckbox'])){ $status_titipan =$_POST['titipancheckbox'];}else{$status_titipan = '';  }
if(isset($_POST['nama_bank'])){$nama_bank =  $_POST['nama_bank'];}else {$nama_bank = '';}
if(isset($_POST['titipancheckbox'])){   
      input_jurnal_umum(
                                    $_POST['akun_kas'],'75','','1',
                                    $_POST['nominal'],
                                    $_POST["no_bukti"].' - Titip',
                                    $_POST["tgl"],
                                   $_POST['id_customer'],
                                    'customer',
                                   $_POST['ket']);
} else {$status_titipan = '';}
                  $query="INSERT INTO trans_bayarjual_header (
                                id_akunkasperkiraan, 
                                bukti_bayarjual, 
                                tgl_pembayaranjual, 
                                nominaljual, 
                                id_masterbank, 
                                rek_asal, 
                                ac_asal, 
                                no_giro_jual, 
                                jatuh_tempo_jual,
                                status_giro_jual, 
                                ket_jual, 
                                id_customer,
                                status_titipan,
                                user_update, nama_bank,
                                tgl_update) 
                                    VALUES(
                                    '$_POST[akun_kas]',
                                    '$_POST[no_bukti]',
                                    '$_POST[tgl]',
                                    '$_POST[nominal]',
                                    '$id_masterbank',
                                    '$rek_tujuan',
                                    '$ac_tujuan',
                                    '$no_giro',
                                    '$jatuh_tempo',
                                    '$status_giro',
                                    '$_POST[ket]',
                                    '$id_customer',
                                    '$status_titipan',
                                    '$_SESSION[namauser]','$nama_bank',
                                    now())";
                                            input_only_log($query);
                                            echo 'Data Tersimpan';

}else{
    echo 'Data Gagal Tersimpan Check kembali Nomor transaksi';
}

 ?>