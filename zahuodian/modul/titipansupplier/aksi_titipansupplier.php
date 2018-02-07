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
if ($module=='titipansupplier' AND $act=='hapus'){
      $query="UPDATE trans_bayarbeli_header SET is_void = '1' WHERE id_bayarbeli='$_GET[id]'";
      //echo $query;
}
elseif ($module=='titipansupplier' AND $act=='hapusdetail'){
      $query="DELETE FROM trans_bayarbeli_detail WHERE id_bayarbeli_detail='$_GET[id]'";
      if(!empty($_GET['id_pi'])){
      $query1="UPDATE trans_invoice SET status_lunas = '0' WHERE id_invoice='$_GET[id_pi]'";
        input_only_log($query1,$module);
    }
      //echo $query;
}
// Input menu
elseif ($module=='titipansupplier' AND $act=='input'){
  // Input menu
   if(isset($_POST['id_masterbank'])){
                    $id_masterbank =  $_POST['id_masterbank'];
        } else {
            $id_masterbank = '';
            }
        if(isset($_POST['rek_tujuan'])){
                    $rek_tujuan = $_POST['rek_tujuan'];
        } else {
            $rek_tujuan = '';
            }
        if(isset($_POST['ac_tujuan'])){
                    $ac_tujuan =  $_POST['ac_tujuan'];
        } else {
            $ac_tujuan = '';
            }
        if(isset($_POST['no_giro'])){
                    $no_giro =   $_POST['no_giro'];
        } else {
            $no_giro = '';
            }
        if(isset($_POST['jatuh_tempo'])){
                    $jatuh_tempo =   $_POST['jatuh_tempo'];
        } else {
            $jatuh_tempo = '';
            }
        if(isset($_POST['status_giro'])){
                    $status_giro =   $_POST['status_giro'];
        } else {
            $status_giro = '';
            }
  
                    $querymt = "INSERT INTO trans_bayarbeli_header (
                                        id_akunkasperkiraan, 
                                        bukti_bayar, 
                                        tgl_pembayaran, 
                                        nominal, 
                                        id_masterbank, 
                                        id_supplier,
                                        rek_tujuan, 
                                        ac_tujuan, 
                                        no_giro, 
                                        jatuh_tempo,
                                        status_giro, 
                                        ket,
                                        status_titipan, 
                                        user_update, 
                                        tgl_update) 
                                            VALUES(
                                            '$_POST[akun_kas]',
                                            '$_POST[no_bukti]',
                                            '$_POST[tgl]',
                                            '$_POST[nominal]',
                                            '$id_masterbank',
                                           '$_POST[supplier]',
                                            '$rek_tujuan',
                                            '$ac_tujuan',
                                            '$no_giro',
                                            '$jatuh_tempo',
                                            '$status_giro',
                                            '$_POST[ket]',
                                            'T',
                                            '$_SESSION[namauser]',
                                            now())";

            $table = "trans_bayarbeli_header";
            $where = "bukti_bayar = '$_POST[no_bukti]' ";
            $query = cekfield($table, $where, $querymt);
            echo $query;

    }
