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
if ($module=='pembayaranpembelian' AND $act=='hapus'){
      $query="SELECT * ,a.bukti_bayar as bukti FROM `trans_bayarbeli_header` a left join `trans_bayarbeli_detail` b on(a.`bukti_bayar`=b.`bukti_bayar`) where id_bayarbeli='$_GET[id]' and  b.bukti_bayar is null group by a.bukti_bayar  ";
      $result=mysql_query($query);
      $r=mysql_fetch_array($result);
        input_only_log("delete from  jurnal_umum where kode_nota='".$r['bukti']."'",$module);
           input_only_log("delete from  jurnal_umum where kode_nota='".$r['bukti']." - Titip'",$module);
       input_data("delete from  trans_bayarbeli_header where bukti_bayar='".$r['bukti']."'",$module);
} 
elseif ($module=='pembayaranpembelian' AND $act=='input'){
if(isset($_POST['id_masterbank'])){      $id_masterbank  =  $_POST['id_masterbank'];} else {$id_masterbank = ''; }
if(isset($_POST['rek_tujuan'])){             $rek_tujuan         =  $_POST['rek_tujuan'];} else {$rek_tujuan = '';}
if(isset($_POST['ac_tujuan'])){              $ac_tujuan          =  $_POST['ac_tujuan'];} else {$ac_tujuan = '';}
if(isset($_POST['no_giro'])){                  $no_giro             =   $_POST['no_giro'];} else {$no_giro = ''; }
if(isset($_POST['jatuh_tempo'])){          $jatuh_tempo     =   $_POST['jatuh_tempo'];}else{$jatuh_tempo =''; }
if(isset($_POST['status_giro'])){            $status_giro       =   $_POST['status_giro'];} else {$status_giro = ''; }
if(isset($_POST['id_supplier'])){             $id_supplier       =   $_POST['id_supplier'];} else {$id_supplier = '';}
if(isset($_POST['titipancheckbox'])){      $status_titipan   =   $_POST['titipancheckbox'];} else {$status_titipan = '';}
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
                                            '$id_supplier',
                                            '$status_titipan',
                                            '$_POST[ket]',
                                            '$_SESSION[namauser]',
                                            now())";
                                                   input_data($query,$module);
    }

elseif ($module=='pembayaranpembelian' AND $act=='inputdetail'){
            $query="UPDATE trans_bayarbeli_header SET 
                                            nominal_alokasi = '$_POST[total_alokasi]',
                                            status_bayar = '$_POST[status]',
                                            sisa_alokasi = '$_POST[sisa_alokasi]'
                                                       WHERE id_bayarbeli = '$_GET[id];'";
            $query_status_0=mysql_query("SELECT * FROM `trans_bayarbeli_detail` WHERE `bukti_bayar`='$_POST[no_bukti]'");
            while ($t=mysql_fetch_array($query_status_0)) {
             input_only_log("UPDATE `trans_invoice` SET `status_lunas`='0' where `id_invoice`='$t[nota_invoice]'",$module);
             input_only_log("UPDATE `trans_retur_pembelian` SET `status`='0' where `kode_rbb`='$t[nota_invoice]'",$module);
             input_only_log("UPDATE `trans_retur_penjualan` SET `status`='0' WHERE `kode_rjb`='$t[nota_invoice]'",$module);
            }
           input_only_log("delete from  trans_bayarbeli_detail where bukti_bayar='$_POST[no_bukti]'",$module);
           input_only_log("delete from  jurnal_umum where kode_nota='$_POST[no_bukti]'",$module);
            input_only_log("UPDATE `trans_bayarbeli_header` SET `status_bayar`='klop' where `bukti_bayar`='$_POST[no_bukti]'",$module);
            $query1= "INSERT INTO trans_bayarbeli_detail(
                                                id_bayarbeli_detail,
                                                bukti_bayar,
                                                bukti_bayar_dimuka,
                                                id_akunkasperkiraan_detail,
                                                nota_invoice, 
                                                ket,
                                                sisa_invoice,
                                                id_supplier,
                                                nominal_alokasi,
                                                user_update, 
                                                tgl_update)
                                                VALUES ";
            
            $itemCount = count($_POST["akun_kasdetail"]);      
            $queryValue = "";
             $query_jurnal =  "";

