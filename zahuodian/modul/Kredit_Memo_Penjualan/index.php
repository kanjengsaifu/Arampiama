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
$aksi="modul/kreditmemopenjualan/aksi_kreditmemo.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
    echo "<h2>Master Kredit Memo Penjualan</h2>
  <div class='btn btn-primary' data-toggle='modal' data-target='#modalstock' >Tambah <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></div>";
  //### modul tambah
  echo"
  <div class='modal fade' id='modalstock' role='dialog'>
    <div class='modal-dialog modal-lg'>
          <div class='modal-content'>
        <div class='modal-header'>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
          <h4 class='modal-title'><b>Tambah</b> Kartu Memo Penjualan</h4>
        </div>

        <div class='modal-body'>";
        echo"
 <form method='post' action='$aksi?module=kreditmemopenjualan&act=input'>
      <table class='table table-hover'>
       <tr>
          <td>
          No Nota Pembelian
          </td>
          <td  colspan='2' id='sup'><select class='form-control' id='customer' name='customer' required>";
            $tampil=mysql_query('SELECT * FROM customer where is_void=0 ');
                    echo '<option value="" selected>- Pilih customer -</option>';
            while($w=mysql_fetch_array($tampil)){
                     echo '<option value='.$w['id_customer'].'>'.$w['nama_customer'].'</option>';
            }
echo "</select>
          <input class='form-control dihitung' type='hidden' id='id_customer' name='id_customer'   readonly >
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
<div class='modal fade' id='customer' role='dialog'>
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
      <th>customer</th>      
    </tr>
    <table border =1>";
    $query=mysql_query("select * from customer where is_void=0 ");
    while ($r=mysql_fetch_array($query)) {
      echo 
      "<tr>
      <td>
      ".$r['nama_customer']."
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

     
  }
}
}
  

?>
<script>
               add_newitemcombobox("customer","customer");
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
                    "ajax": "modul/kreditmemopenjualan/load-data.php",
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

$("#customer").change(function()
 { 
  var id = $("#customer").find(":selected").val();
  $("#id_customer").val(id);
  var dataString = 'text='+ id;
  $.ajax
  ({
    url: 'modul/kreditmemopenjualan/modal_no_nota.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#no_invoi").html(r);
   } 
  });
    $.ajax
  ({
    url: 'modul/kreditmemopenjualan/modal_no_nota_retur.php',
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
    $('#customer').attr('disabled', true);
};
function nilairetur(kode) {
    var kode=kode.split("-");
     $("#no_nota_retur").val(kode[0]);
     $("#no_nota_retur_jumlah").val(kode[1]);
    $("#no_nota_retur_modal").modal("toggle");
    $('#customer').attr('disabled', true);
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