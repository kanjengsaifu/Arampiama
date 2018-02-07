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
    echo '
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#all">Supplier</a></li>
      <li><a data-toggle="tab" href="#payment">List Payment</a></li>
    </ul>

    <div class="tab-content">
      <div id="all" class="tab-pane fade in active">
        <div class="table-responsive">
          <table class="tb_supplier display table table-striped table-bordered table-hover">
          <thead>
          <tr style="background-color:#F5F5F5;"">
            <th id="tablenumber">No</th>
            <th>Supplier</th>
            <th>Region</th>
            <th>Telp Supplier</th>
            <th>Telp Sales</th>
            <th>Aksi</th>
          </tr></thead>
          </table>
        </div>
      </div>
      <div id="payment" class="tab-pane fade">
        <div class="table-responsive">
          <table id="payment" class="display table table-striped table-bordered table-hover" style="width:100%;">
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

  case "new":
  $nota='PP/00001/XI/2017';
  $date = date('dd/MM/YYYY');
  $keterangan='';
  ?>
 
      <table class="table-responsive">
    <tr>
      <td>No. Bukti</td><td>:</td><td><input type="text" class="pp" value="<?= $nota ?>"></td>
      <td>Tanggal</td><td>:</td><td><input type="text" class="date" value="<?= $date ?>"></td>
      <td>Keterangan</td><td>:</td><td><input type="text" class="keterangan" value="<?= $keterangan ?>"></td>
    </tr>
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