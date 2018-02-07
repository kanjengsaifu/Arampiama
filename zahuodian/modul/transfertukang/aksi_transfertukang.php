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
if ($module=='transfertukang' AND $act=='input'){
    $cek = mysql_num_rows(mysql_query("SELECT no_surat_jalan FROM transfer_tukang WHERE is_void = 0 AND no_surat_jalan = '$_POST[no_surat_jalan]'"));
    if ($cek > 0) {
    echo "<script type='text/javascript'>alert('Terjadi Kesalahan pada Penyimpanan, Silahkan ulangi Transaksi.');</script>";
    echo "<a href=javascript:history.go(-1)>Kembali</a> ;(";
    }
    else {
        $sel = mysql_query("SELECT no_transfer_tukang FROM transfer_tukang order by id_trasnfer_tukang desc limit 1");
        $ect = mysql_fetch_array($sel);
        $e = kodesurat($ect['no_transfer_tukang'], TT, no_tt, no_transfer_tukang );
        $no = explode(" ", $e);
        $id = explode("'", $no[3]);
        $no_transfer_tukang = $id[1];
        $itemCount = count($_POST["id_barang_asal"]);
        $itemValues=0;
        $query = "INSERT INTO transfer_tukang(
                            no_transfer_tukang,
                            no_expedisi,
                            no_surat_jalan,
                            tgl_transfer_tukang,
                            id_barang,
                            harga,
                            id_supplier_dari,
                            id_supplier_pada,
                            jumlah,
                            user_update,
                            tgl_update)
                            VALUES ";
        $queryValue = "";
        for($i=0;$i<$itemCount;$i++) {
          if(!empty($no_transfer_tukang) || !empty($_POST["no_expedisi"]) || !empty($_POST["no_surat_jalan"]) || !empty($_POST["tgl_transfer"])  || !empty($_POST["id_barang_asal"][$i]) || !empty($_POST["harga_barang_asal"][$i]) || !empty($_POST["id_gudang_asal"][$i])  ||!empty($_POST["id_gudang_tujuan"])  || !empty($_POST["transfer"][$i]) || !empty($_POST["total"][$i])) {
            $itemValues++;
            if($queryValue!="") {
              $queryValue .= ",";
            }
            $queryValue .= "('" . $no_transfer_tukang . "', '" . $_POST["no_expedisi"] . "', '" . $_POST["no_surat_jalan"] . "', '" . $_POST["tanggaltransfer"] . "', '" . $_POST["id_barang_asal"][$i] . "', '" . $_POST["harga_barang_asal"][$i] . "', '" . $_POST["id_gudang_asal"][$i] . "', '" . $_POST["id_gudang_tujuan"] . "', '" .$_POST["transfer"][$i] . "', '".$_SESSION[namauser]."', now())";
            $stok_sekarang=mysql_query("SELECT stok_tukang from stok_tukang where id_barang='".$_POST[id_barang_asal][$i]."' and id_supplier='".$_POST[id_gudang_asal][$i]."' ");
            $a= mysql_fetch_array($stok_sekarang);
            $queryupdate=("UPDATE stok_tukang set stok_tukang='".($a[0]-$_POST["transfer"][$i])."' where id_barang='".$_POST[id_barang_asal][$i]."' and id_supplier='".$_POST[id_gudang_asal][$i]."'");
            mysql_query($queryupdate);
            $stok_sekarang=mysql_query("SELECT stok_tukang from stok_tukang where id_barang='".$_POST[id_barang_asal][$i]."' and id_supplier='".$_POST[id_gudang_tujuan]."' ");
            $a= mysql_fetch_array($stok_sekarang);
            $queryupdate=("UPDATE stok_tukang set stok_tukang='".($a[0]+$_POST["transfer"][$i])."', harga='".$_POST[harga_barang_asal][$i]."' where id_barang='".$_POST[id_barang_asal][$i]."' and id_supplier='".$_POST[id_gudang_tujuan]."'") ;
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
}
?>