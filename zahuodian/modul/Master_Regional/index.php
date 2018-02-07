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
$aksi="modul/region/aksi_region.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
  $judul = "Modul Regional";
  $desk = "ini adalah Modul untuk mengola Master Regional";
  $button=" <button type='button' data-toggle='modal' data-target='#addtambah' class='btn btn-primary'>Launch modalTambah <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></button>";
  headerDeskripsi($judul,$desk,$button);

           echo '
<div class="modal fade" id="addtambah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Tambah Master Regional</h4>
      </div>
      <div class="modal-body">';
    echo "
 <form method='post' action='$aksi?module=region&act=input'>
    <table class='table table-hover'>
    <tr>
    <td>Nama Regional</td> 
    <td><input type='text'  class='form-control' name='region'/></br>
    <!--input type=checkbox value='1' name='gudang_luar' checked>Gudang Luar-->
    </tr>
    <tr>
    
      <div class='form-group'>
                            <td><input class='btn btn-success' type=submit value=Simpan></td>
                            <td><button class='btn btn-warning' type=button value=Batal data-dismiss='modal'> Batal </button></td>
        </div>
              </form>
        
    </tr>
    </table>";

              echo '</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';
echo '
<div class="modal fade" id="editregion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Edit Regional</h4>
      </div>
      <div class="modal-body" id="showedit">';

    

            echo '</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';

    echo "
          <div class='table-responsive'>
    <table id='region' class='display table table-striped table-bordered table-hover'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
      <th id='tablenumber'>No.</th>
      <th>Regional</th>   
      <th class='tablenumber'>Aksi</th>
    </tr></thead><tbody> ";
    $tampil=mysql_query("SELECT * FROM region WHERE is_void=0");
    $no=1;
      while ($r=mysql_fetch_array($tampil)){
echo "
<tr>
   <td class='number'><span>$no</span></td>
            <td>$r[region]</td>
    
    <td>
    <button onclick='tampiledit(\"$r[id_region]\");' class='btn btn-sm btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></button>
    <a href='$aksi?module=region&act=hapus&id=$r[id_region]' class='btn btn-sm btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
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
}
?>
<script type="text/javascript">
  $('#region').DataTable({
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
                      url: "modul/region/ajax_region.php",
                     data: dataString,
                     cache: false,
                     success: function(r){
                          $("#showedit").html(r);
                     } 
              });
              $('#editregion').modal('show');
            }
</script>