<?php 
 include "../../config/koneksi.php";
 include "../../lib/input.php";
 error_reporting(0);
  session_start();
if ($_POST['update']=='1') {
    mysql_query("DELETE FROM `trans_bayarbeli_header` WHERE `bukti_bayar`='$_POST[no_bukti]';");
     mysql_query("DELETE FROM `giro_keluar` WHERE `bukti_bayar`='$_POST[no_bukti]';");
     mysql_query("DELETE FROM `jurnal_umum` WHERE `kode_nota`='$_POST[no_bukti] - Titip';");
      mysql_query("DELETE FROM `jurnal_umum` WHERE `kode_nota`='$_POST[no_bukti] - Giro';");
       mysql_query("DELETE FROM `jurnal_umum` WHERE `kode_nota`='$_POST[no_bukti] - ';");
}

$query="SELECT * FROM `trans_bayarbeli_header` WHERE `bukti_bayar`='$_POST[no_bukti]'";
$result=mysql_query($query);
$check_header=mysql_num_rows($result);
if ($check_header == '0') {
    if(isset($_POST['id_masterbank'])){      $id_masterbank  =  $_POST['id_masterbank'];} else {$id_masterbank = ''; }
if(isset($_POST['rek_tujuan'])){             $rek_tujuan         =  $_POST['rek_tujuan'];} else {$rek_tujuan = '';}
if(isset($_POST['ac_tujuan'])){              $ac_tujuan          =  $_POST['ac_tujuan'];} else {$ac_tujuan = '';}
if(isset($_POST['no_giro'])){                  $no_giro             =   $_POST['no_giro'];} else {$no_giro = ''; }
if(isset($_POST['jatuh_tempo'])){          $jatuh_tempo     =   $_POST['jatuh_tempo'];}else{$jatuh_tempo =''; }
if(isset($_POST['status_giro'])){            $status_giro       =   $_POST['status_giro'];} else {$status_giro = ''; }


 $status_titipan   =   $_POST['titipancheckbox'];
if ($status_giro =='1') {
    if(isset($_POST['titipancheckbox'])){ 
        $akun_kas ='49';
         $id_supplier=$_POST['id_supplier'];
          $titip=' - Titip';
     }else{
        $akun_kas ='74';
         $id_supplier='';
         $titip=' - Giro';
    }

      foreach ($_POST['nomor_giro'] as $key => $value) {
        if ($value!='') {
                  input_jurnal_umum($akun_kas,
                                    $_POST['akun_kas'][$key],'','1',
                                    $_POST['nominal_giro'][$key],
                                    $_POST["no_bukti"].$titip,
                                    $_POST["tgl"],
                                    $id_supplier,
                                    'supplier',
                                   $_POST['ket']);
      $insert_value.=",('".$_POST['akun_kas'][$key]."','$_POST[no_bukti]','$value','".$_POST['an_giro'][$key]."','".$_POST['nb_giro'][$key]."','".$_POST['jt_giro'][$key]."','".$_POST['nominal_giro'][$key]."','".$_POST['konfirmasi_giro'][$key]."')";
        }
    }
    $insert_giro="INSERT INTO `giro_keluar`( `id_akun_kas_perkiraan`,`bukti_bayar`, `no_giro`, `an_giro`,`nb_giro`,`jt_giro`, `nominal_giro`,`konfirmasi_giro`) VALUES";
    input_only_log($insert_giro.substr($insert_value, 1));
}else{
    if(isset($_POST['titipancheckbox'])){ 
        $akun_kas ='49';
        $titip=' - Titip';
        
     }else{
        $akun_kas ='74';
         $titip=' - ';
         
    }
 input_jurnal_umum($akun_kas,
                                    $_POST['akun_kas'],'','1',
                                    $_POST['nominal'],
                                    $_POST["no_bukti"].$titip,
                                    $_POST["tgl"],
                                    $id_supplier,
                                    'supplier',
                                   $_POST['ket']);
}




                    $query="INSERT INTO trans_bayarbeli_header (
                                        id_akunkasperkiraan, 
                                        bukti_bayar, 
                                        tgl_pembayaran, 
                                        nominal, 
                                        id_masterbank, 
                                        rek_tujuan, 
                                        ac_tujuan, 
                                        no_giro, 
                                        jatuh_tempo,
                                        status_giro, 
                                        id_supplier,
                                        status_titipan,
                                        ket, 
                                        user_update, 
                                        tgl_update) 
                                            VALUES(
                                            '$akun_kas',
                                            '$_POST[no_bukti]',
                                            '$_POST[tgl]',
                                            '$_POST[nominal]',
                                            '$id_masterbank',
                                            '$rek_tujuan',
                                            '$ac_tujuan',
                                            '$no_giro',
                                            '$jatuh_tempo',
                                            '$status_giro',
                                            '$_POST[id_supplier]',
                                            '$status_titipan',
                                            '$_POST[ket]',
                                            '$_SESSION[namauser]',
                                            now())";
                                            input_only_log($query);
                                            echo 'Data Tersimpan';

}else{
    echo 'Data Gagal Tersimpan Check kembali Nomor transaksi';
}

 ?>