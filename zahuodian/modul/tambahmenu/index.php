<?php
 include "config/koneksi.php";
 echo '<link rel="stylesheet" href="asset/css/layout.css">';
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
$aksi="modul/tambahmenu/aksi_tambahmenu.php";
switch($_GET['act']){
  // Tampil Modul
  default:
    echo "<h2>Modul tambah menu</h2>

    <div class='table-responsive'>
       <div class='panel-group' id='akuncollapse'>
  <div class='panel'>
      <h4 class='panel-title'>
        <a class='btn btn-info' data-toggle='collapse' data-parent='#akuncollapse' href='#akuncollapse1'>Tambah menu  <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a>
      </h4>
    <div id='akuncollapse1' class='panel-collapse collapse'>
      <div class='panel-body'>
          <table class='table table-hover'>
                 <form method=POST action='$aksi?module=tambahmenu&act=input'>
              <tr>
                            <div class='form-group'>
          <label>Nama Modul : </label>
          <input class='form-control'  type=text name='nama_mainmenu' >
          </div>


          <div class='form-group'>
          <label>urutan Menu (No.) :</label>
          <input class='form-control' type=text name='no_urut' value='1000'>
          </div>

          <div class='form-group'>
          <label>Root :</label>
          <input class='form-control' type=text name='root'>
          </div>

            <div class='form-group'>
            <label>Aktif :</label><br>
                        <input class='radio-inline' type=radio name='aktif' value='Y' checked> Y
                        <input class='radio-inline'  type=radio name='aktif' value='N'> N</td></tr>
              </div>

            <div class='form-group'>
            <label>Folder Modul</label>
                          <input class='form-control' type=text name='link' >
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
          <small><i>*) <b>\"No. urut\"</b> berfungsi hanya untuk menu level 1,  menu level 2 sort by alphabetical dari <b>nama menu</b>. </i></small><br>
          <small><i>*) <b>\"submenu dari id\"</b> = <b> 0 </b> untuk menu level 1,<b> \"submenu dari id\"</b> = <b>\"id menu\"</b> untuk menu level 1 yg tidak memiliki menu level 2. </i></small>
          <div class='table-responsive'>
		<table id='tambahmenu' class='display table table-bordered table-hover' cellspacing='0' width='100%'>
                <thead>
		<tr>
			<th class='number' >No.</th>
                      <th>id menu</th>
			<th>nama menu</th>
			<th class='number' >no. urut</th>
			<th class='number' >submenu dari id.</th>
                      <th>link URL Modul</th>
			<th class='number' >status</th>
			<th>Aksi</th>
		</tr>
    </thead>
    <tbody>";
	$tampil=mysql_query("SELECT * FROM mainmenu ORDER BY root  asc");
        $no=1;
	    while ($r=mysql_fetch_array($tampil)){
        if($r['root'] == $r['id_mainmenu'] or $r['root'] == 0 ){
          echo "
<tr bgcolor='#C0C0C0'>
   <td class='number'><b>$no.</b></td>
            <td><span><b>$r[id_mainmenu]</b></span></td>
            <td><b>$r[nama_mainmenu]</b></td>";
            if($r[no]==1000){
              echo " <td class='number' ></td>";
            }
            else{
              echo "<td class='number' >$r[no]</td>";
            }
            echo"
            <td class='number' >$r[root]</td>
            <td>$r[link_modul]</td>
            <td class='number' >$r[aktif]</td>
  <td>
   <!--<a href='?module=tambahmenu&act=editmenu&id=$r[id_mainmenu]' class='btn btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></a>-->
    <button type='button' class='btn btn-warning' title='Edit' onclick='tampiledit(\"$r[id_mainmenu]\");' ><span class='glyphicon glyphicon-edit'></span></button>
    <a href='$aksi?module=tambahmenu&act=hapus&id=$r[id_mainmenu]' class='btn btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
  </td>
</tr>";
$no++;
        }
        else {
echo "
<tr>
	 <td class='number'><b>$no.</b></td>
            <td><span>$r[id_mainmenu]</span></td>
            <td>$r[nama_mainmenu]</td>";
            if($r[no]==1000){
              echo " <td class='number' ></td>";
            }
            else{
              echo "<td class='number' >$r[no]</td>";
            }
            echo"
            <td class='number' >$r[root]</td>
            <td>$r[link_modul]</td>
            <td class='number' >$r[aktif]</td>
	<td>
   <!--<a href='?module=tambahmenu&act=editmenu&id=$r[id_mainmenu]' class='btn btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></a>-->
    <button type='button' class='btn btn-warning' title='Edit' onclick='tampiledit(\"$r[id_mainmenu]\");' ><span class='glyphicon glyphicon-edit'></span></button>
		<a href='$aksi?module=tambahmenu&act=hapus&id=$r[id_mainmenu]' class='btn btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
	</td>
</tr>"; 
$no++;
}
}
	echo "</tbody>
		</table>
	</div>";

    echo '
