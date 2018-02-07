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
if ($module=='tukanganpemberian' AND $act=='hapus'){
  $select = mysql_query("SELECT d.*, h.is_void, h.id_trans_beri_tukang_header, h.id_supplier FROM trans_beri_tukang_header h, trans_beri_tukang_detail d WHERE h.id_beri_tukang = d.id_beri_tukang AND h.id_trans_beri_tukang_header = '$_GET[id]'");
  while ($detail = mysql_fetch_array($select)) {
    mysql_query("DELETE FROM jurnal_umum WHERE kode_nota = '$detail[id_beri_tukang]'");
    $tukang = "UPDATE stok_tukang SET stok_tukang = (stok_tukang-($detail[jumlah]*$detail[kali])) WHERE id_barang = '$detail[id_barang]' AND id_supplier = '$detail[id_supplier]'";
    input_only_log($tukang, $module);
    //echo $tukang;
    $melati = "UPDATE stok SET stok_sekarang = (stok_sekarang+($detail[jumlah]*$detail[kali])) WHERE id_barang = '$detail[id_barang]' AND id_gudang = '$detail[id_gudang]'";
    input_only_log($melati, $module);
    //echo $melati;
  }
  $query = "UPDATE trans_beri_tukang_header SET is_void = 1 WHERE id_trans_beri_tukang_header = '$_GET[id]'";
  input_data($query, $module);
                                          //echo "adsad";
                                                        // input_data("UPDATE trans_pur_order set is_void=1 WHERE id='$_GET[id]'",$module);

}
// Input menu
elseif ($module=='tukanganpemberian' AND $act=='input'){
  
  $cek = mysql_num_rows(mysql_query("SELECT nonota_beri_tukang FROM `trans_beri_tukang_header` WHERE is_void = 0 AND nonota_beri_tukang = '$_POST[nonota]'"));
  if ($cek > 0 AND $_POST[nonota]!="") {
    echo "<script type='text/javascript'>alert('Terjadi Kesalahan pada Penyimpanan, Silahkan ulangi Transaksi.');</script>";
    echo ";(";
  }
  else {
    $id_beri_tukang = kode_surat('PBB', 'trans_beri_tukang_header', 'id_beri_tukang', 'id_trans_beri_tukang_header' );
    $query="INSERT INTO trans_beri_tukang_header(
                                                        id_beri_tukang,
                                                        nonota_beri_tukang,
                                                        id_supplier,
                                                        tgl_trans,
                                                        all_total,
                                                        all_discpersen,
                                                        all_discnominal,
                                                        all_ppnpersen,
                                                        all_ppnnominal,
                                                        grand_total,
                                                        user_update, 
                                                        tgl_update)
                                                        VALUES(
                                                          '$id_beri_tukang',
                                                          '$_POST[nonota]',
                                                          '$_POST[supplier2]',
                                                          '$_POST[tgl_trans]',
                                                          '$_POST[alltotal]',
                                                          '$_POST[persendisc]',
                                                          '$_POST[discalltotal]',
                                                             '$_POST[persenppn]',
                                                          '$_POST[totalppn]',
                                                          '$_POST[grandtotal]',
                                                          '$_SESSION[namauser]',
                                                          now()
                                                        )";
      input_only_log($query, $module);
      

  $itemCount = count($_POST["id_po_barang"]);
    $itemValues=0;
    $query = "INSERT INTO trans_beri_tukang_detail(
                                                id_beri_tukang,
                                                id_barang,
                                                id_gudang,
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
      if(!empty($id_beri_tukang) || !empty($_POST["id_po_barang"][$i]) || !empty($_POST["gudang_lkb"][$i]) ||!empty($_POST["jenis_satuan"][$i]) || !empty($_POST["disc1"][$i]) || !empty($_POST["disc2"][$i])  || !empty($_POST["disc3"][$i]) || !empty($_POST["disc4"][$i])|| !empty($_POST["disc5"][$i])  ||!empty($_POST["harga_sat1"][$i])  || !empty($_POST["jumlah"][$i]) || !empty($_POST["total"][$i])) {
        $itemValues++;
        if($queryValue!="") {
          $queryValue .= ",";
        }

          $stok_sekarang=("SELECT stok_sekarang from stok where id_barang='".$_POST["id_po_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");
          $stok_sekarang=mysql_query($stok_sekarang);
          $stok_sekarang=mysql_fetch_array($stok_sekarang);
          $jum_sat1=explode('-', $_POST["jenis_satuan"][$i]);
          $jum_sat= $jum_sat1[2] * $_POST["jumlah"][$i] ;
          $stok_sekarang=$stok_sekarang[0]-$jum_sat;
          $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$_POST["id_po_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");        
          input_only_log($qupdate,$module);
          
          $cektukang = mysql_query("SELECT * FROM stok_tukang WHERE id_barang = '".$_POST["id_po_barang"][$i]."' AND id_supplier = '".$_POST["supplier2"]."'");
          $catch = mysql_num_rows($cektukang);
          if ($catch >= 1) {
            $update = ("UPDATE stok_tukang SET stok_tukang = stok_tukang+$jum_sat, harga = '".($_POST["harga_sat1"][$i]/$jum_sat1[2])."' WHERE id_barang = '".$_POST["id_po_barang"][$i]."' AND id_supplier = '".$_POST["supplier2"]."'");
            input_only_log($update,$module);;
          } else {
            $insert = ("INSERT INTO stok_tukang (id_barang, id_supplier, stok_tukang, harga, user_update, tgl_update)
                        VALUES ('".$_POST["id_po_barang"][$i]."', '".$_POST["supplier2"]."', '$jum_sat', '".($_POST["harga_sat1"][$i]/$jum_sat1[2])."', '$_SESSION[namauser]', now())");
            input_only_log($insert,$module);
          }

        $temp= explode('-',$_POST["jenis_satuan"][$i]);
        $queryValue .= "('" . $id_beri_tukang . "', '" .$_POST["id_po_barang"][$i]  . "', '" .$_POST["gudang_lkb"][$i]  . "', '" . $temp[1] . "', '" . $temp[2] . "', '" .$_POST["disc1"][$i] . "', '" . $_POST["disc2"][$i] . "', '" . $_POST["disc3"][$i] . "', '" . $_POST["disc4"][$i] . "', '" . $_POST["disc5"][$i]. "', '" . $_POST["harga_sat1"][$i] . "', '" .$_POST["jumlah"][$i] . "', '" . $_POST["total"][$i] . "')";
        input_jurnal_umum_tipe_1('154','','1','',$_POST["total"][$i],$id_beri_tukang,$_POST['tgl_trans']);
        input_jurnal_umum_tipe_1('',$_POST['id_akunkasperkiraan'][$i],'1','',$_POST["total"][$i],$id_beri_tukang,$_POST['tgl_trans']);
      }
    }
    $sql = $query.$queryValue;
    if($itemValues!=0) {
      input_data($sql,$module);

    }
  }
}
elseif ($module=='tukanganpemberian' AND $act=='update'){
  $cekdetail = "SELECT d.*, h.id_supplier FROM trans_beri_tukang_detail d JOIN trans_beri_tukang_header h ON h.id_beri_tukang = d.id_beri_tukang WHERE d.id_beri_tukang = '$_POST[no_npb]'";
  $sql=mysql_query($cekdetail);
  if ($jum[id_supplier]!=$_POST['supplier']) {
    while ($jum = mysql_fetch_array($sql)) {
      $kembalitukang = "UPDATE stok_tukang SET stok_tukang = (stok_tukang-($jum[jumlah]*$jum[kali])) WHERE id_barang = '$jum[id_barang]' AND id_supplier = '$jum[id_supplier]';";
      //echo $kembalitukang;
      input_only_log($kembalitukang,$module);
      $kembaligudang = "UPDATE stok SET stok_sekarang = (stok_sekarang+($jum[jumlah]*$jum[kali])) WHERE id_barang = '$jum[id_barang]' AND id_gudang = '$jum[id_gudang]';";
      input_only_log($kembaligudang,$module);
    }
  }
    $sql = mysql_query("DELETE FROM jurnal_umum WHERE kode_nota = '$_POST[no_npb]'");
    $id_terima_tukang = $_POST['no_npb'];
  $sqlini = "UPDATE trans_beri_tukang_header SET 
                                                id_beri_tukang = '$_POST[no_npb]',
                                                nonota_beri_tukang = '$_POST[nonota]',
                                                id_supplier =  '$_POST[supplier]',
                                                tgl_trans = '$_POST[tgl_trans]',
                                                all_total = '$_POST[alltotal]',
                                                all_discpersen = '$_POST[persendisc]',
                                                all_discnominal = '$_POST[discalltotal]',
                                                all_ppnpersen = '$_POST[persenppn]',
                                                all_ppnnominal = '$_POST[totalppn]',
                                                grand_total = '$_POST[grandtotal]',
                                                  tgl_update = now(),
                                                  user_update = '$_SESSION[namauser]'
                                                  WHERE id_trans_beri_tukang_header = '$_POST[id]'";
                                            //echo $sqlini;
  input_only_log($sqlini,$module);

  input_only_log("DELETE FROM trans_beri_tukang_detail WHERE id_beri_tukang='$_POST[no_npb]'",$module);

    $itemCount = count($_POST["id_po_barang"]);
    $itemValues=0;
    $query = "INSERT INTO trans_beri_tukang_detail(
                                                id_trans_beri_tukang_detail,
                                                id_beri_tukang,
                                                id_barang,
                                                id_gudang,
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
      if(!empty($_POST["no_po"]) || !empty($_POST["id_po_barang"][$i]) || !empty($_POST["gudang_lkb"][$i]) || !empty($_POST["disc1"][$i])|| !empty($_POST["jenis_satuan"][$i]) || !empty($_POST["disc2"][$i])  || !empty($_POST["disc3"][$i]) || !empty($_POST["disc4"][$i]) || !empty($_POST["disc5"][$i])  ||  !empty($_POST["harga_sat1"][$i])  || !empty($_POST["jumlah"][$i]) || !empty($_POST["total"][$i]) || !empty($_POST["id_po"][$i])) {
        $itemValues++;
        if($queryValue!="") {
          $queryValue .= ",";
        }

        $stok_sekarang=("SELECT stok_sekarang from stok where id_barang='".$_POST["id_po_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");
          $stok_sekarang=mysql_query($stok_sekarang);
          $stok_sekarang=mysql_fetch_array($stok_sekarang);
          $jum_sat1=explode('-', $_POST["jenis_satuan"][$i]);
          $jum_sat= $jum_sat1[2] * $_POST["jumlah"][$i] ;
          $stok_sekarang=$stok_sekarang[0]-$jum_sat;
          $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$_POST["id_po_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");        
          input_only_log($qupdate,$module);
          
          $cektukang = mysql_query("SELECT * FROM stok_tukang WHERE id_barang = '".$_POST["id_po_barang"][$i]."' AND id_supplier = '".$_POST["supplier"]."'");
          $catch = mysql_num_rows($cektukang);
          if ($catch >= 1) {
            $update = ("UPDATE stok_tukang SET stok_tukang = stok_tukang+$jum_sat, harga = '".($_POST["harga_sat1"][$i]/$jum_sat1[2])."' WHERE id_barang = '".$_POST["id_po_barang"][$i]."' AND id_supplier = '".$_POST["supplier"]."'");
            input_only_log($update,$module);;
          } else {
            $insert = ("INSERT INTO stok_tukang (id_barang, id_supplier, stok_tukang, harga, user_update, tgl_update)
                        VALUES ('".$_POST["id_po_barang"][$i]."', '".$_POST["supplier"]."', '$jum_sat', '".($_POST["harga_sat1"][$i]/$jum_sat1[2])."', '$_SESSION[namauser]', now())");
            input_only_log($insert,$module);
          }

         $tempa=explode('-', $_POST["jenis_satuan"][$i]);
        $queryValue .= "('" . $_POST["id_po"][$i] . "', '" . $_POST["no_npb"] . "', '" . $_POST["id_po_barang"][$i] . "', '" . $_POST["gudang_lkb"][$i] . "', '" . $tempa[1] . "', '" . $tempa[2] . "', '" . $_POST["disc1"][$i] . "', '" . $_POST["disc2"][$i] . "', '" . $_POST["disc3"][$i] . "', '" . $_POST["disc4"][$i] . "', '" . $_POST["disc5"][$i] . "', '" .$_POST["harga_sat1"][$i] . "', '" . $_POST["jumlah"][$i] . "', '" . $_POST["total"][$i] . "' )";
        input_jurnal_umum_tipe_1('154','','1','',$_POST["total"][$i],$id_terima_tukang,$_POST['tgl_trans']);
        input_jurnal_umum_tipe_1('',$_POST['id_akunkasperkiraan'][$i],'1','',$_POST["total"][$i],$id_terima_tukang,$_POST['tgl_trans']);
      }
    }
     $queryEnd = " ON DUPLICATE KEY UPDATE id_trans_beri_tukang_detail = VALUES(id_trans_beri_tukang_detail), id_beri_tukang = VALUES(id_beri_tukang), id_barang = VALUES(id_barang), id_gudang = VALUES(id_gudang), satuan = VALUES(satuan), kali = VALUES(kali),disc1 = VALUES(disc1), disc2 = VALUES(disc2), disc3 = VALUES(disc3), disc4 = VALUES(disc4),  disc5 = VALUES(disc5),harga = VALUES(harga), jumlah = VALUES(jumlah), total = VALUES(total)";
   $sql = $query.$queryValue.$queryEnd;
    if($itemValues!=0) {
    input_data($sql,$module);

    }


}
}
?>