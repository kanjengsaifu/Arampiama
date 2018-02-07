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
 $_ck = (array_search("6",$_SESSION['lvl'], true));
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
$aksi="modul/user/aksi_user.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
  $judul = "Setting Akun ";
  $desk = "Digunkan untuk melakukan setting pada combobox yang akan di munculkan pada transaksi";
  headerDeskripsi($judul,$desk);

  echo "

  </div  class='col-md-12 table-responsive'>
  <!-- Table Utama ############################################################################################################################-->
  <table class='display table table-striped table-bordered table-hover'>
  <thead>
  <th>No</th>
  <th>Nama Module</th>
  <th>Gambar</th>
  <th>Aksi</th>
  </thead>
   <tbody>";
                          $query= mysql_query("Select * from setting_akun");
                          $no=1;
                          while ($r=mysql_fetch_array($query)) {
                            echo "
                            <tr>
                            <td>".$no."</td>
                            <td>$r[nama_modul]</td>
                            <td>$r[gambar]</td>
                            <td> <button onclick='tampiledit(".$r['id'].")' class='btn btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></button>
 

                            </td>
                            </tr>
                            ";
                            $no++;
                          }


                          echo "</tbody>
  </table>
                         
  <!-- Table Utama ############################################################################################################################-->


<!-- Kumpulan Modal ############################################################################################################################-->

<div id='editakun' class='modal fade' role='dialog'>
 <div class='modal-dialog modal-lg'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'>&times;</button>
        <h4 class='modal-title'>Pilih Akun Kas</h4>
      </div>
      <div class='modal-body' id='showedit'>
        
     
      
    </div>
  </div>
<!-- Kumpulan Modal ############################################################################################################################-->


  ";

    break;

  }
}
}
function GetCheckboxes($table, $key, $Label, $Nilai='') {
  $s = "select * from $table order by id_mainmenu";
  $d = mysql_query($s);
  $_arrNilai = explode(',', $Nilai);
  $str = '';
  while ($w = mysql_fetch_array($d)) {
    $_ck = (array_search($w[$key], $_arrNilai) === false)? '' : 'checked';
    $str .= "<tr>
    <td>$w[$key]</td>
    <td style='text-align:left;'>
    <input type=checkbox name='".$key."_array[]' value='$w[$key]' $_ck>  $w[$Label] </td>
    </tr>";
  }
  return $str;
}
?>
<script type="text/javascript">
  function tampiledit(d){
            var dataString = 'id_akun='+ d;
                $.ajax({
                      url: "modul/setting_akun/ajax_setting_akun.php",
                     data: dataString,
                     cache: false,
                     success: function(r){
                          $("#showedit").html(r);
                     } 
              });
              $('#editakun').modal('show');
            }



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
 
                var t =   $('#user').DataTable({
                      "columns": [
                        { "searchable": false },
                        null,
                        { "searchable": false },
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
</script>
