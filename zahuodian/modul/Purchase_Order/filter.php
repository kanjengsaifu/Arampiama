<?php
 include "../../config/koneksi.php";
   $supplier = $_GET['supplier'];

if(isset($supplier)){
  $sup= mysql_query("SELECT * FROM supplier WHERE id_supplier = '$supplier'  ");
     while($data = mysql_fetch_array($sup)){
    echo "<td> Alamat </td>";
    echo "<td><textarea class='form-control' disabled>".$data['alamat_supplier']."</textarea></td>";
    echo "<td> No tlp </td>";
    echo "<td><textarea class='form-control' disabled>".$data['telp1_supplier']."</textarea></td>";
     }
}
else{
	echo '
<td> Alamat </td>
    <td><textarea class="form-control" disabled></textarea></td>
    <td> No tlp </td>
    <td><textarea class="form-control" disabled></textarea></td>
  </tr>';
}
?>