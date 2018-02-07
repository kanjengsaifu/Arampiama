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
elseif ($module=='totalantukangan' AND $act=='input'){
  
  $sel = mysql_query("SELECT * FROM trans_totalan_tukang ORDER BY id_totalan_tukang DESC LIMIT 1");
  $ect = mysql_fetch_array($sel);
  $e = kodesurat($ect['no_totalan_tukang'], NTT, no_ntt, no_totalan_tukang );
  $no = explode(" ", $e);
  $id = explode("'", $no[3]);
  $no_totalan_tukang = $id[1];
  echo $no_totalan_tukang;
  $query="INSERT INTO trans_totalan_tukang(
                                                      no_totalan_tukang,
                                                      id_supplier,
                                                      tgl_totalan,
                                                      nominal_totalan,
                                                      ket,
                                                      user_update, 
                                                      tgl_update)
                                                      VALUES(
                                                        '$no_totalan_tukang',
                                                        '$_POST[id_gudang_tujuan]',
                                                        '$_POST[tanggal]',
                                                        '$_POST[grandtotal]',
                                                        '$_POST[keterangan]',
                                                        '$_SESSION[namauser]',
                                                        now()
                                                      )";
    input_only_log($query, $module);
    //                                                  echo $query;

  $itemCount = count($_POST["id_trans"]);
  $itemValues=0;
  $query = "INSERT INTO trans_totalan_tukang_detail(
                                              no_totalan_tukang,
                                              no_transaksi,
                                              nama,
                                              jumlah,
                                              harga,
                                              total)
                                              VALUES ";
  $queryValue = "";
  for($i=0;$i<$itemCount;$i++) {
    if(!empty($_POST["no_totalan_tukang"]) || !empty($_POST["no_trans"][$i]) || !empty($_POST["nama"][$i]) ||!empty($_POST["jumlah"][$i])||!empty($_POST["harga"][$i]) || !empty($_POST["total"][$i])) {
      $itemValues++;
      if($queryValue!="") {
        $queryValue .= ",";
      }
      if (($_POST['jenis'][$i]) == 'TBT') {
        $qupdate = ("UPDATE bon_tukang SET status_terbayar = 1 WHERE id_bontukang = '".$_POST["id_trans"][$i]."'");
        //echo $qupdate;
        input_only_log($qupdate, $module);
      } else if (($_POST['jenis'][$i]) == 'THP') {
        $qupdate = ("UPDATE hitung_hpp_header SET status = 1 WHERE id_hitung_hpp_heder = '".$_POST["id_trans"][$i]."'");
        //echo $qupdate;
        input_only_log($qupdate, $module);
      }

      $queryValue .= "('" . $no_totalan_tukang . "', '" .$_POST["no_trans"][$i]  . "', '" .$_POST["nama"][$i]  . "', '" .$_POST["jumlah"][$i] . "', '" .$_POST["harga"][$i] . "', '" . $_POST["total"][$i] . "')";
    }
  }
  $sql = $query.$queryValue;
  if($itemValues!=0) {
    input_and_print($sql,$module,$_POST["no_totalan_tukang"] );
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