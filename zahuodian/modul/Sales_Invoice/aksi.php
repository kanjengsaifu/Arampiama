<?php
 include "../../config/koneksi.php";
  include "../../lib/input.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
$module=$_GET['module'];
$act=$_GET['act'];


// Hapus modul
if ($module=='salesinvoice' AND $act=='delete'){
        $query="SELECT * FROM `trans_sales_invoice` where id='$_GET[id]' ";
        $result=mysql_query($query);
        $r=mysql_fetch_array($result);
                    input_only_log("DELETE FROM jurnal_umum where kode_nota='$r[id_invoice]'");
                    input_only_log("DELETE FROM trans_sales_invoice WHERE id_invoice='$r[id_invoice]'");
                    input_only_log("DELETE FROM trans_sales_invoice_detail WHERE id_invoice='$r[id_invoice]'");
                     input_data("UPDATE trans_lkb SET status_trans='1' where id_lkb= '$r[id_lkb]'",$module);
//header('location:../../media.php?module='.$module);
   if(!empty($p)) $message = "Delete Successfully.";
}

// Input menu
elseif ($module=='salesinvoice' AND $act=='input'){
  if (check_lkb($_POST[no_so])==0) {
      if (check_no_si($_POST[kodesi])==0) {
     $kode_si=$_POST[kodesi];
$noz= $_POST[noz];
$query="UPDATE trans_lkb SET status_trans='2' where id_lkb= '$_POST[no_so]'";
input_only_log($query,$module);
   $query="INSERT INTO trans_sales_invoice_detail(
          id_invoice,
          id_barang,
          qty_so,
          qty_so_satuan,
          harga_so,
          total_so,
          qty_si,
          qty_si_satuan,
          qty_si_convert,
          harga_si,
          disc1,
          disc2,
          disc3,
          disc4,
          disc5,
          total,
          hpp,
          user,
          tgl_update)
          VALUES";   
            mysql_query("Delete from jurnal_umum where kode_nota='".$kode_si."'");   

for ($i=0;$i<$noz;$i++){
  $queryhpp=mysql_query("select hpp,id_akunkasperkiraan from barang where id_barang=".$_POST[id_barang][$i]."");
  $hpp = mysql_fetch_array($queryhpp);
  $sum_hpp=($hpp['hpp']* $_POST[qty_si_convert][$i]);

  input_jurnal_umum('93',$hpp['id_akunkasperkiraan'],'1','',$sum_hpp,$kode_si,$_POST[tgl_si],'','customer','',$kode_si);
        $values=  
          "('".$kode_si."','".
          $_POST[id_barang][$i]."','".
          $_POST[qty_so][$i]."','".
          $_POST[qty_so_satuan][$i]."','".
          $_POST[harga_so][$i]."','".
          $_POST[total_so][$i]."','".
          $_POST[qty_si][$i]."','".
          $_POST[qty_si_satuan][$i]."','".
          $_POST[qty_si_convert][$i]."','".
          $_POST[harga_si][$i]."','".
          $_POST[disc1][$i]."','".
          $_POST[disc2][$i]."','".
          $_POST[disc3][$i]."','".
          $_POST[disc4][$i]."','".
          $_POST[disc5][$i]."','".
          $_POST[total][$i]."','".
          $hpp['hpp']."','".
          $_SESSION[namauser]."','".
         " now()')";

input_only_log($query.$values,$module);
}
// Insert Header
 $query = ("INSERT INTO trans_sales_invoice(
                                                                              id_invoice,
                                                                              id_lkb,
                                                                              id_customer,
                                                                              id_sales,
                                                                              no_nota,
                                                                              no_expedisi,
                                                                              tgl_si,
                                                                              tgl,
                                                                              alltotal,
                                                                              alldiscpersen,
                                                                              alldiscnominal,
                                                                              allppnpersen,
                                                                              allppnnominal,
                                                                              grand_total,
                                                                              user,
                                                                              tgl_update)
                                                                              VALUES(
                                                                                              '$kode_si',
                                                                                              '$_POST[no_so]',
                                                                                              '$_POST[customer]',
                                                                                              '$_POST[id_sales]',
                                                                                              '$_POST[no_nota]',
                                                                                              '$_POST[no_expedisi]',
                                                                                              '$_POST[tgl_si]',
                                                                                              '$_POST[tgl_jt]',
                                                                                              '$_POST[alltotal]',
                                                                                              '$_POST[alldiscpersen]',
                                                                                              '$_POST[alldiscnominal]',
                                                                                              '$_POST[allppnpersen]',
                                                                                              '$_POST[allppnnominal]',
                                                                                              '$_POST[grandtotal]',
                                                                                              '$_SESSION[namauser]',
                                                                                              now())"
          );
input_jurnal_umum('36','19','1','',abs($_POST[grandtotal]),$kode_si,$_POST[tgl_si],$_POST[customer],'customer','',$kode_si);
input_and_print($query,$module,$kode_si);
  }else{
     Echo "Transaksi Nomor SI ini Pernah Dibuat";
  }
  }else{
     Echo "Transaksi Sudah Pernah di lakukan";
  }
 

}
elseif ($module=='salesinvoice' AND $act=='update'){
  $noz= $_POST[noz];
     mysql_query("Delete from jurnal_umum where kode_nota='".$_POST[id_invoice_lama]."'");
 for ($i=0; $i < $noz; $i++) { 
    $queryhpp=mysql_query("select hpp,id_akunkasperkiraan from barang where id_barang=".$_POST[id_barang][$i]."");
    $hpp = mysql_fetch_array($queryhpp);
input_jurnal_umum('93',$hpp['id_akunkasperkiraan'],'1','',($_POST[hpp][$i]* $_POST[qty_si_convert][$i]) ,$_POST[id_invoice],$_POST[tgl_si],'','customer','',$_POST[id_invoice]);
$sqlini2 = "UPDATE trans_sales_invoice_detail SET id_invoice='".$_POST[id_invoice]."' ,harga_si='". $_POST[harga_si][$i]."',total='". $_POST[total][$i]."',disc1='". $_POST[disc1][$i]."',disc2='". $_POST[disc2][$i]."',disc3='". $_POST[disc3][$i]."',disc4='". $_POST[disc4][$i]."',disc5='". $_POST[disc5][$i]."',user='". $_SESSION[namauser]."',tgl_update=now() where id='".$_POST[id][$i]."';";
        input_only_log($sqlini2,$module);
 }
 
  $sqlini = "UPDATE trans_sales_invoice SET 
                                  id_invoice='$_POST[id_invoice]',
                                  tgl_si = '$_POST[tgl_si]',
                                  tgl = '$_POST[tgl_jt]',
                                  alltotal = '$_POST[alltotal]',
                                  alldiscpersen = '$_POST[alldiscpersen]',
                                  alldiscnominal = '$_POST[alldiscnominal]',
                                  allppnpersen = '$_POST[allppnpersen]',
                                  allppnnominal = '$_POST[allppnnominal]',
                                  grand_total = '$_POST[grandtotal]',
                                  user = '$_SESSION[namauser]',
                                  tgl_update = now()
                                  where id='$_POST[id_sales_invoice]'";
                                  echo $sqlini;
input_jurnal_umum('36','19','1','',abs($_POST[grandtotal]),$_POST[id_invoice],$_POST[tgl_si],$_POST[id_customer],'customer','',$_POST[id_invoice]);

input_data($sqlini,$module);

}
}


function check_lkb($kode_lkb){
    $sql_cari="SELECT * FROM `trans_lkb` WHERE `id_lkb`='$kode_lkb' and `status_trans`='2' ";
    $result=mysql_query($sql_cari);
    $hasil =mysql_num_rows($result);
    return $hasil;
}
function check_no_si($kode_si){
    $sql_cari="SELECT * FROM `trans_sales_invoice` WHERE `id_invoice`='$kode_si'";
    $result=mysql_query($sql_cari);
    $hasil =mysql_num_rows($result);
    return $hasil;
}
?>