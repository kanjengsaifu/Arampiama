<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
 if(isset($_GET['data'])){
  	$reg = $_GET['data'];
	 $aksi="modul/region/aksi_region.php";
    $edit = mysql_query("SELECT * FROM region WHERE id_region='$reg'");
    $r    = mysql_fetch_array($edit);
	}

  echo "
 <form method=POST action=$aksi?module=region&act=update>
    <table class='table table-hover'>
    <tr>
    <td>Nama Regional</td> 
    <td><input class='hidden' type='text'  name='id' value='$r[id_region]'/></td>
    <td><input type='text' name='region'  class='form-control'  value='$r[region]'/></br>";
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