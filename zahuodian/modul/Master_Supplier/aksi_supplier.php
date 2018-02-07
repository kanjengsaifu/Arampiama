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
if ($module=='supplier' AND $act=='hapus'){
                                                                    $query="UPDATE supplier SET is_void = '1' WHERE id_supplier='$_GET[id]'";

}

// Input menu
elseif ($module=='supplier' AND $act=='input'){
  $kode_supplier=$_POST[kode_supplier];
  $query="INSERT INTO supplier(kode_supplier,
                                 nama_supplier,
                                 id_region,
                                 alamat_supplier,
                                 telp1_supplier,
                                 telp2_supplier,
                                 fax_supplier,
                                 nama_sales,
                                 telp1_sales,
                                 telp2_sales,
                                 batas_limit,
                                 aging,
                                 jenis,
                                 user_update,
                                 tgl_update) 
                         VALUES('$kode_supplier',
                                '$_POST[nama_supplier]',
                                '$_POST[region]',
                                '$_POST[alamat_supplier]',
                                '$_POST[telp1_supplier]',
                                '$_POST[telp2_supplier]',
                                '$_POST[fax_supplier]',
                                '$_POST[nama_sales]',
                                '$_POST[telp1_sales]',
                                '$_POST[telp2_sales]',
                                '$_POST[batas_limit]',
                                '$_POST[aging]',
                                '$_POST[jenis]',
                                '$_SESSION[namauser]',
                                now())";
 
}

// Update menu
elseif ($module=='supplier' AND $act=='update'){
$query="UPDATE supplier SET 
                                                                     nama_supplier     ='$_POST[nama_supplier]',
                                                                     id_region         ='$_POST[region]',
                                                                     jenis             ='$_POST[jenis]',
                                                                     alamat_supplier   ='$_POST[alamat_supplier]',
                                                                     telp1_supplier    ='$_POST[telp1_supplier]',
                                                                     telp2_supplier    ='$_POST[telp2_supplier]',
                                                                     fax_supplier      ='$_POST[fax_supplier]',
                                                                     nama_sales        ='$_POST[nama_sales]',
                                                                     telp1_sales       ='$_POST[telp1_sales]',
                                                                     telp2_sales       ='$_POST[telp2_sales]',
                                                                     batas_limit       ='$_POST[batas_limit]',
                                                                     aging             ='$_POST[aging]',
                                                                     user_update       ='$_SESSION[namauser]',
                                                                     tgl_update        =  now()       
                                                                      WHERE id_supplier = '$_POST[id]' ";

  }
  input_data($query,$module);
}
function cari_kode($kode){
    $var = substr($kode,0,1);
    $sql_cari="SELECT max(`kode_supplier`) as kode FROM `supplier` WHERE `kode_supplier` LIKE '$var%' ";
    $result=mysql_query($sql_cari);
    $hasil=mysql_fetch_array($result);
    $kode = substr( $hasil['kode'],1,6);
    $kode_ururt=1000+1+$kode;
   return $var.substr($kode_ururt,1);
}
?>
