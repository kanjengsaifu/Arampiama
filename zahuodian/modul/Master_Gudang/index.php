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
  $_ck = (array_search("8",$_SESSION['lvl'], true));
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
$aksi="modul/gudang/aksi_gudang.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
  $judul = "Modul Gudang";
  $desk = "ini adalah Modul untuk mengola Master gudang";
  $button= "<button type='button' data-toggle='modal' data-target='#addtambah' class='btn btn-primary'>Tambah Gudang  <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></button>";
  headerDeskripsi($judul,$desk,$button);

           echo '
<div class="modal fade" id="addtambah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Tambah Master Gudang</h4>
      </div>
      <div class="modal-body">';
    echo "
 <form method='post' action='$aksi?module=gudang&act=input'>
    <table class='table table-hover'>
    <tr>
    <td>Nama gudang</td> 
    <td><input type='text'  class='form-control' name='gudang'/></br>
    <input type=checkbox value='1' name='gudang_luar' checked>Gudang Luar</td>
    <td>
      <div class='form-group'>
                            <input class='btn btn-success' type=submit value=Simpan>
                            <button class='btn btn-warning' type=button value=Batal data-dismiss='modal'> Batal </button>
        </div>
              </form>
        </td>
    </tr>
    </table>";

              echo '</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';
echo '
<div class="modal fade" id="editbarang" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Edit Akun Bank</h4>
      </div>
      <div class="modal-body" id="showedit">';

    

            echo '</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';

    echo "
          <div class='table-responsive'>
    <table id='gudang' class='display table table-striped table-bordered table-hover'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
      <th id='tablenumber'>No.</th>
      <th>gudang</th>   
      <th class='tablenumber'>Aksi</th>
    </tr></thead><tbody> ";
    $tampil=mysql_query("SELECT * FROM gudang WHERE is_void=0");
    $no=1;
      while ($r=mysql_fetch_array($tampil)){
echo "
<tr>
   <td class='number'><span>$no</span></td>
            <td>$r[nama_gudang]</td>
    
    <td>
    <button onclick='tampiledit(\"$r[id_gudang]\");' class='btn btn-sm btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></button>
    <a href='$aksi?module=gudang&act=hapus&id=$r[id_gudang]' class='btn btn-sm btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
  </td>
</tr>"; 
$no++;
}
  echo "
  </tbody>
    </table>
  </div>";
    break;
 
 //  case "editmenu":
 //    $edit = mysql_query("SELECT * FROM gudang WHERE id_gudang='$_GET[id]'");
 //    $r    = mysql_fetch_array($edit);

 //    echo "
 //    <h2>Ubah Nama gudang</h2>
 // <form method=POST action=$aksi?module=gudang&act=update>
 //     <div class='table-responsive'>
 //    <table class='table table-hover'>
 //    <tr>
 //    <td>Nama gudang</td> 
 //    <td><input class='hidden' type='text'  name='id' value='$r[id_gudang]'/></td>
 //    <td><input type='text' name='gudang' value='$r[nama_gudang]'/></br>";
 //    if ($r['status_gudang']=='1') {
 //      echo " <input type=checkbox name='gudang_luar' checked>Gudang Luar";
 //    }
 //    else {
 //      echo " <input type=checkbox  name='gudang_luar'>Gudang Luar";
 //    }
 //   echo "
 //    </td>
 //      <div class='form-group'>
 //                            <input class='btn btn-success' type=submit value=Simpan>
 //                            <input class='btn btn-warning' type=button value=Batal onclick=self.history.back()>
 //              </form>
 //        </td>
 //    </tr>


 //        <br /><br />

 //          ";
 //    break; 
    } 
}
}
?>
<script type="text/javascript">
  $('#gudang').DataTable({
  "columns": [
    { "searchable": false },
    null,
    { "searchable": false }
  ],
      "iDisplayLength": 20,
      "aLengthMenu": [ 
      [20, 50,100],
      [20,50, 100]
      ]
    });

    function tampiledit(d){
            var dataString = 'data='+ d;
                $.ajax({
                      url: "modul/gudang/ajax_gudang.php",
                     data: dataString,
                     cache: false,
                     success: function(r){
                          $("#showedit").html(r);
                     } 
              });
              $('#editbarang').modal('show');
            }
</script>