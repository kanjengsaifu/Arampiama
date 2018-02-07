<?php
 include "config/koneksi.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
$aksi="modul/kodenota/aksi_kodenota.php";
switch($_GET['act']){
  // Tampil Modul
  default:
    echo "
            <div class='row'>
                  <div class='col-md-6'>
                        <h2>Modul Kode Nota</h2>
                         <p class='deskripsi'>ini adalah Modul untuk mengganti kode nota semua transaksi </p>
                   </div>
            </div>

    <div class='table-responsive'>
       <div class='panel-group' id='akuncollapse'>
  <div class='panel'>
      <h4 class='panel-title'>
        <a class='btn btn-info' data-toggle='collapse' data-parent='#akuncollapse' href='#akuncollapse1'>Tambah Kode Nota  <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a>
      </h4>
    <div id='akuncollapse1' class='panel-collapse collapse'>
      <div class='panel-body'>
          <table class='table table-hover'>
                 <form method=POST action='$aksi?module=kodenota&act=input'>
              <tr>
          <div class='form-group'>
                    <label>Kode Nota : </label>
                    <input class='form-control'  type=text name='nama_kodenota' required>
          </div>


          <div class='form-group'>
                    <label>Module :</label>
                    <select  class='form-control'  name='link_modul' required>";
                   
                   $main = mysql_query("SELECT * FROM mainmenu where aktif='Y' AND  root !=0 ");
                   echo "<option value='' selected> - Pilih Supplier - </option>";
                  while($w=mysql_fetch_array($main)){
                      echo "<option value=$w[link_modul]>$w[nama_mainmenu]</option>";
                  }
                    echo"
                    </select>
          </div>

          <div class='form-group'>
                    <label>Keterangan :</label>
                    <input class='form-control' type=text name='ket'>
          </div>


           <div class='form-group'>
                            <input class='btn btn-success' type=submit value=Simpan>
            </div>
              </tr>
                </form>                
          </table>
      </div>
    </div>
  </div>
</div>         
    </div>
          
          <div class='table-responsive'>
		<table class='table table-bordered'>
                <thead>
		<tr style='background-color:#F5F5F5;'>
			<th class='number' >No</th>
			<th>Kode Nota</th>
                      <th>Module</th>
			<th>Keterangan</th>
			<th>Aksi</th>
		</tr>
                </thead>
                <tbody>";
	$tampil=mysql_query("SELECT * FROM kodenota k join mainmenu m on k.link_modul = m.link_modul  ORDER BY id_kodenota");
            $no=1;
	    while ($r=mysql_fetch_array($tampil)){
          echo "
                    <tr>
                          <td>$no</td>
                          <td>$r[nama_kodenota]</td>
                          <td>$r[nama_mainmenu] - ($r[link_modul])</td>
                          <td>$r[ket]</td>
                          <td>
                              <a href='?module=kodenota&act=edit&id=$r[id_kodenota]' class='btn btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></a>
                              <a href='$aksi?module=kodenota&act=hapus&id=$r[id_kodenota]' class='btn btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
                          </td>
                    </tr>";
                    $no++;
      }
	echo "
                    </tbody>
		</table>
	</div>";
    break;
 
  case "edit":
    $edit = mysql_query("SELECT * FROM kodenota k join mainmenu m on k.link_modul = m.link_modul WHERE id_kodenota='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

                echo "
            <div class='row'>
                  <div class='col-md-6'>
                        <h2>Edit Kode Nota</h2>
                         <p class='deskripsi'>ini adalah Modul untuk mengganti kode nota semua transaksi </p>
                   </div>
            </div>

    <div class='table-responsive'>
       <div class='panel-group'>
  <div class='panel'>
      <div class='panel-body'>
          <table class='table table-hover'>
                 <form method=POST action='$aksi?module=kodenota&act=update'>
              <tr>
          <div class='form-group'>
                    <label>Kode Nota : </label>
                    <input class='form-control'  type=text name='nama_kodenota' value='$r[nama_kodenota]'>
                    <input class='form-control'  type='hidden' name='id_kodenota' value='$r[id_kodenota]'>
          </div>

          <div class='form-group'>
                    <label>Module :</label>
                    <select  class='form-control'  name='link_modul' required>";
                   
                   $main = mysql_query("SELECT * FROM mainmenu where aktif='Y' AND  root !=0 ");
                   echo "<option value='$r[link_modul]' selected> $r[nama_mainmenu]</option>";
                  while($w=mysql_fetch_array($main)){
                      echo "<option value=$w[link_modul]>$w[nama_mainmenu]</option>";
                  }
                    echo"
                    </select>
          </div>

          <div class='form-group'>
                    <label>Keterangan :</label>
                    <input class='form-control' type='text' name='ket' value='$r[ket]'>
          </div>


           <div class='form-group'>
                            <input class='btn btn-success' type=submit value=Simpan>
                            <input class='btn btn-warning' type=button value=Batal onclick=self.history.back()>
            </div>
              </tr>
                </form>                
          </table>
      </div>
    </div>
  </div>
</div>         
    </div>
          
          <div class='table-responsive'>
    <table class='table table-bordered'>
                <thead>
    <tr style='background-color:#F5F5F5;'>
      <th class='tablenumber' >No</th>
      <th>Kode Nota</th>
                      <th>Module</th>
      <th>Keterangan</th>
      <th>Aksi</th>
    </tr>
                </thead>
                <tbody>";
  $tampil=mysql_query("SELECT * FROM kodenota k join mainmenu m on k.link_modul = m.link_modul  ORDER BY id_kodenota");
            $no=1;
      while ($rt=mysql_fetch_array($tampil)){
          echo "
                    <tr>
                          <td>$no</td>
                          <td>$rt[nama_kodenota]</td>
                          <td>$rt[nama_mainmenu] - ($rt[link_modul])</td>
                          <td>$rt[ket]</td>t
                          <td>
                              <a href='?module=kodenota&act=edit&id=$rt[id_kodenota]' class='btn btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></a>
                              <a href='$aksi?module=kodenota&act=hapus&id=$rt[id_kodenota]' class='btn btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
                          </td>
                    </tr>";
                    $no++;
      }
  echo "
                    </tbody>
    </table>
  </div>";
    break;
}
}

?>
