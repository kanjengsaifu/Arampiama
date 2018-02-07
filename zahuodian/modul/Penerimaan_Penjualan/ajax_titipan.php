<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
$data = $_GET['data'];
if ($data=='supplier') {
	$query= "Select * from supplier where is_void=0";
	$data = mysql_query($query);
	 echo "<div id='hapus'><select id='id_supplier' name='id_supplier' class='chosen-select1 form-control'> ";
     while($r= mysql_fetch_array($data)){
              echo "<option value='$r[id_supplier]'>$r[nama_supplier]</option>";
          }
            echo "</select>
          <script>add_newitemcombobox('id_supplier','Supplier');</script></div>";
}elseif ($data=='supplier2') {
  $query= "Select * from supplier where is_void=0";
  $data = mysql_query($query);
   echo "<div id='hapus2'><select id='id_supplier2' name='id_supplier' class='chosen-select1 form-control'> ";
     while($r= mysql_fetch_array($data)){
              echo "<option value='$r[id_supplier]'>$r[nama_supplier]</option>";
          }
             echo "</select>

          <script>
          add_newitemcombobox('id_supplier2','Supplier');</script></div>";
}elseif ($data=='customer') {
  $query= "Select * from customer where is_void=0";
  $data = mysql_query($query);
   echo "<div id='hapus'><select id='id_customer' name='id_customer' class='chosen-select1 form-control'> ";
     while($r= mysql_fetch_array($data)){
              echo "<option value='$r[id_customer]'>$r[nama_customer]</option>";
          }
            echo "</select>
          <script>add_newitemcombobox('id_customer','customer');</script></div>";
}elseif ($data=='customer2') {
  $query= "Select * from customer where is_void=0";
  $data = mysql_query($query);
   echo "<div id='hapus2'><select id='id_customer2' name='id_customer' class='chosen-select1 form-control'> ";
     while($r= mysql_fetch_array($data)){
              echo "<option value='$r[id_customer]'>$r[nama_customer]</option>";
          }
             echo "</select>

          <script>
          add_newitemcombobox('id_customer2','customer');</script></div>";
}

        
?>