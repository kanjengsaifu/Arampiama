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
 $_ck = (array_search("4",$_SESSION['lvl'], true));
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
$aksi="modul/akunkasperkiraan/aksi_akunkasperkiraan.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:

  $judul = "Master Akun Akuntasi";
  $desk = "ini adalah modul master Akun Akuntasi";
  $button=" <a class='btn btn-info' data-toggle='modal' data-target='#myModal'> Tambah Akun</a>";
  headerDeskripsi($judul,$desk,$button);

    echo "
     
      <div id='myModal' class='modal fade' role='dialog'>
  <div class='modal-dialog'>

    <!-- Modal content-->
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'>&times;</button>
        <h4 class='modal-title'>Modal Header</h4>
      </div>
      <div class='modal-body'>
      <form method='post' action='$aksi?module=akunkasperkiraan&act=input'>
       <table class='table table-hover'>
                
                <tr>
                  <td><b>Kode Header</b></td>
                  <td>";echo '<select class="form-control chosen-select" id="header" name="header" required>';
                $select = mysql_query("SELECT * FROM akun_header WHERE is_void=0 order by kode_akun_header asc");
                           echo "<option value='' selected>- Pilih Kode -</option>";
                        while($row=mysql_fetch_array($select)){
                          $select2= mysql_query("SELECT max(kode_akun) as akunmax FROM akun_kas_perkiraan WHERE  kode_akun_header ='$row[kode_akun_header]'  ");
                          $hasil = mysql_fetch_array($select2);
                             echo "<option value='".$hasil['akunmax']."'>$row[nama_akun]</option>";
                             }echo "</select></td>
                </tr>
                <tr>
                  <td><b>Kode Akun Kategori</b></td> 
                  <td><input  class='form-control' type='text' id='kode' name='kode' readonly /></td>
              </tr>
                <tr>
                  <td><b>Nama Akun  Kategori</b></td> 
                  <td><input  class='form-control' type='text' name='akun'/></td>
                </tr>
               <tr>
                  <td><b>Keterangan</b></td> 
                  <td><input  class='form-control' type='text' name='ket'/></td>
              </tr>            
          </table>
          <div class='modal-footer'>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
            <input class='btn btn-sm btn-success' type=submit value=Simpan>
          </div>
          </form>
      </div>
      
    </div>

  </div>
</div>
    <table id='akun' class='display table table-striped table-bordered table-hover' >
    <thead>
    <tr style='background-color:#F5F5F5;'>
      <th id='tablenumber'>No.</th>
      <th>Kode Header</th>
      <th>Kode Akun Akuntasi</th>
      <th>Nama Akun Akuntasi</th>
      <th>Saldo</th>
     <th>Keterangan</th>
      <th>Aksi</th>
    </tr>
    </thead><tbody> ";
    $tampil=mysql_query("SELECT * FROM akun_kas_perkiraan WHERE is_void=0 order by kode_akun_header ");
    $no=1;
      while ($r=mysql_fetch_array($tampil)){
          echo "
        <tr>
           <td class='tablenumber'>$no</td>
            <td>$r[kode_akun_header]</td>
            <td>$r[kode_akun]</td>
            <td>$r[nama_akunkasperkiraan]</td>
            <td>$r[saldo]</td>
            <td>$r[ket]</td>
    <td>
   <a href='$aksi?module=akunkasperkiraan&act=hapus&id=$r[id_akunkasperkiraan]' class='btn btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
  </td>
</tr>";
 $no++;
}

  echo "</tbody>
    </table>
  </div>


<!-- ################################# Modal Edit ########################### -->
<div class='modal fade' id='editbarang' role='dialog'>
    <div class='modal-dialog'>
      <div class='modal-content'>
        <div class='modal-header'>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
          <h4 class='modal-title'>Modal Header</h4>
        </div>
        <div class='modal-body' id='showedit'>
        
        </div>
      
      </div>
    </div>
  </div>
</div>
<!-- ################################# Modal Edit ########################### -->



  ";
    break;
 
 }
}
}
?>
<script>
        $("#qty_saldo_d").keydown(function(){
          setTimeout(function() {
          var tempt=($("#qty_saldo_d").val());
          $("#qty_saldo_k").val(tempt);
          }, 0);
        })

kodex('header','kode')
function kodex(id_combo,id_text){
$("#"+id_combo).change(function()
 { 
  var id = $("#"+id_combo).find(":selected").val();
  var id = id.split("-");

  var jum = parseInt(id[1]) + 101;
  jum = jum.toString();
  $("#"+id_text).val(id[0]+'-'+jum.substring(1, 3));
 })
        }



</script>
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
 
                var t =   $('#akun').DataTable({
                      "columns": [
                        { "searchable": false },
                        null,
                        { "searchable": false },
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false }
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                    "order": [[1, 'asc']],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });
            });


 function modaledit(id){
            var dataString = 'id_akun='+ id;
                $.ajax({
                      url: "modul/akunkasperkiraan/ajax_editakunkas.php",
                     data: dataString,
                     cache: false,
                     success: function(r){
                          $("#showedit").html(r);
                     } 
              });
              $('#editbarang').modal('show');
            }
</script>
