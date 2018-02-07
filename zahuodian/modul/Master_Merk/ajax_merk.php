<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
 if(isset($_GET['data'])){
  	$not = $_GET['data'];
	 $aksi="modul/masterbank/aksi_masterbank.php";
$edit = mysql_query("SELECT * FROM merk WHERE id_merk='$not'");
$r    = mysql_fetch_array($edit);
	}

	 echo "
 <form method=POST action=$aksi?module=merk&act=update>
     <div class='table-responsive'>
    <table class='table table-hover'>
    <tr>
    <td>Nama Merk</td> 
    <td><input class='hidden' type='text'  name='id' value='$r[id_merk]'/></td>
    <td><input type='text' name='merk' value='$r[merk]'/></td>
    </tr>
    <tr>
    <td colspan='3'>
      <div class='form-group'>
                            <input class='btn btn-success' type=submit value=Simpan>
                             <button class='btn btn-warning' type=button value=Batal data-dismiss='modal'> Batal </button>
              </form>
          </div>
        </td>
    </tr>
    </table>
          ";
?>