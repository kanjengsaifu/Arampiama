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
if ($module=='titipancustomer' AND $act=='hapus'){
      $query="UPDATE trans_bayarjual_header SET is_void = '1' WHERE id_bayarjual='$_GET[id]'";
      //echo $query;
}
elseif ($module=='titipancustomer' AND $act=='hapusdetail'){
      $query="DELETE FROM trans_bayarjual_detail WHERE id_bayarjual_detail='$_GET[id]'";
      if(!empty($_GET['id_pi'])){
      $query1="UPDATE trans_sales_invoice SET status_lunas = '0' WHERE id_invoice='$_GET[id_pi]'";
        input_only_log($query1,$module);
    }
      //echo $query;
}
// Input menu
elseif ($module=='titipancustomer' AND $act=='input'){
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

            $query="INSERT INTO trans_bayarjual_header (
                                id_akunkasperkiraan, 
                                bukti_bayarjual, 
                                tgl_pembayaranjual, 
                                nominaljual, 
                                id_masterbank,
                                 id_customer,  
                                rek_asal, 
                                ac_asal, 
                                no_giro_jual, 
                                jatuh_tempo_jual,
                                status_giro_jual, 
                                ket_jual, 
                                status_titipan, 
                                user_update, 
                                tgl_update) 
                                    VALUES(
                                    '$_POST[akun_kas]',
                                    '$_POST[no_bukti]',
                                    '$_POST[tgl]',
                                    '$_POST[nominal]',
                                    '$id_masterbank',
                                   '$_POST[customer]',
                                    '$rek_tujuan',
                                    '$ac_tujuan',
                                    '$no_giro',
                                    '$jatuh_tempo',
                                    '$status_giro',
                                    '$_POST[ket]',
                                    'T',
                                    '$_SESSION[namauser]',
                                    now())";

            
            echo $query;
    }
elseif ($module=='titipancustomer' AND $act=='inputdetail'){
  // Input detail menu
    if(isset($_GET['id'])){
        $id_pay =$_GET['id'];
      }
            $query="UPDATE trans_bayarjual_header SET 
                                            nominal_alokasi_jual = '$_POST[total_alokasi]',
                                            status_bayar_jual = '$_POST[status]',
                                            sisa_alokasi_jual = '$_POST[sisa_alokasi]'
                                                       WHERE id_bayarjual = '$id_pay'";

            $itemCount = count($_POST["akun_kasdetail"]);
            $itemValues=0;
            $query1= "INSERT INTO trans_bayarjual_detail(
                                                id_bayarjual_detail,
                                                bukti_bayarjual,
                                                id_akunkasperkiraan_detail,
                                                nota_invoice,
                                                ket_detail_jual,
                                                sisa_invoice_detail_jual,
                                                nominal_alokasi_detail_jual,
                                                user_update, 
                                                tgl_update,
                                                id_customer)
                                                VALUES ";
                                                   //akun_kasdetail, no_invoice, ketdetail, sisa_invoice, nominal_alokasi, total_alokasi, sisa_alokasi
            $queryValue = "";
            for($i=0;$i<$itemCount;$i++) {
            if(!empty($_POST["no_bukti"]) || !empty($_POST["akun_kasdetail"][$i]) || !empty($_POST["nominal_alokasi"][$i])) {
            $itemValues++;
            if($queryValue!="") {
            $queryValue .= ",";
            }
        $queryValue .= "('" . $_POST["id_bayarbeli_detail"] [$i] . "','" . $_POST["no_bukti"] . "', '" .$_POST["akun_kasdetail"][$i]  . "', '" . $_POST["no_invoice"][$i] . "', '" . $_POST["ketdetail"][$i] . "', '" .$_POST["sisa_invoice"][$i] . "', '" . $_POST["nominal_alokasi"][$i] . "', '" .  $_SESSION["namauser"] . "', now(),'".$_POST['viewakuntextsave'][$i]."' )";
      }
    }
    $queryEnd = " ON DUPLICATE KEY UPDATE id_bayarjual_detail = VALUES(id_bayarjual_detail) , id_akunkasperkiraan_detail = VALUES(id_akunkasperkiraan_detail), nota_invoice = VALUES(nota_invoice), ket_detail_jual = VALUES(ket_detail_jual), sisa_invoice_detail_jual = VALUES(sisa_invoice_detail_jual), nominal_alokasi_detail_jual = VALUES(nominal_alokasi_detail_jual), user_update = VALUES(user_update), tgl_update = VALUES(tgl_update)";
    $sql = $query1.$queryValue.$queryEnd;

        //update status lunas trans invoice
    $pisahbuktibayar = explode(" -",$_POST['no_bukti']);
     for($i=0;$i<$itemCount;$i++) {
        if( !empty($_POST["no_invoice"][$i]) && ($_POST["sisa_invoice"][$i] <= 0 )){
                $query2="UPDATE trans_sales_invoice SET 
                               status_lunas = '1'
                                           WHERE id_invoice =  '" . $_POST["no_invoice"][$i] . "'";
                        mysql_query("UPDATE trans_bayarjual_detail SET 
                                      sisa_invoice_detail_jual = 0
                                                  WHERE nota_invoice =  '" . $_POST["no_invoice"][$i] . "'");
       input_only_log($query2,$module);
        //echo $query2;
        }
          if( !empty($_POST["no_invoice"][$i]) && ($pisahbuktibayar[0] != "BGM")){
                                $query41 = mysql_query("SELECT customer.id_customer, customer.saldo_piutang FROM customer RIGHT JOIN trans_sales_invoice ON(trans_sales_invoice.id_customer =customer.id_customer) WHERE trans_sales_invoice.id_invoice='".$_POST["no_invoice"][$i]."'");
                                $r=mysql_fetch_array($query41);
                                if(!empty($_POST['nominal_alokasi123'][$i])){
                                     $saldo_hutang=$r['saldo_piutang']+$_POST['nominal_alokasi123'][$i]-$_POST['nominal_alokasi'][$i];
                                } else {
                                    $saldo_hutang=$r['saldo_piutang']-$_POST['nominal_alokasi'][$i];
                                }
                               input_only_log("UPDATE customer set saldo_piutang=$saldo_hutang where id_customer='$r[id_customer]'",$module);
        //echo "UPDATE customer set saldo_piutang=$saldo_hutang where id_customer='$r[id_customer]'";
        } 
     }
if($itemValues!=0) {
   input_only_log($sql,$module);
        //echo $sql;
    }
//echo $query;
}
//######################################### Update menu
elseif ($module=='titipancustomer' AND $act=='edit'){
                                            $query ="UPDATE  trans_bayarjual_header SET 
                                            id_akunkasperkiraan =   '$_POST[akun_kas]',
                                             bukti_bayarjual =   '$_POST[no_bukti]',
                                            tgl_pembayaranjual =   '$_POST[tgl]',
                                            nominaljual =   '$_POST[nominal]',
                                            id_masterbank =   '$id_masterbank',
                                            id_customer =   '$_POST[customer]',
                                            rek_asal =   '$rek_tujuan',
                                            ac_asal =   '$ac_tujuan',
                                            no_giro_jual =   '$no_giro',
                                            jatuh_tempo_jual =   '$jatuh_tempo',
                                            status_giro_jual =   '$status_giro',
                                            ket_jual =   '$_POST[ket]',
                                            status_titipan =    'T',
                                            user_update =   '$_SESSION[namauser]',
                                            tgl_update =     now()
                                            WHERE id_bayarjual =   '$_POST[id_bayar]'"; 
 
  }
input_data($query,$module);
}

?>
