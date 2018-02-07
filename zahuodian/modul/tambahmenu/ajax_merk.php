<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
 if(isset($_GET['data'])){
  	$not = $_GET['data'];
	 $aksi="modul/tambahmenu/aksi_tambahmenu.php";
    $edit = mysql_query("SELECT * FROM mainmenu WHERE id_mainmenu='$not'");
    $r    = mysql_fetch_array($edit);
	}

	 echo "
           <form method=POST action=$aksi?module=tambahmenu&act=update>
<table class='table table-hover'>
                 <tr>
           <input class='form-control' type=hidden name=id value='$r[id_mainmenu]'>

          <div class='form-group'>
          <label>Nama Modul : </label>
          <input class='form-control'  type=text name='nama_mainmenu' value='$r[nama_mainmenu]'>
          </div>


          <div class='form-group'>
          <label>urutan Menu (No.) :</label>
          <input class='form-control' type=text name='no_urut' value='$r[no]'>
          </div>

          <div class='form-group'>
          <label>Root :</label>
          <input class='form-control' type=text name='root' value='$r[root]'>
          </div>";

           if ($r['aktif']=='Y'){
            echo "
            <div class='form-group'>
            <label>Aktif :</label><br>
                        <input class='radio-inline' type=radio name='aktif' value='Y' checked> Y
                        <input class='radio-inline' type=radio name='aktif' value='N'> N</td></tr>";
          }
          else{
            echo "
            <div class='form-group'>
            <label>Aktif :</label><br>
                        <input class='radio-inline' type=radio name='aktif' value='Y'> Y  
                        <input class='radio-inline' type=radio name='aktif' value='N' checked> N</td></tr>";
          }
          echo"
            <div class='form-group'>
            <label>Link URL Modul</label>
                          <input class='form-control' type=text name='link' value='$r[link_modul]'>
              </div>

           <div class='form-group'>
                            <input class='btn btn-success' type=submit value=Update>
                             <button class='btn btn-warning' type=button value=Batal data-dismiss='modal'> Batal </button>
              </div>
                 </tr>            
          </table>
                    </form>

          ";
?>