for($i=0;$i<$itemCount;$i++) {
    if($queryValue!="") { $queryValue .= ",";$query_jurnal .= ","; }
    $queryValue .= "('" . $_POST["id_bayarbeli_detail"] [$i] . "',
                                '" . $_POST["no_bukti"] . "',
                                '" . $_POST["bukti_bayar_dimuka"][$i] . "',
                                '" .$_POST["akun_kasdetail"][$i]  . "',
                                '" . $_POST["no_invoice"][$i] . "', 
                                '" . $_POST["ketdetail"][$i] . "',
                                '" .$_POST["sisa_invoice"][$i] . "',
                                '" .$_POST["id_supplier"][$i] . "', 
                                '" . $_POST["nominal_alokasi"][$i] . "', 
                                '" .  $_SESSION["namauser"] . "',
                                now() )";
    $BGK =explode(' - ', $_POST["no_bukti"]);
        $akun_utama=$_POST["id_akun"];
        $kete=$_POST["no_invoice"][$i] .' - '.$_POST["ketdetail"][$i];
    if ($_POST["nominal_alokasi"][$i]<=0) {
    ####################-- Jurnal Terbalik --#####################################
    input_jurnal_umum($akun_utama,
                                    $_POST["akun_kasdetail"][$i],'1','',
                                    abs($_POST["nominal_alokasi"][$i]),
                                    $_POST["no_bukti"],
                                    $_POST["tgl"],
                                    $_POST[id_supplier][$i],
                                    'supplier',
                                    $kete);
    #######################################################################
    }else{
        if ($_POST['kode_user'][$i]=='RJB') {
               input_jurnal_umum($_POST["akun_kasdetail"][$i],
                                    $akun_utama,'','1',
                                    $_POST["nominal_alokasi"][$i],
                                    $_POST["no_bukti"],
                                    $_POST["tgl"],
                                    $_POST[id_supplier][$i],
                                    'customer',
                                    $kete);
        }else {
                input_jurnal_umum($_POST["akun_kasdetail"][$i],
                                    $akun_utama,'','1',
                                    $_POST["nominal_alokasi"][$i],
                                    $_POST["no_bukti"],
                                    $_POST["tgl"],
                                    $_POST[id_supplier][$i],
                                    'supplier',
                                    $kete);
        }
    }
        if( !empty($_POST["no_invoice"][$i])){
            if ($_POST["sisa_invoice"][$i] <= 0) {
                input_only_log("UPDATE trans_invoice SET 
                               status_lunas = '1'  WHERE id_invoice =  '" . $_POST["no_invoice"][$i] . "'",$module);
            }
            input_only_log("UPDATE `trans_retur_pembelian` SET `status`='1' where `kode_rbb`='" . $_POST["no_invoice"][$i] . "'",$module);
            input_only_log("UPDATE `trans_retur_penjualan` SET `status`='1' WHERE `kode_rjb`='" . $_POST["no_invoice"][$i] . "'",$module);

                // input_only_log("UPDATE trans_bayarjual_detail SET 
                //                       sisa_invoice_detail_jual = 0 WHERE nota_invoice =  '" . $_POST["no_invoice"][$i] . "'",$module);
        }
}
    $sql = $query1.$queryValue;
   input_data($sql,$module);

            //   if( !empty($_POST["no_invoice"][$i]) && ($pisahbuktibayar[0] != "BGK")){
            //                 $query41 = mysql_query("SELECT supplier.id_supplier, supplier.saldo_hutang FROM supplier RIGHT JOIN trans_invoice ON(trans_invoice.id_supplier =supplier.id_supplier) WHERE trans_invoice.id_invoice='".$_POST["no_invoice"][$i]."'");
            //                 $r=mysql_fetch_array($query41);
            //                 if(!empty($_POST['nominal_alokasi123'][$i])){
            //                      $saldo_hutang=$r['saldo_hutang']+$_POST['nominal_alokasi123'][$i]-$_POST['nominal_alokasi'][$i];
            //                 } else {
            //                     $saldo_hutang=$r['saldo_hutang']-$_POST['nominal_alokasi'][$i];
            //                 }
            //                input_only_log("UPDATE supplier set saldo_hutang=$saldo_hutang where id_supplier='$r[id_supplier]'",$module);
            // //echo "UPDATE customer set saldo_piutang=$saldo_hutang where id_customer='$r[id_customer]'";
            // } 

     // }

}


}

?>
