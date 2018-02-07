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
  // security akses link
  $_ck = (array_search("7",$_SESSION['lvl'], true));
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
$aksi="modul/kreditmemo/aksi_kreditmemo.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
    echo "<h2>Master Kredit Memo Pembelian</h2>
  <div class='btn btn-primary' data-toggle='modal' data-target='#modalstock' >Tambah <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></div>";
  //### modul tambah
  echo"
  <div class='modal fade' id='modalstock' role='dialog'>
    <div class='modal-dialog modal-lg'>
          <div class='modal-content'>
        <div class='modal-header'>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
          <h4 class='modal-title'><b>Tambah</b> Kartu Memo Pembelian</h4>
        </div>

        <div class='modal-body'>";
        echo"
 <form method='post' action='$aksi?module=kreditmemo&act=input'>
      <table class='table table-hover'>
       <tr>
          <td>
          No Nota Pembelian
          </td>
          <td  colspan='2' id='sup'><select class='form-control' id='supplier' name='supplier' required>";
            $tampil=mysql_query('SELECT * FROM Supplier where is_void=0 ');
                    echo '<option value="" selected>- Pilih Supplier -</option>';
            while($w=mysql_fetch_array($tampil)){
                     echo '<option value='.$w['id_supplier'].'>'.$w['nama_supplier'].'</option>';
            }
echo "</select>
          <input class='form-control dihitung' type='hidden' id='id_supplier' name='id_supplier'   readonly >
          </td>
          </td>
          </tr>
          <tr>
          <td>
          No Nota Pembelian
          </td>
          <td>
          <input class='form-control dihitung'  id='no_nota' name='no_nota'  data-target  ='#no_nota_modal' data-toggle ='modal'  readonly >
          </td>
          <td>
          <input class='form-control dihitung' id='no_nota_jumlah' name='no_nota_jumlah'   readonly >
          </td>
          </tr>
           <tr>
          <td>
          No Nota Retur
          </td>
          <td>
          <input class='form-control dihitung' id='no_nota_retur' name='no_nota_retur'  data-target ='#no_nota_retur_modal' data-toggle ='modal' readonly  >
          </td>
          <td>
          <input class='form-control dihitung' id='no_nota_retur_jumlah' name='no_nota_retur_jumlah'  readonly >
          </td>
          </tr>
           <tr>
          <td>
         Total
          </td>
          <td>
         ------------->
          </td>
          <td>
          <input class='form-control' id='total' name='total' readonly    >
          </td>
          </tr>
      </table>
      <div class='form-group'>
                            <input class='btn btn-success' type=submit value=Simpan>
                            <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
      </div>
 </form>
        </div>
      </div>
      
    </div>
  </div>";


        echo "
 <div class='col-md-12'>
<div class='table-responsive'>
    <table id='kreditmemo' class='table table-hover' style='width:100%;'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
      <th id='tablenumber'>No</th>
      <th>Kode Retur</th>
      <th>Kode Invoice</th>
      <th>Grand Total awal </th>
      <th>Grand Total Retur</th>
      <th>Hasil</th>
      <th>Aksi</th>
    </tr>
    </thead>
    </table>
  </div>
  </div>

<!--Modal no_nota-->
<div class='modal fade' id='no_nota_modal' role='dialog'>
    <div class='modal-dialog'>
    
      <!-- Modal content-->
      <div class='modal-content'>
        <div class='modal-header'>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
          <h4 class='modal-title'>Nomer Po</h4>
        </div>
        <div class='modal-body'>
    <table border='1' class='table table-hover'>
    <tr style='background-color:#F5F5F5;'>
      <th id='tablenumber'>No</th>
      <th>No Nota Invoice</th>      
    </tr>
<tbody id='no_invoi'>

</tbody>
    </table>
        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
        </div>
      </div>      
    </div>
  </div>
<!--Modal no_nota-->


<!--Modal no_nota-->
<div class='modal fade' id='no_nota_retur_modal' role='dialog'>
    <div class='modal-dialog'>
    
      <!-- Modal content-->
      <div class='modal-content'>
        <div class='modal-header'>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
          <h4 class='modal-title'>Nomer Po</h4>
        </div>
        <div class='modal-body'>
    <table border='1' class='table table-hover'>
    <tr style='background-color:#F5F5F5;'>
      <th id='tablenumber'>No</th>
      <th>No Nota Retur</th>      
    </tr>

<tbody id='no_retur'>

</tbody>
    </table>
        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
        </div>
      </div>      
    </div>
  </div>
<!--Modal no_nota-->
<!--Modal no_nota-->
<div class='modal fade' id='supplier' role='dialog'>
    <div class='modal-dialog'>
    
      <!-- Modal content-->
      <div class='modal-content'>
        <div class='modal-header'>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
          <h4 class='modal-title'>Nomer Po</h4>
        </div>
        <div class='modal-body'>
    <table border='1' class='table table-hover'>
    <tr style='background-color:#F5F5F5;'>
      <th id='tablenumber'>No</th>
      <th>Supplier</th>      
    </tr>
    <table border =1>";
    $query=mysql_query("select * from supplier where is_void=0 ");
    while ($r=mysql_fetch_array($query)) {
      echo 
      "<tr>
      <td>
      ".$r['nama_supplier']."
      </td>
      </tr>
      ";
        
     } 


