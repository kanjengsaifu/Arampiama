<?php
 include "../../config/koneksi.php";
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
if ($module=='transfergudang' AND $act=='input'){
  $itemCount = count($_POST["id_barang_asal"]);
    $itemValues=0;
    $query = "INSERT INTO transfer_gudang(
                        id_transfer_gudang,
                        no_expedisi,
                        no_surat_jalan,
                        tgl_transfer,
                        id_barang,
                        gudang_asal,
                        gudang_tujuan,
                        jumlah)
                        VALUES ";
    $queryValue = "";
    for($i=0;$i<$itemCount;$i++) {
      if(!empty($_POST["id_transfer_gudang"]) || !empty($_POST["no_expedisi"]) || !empty($_POST["no_surat_jalan"]) || !empty($_POST["tgl_transfer"])  || !empty($_POST["id_barang_asal"][$i]) || !empty($_POST["id_gudang_asal"][$i])  ||!empty($_POST["id_gudang_tujuan"])  || !empty($_POST["transfer"][$i]) || !empty($_POST["total"][$i])) {
        $itemValues++;
        if($queryValue!="") {
          $queryValue .= ",";
        }
        $queryValue .= "('" . $_POST["id_transfer_gudang"] . "', '" . $_POST["no_expedisi"] . "', '" . $_POST["no_surat_jalan"] . "', '" . $_POST["tanggaltransfer"] . "', '" . $_POST["id_barang_asal"][$i] . "', '" . $_POST["id_gudang_asal"][$i] . "', '" . $_POST["id_gudang_tujuan"] . "', '" .$_POST["transfer"][$i] . "')";
        $stok_sekarang=mysql_query("SELECT stok_sekarang from stok where id_barang='".$_POST[id_barang_asal][$i]."' and id_gudang='".$_POST[id_gudang_asal][$i]."' ");
        $a= mysql_fetch_array($stok_sekarang);
        $queryupdate=("UPDATE stok set stok_sekarang='".($a[0]-$_POST["transfer"][$i])."' where id_barang='".$_POST[id_barang_asal][$i]."' and id_gudang='".$_POST[id_gudang_asal][$i]."'");
        mysql_query($queryupdate);
        $stok_sekarang=mysql_query("SELECT stok_sekarang from stok where id_barang='".$_POST[id_barang_asal][$i]."' and id_gudang='".$_POST[id_gudang_tujuan]."' ");
        $a= mysql_fetch_array($stok_sekarang);
        $queryupdate=("UPDATE stok set stok_sekarang='".($a[0]+$_POST["transfer"][$i])."' where id_barang='".$_POST[id_barang_asal][$i]."' and id_gudang='".$_POST[id_gudang_tujuan]."'") ;
        mysql_query($queryupdate);
         

      }
    }
    $sql = $query.$queryValue;
    echo $sql;
    if($itemValues!=0) {
      $result = mysql_query($sql);
      if(!empty($result)) $message = "Added Successfully.";
    }

header('location:../../media.php?module='.$module);
    }
}
?>