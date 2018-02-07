<?php
 include "../../config/koneksi.php";
   $supplier = $_GET['supplier'];

if(isset($supplier)){
  $sup= mysql_query("SELECT * FROM supplier WHERE id_supplier = '$supplier'  ");
     while($data = mysql_fetch_array($sup)){
    echo "<td><textarea disabled>".$data['alamat_supplier']."</textarea></td>";
    
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