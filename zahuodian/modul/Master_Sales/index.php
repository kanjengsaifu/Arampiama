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
$aksi="modul/sales/aksi_sales.php";
switch($_GET['act']){
  // Tampil Modul
  default:
  $judul = "Modul Sales";
  $desk = "ini adalah Modul untuk mengola Data Sales";
  $button="   <a class='btn btn-info' data-toggle='collapse' data-parent='#akuncollapse' href='#akuncollapse1'>Tambah Data Sales  <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a>";
  headerDeskripsi($judul,$desk,$button);
    echo "
       <div class='panel-group' id='akuncollapse'>
  <div class='panel'>
      <h4 class='panel-title'>
     
      </h4>
    <div id='akuncollapse1' class='panel-collapse collapse'>
      <div class='panel-body'>
          <table class='table table-hover'>
                 <form method=POST action='$aksi?module=sales&act=input'>
              <tr>
          <div class='form-group'>
                    <label>Nama Sales : </label>
                    <input class='form-control'  type=text name='nama_sales' required>
          </div>

          <div class='form-group'>
                    <label>Telpon1 Sales : </label>
                    <input class='form-control'  type=text name='Telp1_sales' required>
          </div>

          <div class='form-group'>
                    <label>Telpon2 Sales : </label>
                    <input class='form-control'  type=text name='Telp2_sales' required>
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
       <div class='table-responsive'>
    <table id='sales' class='display table table-striped table-bordered table-hover' cellspacing='0' width='100%'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
        <th id='tablenumber'>No</th>
        <th>Nama Sales</th>
        <th>Telpon1</th>
        <th>Telpon2</th>
        <th>Keterangan</th>
        <th>Aksi</th>
    </tr>
        </thead>
    </table>
    </div>";
break;
 
  case "edit":
    $edit = mysql_query("SELECT * FROM sales WHERE id_sales='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

                echo "
            <div class='row'>
                  <div class='col-md-6'>
                        <h2>Edit Sales</h2>
                         <p class='deskripsi'>ini adalah Modul untuk mengola Data Sales </p>
                   </div>
            </div>


       <div class='panel-group'>
  <div class='panel'>
      <div class='panel-body'>
          <table class='table table-hover'>
                 <form method=POST action='$aksi?module=sales&act=update'>
              <tr>
          <div class='form-group'>
                    <label>Nama Sales : </label>
                    <input class='form-control'  type=text name='nama_sales' value='$r[nama_sales]'>
                    <input class='form-control'  type='hidden' name='id_sales' value='$r[id_sales]'>
          </div>

          <div class='form-group'>
                    <label>Telpone :</label>
                    <input class='form-control' type='text' name='Telp1_sales' value='$r[Telp1_sales]'>
          </div>

          <div class='form-group'>
                    <label>Telpone :</label>
                    <input class='form-control' type='text' name='Telp2_sales' value='$r[Telp2_sales]'>
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

          
          <div class='table-responsive'>
    <table class='table table-bordered'>
                <thead>
    <tr style='background-color:#F5F5F5;'>
      <th class='tablenumber' >No</th>
      <th>Nama Sales</th>
      <th>Telpon1</th>
      <th>Telpon2</th>
      <th>Keterangan</th>
      <th>Aksi</th>
    </tr>
                </thead>
                <tbody>";
  $tampil=mysql_query("SELECT * FROM sales  ORDER BY id_sales");
            $no=1;
      while ($rt=mysql_fetch_array($tampil)){
          echo "
                    <tr>
                          <td>$no</td>
                          <td>$rt[nama_sales]</td>
                          <td>$rt[Telp1_sales]</td>
                          <td>$rt[Telp2_sales]</td>
                          <td>$rt[ket]</td>t
                          <td>
                              <a href='?module=sales&act=edit&id=$rt[id_sales]' class='btn btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></a>
                              <a href='$aksi?module=sales&act=hapus&id=$rt[id_sales]' class='btn btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
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
 
                var t = $('#sales').DataTable({
                    "columns": [
                        { "searchable": false },
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false }
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                      "pagingType" : "simple_numbers",
                      "language": {
                            "decimal": ",",
                            "thousands": "."
                          },
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/sales/load_data.php",
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