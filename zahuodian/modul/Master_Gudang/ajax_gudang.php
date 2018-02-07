<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
 if(isset($_GET['data'])){
  	$not = $_GET['data'];
	 $aksi="modul/gudang/aksi_gudang.php";
    $edit = mysql_query("SELECT * FROM gudang WHERE id_gudang='$not'");
    $r    = mysql_fetch_array($edit);
	}

  echo "
 <form method=POST action=$aksi?module=gudang&act=update>
    <table class='table table-hover'>
    <tr>
    <td>Nama gudang</td> 
    <td><input class='hidden' type='text'  name='id' value='$r[id_gudang]'/></td>
    <td><input type='text' name='gudang'  class='form-control'  value='$r[nama_gudang]'/></br>";
    if ($r['status_gudang']=='1') {
      echo " <input type=checkbox name='gudang_luar' checked>Gudang Luar";
    }
    else {
      echo " <input type=checkbox  name='gudang_luar'>Gudang Luar";
    }
   echo "
    </td>
    </tr>
    <tr>
    <td colspan='3'>
      <div class='form-group'>
                            <input class='btn btn-success' type=submit value=Simpan>
                             <button class='btn btn-warning' type=button value=Batal data-dismiss='modal'> Batal </button>
        </div>
              </form>
        </td>
    </tr>
    </table>
          ";
	?>