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
if ($module=='tukanganpenerimaan' AND $act=='hapus'){
  $select = mysql_query("SELECT d.*, h.is_void, h.id_trans_terima_tukang_header, h.id_supplier FROM trans_terima_tukang_header h, trans_terima_tukang_detail d WHERE h.id_terima_tukang = d.id_terima_tukang AND h.id_trans_terima_tukang_header = '$_GET[id]'");
  while ($detail = mysql_fetch_array($select)) {
    $melati = "UPDATE stok SET stok_sekarang = stok_sekarang-'$detail[jumlah]' WHERE id_barang = '$detail[id_barang]' AND id_gudang = '$detail[id_gudang]'";
    input_only_log($melati, $module);
  }
  $query = "UPDATE trans_terima_tukang_header SET is_void = 1 WHERE id_trans_terima_tukang_header = '$_GET[id]'";
  input_data($query, $module);
                                          //echo "adsad";
                                                        // input_data("UPDATE trans_pur_order set is_void=1 WHERE id='$_GET[id]'",$module);

}
// Input menu
elseif ($module=='hitunghpp' AND $act=='input'){
  
  $sel = mysql_query("SELECT no_hitung_hpp FROM hitung_hpp_header order by id_hitung_hpp_heder desc limit 1");
  $ect = mysql_fetch_array($sel);
  $e = kodesurat($ect['no_hitung_hpp'], THP, no_thp, no_hitung_hpp );
  $no = explode(" ", $e);
  $id = explode("'", $no[3]);
  $no_hitung_hpp = $id[1];
  echo $no_hitung_hpp;
  $query="INSERT INTO hitung_hpp_header(
                                                      no_hitung_hpp,
                                                      id_trans_terima_tukang_header,
                                                      id_barang_header,
                                                      id_supplier,
                                                      tgl_trans,
                                                      total,
                                                      jumlah,
                                                      hpp_akhir,
                                                      user_update, 
                                                      tgl_update)
                                                      VALUES(
                                                        '$no_hitung_hpp',
                                                        '$_POST[supplier]',
                                                        '$_POST[id_brg]',
                                                        '$_POST[id_tukang]',
                                                        '$_POST[tgl_trans]',
                                                        '$_POST[total]',
                                                        '$_POST[total_barang]',
                                                        '$_POST[total_hpp]',
                                                        '$_SESSION[namauser]',
                                                        now()
                                                      )";
    input_only_log($query, $module);
    //                                                  echo $query;
    $updatehpp = ("UPDATE barang SET hpp = '$_POST[total_hpp]' WHERE id_barang = '$_POST[id_brg]'");
    //echo $updatehpp;
    input_only_log($updatehpp, $module);
    $update_bar = ("UPDATE trans_terima_tukang_detail d, trans_terima_tukang_header h SET d.status = '1' WHERE d.id_terima_tukang = h.id_terima_tukang AND d.id_barang = '$_POST[id_brg]' AND h.id_supplier = '$_POST[id_tukang]' AND h.id_terima_tukang = '$_POST[supplier]'");
    //echo $update_bar;
    input_only_log($update_bar, $module);
    $sql = ("SELECT COUNT(status) AS catch, SUM(status) AS status FROM trans_terima_tukang_detail WHERE id_terima_tukang = '$_POST[supplier]'");
    echo $sql;
    $select = mysql_query($sql);
    //$catch = mysql_num_rows($select);
    $row = mysql_fetch_array($select);
    echo " = ";
    if ($row['catch'] == $row['status']) {
      $update = ("UPDATE trans_terima_tukang_header SET status = '1' WHERE id_terima_tukang = '$_POST[supplier]'");
    } else {
      $update = ("UPDATE trans_terima_tukang_header SET status = '0' WHERE id_terima_tukang = '$_POST[supplier]'");
    }
    //echo $update;
    input_only_log($update, $module);
    
  $itungBarang = count($_POST["id_barang"]);
  for ($h=0; $h < $itungBarang; $h++) { 
    if (($_POST['id_barang'][$h])!="") {
      $cektukang = mysql_query("SELECT * FROM stok_tukang WHERE id_barang = '".$_POST["id_barang"][$h]."' AND id_supplier = '".$_POST["id_tukang"]."'");
      $catch = mysql_num_rows($cektukang);
      if ($catch >= 1) {
        $query = ("UPDATE stok_tukang SET stok_tukang = stok_tukang-'".$_POST["jumlah"][$h]."' WHERE id_stoktukang = '".$_POST["id_barang"][$h]."' AND id_supplier = '".$_POST["id_tukang"]."'");
        input_only_log($query, $module);
      } else {
        $query = ("INSERT INTO stok_tukang (id_barang, id_supplier, stok_tukang, harga, user_update, tgl_update) 
                  VALUES ('".$_POST["id_barang"][$h]."', '".$_POST["id_tukang"]."', (0-'".$_POST["jumlah"][$h]."'), '".$_POST["hpp"][$h]."', '$_SESSION[namauser]', now())");
        input_only_log($query, $module);
      }
    }
    
  } 

  $itemCount = count($_POST["nama_barang"]);
  $itemValues=0;
  $query = "INSERT INTO hitung_hpp_detail(
                                              no_hitung_hpp,
                                              nama_barang,
                                              jumlah_barang,
                                              harga_barang,
                                              total_biaya)
                                              VALUES ";
  $queryValue = "";
  for($i=0;$i<$itemCount;$i++) {
    if(!empty($_POST["no_thp"]) || !empty($_POST["nama_barang"][$i]) || !empty($_POST["jumlah"][$i]) ||!empty($_POST["hpp"][$i]) || !empty($_POST["harga"][$i])) {
      $itemValues++;
      if($queryValue!="") {
        $queryValue .= ",";
      }

      $queryValue .= "('" . $no_hitung_hpp . "', '" .$_POST["nama_barang"][$i]  . "', '" .$_POST["jumlah"][$i]  . "', '" .$_POST["hpp"][$i] . "', '" . $_POST["harga"][$i] . "')";
    }
  }
  $sql = $query.$queryValue;
  if($itemValues!=0) {
    input_and_print($sql,$module,$_POST["no_npb"] );
    //echo $sql;
  }
}
elseif ($module=='tukanganpenerimaan' AND $act=='update'){
  $sqlini = "UPDATE trans_terima_tukang_header SET 
                                                id_terima_tukang = '$_POST[no_npb]',
                                                nonota_terima_tukang = '$_POST[nonota]',
                                                id_supplier =  '$_POST[supplier]',
                                                tgl_trans = '$_POST[tgl_trans]',
                                                  tgl_update = now(),
                                                  user_update = '$_SESSION[namauser]'
                                                  WHERE id_trans_terima_tukang_header = '$_POST[id]'";
  //                                          echo $sqlini;
  input_only_log($sqlini,$module);
$cekdetail = "SELECT * FROM trans_terima_tukang_detail WHERE id_terima_tukang = '$_POST[no_npb]'";
$sql=mysql_query($cekdetail);
while ($jum = mysql_fetch_array($sql)) {
  $kembaligudang = "UPDATE stok SET stok_sekarang = stok_sekarang-'$jum[jumlah]' WHERE id_barang = '$jum[id_barang]' AND id_gudang = '$jum[id_gudang]'";
  //echo $kembaligudang;
  input_only_log($kembaligudang,$module);
}
  input_only_log("DELETE FROM trans_terima_tukang_detail WHERE id_terima_tukang = '$_POST[no_npb]'",$module);
//
    $itemCount = count($_POST["id_po_barang"]);
    $itemValues=0;
    $query = "INSERT INTO trans_terima_tukang_detail(
                                                id_trans_terima_tukang_detail,
                                                id_terima_tukang,
                                                id_barang,
                                                id_gudang,
                                                satuan,
                                                kali,
                                                jumlah,
                                                biaya_tukang)
                                                VALUES ";

    $queryValue = "";
    for($i=0;$i<$itemCount;$i++) {
      if(!empty($_POST["id_po"]) || !empty($_POST["id_po_barang"][$i]) || !empty($_POST["gudang_lkb"][$i]) || !empty($_POST["disc1"][$i])|| !empty($_POST["jenis_satuan"][$i]) || !empty($_POST["jumlah"][$i]) || !empty($_POST["biaya_tukang"][$i])) {
        $itemValues++;
        if($queryValue!="") {
          $queryValue .= ",";
        }

        $stok_sekarang=("SELECT stok_sekarang from stok where id_barang='".$_POST["id_po_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");
         $stok_sekarang=mysql_query($stok_sekarang);
          $stok_sekarang=mysql_fetch_array($stok_sekarang);
          $jum_sat1=explode('-', $_POST["jenis_satuan"][$i]);
          $jum_sat= $jum_sat1[2] * $_POST["jumlah"][$i] ;
          $stok_sekarang=$stok_sekarang[0]+$jum_sat;
        $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$_POST["id_po_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");
        input_only_log($qupdate,$module);
        //echo $qupdate;

         $tempa=explode('-', $_POST["jenis_satuan"][$i]);
        $queryValue .= "('" . $_POST["id_po"][$i] . "', '" . $_POST["no_npb"] . "', '" . $_POST["id_po_barang"][$i] . "', '" . $_POST["gudang_lkb"][$i] . "', '" . $tempa[1] . "', '" . $tempa[2] . "', '" . $_POST["jumlah"][$i] . "', '" . $_POST["biaya_tukang"][$i] . "' )";
      }
    }
     $queryEnd = " ON DUPLICATE KEY UPDATE id_trans_terima_tukang_detail = VALUES(id_trans_terima_tukang_detail), id_terima_tukang = VALUES(id_terima_tukang), id_barang = VALUES(id_barang), id_gudang = VALUES(id_gudang), satuan = VALUES(satuan), kali = VALUES(kali), jumlah = VALUES(jumlah), biaya_tukang = VALUES(biaya_tukang)";
   $sql = $query.$queryValue.$queryEnd;
    if($itemValues!=0) {
    input_data($sql,$module);
    //echo $sql;
    }


}
}
?>