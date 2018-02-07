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
 $_ck = (array_search("2",$_SESSION['lvl'], true));
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
$aksi="modul/supplier/aksi_supplier.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
echo ButtonCase($_GET['module'],'Tambah',$ubah='');

    echo '
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#all">Semua</a></li>
      <li><a data-toggle="tab" href="#B">Tukang</a></li>
      <li><a data-toggle="tab" href="#A">Supplier</a></li>
      <li><a data-toggle="tab" href="#C">Lain-lain</a></li>
    </ul>

    <div class="tab-content">
      <div id="all" class="tab-pane fade in active">
        <div class="table-responsive">
          <table class="tb_supplier display table table-striped table-bordered table-hover">
          <thead>
          <tr style="background-color:#F5F5F5;"">
            <th id="tablenumber">No</th>
            <th>Kode & Supplier</th>
            <th>Region</th>
            <th>Telp Supplier</th>
            <th>Telp Sales</th>
            <th>Aksi</th>
          </tr></thead>
          </table>
        </div>
      </div>
      <div id="B" class="tab-pane fade">
        <div class="table-responsive">
          <table id="supplier-a" class="display table table-striped table-bordered table-hover" style="width:100%;">
          <thead>
          <tr style="background-color:#F5F5F5;"">
            <th id="tablenumber">No</th>
            <th>Kode</th>
            <th>Supplier</th>
            <th>Tipe Supplier</th>
            <th>Alamat</th>
            <th>Region</th>
            <th>Telp Supplier</th>
            <th>Telp Sales</th>
            <th>Aksi</th>
          </tr></thead>
          </table>
        </div>
      </div>
      <div id="A" class="tab-pane fade">
        <div class="table-responsive">
          <table id="supplier-b" class="display table table-striped table-bordered table-hover" style="width:100%;">
          <thead>
          <tr style="background-color:#F5F5F5;"">
            <th id="tablenumber">No</th>
            <th>Kode</th>
            <th>Supplier</th>
            <th>Tipe Supplier</th>
            <th>Alamat</th>
            <th>Region</th>
            <th>Telp Supplier</th>
            <th>Telp Sales</th>
            <th>Aksi</th>
          </tr></thead>
          </table>
        </div>
      </div>
      <div id="C" class="tab-pane fade">
        <div class="table-responsive">
          <table id="supplier-c" class="display table table-striped table-bordered table-hover" style="width:100%;">
          <thead>
          <tr style="background-color:#F5F5F5;"">
            <th id="tablenumber">No</th>
            <th>Kode</th>
            <th>Supplier</th>
            <th>Tipe Supplier</th>
            <th>Alamat</th>
            <th>Region</th>
            <th>Telp Supplier</th>
            <th>Telp Sales</th>
            <th>Aksi</th>
          </tr></thead>
          </table>
        </div>
      </div>
    </div>
    ';
    break;

  case "tambah":
  echo ButtonAksi('Tambah Supplier');
