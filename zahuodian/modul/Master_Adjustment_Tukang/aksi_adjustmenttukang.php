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
if ($module=='adjustmenttukang' AND $act=='hapus'){
  $select = mysql_query("SELECT * FROM adjustment_tukang WHERE id_adjustmen_tukang = '$_GET[id]'");
  $cheker = mysql_fetch_array($select);

  $return = "UPDATE stok_tukang SET stok_tukang = stok_tukang-'$cheker[plus_minus]' WHERE id_supplier = '$cheker[id_supplier]' AND id_barang = '$cheker[id_barang]'";
  //echo $return;
  input_only_log($return,$module);
                             

                              $query="UPDATE adjustment_tukang SET is_void = '1' WHERE id_adjustmen_tukang='$_GET[id]'";

}
// Input menu
elseif ($module=='adjustmenttukang' AND $act=='input'){
  
  // Input menu


$stok_sekarang=("SELECT stok_tukang FROM stok_tukang WHERE id_barang='".$_POST["id_barang"]."' and id_supplier = '" . $_POST["supplier"]."'");
        $stok_sekarang=mysql_query($stok_sekarang);
        $stok_sekarang1=mysql_fetch_array($stok_sekarang);
        $stok_sekarang=$stok_sekarang1[0]+$_POST['plus_minus'];
        $qupdate=("UPDATE stok_tukang SET stok_tukang = $stok_sekarang WHERE id_barang='".$_POST["id_barang"]."' and id_supplier = '".$_POST["supplier"]."'");
  
        input_only_log($qupdate,$module);
          $query="INSERT INTO adjustment_tukang(id_supplier,
                                 id_barang,
                                 tgl_adjustment,
                                 stok_awal,
                                 plus_minus,
                                 keterangan,
                                 user_update,
                                 tgl_update
                                 ) 
                         VALUES('$_POST[supplier]',
                                '$_POST[id_barang]',
                                '$_POST[tgl_adjustment]',
                                $stok_sekarang1[0],
                                '$_POST[plus_minus]',
                                '$_POST[keterangan]',
                                '$_SESSION[namauser]',
                                now())";


}

// Update menu
elseif ($module=='adjustmenttukang' AND $act=='update'){

$select = mysql_query("SELECT * FROM adjustment_tukang WHERE id_adjustmen_tukang = '$_POST[id]'");
$cheker = mysql_fetch_array($select);

$return = "UPDATE stok_tukang SET stok_tukang = stok_tukang-'$cheker[plus_minus]' WHERE id_supplier = '$cheker[id_supplier]' AND id_barang = '$cheker[id_barang]'";
//echo $return;
input_only_log($return,$module);
$stok_sekarang=("SELECT stok_tukang FROM stok_tukang WHERE id_barang='".$_POST["id_barang"]."' and id_supplier = '" . $_POST["supplier"]."'");
        $stok_sekarang=mysql_query($stok_sekarang);
        $stok_sekarang1=mysql_fetch_array($stok_sekarang);
        $stok_sekarang=$stok_sekarang1[0]+$_POST['plus_minus'];
        $qupdate=("UPDATE stok_tukang SET stok_tukang = $stok_sekarang WHERE id_barang='".$_POST["id_barang"]."' and id_supplier = '".$_POST["supplier"]."'");
    //echo $qupdate;
        input_only_log($qupdate,$module);
$query="UPDATE adjustment_tukang SET 
            id_supplier         = '$_POST[supplier]',
            id_barang           = '$_POST[id_barang]',
            tgl_adjustment      = '$_POST[tgl_adjustment]',
            stok_awal           = '$stok_sekarang1[0]',
            plus_minus          = '$_POST[plus_minus]',
            keterangan          = '$_POST[keterangan]',
            user_update         = '$_SESSION[namauser]',
            tgl_update          =  now()       
            WHERE id_adjustmen_tukang = '$_POST[id]'";

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
