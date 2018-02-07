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
if ($module=='salesorder' AND $act=='hapus'){
  $query=mysql_query("SELECT * FROM `trans_sales_order` WHERE id='$_GET[id]'");
  $r=mysql_fetch_array($query);
  input_only_log("DELETE FROM `trans_sales_order` WHERE `id_sales_order`='$r[id_sales_order]'");
  input_only_log("DELETE FROM `trans_sales_order_detail` WHERE `id_sales_order`='$r[id_sales_order]'");
header('location:../../media.php?module='.$module);
   if(!empty($p)) $message = "Delete Successfully.";
}
// Input menu
elseif ($module=='salesorder' AND $act=='input'){
  $kode_so=kode_surat('SO','trans_sales_order','id_sales_order','id');
 input_only_log("INSERT INTO trans_sales_order(
                                                  id_sales_order,
                                                  id_customer,
                                                  id_sales,
                                                  grand_total,
                                                  alltotal,
                                                  discper,
                                                  disc,
                                                  ppn,
                                                  ppnper,
                                                  tgl_so,
                                                  tgl_update,
                                                  user_update)
                                                  VALUES(
                                                  '$kode_so',
                                                  '$_POST[customer]',
                                                  '$_POST[sales]',
                                                  '$_POST[grandtotal]',
                                                  '$_POST[alltotal]',
                                                  '$_POST[persendisc]',
                                                  '$_POST[discalltotal]',
                                                  '$_POST[totalppn]',
                                                  '$_POST[persenppn]',
                                                  '$_POST[tgl_so]',
                                                  now(),
                                                  '$_SESSION[namauser]'
                                                  )",$module);

  $itemCount = count($_POST["id_so_barang"]);
  echo $itemCount;
    $itemValues=0;
    $query = "INSERT INTO trans_sales_order_detail(
                                                id_sales_order,
                                                id_barang,
                                                satuan,
                                                kali,
                                                disc1,
                                                disc2,
                                                disc3,
                                                disc4,
                                                disc5,
                                                harga,
                                                jumlah,
                                                total)
                                                VALUES ";
    $queryValue = "";
    for($i=0;$i<$itemCount;$i++) {
      if(!empty($kode_so) || !empty($_POST["id_so_barang"][$i]) ||!empty($_POST["jenis_satuan"][$i]) || !empty($_POST["disc1"][$i]) || !empty($_POST["disc2"][$i])  || !empty($_POST["disc3"][$i]) || !empty($_POST["disc4"][$i])|| !empty($_POST["disc5"][$i])  ||!empty($_POST["harga_sat1"][$i])  || !empty($_POST["jumlah"][$i]) || !empty($_POST["total"][$i])) {
        $itemValues++;
        if($queryValue!="") {
          $queryValue .= ",";
        }
        $temp= explode('-',$_POST["jenis_satuan"][$i]);
        $queryValue .= "('" . $kode_so . "', '" . $_POST["id_so_barang"][$i] . "', '" . $temp[1] . "', '" . $temp[2] . "', '" . $_POST["disc1"][$i] . "', '" . $_POST["disc2"][$i] . "', '" . $_POST["disc3"][$i] . "', '" . $_POST["disc4"][$i]. "', '" . $_POST["disc5"][$i] . "', '" . $_POST["harga_sat1"][$i] . "', '" .$_POST["jumlah"][$i] . "', '" . $_POST["total"][$i] . "')";
      }
    }
    $sql = $query.$queryValue;
    if($itemValues!=0) {
      $sel=mysql_query("SELECT * FROM trans_sales_order WHERE id_sales_order='$kode_so'");
      $ect=mysql_fetch_array($sel);
      $id_header=$ect[id];
      input_and_print($sql,$module, $id_header);
    }
    }
elseif ($module=='salesorder' AND $act=='update'){
  $sqlini = "UPDATE trans_sales_order SET 
                                                id_sales_order = '$_POST[no_so]',
                                                  id_customer =  '$_POST[customer]',
                                                  id_sales = '$_POST[sales]',
                                                  grand_total = '$_POST[grandtotal]',
                                                  alltotal = '$_POST[alltotal]',
                                                  discper = '$_POST[persendisc]',
                                                  disc = '$_POST[discalltotal]',
                                                  ppn = '$_POST[totalppn]',
                                                  ppnper = '$_POST[persenppn]',
                                                  tgl_so = '$_POST[tgl_so]',
                                                  tgl_update = now(),
                                                  user_update = '$_SESSION[namauser]'
                                                  WHERE id = '$_POST[id]'";
 input_only_log($sqlini,$module);

 input_only_log("DELETE FROM trans_sales_order_detail WHERE id_sales_order='$_POST[no_so]'",$module);

     $itemCount = count($_POST["id_so_barang"]);
    $itemValues=0;
    $query = "INSERT INTO trans_sales_order_detail(
                                                id,
                                                id_sales_order,
                                                id_barang,
                                                satuan,
                                                kali,
                                                disc1,
                                                disc2,
                                                disc3,
                                                disc4,
                                                disc5,
                                                harga,
                                                jumlah,
                                                total)
                                                VALUES ";
    $queryValue = "";
    for($i=0;$i<$itemCount;$i++) {
      if(!empty($_POST["no_so"]) || !empty($_POST["id_so_barang"][$i]) || !empty($_POST["jenis_satuan"][$i]) || !empty($_POST["disc1"][$i]) || !empty($_POST["disc2"][$i])  || !empty($_POST["disc3"][$i]) || !empty($_POST["disc4"][$i]) || !empty($_POST["disc5"][$i]) ||  !empty($_POST["harga_sat1"][$i])  || !empty($_POST["jumlah"][$i]) || !empty($_POST["total"][$i]) || !empty($_POST["id_so"][$i])) {
        $itemValues++;
        if($queryValue!="") {
          $queryValue .= ",";
        }
        $tempa=explode('-', $_POST["jenis_satuan"][$i]);
        $queryValue .= "('" . $_POST["id_so"][$i] . "', '" . $_POST["no_so"] . "', '" . $_POST["id_so_barang"][$i] . "', '" . $tempa[1] . "', '" . $tempa[2] . "', '" . $_POST["disc1"][$i] . "', '" . $_POST["disc2"][$i] . "', '" . $_POST["disc3"][$i] . "', '" . $_POST["disc4"][$i]. "', '" . $_POST["disc5"][$i] . "', '" .$_POST["harga_sat1"][$i] . "', '" . $_POST["jumlah"][$i] . "', '" . $_POST["total"][$i] . "' )";
      }
    }
     $queryEnd = "ON DUPLICATE KEY UPDATE id = VALUES(id), id_sales_order = VALUES(id_sales_order), id_barang = VALUES(id_barang),satuan = VALUES(satuan), kali = VALUES(kali), disc1 = VALUES(disc1), disc2 = VALUES(disc2), disc3 = VALUES(disc3), disc4 = VALUES(disc4), disc5 = VALUES(disc5), harga = VALUES(harga), jumlah = VALUES(jumlah), total = VALUES(total)";
   $sql = $query.$queryValue.$queryEnd;
    if($itemValues!=0) {
    input_data($sql,$module);
    }
}
}
?>