<?php
 include "../../config/koneksi.php";
   $customer = $_GET['customer'];
if(isset($customer)){
  $sup= mysql_query("SELECT * FROM customer WHERE id_customer = '$customer'  ");
     $data = mysql_fetch_array($sup);
    echo $data['alamat_customer'].'@'.$data['telp_customer'];
} else{
	echo '
<td> Alamat </td>
    <td><textarea disabled></textarea></td>
    <td> No tlp </td>
    <td><textarea disabled></textarea></td>
  </tr>';
}
?>