?>
   
    <table class='table-bordered'>
      <thead>
         <tr>
         <th>No. </th>
          <th width="400px">Nama Supplier</th>
          <th>No. Telp</th>
          <th>No. Telp</th>
          <th>Batas Hutang</th>
          <th>Tempo Hutang</th>
          <th>Jenis</th>
          <th>Region</th>
          <th>Alamat</th>
         </tr>
      </thead>
      <tbody>
      <?php for ($i=1; $i <= 20 ; $i++) :?>
          <tr>
            <td align="Center" background-color="white"><strong><?= $i ?>.</strong></td>
            <td width="400px"><?= GenerateInput("nama_supplier-$i",'nama_supplier[]','text','','full-input nm_supplier','Nama Supplier')?></td>
            <td><?= GenerateInput('','telp1_supplier','text','','telp','Nomor Telp');  ?></td>
            <td><?= GenerateInput('','telp2_supplier','text','','telp','Nomor Telp');  ?></td>
            <td><?= GenerateInput('','batas_hutang[]','text','','nominal right');  ?></td>
            <td><?= GenerateInput('','tempo_hutang[]','text','','hari');  ?></td>
            <td id="td_jenis_supplier-<?=$i?>"></td>
            <td id="td_region-<?=$i?>"></td>
            <td><?= GenerateInput('','alamat_supplier[]','text','','');  ?></td>
          </tr>
      <?php endfor; ?> 
      </tbody>
    </table>
    <?php  
     break;
  case "editmenu":
    $edit = mysql_query("SELECT * FROM supplier WHERE id_supplier='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<h2>Edit Modul</h2>
 <form method='post' action='$aksi?module=supplier&act=update'>
  <td><input class='hidden' type='text'  name='id' value='$r[id_supplier]'/></td>
     <div class='table-responsive'>
      <table class='table table-hover'>
        <tr>
          <td>Kode</td> 
          <td><input type='text' class='form-control' name='kode_supplier' value='$r[kode_supplier]' readonly/></td>
          <td>Regional</td>
          <td>
            <select id='region' name='region' class='form-control' >";

$tampil=mysql_query("SELECT * FROM region where is_void='0'");
          if ($r["id_region"]==0){
            echo "<option value='' selected>--Pilih Regional--</option>";
          }   
          while($w=mysql_fetch_array($tampil)){
            if ($r["id_region"]==$w["id_region"]){
              echo "<option value=$w[id_region] selected>$w[region]</option>";
            }
            else{
              echo "<option value=$w[id_region]>$w[region]</option>";
            }
          }
echo "
      </select><script>add_newitemcombobox('region');</script>
          </td>
        </tr>
        <tr>
          <td>Nama</td> 
          <td><input type='text' class='form-control' name='nama_supplier' value='$r[nama_supplier]'/></td>
            <td>Alamat</td> 
            <td><textarea type='text' class='form-control' name='alamat_supplier'>$r[alamat_supplier]</textarea></td>
        </tr>
     <tr>
    <td>HP/Telp 1</td> 
    <td><input type='text' class='form-control' name='telp1_supplier' value='$r[telp1_supplier]'/></td>
    <td>HP/Telp 2</td> 
    <td><input type='text' class='form-control' name='telp2_supplier' value='$r[telp2_supplier]'/></td>
    </tr>
     <tr>
    <td>Fax</td> 
    <td><input type='text' class='form-control' name='fax_supplier' value='$r[fax_supplier]'/></td>
    <td>Jenis </td>
    <td>
      <select name='jenis' class='form-control'>";
      if ($r['jenis']=='A') {
        echo "
        <option value='B' selected>Supplier</option>
        <option value='A'>Tukang</option>
        <option value='C'>Tukang</option>
        ";
      } else if ($r['jenis']=='B') {
        echo "
        <option value='B'>Supplier</option>
        <option value='A' selected>Tukang</option>
        <option value='C'>Tukang</option>";
      } else if ($r['jenis']=='C') {
        echo "\
        <option value='B'>Supplier</option>
        <option value='A'>Tukang</option>
        <option value='C' selected>Lain-lain</option>";
      }
      echo ";
      </select>
    </td>
    </tr>
    <tr>
          <td>Nama Sales</td> 
          <td><input type='text' class='form-control' name='nama_sales' value='$r[nama_sales]'/></td>
        </tr>
        <tr>
    <td>HP/Telp 1</td> 
    <td><input type='text' class='form-control' name='telp1_sales' value='$r[telp1_sales]'/></td>
    <td>HP/Telp 2</td> 
    <td><input type='text' class='form-control' name='telp2_sales' value='$r[telp2_sales]'/></td>
    </tr>
     <tr>
    <td>Max.Limit</td> 
    <td><input type='text' class='form-control' name='batas_limit' value='$r[batas_limit]'/></td>
    </tr>
     <tr>
    <td>Max.Tempo</td> 
    <td><input type='text' class='form-control' name='aging' value='$r[aging]'/></td>
    </tr>

      </table>
      <div class='form-group'>
                            <input class='btn btn-success' type=submit value=Simpan>
                            <input class='btn btn-warning' type=button value=Batal onclick=self.history.back()><div>
      </div>
      </from>";
    break;
  }
}
}
?>
<script type="text/javascript">

            $(document).ready(function () {
              $('.nm_supplier').blur(function () {
                if($(this).val()!=''){
                  var id=$(this).attr("id");
                  id=id.split('-');
                  if ($("#td_jenis_supplier-"+id[1]).html()=='') {
                  $.ajax({
                      type  : "POST",
                      url   : "ajax/index.php?file=ajax_selected_region&id="+id[1],
                      success : function(data){
                        $("#td_region-"+id[1]).html(data);
                      }
                    });
                  $.ajax({
                      type  : "GET",
                      url   : "ajax/index.php?file=ajax_selected_jenis_supplier",
                      success : function(data){
                        $("#td_jenis_supplier-"+id[1]).html(data);
                      }
                    });
                  };
                };
              })
              $("#nama_supplier").keyup(function(){
              str=$(this).val().substr(0,1);
              $('#kode_caption').text(str.toUpperCase());
              $('#kode_supplier').val(str.toUpperCase()); 
              if (str.length==1){
                $.ajax({
                  type  : "POST",
                  url   : "modul/supplier/kode.php",
                  data  : "nama_supplier="+str,
                  success : function(kode){
                    $("#kode_supplier").val(kode);
                    $("#kode_caption").text(kode); 
                  }
                });
              }
            });
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

                var t = $('#supplier').DataTable({
                      "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false }
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                       "pagingType" : "simple_numbers",
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/supplier/load-data.php",
                    "order": [[1, 'asc']],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                      
                    }
                });
                var u = $('#supplier-a').DataTable({
                      "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false }
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                       "pagingType" : "simple_numbers",
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/supplier/load-data-a.php",
                    "order": [[1, 'asc']],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                      
                    }
                });
                var u = $('#supplier-b').DataTable({
                      "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false }
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                       "pagingType" : "simple_numbers",
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/supplier/load-data-b.php",
                    "order": [[1, 'asc']],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                      
                    }
                });
                var u = $('#supplier-c').DataTable({
                      "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false }
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                       "pagingType" : "simple_numbers",
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/supplier/load-data-c.php",
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