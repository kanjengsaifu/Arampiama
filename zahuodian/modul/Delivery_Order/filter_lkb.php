<?php
 include "../../config/koneksi.php";
   $customer = $_GET['customer'];

if(isset($customer)){
  $sup= mysql_query("SELECT * FROM customer WHERE id_customer = '$customer'  ");
     while($data = mysql_fetch_array($sup)){
    echo "<td><textarea disabled>".$data['alamat_customer']."</textarea></td>";
    
     }
}
else{
	echo '
<td> Alamat </td>
    <td><textarea disabled></textarea></td>
    <td> No tlp </td>
    <td><textarea disabled></textarea></td>
  </tr>';
}
?>