echo "  
    </table>
        </div>
        <div class='modal-footer'>
          <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
        </div>
      </div>      
    </div>
  </div>
<!--Modal no_nota-->
  ";
    break;

     
  case "editmenu":

    $edit = mysql_query("SELECT * FROM kreditmemo_stok adj,gudang gdg,barang brg WHERE adj.id_gudang=gdg.id_gudang and adj.id_barang=brg.id_barang and adj.is_void='0'  and id_kreditmemo='$_GET[id]'");
    $v    = mysql_fetch_array($edit);

    echo "<h2>Modul kreditmemo</h2>
    <form method='post' action='$aksi?module=kreditmemo&act=update'>
     <div class='table-responsive'>
      <table class='table table-hover'>
        <tr>
          <td>Gudang</td> <input type='hidden' name='id' value='$v[id_kreditmemo]'/>
          <td>
           <select class='form-control' name='gudang'>";
$tampil=mysql_query("SELECT * FROM gudang where is_void='0'");
          if ($r[id_merk]==0){
            echo "<option value=$v[id_gudang] selected>$v[nama_gudang]</option>";
          }   

          while($w=mysql_fetch_array($tampil)){
            if ($r[id_gudang]==$w[id_gudang]){
              echo "<option value=$w[id_gudang] selected>$w[nama_gudang]</option>";
            }
            else{
              echo "<option value=$w[id_gudang]>$w[nama_gudang]</option>";
            }
          }
          echo "
          </select>
          </td>
        </tr>
        <tr>
          <td>Barang</td> 
          <td> <select class='form-control' name='barang'>";
$tampil=mysql_query("SELECT * FROM barang where is_void='0'");
          if ($r[id_merk]==0){
            echo "<option value=$v[id_barang] selected>$v[nama_barang]</option>";
          }   

          while($w=mysql_fetch_array($tampil)){
            if ($r[id_barang]==$w[id_barang]){
              echo "<option value=$w[id_barang] selected>$w[nama_barang]</option>";
            }
            else{
              echo "<option value=$w[id_barang]>$w[nama_barang]</option>";
            }
          }
          echo "
          </select>
</td>
        </tr>
     <tr>
    <td>tanggal</td> 
    <td><input id='datepicker' class='form-control'  type='date' name='tgl_kreditmemo' value=$v[tgl_kreditmemo]></td>
    </tr>
     <tr>
    <td>Plus-minus</td> 
    <td><input type='hidden' class='form-control'  type='number' name='plusminus_barang_awal' value=$v[plusminus_barang]>
    <input class='form-control'  type='number' name='plusminus_barang' value=$v[plusminus_barang]></td>
    </tr>
    <tr>
    <td>Keterangan</td> 
    <td><textarea class='form-control'  type='text' name='keterangan' > $v[keterangan]</textarea></td>
    </tr>
    <tr>
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
               add_newitemcombobox("supplier","Supplier");
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
 
                var t = $('#kreditmemo').DataTable({
                    "columns": [
                        { "searchable": false },
                        null,
                        null,
                        null,
                        null,
                       null,
                        null
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/kreditmemo/load-data.php",
                    "order": [[3, 'desc']],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });
            });

$("#supplier").change(function()
 { 
  var id = $("#supplier").find(":selected").val();
  $("#id_supplier").val(id);
  var dataString = 'text='+ id;
  $.ajax
  ({
    url: 'modul/kreditmemo/modal_no_nota.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#no_invoi").html(r);
   } 
  });
    $.ajax
  ({
    url: 'modul/kreditmemo/modal_no_nota_retur.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#no_retur").html(r);
   } 
  });
 })
function nilaipo(kode) {
    var kode=kode.split("-");
     $("#no_nota").val(kode[0]);
     $("#no_nota_jumlah").val(kode[1]);
    $("#no_nota_modal").modal("toggle");
    $('#supplier').attr('disabled', true);
};
function nilairetur(kode) {
    var kode=kode.split("-");
     $("#no_nota_retur").val(kode[0]);
     $("#no_nota_retur_jumlah").val(kode[1]);
    $("#no_nota_retur_modal").modal("toggle");
    $('#supplier').attr('disabled', true);
};

$('.dihitung').on("click focus", function(){
  var jum_no=($('#no_nota_jumlah').val()!=''?$('#no_nota_jumlah').val():0),
  jum_no_retur=($('#no_nota_retur_jumlah').val()!=''?$('#no_nota_retur_jumlah').val():0),
  total_jum= (parseInt(jum_no)-parseInt(jum_no_retur));

  if (!isNaN(total_jum)) {
    $('#total').val(total_jum);
  };

})

        </script>