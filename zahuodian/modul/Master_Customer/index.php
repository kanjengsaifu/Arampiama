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
  $_ck = (array_search("3",$_SESSION['lvl'], true));
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
$aksi="modul/customer/aksi_customer.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
      $judul = "Master Customer";
  $desk = "Modul Yang digunakan Untun Menyimpan data customer";
  $button="<div class='btn btn-primary' data-toggle='modal' data-target='#modalcustomer' >Tambah Customer<span class='glyphicon glyphicon-plus' aria-hidden='true'></span></div>";
  headerDeskripsi($judul,$desk,$button);

    echo "
    
      <!--<a href='?module=customer&act=tambahmenu' class='btn btn-primary' >Tambah<span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a>-->";
  //### modul tambah
  echo"
  <div class='modal fade' id='modalcustomer' role='dialog'>
    <div class='modal-dialog modal-lg'>
    
      <div class='modal-content'>
        <div class='modal-header'>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
          <h4 class='modal-title'><b>Tambah</b> Customer</h4>
        </div>

        <div class='modal-body'>";
        echo "
       <form method='post' action='$aksi?module=customer&act=input'>
       <input type=hidden name=kode_customer id=kode_customer>
      <table class='table table-hover'>
        <tr>
          <td>Kode</td> 
          <td><span id=kode_caption></span></td>
          <td>Regional</td>
          <td><select  class='form-control' id='region' name='region' >";
          $select = mysql_query("SELECT * FROM region WHERE is_void = 0;");
          while ($row = mysql_fetch_array($select)) {
            if ($row[id_region]==0) {
              echo "<option value='' selected>- Pilih Regional -</option>";
            }else{
              echo "<option value=$row[id_region] selected>$row[region]</option>";
            }
          }echo '</select>';
          echo "
          <!--div class='btn btn-primary' type='button' id='search' data-toggle='modal' data-target='#search-md'>Regional <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></div-->
          </td>
        </tr>
        <tr>
          <td>Nama</td> 
          <td><input type='text' class='form-control' name='nama_customer' id='nama_customer'/></td>
            <td>Alamat</td> 
            <td><textarea type='text' class='form-control' name='alamat_customer'></textarea></td>
        </tr>
        <tr>
            <td>Alamat Kirim</td> 
            <td><textarea type='text' class='form-control' name='alamat_kirim'></textarea></td>
            <td>Alamat Tagihan</td> 
            <td><textarea type='text' class='form-control' name='alamat_tagihan'></textarea></td>
        </tr>
     <tr>
    <td>HP/Telp 1</td> 
    <td><input class='form-control' name='telp_customer'/></td>
     <td>HP/Telp 2</td> 
    <td><input class='form-control' name='telp_customer2'/></td>
    </tr>
    <tr>
    <td>HP/Telp 3</td> 
    <td><input class='form-control' name='telp_customer3'/></td>
     <td>HP/Telp 4</td> 
    <td><input class='form-control' name='telp_customer4'/></td>
    </tr>
     <tr>
    <td>Hutang Maksimal </td> 
    <td><input class='form-control' name='batas_limit' placeholder='exc:10000000'/></td>
    </tr>
     <tr>
    <td>Max.Tempo</td> 
    <td><input class='form-control' name='aging' placeholder='exc:7 '/></td><td>Hari</td>
    </tr>

      </table>
      <div class='form-group'>
                            <input class='btn btn-success' type=submit value=Simpan>
                           <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
      </div>
      </from>";

        echo "
        </div>
      </div>
      
    </div>
  </div>


      <br /><br />
  <div class='table-responsive'>
    <table id='customer' class='display table table-striped table-bordered table-hover'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
      <th id='tablenumber'>No</th>
      <th>Kode</th>
      <th>customer</th>
      <th>Alamat</th>
      <th>Telp</th>
      <th>Limit</th>
      <th>Aging</th>
      <th class='tablenumber'>Edit</th>
    </tr>
    </thead>
    </table>
  </div>";
    break;

  case "editmenu":
    $edit = mysql_query("SELECT * FROM customer WHERE id_customer='$_GET[id]'");
    $r    = mysql_fetch_array($edit);
        echo '
    <div class="row">
          <div class="col-md-6">
                <h2>Edit Master Customer</h2>
                 <p class="deskripsi">modul Edit Customer</p>
           </div>
      </div>
            <hr class="deskripsihr" style="margin-bottom:0px;"><br>';
    echo "
     <form method='post' action='$aksi?module=customer&act=update'>
     <td><input class='hidden' type='text'  name='id' value='$r[id_customer]'/></td>
     <div class='table-responsive'>
      <table class='table table-hover'>
        <tr>
          <td>Kode</td> 
          <td><input type='text' class='form-control' name='kode_customer' value='$r[kode_customer]' readonly/></td>
          <td>Regional</td>
          <td>
            <select name='region' id='region' class='form-control' >";

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
      </select>
          </td>
        </tr>
        <tr>
          <td>Nama</td> 
          <td><input type='text' class='form-control' name='nama_customer' value='$r[nama_customer]'/></td>
            <td>Alamat</td> 
            <td><textarea type='text' class='form-control' name='alamat_customer'/>$r[alamat_customer]</textarea></td>
        </tr>
        <tr>
            <td>Alamat Kirim</td> 
            <td><textarea type='text' class='form-control' name='alamat_kirim' />$r[alamat_kirim]</textarea></td>
            <td>Alamat Tagihan</td> 
            <td><textarea type='text' class='form-control' name='alamat_tagihan' />$r[alamat_tagihan]</textarea></td>
        </tr>
     <tr>
    <td>HP/Telp 1</td> 
    <td><input class='form-control' name='telp_customer' value='$r[telp_customer]'/></td>
      <td>HP/Telp 2</td> 
    <td><input class='form-control'  name='telp_customer2' value='$r[telp_customer2]'/></td>
    </tr>
    <tr>
    <td>HP/Telp 3</td> 
    <td><input class='form-control' name='telp_customer3' value='$r[telp_customer3]'/></td>
      <td>HP/Telp 4</td> 
    <td><input class='form-control' name='telp_customer4' value='$r[telp_customer4]'/></td>
    </tr>
     <tr>
    <td>Max.Limit</td> 
    <td><input class='form-control' name='batas_limit' value='$r[batas_limit]'/></td>
    </tr>
     <tr>
    <td>Max.Tempo</td> 
    <td><input class='form-control' name='aging' value='$r[aging]'/></td>
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
<script>
$(document).ready(function () {
                add_newitemcombobox2("region","region");
              function add_newitemcombobox2(id_cobobox,nama){
                selectkategori = $('#'+id_cobobox);
                selectkategori.chosen({ no_results_text: 'Apakah Anda mau menambah '+nama+' baru :', width: '100%'});
                $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
                chosenkategori = selectkategori.data('chosen');
                chosenkategori.dropdown.find('input').on('keyup', function(e)
                {
                if (e.which == 13 && chosenkategori.dropdown.find('li.no-results').length > 0)
                    {
                    var option = $("<option>").val(this.value).text(this.value);
                    selectkategori.prepend(option);
                    selectkategori.find(option).prop('selected', true);
                    selectkategori.trigger("chosen:updated");      
                    }
                  i++;
                });
              } 
  $("#nama_customer").keyup(function(){
    str=$(this).val().substr(0,1);
    $('#kode_caption').text(str.toUpperCase());
    $('#kode_customer').val(str.toUpperCase()); 
    if (str.length==1){
      $.ajax({
        type  : "POST",
        url   : "modul/customer/kode.php",
        data  : "nama_customer="+str,
        success : function(kode){
          $("#kode_customer").val(kode);
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

    var t = $('#customer').DataTable({
        "columns": [
            { "searchable": false },
            null,
            null,
            null,
            null,
            { "searchable": false },
            { "searchable": false },
            { "searchable": false }
          ],
        "iDisplayLength": 20,
           "aLengthMenu": [ [20, 50,100],[20,50,100]],
        "processing": true,
        "serverSide": true,
        "ajax": "modul/customer/load-data.php",
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