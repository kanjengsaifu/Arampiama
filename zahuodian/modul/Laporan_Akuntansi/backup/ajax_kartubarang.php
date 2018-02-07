<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
$data = $_GET['data'];
$query= "select id_barang,satuan1 as satuan,kali1 as kali  from (SELECT id_barang,satuan1,kali1 FROM barang 
union all
SELECT id_barang,satuan2,kali2 FROM barang 
union all
SELECT id_barang,satuan3,kali3 FROM barang 
union all
SELECT id_barang,satuan4,kali4 FROM barang 
union all
SELECT id_barang,satuan5,kali5 FROM barang) data_satuan where id_barang='$data'";
$data = mysql_query($query);
	 echo "<select id='satuan' name='satuan' class='chosen-select1 form-control'> ";
     while($r= mysql_fetch_array($data)){
              echo "<option value='$r[kali]#$r[satuan]'>$r[satuan]</option>";
          }
          echo "</select>
          <script>add_newitemcombobox('satuan','satuan');</script>";
?>