elseif ($module=='titipansupplier' AND $act=='inputdetail'){
  // Input detail menu
    if(isset($_GET['id'])){
        $id_pay =$_GET['id'];
      }
            $query="UPDATE trans_bayarbeli_header SET 
                                            nominal_alokasi = '$_POST[total_alokasi]',
                                            status_bayar = '$_POST[status]',
                                            sisa_alokasi = '$_POST[sisa_alokasi]'
                                                       WHERE id_bayarbeli = '$id_pay'";

            $itemCount = count($_POST["akun_kasdetail"]);
            $itemValues=0;
            $query1= "INSERT INTO trans_bayarbeli_detail(
                                                id_bayarbeli_detail,
                                                bukti_bayar,
                                                id_akunkasperkiraan_detail,
                                                nota_invoice,
                                                ket,
                                                sisa_invoice,
                                                id_supplier,
                                                nominal_alokasi,
                                                user_update, 
                                                tgl_update)
                                                VALUES ";
                                                   //akun_kasdetail, no_invoice, ketdetail, sisa_invoice, nominal_alokasi, total_alokasi, sisa_alokasi
            $queryValue = "";
            for($i=0;$i<$itemCount;$i++) {
            if(!empty($_POST["no_bukti"]) || !empty($_POST["akun_kasdetail"][$i]) || !empty($_POST["nominal_alokasi"][$i])) {
            $itemValues++;
            if($queryValue!="") {
            $queryValue .= ",";
            }
        $queryValue .= "('" . $_POST["id_bayarbeli_detail"] [$i] . "','" . $_POST["no_bukti"] . "', '" .$_POST["akun_kasdetail"][$i]  . "', '" . $_POST["no_invoice"][$i] . "', '" . $_POST["ketdetail"][$i] . "', '" .$_POST["sisa_invoice"][$i] . "','" .$_POST["viewakuntextsave"][$i] . "', '" . $_POST["nominal_alokasi"][$i] . "', '" .  $_SESSION["namauser"] . "', now() )";
      }
    }
    $queryEnd = " ON DUPLICATE KEY UPDATE id_bayarbeli_detail = VALUES(id_bayarbeli_detail) , id_akunkasperkiraan_detail = VALUES(id_akunkasperkiraan_detail), nota_invoice = VALUES(nota_invoice), ket = VALUES(ket), sisa_invoice = VALUES(sisa_invoice), id_supplier = VALUES(id_supplier), nominal_alokasi = VALUES(nominal_alokasi), user_update = VALUES(user_update), tgl_update = VALUES(tgl_update)";
    $sql = $query1.$queryValue.$queryEnd;

        //update status lunas trans invoice
       $pisahbuktibayar = explode(" -",$_POST['no_bukti']);
     for($i=0;$i<$itemCount;$i++) {
        if( !empty($_POST["no_invoice"][$i]) && ($_POST["sisa_invoice"][$i] <= 0 )){
                $query2="UPDATE trans_invoice SET 
                               status_lunas = '1'
                                           WHERE id_invoice =  '" . $_POST["no_invoice"][$i] . "'";
                mysql_query("UPDATE trans_bayarbeli_detail SET 
                               sisa_invoice = 0
                                           WHERE nota_invoice =  '" . $_POST["no_invoice"][$i] . "'");
        }
        input_only_log($query2,$module);
              if( !empty($_POST["no_invoice"][$i]) && ($pisahbuktibayar[0] != "BGK")){
                            $query41 = mysql_query("SELECT supplier.id_supplier, supplier.saldo_hutang FROM supplier RIGHT JOIN trans_invoice ON(trans_invoice.id_supplier =supplier.id_supplier) WHERE trans_invoice.id_invoice='".$_POST["no_invoice"][$i]."'");
                            $r=mysql_fetch_array($query41);
                            if(!empty($_POST['nominal_alokasi123'][$i])){
                                 $saldo_hutang=$r['saldo_hutang']+$_POST['nominal_alokasi123'][$i]-$_POST['nominal_alokasi'][$i];
                            } else {
                                $saldo_hutang=$r['saldo_hutang']-$_POST['nominal_alokasi'][$i];
                            }
                           input_only_log("UPDATE supplier set saldo_hutang=$saldo_hutang where id_supplier='$r[id_supplier]'",$module);
            //echo "UPDATE customer set saldo_piutang=$saldo_hutang where id_customer='$r[id_customer]'";
            } 

     }
if($itemValues!=0) {
    input_only_log($sql,$module);
       // echo $sql;
    }
//echo $query;
}
// Update menu
elseif ($module=='titipansupplier' AND $act=='edit'){
                                            $query ="UPDATE trans_bayarbeli_header SET 
                                            id_akunkasperkiraan = '$_POST[akun_kas]',
                                            bukti_bayar = '$_POST[no_bukti]',
                                            tgl_pembayaran = '$_POST[tgl]',
                                            nominal = '$_POST[nominal]',
                                            id_masterbank = '$id_masterbank',
                                           id_supplier = '$_POST[supplier]',
                                            rek_tujuan = '$rek_tujuan',
                                            ac_tujuan = '$ac_tujuan',
                                            no_giro = '$no_giro',
                                            jatuh_tempo = '$jatuh_tempo',
                                            status_giro = '$status_giro',
                                            ket = '$_POST[ket]',
                                           status_titipan =  'T',
                                            user_update = '$_SESSION[namauser]',
                                          tgl_update =   now()
                                            WHERE id_bayarbeli = '$_POST[id_bayar]' "; 
  }
input_data($query,$module);
}

?>
