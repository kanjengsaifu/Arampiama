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
if ($module=='adjustment' AND $act=='hapus'){
                              $adjustment=("SELECT id_barang,id_gudang,plusminus_barang from adjustment_stok where id_adjustment='$_GET[id]'");
                              $adjustment=mysql_query($adjustment);
                              $adj=mysql_fetch_array($adjustment);

                              $stok_sekarang=("SELECT stok_sekarang from stok where id_barang='".$adj[0]."' and id_gudang = '" . $adj[1]."'");
                              $stok_sekarang=mysql_query($stok_sekarang);
                              $stok_sekarang1=mysql_fetch_array($stok_sekarang);
                              $stok_sekarang=$stok_sekarang1[0]-$adj[2];
                              $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$adj[0]."' and id_gudang = '" . $adj[1]."'");
                              input_only_log($qupdate,$module);

                              $query="UPDATE adjustment_stok SET is_void = '1' WHERE id_adjustment='$_GET[id]'";

}
// Input menu
elseif ($module=='adjustment' AND $act=='input'){
  
  // Input menu


$stok_sekarang=("SELECT stok_sekarang from stok where id_barang='".$_POST["barang"]."' and id_gudang = '" . $_POST["gudang"]."'");
        $stok_sekarang=mysql_query($stok_sekarang);
        $stok_sekarang1=mysql_fetch_array($stok_sekarang);
        $stok_sekarang=$stok_sekarang1[0]+$_POST['plusminus_barang'];
        $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$_POST["barang"]."' and id_gudang = '" . $_POST["gudang"]."'");
  
        input_only_log($qupdate,$module);
          $query="INSERT INTO adjustment_stok(id_gudang,
                                 id_barang,
                                 tgl_adjustment,
                                 stok_awal,
                                 plusminus_barang,
                                 keterangan,
                                 user_update,
                                 tgl_update
                                 ) 
                         VALUES('$_POST[gudang]',
                                '$_POST[barang]',
                                '$_POST[tgl_adjustment]',
                                $stok_sekarang1[0],
                                '$_POST[plusminus_barang]',
                                '$_POST[keterangan]',
                                '$_SESSION[namauser]',
                                now())";


}

// Update menu
elseif ($module=='adjustment' AND $act=='update'){

  $stok_sekarang=("SELECT stok_sekarang from stok where id_barang='".$_POST["barang"]."' and id_gudang = '" . $_POST["gudang"]."'");
        $stok_sekarang=mysql_query($stok_sekarang);
        $stok_sekarang1=mysql_fetch_array($stok_sekarang);
        $stok_sekarang=$stok_sekarang1[0]+$_POST['plusminus_barang']-$_POST['plusminus_barang_awal'];
        $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$_POST["barang"]."' and id_gudang = '" . $_POST["gudang"]."'");

        input_only_log($qupdate,$module);
$query="UPDATE adjustment_stok SET 
                                                                            id_gudang           ='$_POST[gudang]',
                                                                            id_barang           ='$_POST[barang]',
                                                                            tgl_adjustment      ='$_POST[tgl_adjustment]',
                                                                            plusminus_barang    ='$_POST[plusminus_barang]',
                                                                            keterangan          ='$_POST[keterangan]',
                                                                            user_update       ='$_SESSION[namauser]',
                                                                            tgl_update        =  now()       
                                                                            WHERE id_adjustment = '$_POST[id]'";

               

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
