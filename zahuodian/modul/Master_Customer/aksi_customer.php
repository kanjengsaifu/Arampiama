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
if ($module=='customer' AND $act=='hapus'){
  $query="UPDATE customer SET is_void = '1' WHERE id_customer='$_GET[id]'";
  input_data($query,$module);
}

// Input menu
elseif ($module=='customer' AND $act=='input'){

        if ((check_data('region','id_region',$_POST['region']))== 0) {
              $query="INSERT INTO region(
                                            region,
                                            user_update,
                                            tgl_update) 
                                            VALUES(
                                            '$_POST[region]',
                                            '$_SESSION[namauser]',
                                            now())";
                                              echo $query;
        input_only_log($query,$module);
        $query = mysql_query("SELECT max(id_region) as id_region from region ");
        $r= mysql_fetch_array($query);
        $id_region=$r['id_region'];
        }else{
            echo "a";
             $id_region=$_POST['region'];
        }
$kode_customer=cari_kode($_POST[kode_customer]);
  $query="INSERT INTO customer(kode_customer,
                                 nama_customer,
                                 id_region,
                                 alamat_customer,
                                 alamat_kirim,
                                 alamat_tagihan,
                                 telp_customer,
                                 telp_customer2,
                                 telp_customer3,
                                 telp_customer4,
                                 batas_limit,
                                 aging,
                                 user_update,
                                 tgl_update) 
                         VALUES('$kode_customer',
                                '$_POST[nama_customer]',
                                '$id_region',
                                '$_POST[alamat_customer]',
                                '$_POST[alamat_kirim]',
                                '$_POST[alamat_tagihan]',
                                '$_POST[telp_customer]',                                
                                '$_POST[telp_customer2]',
                                '$_POST[telp_customer3]',
                                '$_POST[telp_customer4]',
                                '$_POST[batas_limit]',
                                '$_POST[aging]',
                                '$_SESSION[namauser]',now())";
input_data($query,$module);
 
}

// Update menu
elseif ($module=='customer' AND $act=='update'){
$query = ("UPDATE customer SET 
                                                                     kode_customer    ='$_POST[kode_customer]',
                                                                     nama_customer    ='$_POST[nama_customer]',
                                                                     id_region        ='$_POST[region]',
                                                                     alamat_customer  ='$_POST[alamat_customer]',
                                                                     alamat_kirim     ='$_POST[alamat_kirim]',
                                                                     alamat_tagihan   ='$_POST[alamat_tagihan]',
                                                                     telp_customer    ='$_POST[telp_customer]',
                                                                      telp_customer2    ='$_POST[telp_customer2]',
                                                                       telp_customer3    ='$_POST[telp_customer3]',
                                                                        telp_customer4    ='$_POST[telp_customer4]',
                                                                     batas_limit      ='$_POST[batas_limit]',
                                                                     aging            ='$_POST[aging]',
                                                                     user_update      ='$_SESSION[namauser]',
                                                                     tgl_update       = now()
                                                                      WHERE id_customer = '$_POST[id]' ");
echo $query;
input_data($query,$module);
  }
  
}
function cari_kode($kode){
    $var = substr($kode,0,1);
    $sql_cari="SELECT max(`kode_customer`) as kode FROM `customer` WHERE `kode_customer` LIKE '$var%' ";
    $result=mysql_query($sql_cari);
    $hasil=mysql_fetch_array($result);
    $kode = substr( $hasil['kode'],1,9);
    $kode_ururt=100001+$kode;
   return $var.substr($kode_ururt,1);
}

?>
