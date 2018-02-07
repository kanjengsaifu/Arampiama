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
if ($module=='purchaseorder' AND $act=='hapus'){
  $query=mysql_query("SELECT * FROM `trans_pur_order` WHERE id='$_GET[id]'");
  $r=mysql_fetch_array($query);
  input_only_log("DELETE FROM `trans_pur_order` WHERE `id_pur_order`='$r[id_pur_order]'");
  input_only_log("DELETE FROM `trans_pur_order_detail` WHERE `id_pur_order`='$r[id_pur_order]'");
header('location:../../media.php?module='.$module);
}
// Input menu
elseif ($module=='purchaseorder' AND $act=='input'){
  $kode_po=kode_surat('PO','trans_pur_order','id_pur_order','id');
 input_only_log("INSERT INTO trans_pur_order(
                                                  id_pur_order,
                                                  id_supplier,
                                                  grand_total,
                                                  alltotal,
                                                  discper,
                                                  disc,
                                                  ppn,
                                                  ppnper,
                                                  tgl_po,
                                                  tgl_update,
                                                  user_update)
                                                  VALUES(
                                                  '$kode_po',
                                                  '$_POST[supplier2]',
                                                  '$_POST[grandtotal]',
                                                  '$_POST[alltotal]',
                                                  '$_POST[persendisc]',
                                                  '$_POST[discalltotal]',
                                                  '$_POST[totalppn]',
                                                  '$_POST[persenppn]',
                                                  '$_POST[tgl_po]',
                                                  now(),
                                                  '$_SESSION[namauser]'
                                                  )",$module);

  $itemCount = count($_POST["id_po_barang"]);
    $itemValues=0;
    $query = "INSERT INTO trans_pur_order_detail(
                                                id_pur_order,
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
      if(!empty($kode_po) || !empty($_POST["id_po_barang"][$i]) ||!empty($_POST["jenis_satuan"][$i]) || !empty($_POST["disc1"][$i]) || !empty($_POST["disc2"][$i])  || !empty($_POST["disc3"][$i]) || !empty($_POST["disc4"][$i])|| !empty($_POST["disc5"][$i])  ||!empty($_POST["harga_sat1"][$i])  || !empty($_POST["jumlah"][$i]) || !empty($_POST["total"][$i])) {
        $itemValues++;
        if($queryValue!="") {
          $queryValue .= ",";
        }
        $temp= explode('-',$_POST["jenis_satuan"][$i]);
        $queryValue .= "('" . $kode_po . "', '" .$_POST["id_po_barang"][$i]  . "', '" . $temp[1] . "', '" . $temp[2] . "', '" .$_POST["disc1"][$i] . "', '" . $_POST["disc2"][$i] . "', '" . $_POST["disc3"][$i] . "', '" . $_POST["disc4"][$i] . "', '" . $_POST["disc5"][$i]. "', '" . $_POST["harga_sat1"][$i] . "', '" .$_POST["jumlah"][$i] . "', '" . $_POST["total"][$i] . "')";
      }
    }
    $sql = $query.$queryValue;
    if($itemValues!=0) {
      input_and_print($sql,$module,$kode_po);
    }
    }
elseif ($module=='purchaseorder' AND $act=='update'){
  $sqlini = "UPDATE trans_pur_order SET 
                                                id_pur_order = '$_POST[no_po]',
                                                  id_supplier =  '$_POST[supplier]',
                                                  grand_total = '$_POST[grandtotal]',
                                                  alltotal = '$_POST[alltotal]',
                                                  discper = '$_POST[persendisc]',
                                                  disc = '$_POST[discalltotal]',
                                                  ppn = '$_POST[totalppn]',
                                                  ppnper = '$_POST[persenppn]',
                                                  tgl_po = '$_POST[tgl_po]',
                                                  tgl_update = now(),
                                                  user_update = '$_SESSION[namauser]'
                                                  WHERE id = '$_POST[id]'";
 input_only_log($sqlini,$module);

 input_only_log("DELETE FROM trans_pur_order_detail WHERE id_pur_order='$_POST[no_po]'",$module);

     $itemCount = count($_POST["id_po_barang"]);
    $itemValues=0;
    $query = "INSERT INTO trans_pur_order_detail(
                                                id,
                                                id_pur_order,
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
      if(!empty($_POST["no_po"]) || !empty($_POST["id_po_barang"][$i]) || !empty($_POST["disc1"][$i])|| !empty($_POST["jenis_satuan"][$i]) || !empty($_POST["disc2"][$i])  || !empty($_POST["disc3"][$i]) || !empty($_POST["disc4"][$i]) || !empty($_POST["disc5"][$i])  ||  !empty($_POST["harga_sat1"][$i])  || !empty($_POST["jumlah"][$i]) || !empty($_POST["total"][$i]) || !empty($_POST["id_po"][$i])) {
        $itemValues++;
        if($queryValue!="") {
          $queryValue .= ",";
        }
         $tempa=explode('-', $_POST["jenis_satuan"][$i]);
        $queryValue .= "('" . $_POST["id_po"][$i] . "', '" . $_POST["no_po"] . "', '" . $_POST["id_po_barang"][$i] . "', '" . $tempa[1] . "', '" . $tempa[2] . "', '" . $_POST["disc1"][$i] . "', '" . $_POST["disc2"][$i] . "', '" . $_POST["disc3"][$i] . "', '" . $_POST["disc4"][$i] . "', '" . $_POST["disc5"][$i] . "', '" .$_POST["harga_sat1"][$i] . "', '" . $_POST["jumlah"][$i] . "', '" . $_POST["total"][$i] . "' )";
      }
    }
     $queryEnd = " ON DUPLICATE KEY UPDATE id = VALUES(id), id_pur_order = VALUES(id_pur_order), id_barang = VALUES(id_barang),satuan = VALUES(satuan), kali = VALUES(kali),disc1 = VALUES(disc1), disc2 = VALUES(disc2), disc3 = VALUES(disc3), disc4 = VALUES(disc4),  disc5 = VALUES(disc5),harga = VALUES(harga), jumlah = VALUES(jumlah), total = VALUES(total)";
   $sql = $query.$queryValue.$queryEnd;
    if($itemValues!=0) {
    input_data($sql,$module);
    }


}
}

?>