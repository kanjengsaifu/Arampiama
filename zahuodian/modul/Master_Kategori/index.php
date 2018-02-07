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
$aksi="modul/kategori/aksi_kategori.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
    echo "<h2>Modul Kategori</h2>
     <div class='table-responsive'>

 <div class='panel-group' id='kategoricollapse'> <!-- ################## PANEL GROUP ################### -->
  <div class='panel'>
      <h4 class='panel-title'>
        <a class='btn btn-info' data-toggle='collapse' data-parent='#kategoricollapse' href='#kategoricollapse1'> Tambah Kategori</a>
        <a class='btn btn-info' data-toggle='collapse' data-parent='#kategoricollapse' href='#kategoricollapse2'> Tambah Subkategori</a>
      </h4>
    <div id='kategoricollapse1' class='panel-collapse collapse'>
      <div class='panel-body'> <!-- ################## PANEL BODY 1 ################### -->
          <table class='table table-hover'>
                <form method='post' action='$aksi?module=kategori&act=input'>
                <tr>
                  <td><b>Nama Kategori</b></td> 
                  <td><input type='text' name='kategori'/></td>
                  <td>
                  <div class='form-group'>
                    <input class='btn btn-success' type=submit value=Simpan>
                    </div>
                  </td>
                </tr>
                </form>                
          </table>
      </div> <!-- ################## END PANEL BODY 1################### -->
    </div>
    <div id='kategoricollapse2' class='panel-collapse collapse'>
      <div class='panel-body'>
        <table class='table table-hover'>
                <form method='post' action='$aksi?module=kategori&act=input'>
                <tr>
                  <td><b>Nama Subkategori</b></td> 
                  <td><input type='text' name='kategori'/></td>
                   <td>kategori induk</td>
                <td>
                      <select class='form-control' name='subkategori'>";
                            $tampil24=mysql_query("SELECT * FROM kategori where is_void=0 AND root=0 ");
            echo "<option value='' selected>- Pilih kategori-</option>";
         while($w=mysql_fetch_array($tampil24)){
              echo "<option value=$w[id_kategori] selected>$w[kategori]</option>";
            }
echo "
           </select>
                </td>
                  <td>
                  <div class='form-group'>
                    <input class='btn btn-success' type=submit value=Simpan>
                    </div>
                  </td>
                </tr>
                </form>                
          </table>
      </div>
    </div>
  </div>
</div>      <!-- ################## END PANEL GROUP ################### -->    
    </div>



          <div class='table-responsive'>
    <table id='kategori' class='table table-hover'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
      <th id='tablenumber'>No.</th>
      <th>Kategori</th>
      <th>Subkategori</th>
      <th class='tablenumber'>Aksi</th>
    </tr>
    </thead><tbody> ";
    $tampil=mysql_query("SELECT * FROM kategori WHERE is_void=0 order by id_kategori desc");
    $no=1;
      while ($r=mysql_fetch_array($tampil)){
        if ($r[root]==0){
  echo "
<tr class='bg-warning'>
   <td class='number' style='font-weight:bold;font-size: 16px;'></td>
            <td style='font-weight:bold;font-size: 16px;'>$r[kategori]</td>
            <td> </td>
    <td>
    <a href='?module=kategori&act=editmenu&id=$r[id_kategori]' class='btn btn-sm btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></a>
    <a href='$aksi?module=kategori&act=hapus&id=$r[id_kategori]' class='btn btn-sm btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
  </td>
</tr>";
 $tampil2 = mysql_query("SELECT * FROM kategori WHERE root='$r[id_kategori]' order by id_kategori desc");
 while  ($p=mysql_fetch_array($tampil2)){
  echo "
<tr>
   <td class='number'></td>
            <td style='font-size: 20px;padding: 0px; font-weight:bold;'>&#8594;</td>
            <td>$p[kategori]</td>
    <td>
    <a href='?module=kategori&act=editmenu&id=$p[id_kategori]' class='btn btn-sm btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></a>
    <a href='$aksi?module=kategori&act=hapus&id=$p[id_kategori]' class='btn btn-sm btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
  </td>
</tr>";}
}
 $no++;}

  echo "</tbody>
    </table>
  </div>";
    break;
 
  case "editmenu":
    $edit = mysql_query("SELECT * FROM kategori WHERE id_kategori='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "
    <h2>Ubah Nama kategori</h2>
     <div class='table-responsive'>
     <form method=POST action=$aksi?module=kategori&act=update>
    <table class='table table-hover'>
    <tr>
    <td>Nama kategori</td> 
    <td><input class='hidden' type='text'  name='id' value='$r[id_kategori]'/></td>
    <td><input type='text' name='kategori' value='$r[kategori]'/></td>
    <td>
      <div class='form-group'>
                            <input class='btn btn-success' type=submit value=Simpan>
                            <input class='btn btn-warning' type=button value=Batal onclick=self.history.back()>
              </form>
        </td>
    </tr>
    </table>
    </form>
    </div>

          ";
    break; 
    } 
}
}
?>
<script>
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
 
                var t = $('#kategori').DataTable({
                    "columns": [
                      { "searchable": false, "orderable": false },
                      { "orderable": false },
                      { "orderable": false },
                      { "searchable": false, "orderable": false }
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                    "order": [[0, 'desc']],
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