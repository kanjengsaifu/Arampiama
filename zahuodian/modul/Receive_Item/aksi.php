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
if ($module=='laporanbarangmasuk' AND $act=='hapus'){
  $select = mysql_query("SELECT d.*, h.id AS head, h.id_lpb, h.is_void FROM trans_lpb h JOIN trans_lpb_detail d ON h.id_lpb = d.id_lpb WHERE h.id = '$_GET[id]'");
  while ($detail = mysql_fetch_array($select)) {
    $update_stok = "UPDATE stok SET stok_sekarang = (stok_sekarang-$detail[qty_diterima_convert]) WHERE id_barang = '$detail[id_barang]' AND id_gudang = '$detail[id_gudang]'";
    input_only_log($update_stok,$module);

    $id_lpb=$detail[id_lpb];
    $id_pur_order=$detail[id_pur_order];
  }
 input_only_log("DELETE FROM `trans_lpb` WHERE `id_lpb`='$id_lpb'");
 input_only_log("DELETE FROM `trans_lpb_detail` WHERE `id_lpb`='$id_lpb'");
$select = mysql_query("SELECT * FROM `trans_lpb` a,trans_pur_order b WHERE a.`id_pur_order`=b.`id_pur_order` and a.id_pur_order= '$id_pur_order'");
if (mysql_num_rows($select)>=1) {
  $query ="UPDATE trans_pur_order SET status_trans = '1'  WHERE  id_pur_order='$id_pur_order'";
  input_data($query,$module);
}else{
  $query ="UPDATE trans_pur_order SET status_trans = '0'  WHERE  id_pur_order='$id_pur_order'";
  input_data($query,$module);
}
}

