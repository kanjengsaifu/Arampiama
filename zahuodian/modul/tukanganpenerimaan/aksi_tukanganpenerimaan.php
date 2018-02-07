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

  $sel = mysql_query("SELECT status FROM trans_terima_tukang_header WHERE id_trans_terima_tukang_header = '$_GET[id]'");
  $ect = mysql_fetch_array($sel);
  if ($ect['status']==1) {
    header('location:../../media.php?module='.$module.'&act=detail&id='.$_GET['id']);
  } else {
  $select = mysql_query("SELECT d.*, h.is_void, h.id_trans_terima_tukang_header, h.id_supplier FROM trans_terima_tukang_header h, trans_terima_tukang_detail d WHERE h.id_terima_tukang = d.id_terima_tukang AND h.id_trans_terima_tukang_header = '$_GET[id]'");
  while ($detail = mysql_fetch_array($select)) {
    mysql_query("DELETE FROM jurnal_umum WHERE kode_nota = '$detail[id_terima_tukang]'");
    $melati = "UPDATE stok SET stok_sekarang = (stok_sekarang-($detail[jumlah]*$detail[kali])) WHERE id_barang = '$detail[id_barang]' AND id_gudang = '$detail[id_gudang]'";
    input_only_log($melati, $module);
  }
  $query = "UPDATE trans_terima_tukang_header SET is_void = 1 WHERE id_trans_terima_tukang_header = '$_GET[id]'";
  input_data($query, $module);
  }
                                          //echo "adsad";
                                                        // input_data("UPDATE trans_pur_order set is_void=1 WHERE id='$_GET[id]'",$module);

}
// Input menu
elseif ($module=='tukanganpenerimaan' AND $act=='input'){
  
  $cek = mysql_num_rows(mysql_query("SELECT nonota_terima_tukang FROM trans_terima_tukang_header WHERE is_void = 0 AND nonota_terima_tukang = '$_POST[nonota]'"));
  if ($cek > 0 AND $_POST[nonota]!="") {
    echo "<script type='text/javascript'>alert('Terjadi Kesalahan pada Penyimpanan, Silahkan ulangi Transaksi.');</script>";
    echo "<a href=javascript:history.go(-1)>Kembali</a> ;(";
  }
  else {
    $id_terima_tukang = kode_surat('PBJ', 'trans_terima_tukang_header', 'id_terima_tukang', 'id_trans_terima_tukang_header' );
    $query="INSERT INTO trans_terima_tukang_header(
                                                        id_terima_tukang,
                                                        nonota_terima_tukang,
                                                        id_supplier,
                                                        tgl_trans,
                                                        grandtotal,
                                                        user_update, 
                                                        tgl_update)
                                                        VALUES(
                                                          '$id_terima_tukang',
                                                          '$_POST[nonota]',
                                                          '$_POST[supplier2]',
                                                          '$_POST[tgl_trans]',
                                                          '$_POST[alltotal]',
                                                          '$_SESSION[namauser]',
                                                          now()
                                                        )";
      input_only_log($query, $module);
      //                                                  echo $query;
      
  $itemCount = count($_POST["id_npj_barang"]);
    $itemValues=0;
    $query = "INSERT INTO trans_terima_tukang_detail(
                                                id_terima_tukang,
                                                id_barang,
                                                id_gudang,
                                                satuan,
                                                kali,
                                                harga,
                                                jumlah,
                                                total)
                                                VALUES ";
    $queryValue = "";
    for($i=0;$i<$itemCount;$i++) {
      if(!empty($id_terima_tukang) || !empty($_POST["id_npj_barang"][$i]) || !empty($_POST["gudang_lkb"][$i]) ||!empty($_POST["jenis_satuan"][$i]) || !empty($_POST["harga_sat1"][$i]) || !empty($_POST["jumlah"][$i]) || !empty($_POST["total"][$i])) {
        $itemValues++;
        if($queryValue!="") {
          $queryValue .= ",";
        }

        $stok_sekarang=("SELECT stok_sekarang from stok where id_barang='".$_POST["id_npj_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");
         $stok_sekarang=mysql_query($stok_sekarang);
          $stok_sekarang=mysql_fetch_array($stok_sekarang);
          $jum_sat1=explode('-', $_POST["jenis_satuan"][$i]);
          $jum_sat= $jum_sat1[2] * $_POST["jumlah"][$i] ;
          $stok_sekarang=$stok_sekarang[0]+$jum_sat;
        $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$_POST["id_npj_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");
        input_only_log($qupdate,$module);
        //echo $qupdate;
        $hpp=("UPDATE barang SET hpp = '".($_POST["harga_sat1"][$i])/$jum_sat1[2]."' WHERE id_barang='".$_POST["id_npj_barang"][$i]."'");
        input_only_log($hpp,$module);
        //echo $hpp;
        $temp= explode('-',$_POST["jenis_satuan"][$i]);
        $queryValue .= "('" . $id_terima_tukang . "', '" .$_POST["id_npj_barang"][$i]  . "', '" .$_POST["gudang_lkb"][$i]  . "', '" . $temp[1] . "', '" . $temp[2] . "', '" .$_POST["harga_sat1"][$i] . "', '" .$_POST["jumlah"][$i] . "', '" . $_POST["total"][$i] . "')";
        input_jurnal_umum_tipe_1($_POST['id_akunkasperkiraan'][$i],'','1','',$_POST["total"][$i],$id_terima_tukang,$_POST['tgl_trans'],$_POST['supplier2'],'supplier','',$id_terima_tukang);
        input_jurnal_umum_tipe_1('','71','1','',$_POST["total"][$i],$id_terima_tukang,$_POST['tgl_trans'],$_POST['supplier2'],'supplier','',$id_terima_tukang);
      }
    }
    $sql = $query.$queryValue;
    if($itemValues!=0) {
      input_data($sql,$module);
    }
  }
}
elseif ($module=='tukanganpenerimaan' AND $act=='update'){
  $cekdetail = "SELECT * FROM trans_terima_tukang_detail WHERE id_terima_tukang = '$_POST[no_npb]'";
  $sql=mysql_query($cekdetail);
  while ($jum = mysql_fetch_array($sql)) {
    $kembaligudang = "UPDATE stok SET stok_sekarang = (stok_sekarang-($jum[jumlah]*$jum[kali])) WHERE id_barang = '$jum[id_barang]' AND id_gudang = '$jum[id_gudang]'";
    //echo $kembaligudang;
    input_only_log($kembaligudang,$module);
  }
  mysql_query("DELETE FROM jurnal_umum WHERE kode_nota = '$_POST[no_npb]'");
  $id_terima_tukang = $_POST['no_npb'];
  $sqlini = "UPDATE trans_terima_tukang_header SET 
                                                id_terima_tukang = '$_POST[no_npb]',
                                                nonota_terima_tukang = '$_POST[nonota]',
                                                id_supplier =  '$_POST[supplier]',
                                                tgl_trans = '$_POST[tgl_trans]',
                                                grandtotal = '$_POST[alltotal]',
                                                  tgl_update = now(),
                                                  user_update = '$_SESSION[namauser]'
                                                  WHERE id_trans_terima_tukang_header = '$_POST[idpbj]'";
  //                                          echo $sqlini;
  input_only_log($sqlini,$module);
  input_only_log("DELETE FROM trans_terima_tukang_detail WHERE id_terima_tukang = '$_POST[no_npb]'",$module);
//
    $itemCount = count($_POST["id_npj_barang"]);
    $itemValues=0;
    $query = "INSERT INTO trans_terima_tukang_detail(
                                                id_trans_terima_tukang_detail,
                                                id_terima_tukang,
                                                id_barang,
                                                id_gudang,
                                                satuan,
                                                kali,
                                                harga,
                                                jumlah,
                                                total)
                                                VALUES ";

    $queryValue = "";
    for($i=0;$i<$itemCount;$i++) { 
      if(!empty($_POST["id_po"]) || !empty($_POST["id_npj_barang"][$i]) || !empty($_POST["gudang_lkb"][$i]) || !empty($_POST["disc1"][$i])|| !empty($_POST["jenis_satuan"][$i]) || !empty($_POST["jumlah"][$i]) || !empty($_POST["biaya_tukang"][$i])) {
        $itemValues++;
        if($queryValue!="") {
          $queryValue .= ",";
        }

        $stok_sekarang=("SELECT stok_sekarang from stok where id_barang='".$_POST["id_npj_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");
         $stok_sekarang=mysql_query($stok_sekarang);
          $stok_sekarang=mysql_fetch_array($stok_sekarang);
          $jum_sat1=explode('-', $_POST["jenis_satuan"][$i]);
          $jum_sat= $jum_sat1[2] * $_POST["jumlah"][$i] ;
          $stok_sekarang=$stok_sekarang[0]+$jum_sat;
        $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$_POST["id_npj_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");
        input_only_log($qupdate,$module);
        //echo $qupdate;

         $tempa=explode('-', $_POST["jenis_satuan"][$i]);
        $queryValue .= "('" . $_POST["id_po"][$i] . "', '" . $_POST["no_npb"] . "', '" . $_POST["id_npj_barang"][$i] . "', '" . $_POST["gudang_lkb"][$i] . "', '" . $tempa[1] . "', '" . $tempa[2] . "', '" . $_POST["harga_sat1"][$i] . "', '" . $_POST["jumlah"][$i] . "', '" . $_POST["total"][$i] . "' )";
input_jurnal_umum_tipe_1($_POST['id_akunkasperkiraan'][$i],'','1','',$_POST["total"][$i],$id_terima_tukang,$_POST['tgl_trans'],$_POST['supplier'],'supplier','',$id_terima_tukang);
input_jurnal_umum_tipe_1('','71','1','',$_POST["total"][$i],$id_terima_tukang,$_POST['tgl_trans'],$_POST['supplier'],'supplier','',$id_terima_tukang);
      }
    }
     $queryEnd = " ON DUPLICATE KEY UPDATE id_trans_terima_tukang_detail = VALUES(id_trans_terima_tukang_detail), id_terima_tukang = VALUES(id_terima_tukang), id_barang = VALUES(id_barang), id_gudang = VALUES(id_gudang), satuan = VALUES(satuan), kali = VALUES(kali), harga = VALUES(harga), jumlah = VALUES(jumlah), total = VALUES(total)";
   $sql = $query.$queryValue.$queryEnd;
    if($itemValues!=0) {
    input_data($sql,$module);
    //echo $sql;
    }


}
}
?>