<div class="modal fade" id="editmenu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Edit Menu</h4>
      </div>
      <div class="modal-body" id="showedit">';

    

            echo '</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';

    break;
 
  case "editmenu":
    $edit = mysql_query("SELECT * FROM mainmenu WHERE id_mainmenu='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

             echo "<h2> Edit  Menu</h2>

    <div class='table-responsive'>
  <div class='panel'>
      <div class='panel-body'>
          <table class='table table-hover'>
                 <tr>
                      <form method=POST action=$aksi?module=tambahmenu&act=update>
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

           if ($r[aktif]=='Y'){
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
            <label>Folder Modul</label>
                          <input class='form-control' type=text name='link' value='$r[link_modul]'>
              </div>

           <div class='form-group'>
                            <input class='btn btn-success' type=submit value=Update>
                            <input class='btn btn-warning' type=button value=Batal onclick=self.history.back()>
              </div>
          </form>
                 </tr>            
          </table>
      </div>
    </div>
  </div>
          
          <div class='table-responsive'>
    <table class='table table-bordered'>
    <tr style='background-color:#F5F5F5;'>
      <th class='number' >id menu</th>
      <th>nama menu</th>
      <th class='number' >no. urut</th>
      <th class='number' >submenu dari id.</th>
                      <th>folder Modul</th>
      <th class='number' >status</th>
      <th>Aksi</th>
    </tr>";
  $tampil=mysql_query("SELECT * FROM mainmenu ORDER BY id_mainmenu");
      while ($r=mysql_fetch_array($tampil)){
        if($r['root'] == $r['id_mainmenu'] or $r['root'] == 0 ){
          echo "
<tr bgcolor='#C0C0C0'>
   <td class='number'><span><b>$r[id_mainmenu]</b></span></td>
            <td><b>$r[nama_mainmenu]</b></td>";
            if($r[no]==1000){
              echo " <td class='number' ></td>";
            }
            else{
              echo "<td class='number' >$r[no]</td>";
            }
            echo"
            <td class='number' >$r[root]</td>
            <td>$r[link_modul]</td>
            <td class='number' >$r[aktif]</td>
  <td>
   <!--<a href='?module=tambahmenu&act=editmenu&id=$r[id_mainmenu]' class='btn btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></a>-->
    <button type='button' class='btn btn-warning' title='Edit' onclick='tampiledit(\"$r[id_mainmenu]\");' ><span class='glyphicon glyphicon-edit'></span></button>
    <a href='$aksi?module=tambahmenu&act=hapus&id=$r[id_mainmenu]' class='btn btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
  </td>
</tr>";
        }
        else {
echo "
<tr>
   <td class='number'><span>$r[id_mainmenu]</span></td>
            <td>$r[nama_mainmenu]</td>";
            if($r[no]==1000){
              echo " <td class='number' ></td>";
            }
            else{
              echo "<td class='number' >$r[no]</td>";
            }
            echo"
            <td class='number' >$r[root]</td>
            <td>$r[link_modul]</td>
            <td class='number' >$r[aktif]</td>
  <td>
   <!--<a href='?module=tambahmenu&act=editmenu&id=$r[id_mainmenu]' class='btn btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></a>-->
    <button type='button' class='btn btn-warning' title='Edit' onclick='tampiledit(\"$r[id_mainmenu]\");' ><span class='glyphicon glyphicon-edit'></span></button>
    <a href='$aksi?module=tambahmenu&act=hapus&id=$r[id_mainmenu]' class='btn btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
  </td>
</tr>"; 
}
}
  echo "
    </table>
  </div>";
    break;
}
}
$tampil=mysql_query("SELECT * FROM users where is_void=0 and username='$_SESSION[namauser]' and password='$_SESSION[passuser]'");
      $y=mysql_fetch_array($tampil);
echo "   <a href='?module=user&act=editmenu&id=$y[id_users]' class='btn btn-primary' title='Edit'>Menu User <span class='glyphicon glyphicon-edit'></span></a>"
?>
<script type="text/javascript">
          $(document).ready(function () {
                $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
                {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };
 
                var t =   $('#tambahmenu').DataTable({
                    "iDisplayLength": 100,
                       "aLengthMenu": [ [100,150,200],[100,150,200]],
                    "order": [[4, 'asc']],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });
            });
          function tampiledit(d){
            var dataString = 'data='+ d;
                $.ajax({
                      url: "modul/tambahmenu/ajax_merk.php",
                     data: dataString,
                     cache: false,
                     success: function(r){
                          $("#showedit").html(r);
                     } 
              });
              $('#editmenu').modal('show');
            }
</script>
<style type="text/css">
  #tambahmenu_wrapper{    overflow-x: hidden;}
</style>
