<?php
 include "../../config/koneksi.php";
   $customer = $_GET['customer'];
if(isset($customer)){
  $sup= mysql_query("SELECT * FROM customer WHERE id_customer = '$customer'  ");
     while($data = mysql_fetch_array($sup)){
    echo $data['alamat_customer'];
     }
}
?>