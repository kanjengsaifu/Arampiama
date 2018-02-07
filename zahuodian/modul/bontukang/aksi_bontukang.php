<?php
session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "<link href='style.css' rel='stylesheet' type='text/css'>
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
include "../../config/koneksi.php";
include "../../lib/input.php";

$module=$_GET['module'];
$act=$_GET['act'];

// Hapus modul
if ($module=='bontukang' AND $act=='hapus'){
                             

                              $query="UPDATE bon_tukang SET is_void = '1' WHERE id_bon_tukang='$_GET[id]'";

}
// Input menu
elseif ($module=='bontukang' AND $act=='input'){
  $cek = mysql_num_rows(mysql_query("SELECT no_bukti FROM bon_tukang WHERE is_void = 0 AND no_bukti = '$_POST[no_bukti]'"));
  if ($cek > 0) {
  echo "<script type='text/javascript'>alert('Terjadi Kesalahan pada Penyimpanan, Silahkan ulangi Transaksi.');</script>";
  echo "<a href=javascript:history.go(-1)>Kembali</a> ;(";
  }
  else {
      $sel = mysql_query("SELECT no_bon_tukang FROM bon_tukang order by id_bon_tukang desc limit 1");
      $ect = mysql_fetch_array($sel);
      $e = kodesurat($ect['no_bon_tukang'], TBT, no_tbt, no_bon_tukang );
      $no = explode(" ", $e);
      $id = explode("'", $no[3]);
      $no_bon_tukang = $id[1];
        $query="INSERT INTO bon_tukang(
                              no_bon_tukang,
                              id_supplier,
                               tgl_trans,
                               nominal,
                               no_bukti,
                               keterangan,
                               user_update,
                               tgl_update
                               ) 
                       VALUES('$no_bon_tukang',
                              '$_POST[supplier]',
                              '$_POST[tanggalbon]',
                              '$_POST[nominal]',
                              '$_POST[no_bukti]',
                              '$_POST[keterangan]',
                              '$_SESSION[namauser]',
                              now())";

  }
}

// Update menu
elseif ($module=='bontukang' AND $act=='update'){

$query="UPDATE bon_tukang SET 
            no_bon_tukang       = '$_POST[no_tbt]',
            id_supplier         = '$_POST[supplier]',
            tgl_trans           = '$_POST[tanggalbon]',
            nominal             = '$_POST[nominal]',
            no_bukti            = '$_POST[no_bukti]',
            keterangan          = '$_POST[keterangan]',
            user_update         = '$_SESSION[namauser]',
            tgl_update          =  now()       
            WHERE id_bon_tukang = '$_POST[id]'";

  //echo $query;

  }
    
     // $edit = mysql_query("SELECT * FROM users WHERE username='admin2222'");
    //$r    = mysql_fetch_array($edit);
   // $tamp =$r['level'];
    // $_arrNilai = explode(',', $tamp);
                   
  //echo $_arrNilai[3];
  //echo $lvl;
 input_data($query,$module);
}
?>