elseif ($module=='laporanbarangmasuk' AND $act=='input'){
$query = ("INSERT INTO trans_lpb(
                                                  id_lpb,
                                                  id_pur_order,
                                                  id_supplier,
                                                  tgl_lpb,
                                                  no_nota_supplier, 
                                                  no_expedisi,
                                                  user_update,
                                                  tgl_update)
                                                  VALUES(
                                                    '$_POST[no_lbm]',
                                                    '$_POST[no_po]',
                                                    '$_POST[supplier]',
                                                    '$_POST[tgl_lbm]',
                                                    '$_POST[no_nota_supplier]',
                                                    '$_POST[no_expedisi]',
                                                    '$_SESSION[namauser]',
                                                    now() )");


              input_only_log($query,$module);
  $itemCount = count($_POST["id_lbm"]);
    $itemValues=0;
    $query = "INSERT INTO trans_lpb_detail(
                                                  id_lpb,
                                                  id_pur_order,
                                                  id_barang,
                                                  id_gudang,
                                                  qty,
                                                  qty_diterima,
                                                  qty_convert,
                                                  qty_diterima_convert,
                                                  qty_satuan,
                                                  qty_diterima_satuan,
                                                  kode_barang_po,
                                                  user,
                                                  tgl_update)
                                                VALUES ";
    $queryValue = "";
    $counter = 0;
    for($i=0;$i<$itemCount;$i++) {
      if(!empty($_POST["id_lbm"]) || !empty($_POST["id_barang"][$i]) || !empty($_POST["lbr_gudang"][$i]) || !empty($_POST["jumlah_diminta"][$i])  || !empty($_POST["selisih"][$i])|| !empty($_POST["selisih"][$i])|| !empty($_POST["selisih"][$i])|| !empty($_POST["selisih"][$i])) {
        $itemValues++;
        if($queryValue!="") {
          $queryValue .= ",";
        }

        $stok_sekarang=("SELECT stok_sekarang from stok where id_barang='".$_POST["id_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lbm"][$i]."'");
        $stok_sekarang=mysql_query($stok_sekarang);
        $stok_sekarang=mysql_fetch_array($stok_sekarang);
        $jum_sat1=explode('-', $_POST["jenis_satuan"][$i]);
        $jum_sat= $jum_sat1[2] * $_POST["selisih"][$i] ;
        $stok_sekarang=$stok_sekarang[0]+$jum_sat;
        $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$_POST["id_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lbm"][$i]."'");
        input_only_log($qupdate,$module);
        $qty_po_convert = $_POST["jumlah_diminta"][$i]*$_POST["qty_convert"][$i];
        $queryValue .= "('" . $_POST["no_lbm"] . "', '" . $_POST["no_po"] . "', '" . $_POST["id_barang"][$i] . "', '" . $_POST["gudang_lbm"][$i] . "', '" . $_POST["jumlah_diminta"][$i] . "', '" . $_POST["selisih"][$i] . "', '" .$qty_po_convert  . "', '" . $jum_sat . "', '" . $_POST["qty_satuan"][$i]. "', '" . $jum_sat1[1] . "', '" . $_POST["id_lbm" ] [$i]. "', '" .$_SESSION["namauser"]. "', now())";
        if ($qty_po_convert>$jum_sat ) {
         $counter=$counter+1;
        }
      }
    }
    $sql = $query.$queryValue;

    if ($counter==0) {
       // Mengubah PO sudah Lunas
   $query2 = ("UPDATE trans_pur_order SET status_trans = '2'  WHERE  id_pur_order='$_POST[no_po]'");
    input_only_log($query2,$module);
    }else{
      // Mengubah PO tidak bisa di edit
    $query2 = ("UPDATE trans_pur_order SET status_trans = '1'  WHERE  id_pur_order='$_POST[no_po]'");
    input_only_log($query2,$module);
    }


    if($itemValues!=0) {
      input_data($sql,$module);
    
    }
    $sel=mysql_query("SELECT * FROM trans_lpb WHERE id_lpb='$_POST[no_lbm]'");
    $ect=mysql_fetch_array($sel);
    $id_header=$ect[id];
    echo "<script>window.open('cetak.php?id=$id_header')</script>";
    

    //###### UPDATE 
} elseif ($module=='laporanbarangmasuk' AND $act=='update'){
  input_only_log("UPDATE trans_lpb SET 
                                                  id_pur_order = '$_POST[no_po]',
                                                   id_lpb = '$_POST[no_lbm]',
                                                  id_supplier = '$_POST[supplier]',
                                                  tgl_lpb = '$_POST[tgl_lbm]',
                                                  no_nota_supplier = '$_POST[no_nota_supplier]',
                                                  user_update ='$_SESSION[namauser]',
                                                 no_expedisi  ='$_POST[no_expedisi]',
                                                  tgl_update = now()
                                                  WHERE  id = '$_POST[id]' ",$module);
 
  $itemCount = count($_POST["id_lbm"]);
    $itemValues=0;
    $query = "INSERT INTO trans_lpb_detail(
                                                  id,
                                                  id_lpb,
                                                  id_barang,
                                                  id_gudang,
                                                  qty_diterima,
                                                  qty_diterima_convert,
                                                  qty_diterima_satuan)
                                                VALUES ";
                                            
    $queryValue = "";

    for($i=0;$i<$itemCount;$i++) {
      if(!empty($_POST["id_lbm"][$i]) || !empty($_POST["id_barang"][$i]) || !empty($_POST["lbr_gudang"][$i]) || !empty($_POST["jumlah_diminta"][$i])  || !empty($_POST["selisih"][$i])) {
        $itemValues++;
     
        $qty = explode('-',$_POST["jumlah_diminta"][$i] );
        $jum_sat1=explode('-', $_POST["jenis_satuan"][$i]);
        $jum_sat= $jum_sat1[2] * $_POST["selisih"][$i] ;
        echo $jum_sat."<br>";

        $stok_sekarang=("SELECT stok_sekarang from stok where id_barang='".$_POST["id_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lbm"][$i]."'");
        $stok_sekarang=mysql_query($stok_sekarang);
        $stok_sekarang=mysql_fetch_array($stok_sekarang);
   
        $stok_sekarang=$stok_sekarang[0]+$jum_sat-$_POST["selisih2"][$i];
        $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$_POST["id_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lbm"][$i]."'");
        input_only_log($qupdate,$module);
        if($queryValue!="") {
          $queryValue .= ",( '" . $_POST["id_lbm"][$i] . "','" . $_POST["no_lbm"] . "', '" . $_POST["id_barang"][$i] . "', '" . $_POST["gudang_lbm"][$i] . "', '" . $_POST["selisih"][$i] ."', '" . $jum_sat ."', '" . $jum_sat1[1] ."')";;
        }
        else{
            $queryValue .= "( '" . $_POST["id_lbm"][$i] . "','" . $_POST["no_lbm"] . "', '" . $_POST["id_barang"][$i] . "', '" . $_POST["gudang_lbm"][$i] . "', '" . $_POST["selisih"][$i] ."', '" . $jum_sat ."', '" . $jum_sat1[1] ."')";
        }
        
      }
    }
     $queryEnd = " ON DUPLICATE KEY UPDATE id= VALUES(id), id_lpb = VALUES(id_lpb), id_barang = VALUES(id_barang), id_gudang = VALUES(id_gudang), qty_diterima = VALUES(qty_diterima), qty_diterima_convert = VALUES(qty_diterima_convert), qty_diterima_satuan = VALUES(qty_diterima_satuan)";
   $sql = $query.$queryValue.$queryEnd;

    if($itemValues!=0) {
        input_data($sql,$module);
      if(!empty($result)) $message = "Added Successfully.";
    }
    $cek1 = mysql_query("SELECT SUM(d.jumlah) AS pesan FROM trans_pur_order_detail d JOIN trans_pur_order h ON h.id_pur_order=d.id_pur_order WHERE h.is_void = 0 AND d.id_pur_order = '$_POST[no_po]'");
    $pesan = mysql_fetch_array($cek1);
    $cek2 = mysql_query("SELECT SUM(d.qty_diterima) AS terima FROM trans_lpb_detail d JOIN trans_lpb h ON h.id_lpb=d.id_lpb WHERE h.is_void = 0 AND d.id_pur_order = '$_POST[no_po]'");
    $terima = mysql_fetch_array($cek2);
    if ($terima['terima'] >= $pesan['pesan']) {
      $query2 = ("UPDATE trans_pur_order SET status_trans = '2'  WHERE  id_pur_order='$_POST[no_po]'");
      input_only_log($query2,$module);
    } else {
      $query2 = ("UPDATE trans_pur_order SET status_trans = '1'  WHERE  id_pur_order='$_POST[no_po]'");
      input_only_log($query2,$module);
    }
}
elseif ($module=='laporanbarangmasuk' AND $act=='pengiriman'){
input_only_log("INSERT INTO trans_lpb(
                                                  id_lpb,
                                                  id_pur_order,
                                                  id_supplier,
                                                  tgl_lpb,
                                                  no_nota_supplier,
                                                  status_nota,
                                                  user_update,
                                                  tgl_update)
                                                  VALUES(
                                                    '$_POST[no_lbm]',
                                                    '$_POST[no_po]',
                                                    '$_POST[supplier]',
                                                    '$_POST[tgl_lbm]',
                                                    '$_POST[no_nota_supplier]','1',
                                                    '$_SESSION[namauser]',
                                                    now()
                                                  )",$module);
input_only_log("UPDATE trans_pur_order SET status_lpb = '1'  WHERE  id_pur_order='$_POST[no_po]'",$module);

  $itemCount = count($_POST["id_lbm"]);
    $itemValues=0;
    $query = "INSERT INTO trans_lpb_detail(
                                                  id_lpb,
                                                  id_pur_order,
                                                  id_barang,
                                                  id_gudang,
                                                  qty,
                                                  qty_diterima,
                                                  qty_convert,
                                                  qty_diterima_convert,
                                                  qty_satuan,
                                                  qty_diterima_satuan,
                                                  kode_barang_po,
                                                  user,
                                                  tgl_update)
                                                VALUES ";
    $queryValue = "";
    for($i=0;$i<$itemCount;$i++) {
      if(!empty($_POST["id_lbm"]) || !empty($_POST["id_barang"][$i]) || !empty($_POST["lbr_gudang"][$i]) || !empty($_POST["jumlah_diminta"][$i])  || !empty($_POST["selisih"][$i])|| !empty($_POST["selisih"][$i])|| !empty($_POST["selisih"][$i])|| !empty($_POST["selisih"][$i])) {
        $itemValues++;
        if($queryValue!="") {
          $queryValue .= ",";
        }
        $stok_sekarang=("SELECT stok_sekarang from stok where id_barang='".$_POST["id_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lbm"][$i]."'");
        $stok_sekarang=mysql_query($stok_sekarang);
        $stok_sekarang=mysql_fetch_array($stok_sekarang);
        $jum_sat1=explode('-', $_POST["jenis_satuan"][$i]);
        $qty = explode('-',$_POST["jumlah_diminta"][$i] );
        $jum_sat= $jum_sat1[2] * $_POST["selisih"][$i] ;
        $stok_sekarang=$stok_sekarang[0]+$jum_sat;
        $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$_POST["id_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lbm"][$i]."'");
        input_only_log($qupdate,$module);
        $queryValue .= "('" . $_POST["no_lbm"] . "', '" . $_POST["no_po"] . "', '" . $_POST["id_barang"][$i] . "', '" . $_POST["gudang_lbm"][$i] . "', '" . $qty[0] . "', '" . $_POST["selisih"][$i] . "', '" . $_POST["qty_convert"][$i] . "', '" . $jum_sat . "', '" . $_POST["qty_satuan"][$i]. "', '" . $jum_sat1[1] . "', '" . $_POST["id_lbm" ] [$i]. "', '" .$_SESSION["namauser"]. "', now())";

      }
    }
    $sql = $query.$queryValue;
    echo $sql;
    if($itemValues!=0) {
     input_data($sql,$module);
      if(!empty($result)) $message = "Added Successfully.";
    }
  }
}
?>