  <?php
 include "../../config/koneksi.php";
 $filter = $_GET['kd'];
  $gudang = $_GET['kd2'];
  $noz= $_GET['nox'];
  $gdg2= $_GET['gdg2'];

 $gudang_asal= mysql_query("SELECT * FROM stok s,barang b,gudang g WHERE g.id_gudang=s.id_gudang and s.id_barang=b.id_barang and b.id_barang = '$filter' and s.id_gudang='$gudang'  ");
  $data_asal = mysql_fetch_array($gudang_asal);
   $gudang_tujuan= mysql_query("SELECT * FROM stok s,barang b WHERE s.id_barang=b.id_barang and b.id_barang = '$filter' and id_gudang='$gdg2' ");
  $data_tujuan = mysql_fetch_array($gudang_tujuan);
   ?>
  <tr class="inputtable">
  <input type=hidden name="id_barang_asal[]" value="<?php echo $data_asal['id_barang']?>"  id="id_barang_asal-<?php echo $noz; ?>" >
    <input type=hidden name="id[]" value="" id="id-<?php echo $noz; ?>" >
	<td>
	     <input type="text" name="kode_barang_asal[]" value="<?php echo $data_asal['kode_barang']?>"   id="kode_barang_asal-<?php echo $noz; ?>"  disabled />
	  </td>
	  <td>
	     <input type="text" name="nama_barang_asal[]" value="<?php echo $data_asal['nama_barang']?>" id="nama_barang_asal-<?php echo $noz; ?>"  disabled />
	  </td>
    <td>
    <input type="hidden" name="id_gudang_asal[]" value="<?php echo $data_asal['id_gudang']?>" id="id_gudang_asal-<?php echo $noz; ?>"  />
       <input type="text" name="nama_gudang_asal[]" value="<?php echo $data_asal['nama_gudang']?>" id="nama_gudang_asal-<?php echo $noz; ?>"  disabled />
    </td>
	 <td>
	     <input type="text" name="stok_sekarang_asal[]" value="<?php echo $data_asal['stok_sekarang']?>"  id="stok_sekarang_asal-<?php echo $noz; ?>"  class="hitung" readonly/>
	  </td>
   <td>
       <input type="text" name="stok_sekarang_tujuan[]" value="<?php echo $data_tujuan['stok_sekarang']?>"  id="stok_sekarang_tujuan-<?php echo $noz; ?>"  class="hitung" readonly />
    </td>
    <td><input type="text" name="transfer[]" id="transfer-<?php echo $noz; ?>"  class="hitung" /></td>
        <td>
       <input type="text" name="total_asal[]" value="<?php echo $data_asal['stok_sekarang']?>"  id="total_asal-<?php echo $noz; ?>"  class="hitung" readonly/>
    </td>
    <td>
       <input type="text" name="total_tujuan[]" value="<?php echo $data_tujuan['stok_sekarang']?>"  id="total_tujuan-<?php echo $noz; ?>"  class="hitung" readonly/>
    </td>

  <td>
    <div class="btn btn-primary" type="button" id="search" data-toggle="modal" data-target="#search-md"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></div>
  <div  class="btn btn-danger" name="del_item" onclick="deleteRow(this)"><span class='glyphicon glyphicon-trash'></span></div>
  </td>
</tr>
