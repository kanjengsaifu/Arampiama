<?php
 include "config/coneksi.php";
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
$aksi="modul/hitunghpp/aksi_hitunghpp.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){ 
  // Tampil Modul
  default:

    $judul = "Trans Hitung Hpp";
    $desk = "ini adalah modul untuk mrnhitung hpp barang jadi ";
    $button= "<a href='?module=hitunghpp&act=tambah' class='btn btn-primary' >Buat Baru <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a><span class='success'>";
    headerDeskripsi($judul,$desk,$button);
  
    echo '
   <div class="table-responsive">
<table id="hitung_hpp" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
          <th id="tablenumber">No</th>
          <th>No. Hitung Hpp</th>
          <th>No. Penerimaan Barang Jadi</th>
          <th>Nama Barang</th>
          <th>Tanggal</th>
          <th>Hpp Akhir</th>
          <th style="width:70px;">Aksi</th>
          </tr>
        </thead>
    </table>
  </div>';
    break;
 
  case 'tambah':
    $judul = "<h2><b>Tambah</b> Trans Hitung Hpp</h2>";
    $desk = " ini adalah modul untuk menghitung hpp barang jadi";
    headerDeskripsi($judul,$desk);
  echo "
    <form method='post' action='".$aksi."?module=hitunghpp&act=input' id='addhpp'>
    <input  name='hppId' id ='hppId' type='hidden'>
    <input type='hidden' name='id_brg' id='id_brg'>
      <table class='table table-hover' border=0 id=tambah>";
  echo '
  <tr>
  <td>No. THP</td><td><strong>:</strong></td>
  <td>';
  $tampil23=mysql_query("SELECT * FROM hitung_hpp_header order by id_hitung_hpp_heder desc limit 1 ");
  $r    = mysql_fetch_array($tampil23);
  echo kodesurat($r['no_hitung_hpp'], THP, no_thp, no_hitung_hpp );
  echo '
  </td>
 <td>Tanggal Transaksi</td><td><strong>:</strong></td>
    <td><input class="datetimepicker form-control" value="'.date('Y-m-d').'"" name="tgl_trans" required></td>
 </tr>
  '; 
  echo "
  <tr>
     <td>No. PBJ</td> <td><strong>:</strong></td><td id='sup'>";
   echo '<select  id="supplier" name="supplier" class="chosen-select form-control" tabindex="2" required>';
$tampil=mysql_query("SELECT * FROM trans_terima_tukang_header WHERE status = 0 AND is_void = 0");
            echo "<option value='' selected>- Pilih Nomor PBJ -</option>";
         while($w=mysql_fetch_array($tampil)){
              echo "<option value=$w[id_terima_tukang]>$w[id_terima_tukang]</option>";
            }
echo '</select></td>
<td>Nama Barang</td><td><strong>:</strong></td>
<td><input  name="no_po" id ="no_po" data-toggle="modal" class="form-control" data-target="#myModal" readonly/>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Barang Pada No PBJ</h4>
        </div>
        <div class="modal-body">
    <table border="1" class="table table-hover">
    <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Nama Barang</th>
      <th>Jumlah</th>      
    </tr>

<tbody id="tampil">

</tbody>
    </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>      
    </div>
  </div>
  </td>
  <td>
  </tr>
  <tr>
    <td>Nama Supplier</td><td><strong>:</strong></td>
    <td>
      <input type="hidden" id="id_tukang" name="id_tukang" value="">
      <input name="tukang" id="tukang" value="" class="form-control" readonly>
    </td>
  </tr>
 </table>
   <a class="btn btn-success" title="tambah row" onclick="AddRow('.$no.')"><span class="glyphicon glyphicon-plus"></span> Barang</a>';
//####################################################  ALOKAISI DETAIL ##########
   echo'
<table id="alokasi_tampil" class="table table-hover table-bordered" cellspacing="0">
<thead>
  <tr style="background-color:#F5F5F5;">
    <th id="tablenumber">No</th>
    <th>Kode Barang</th>
    <th>Nama Barang</th>
    <th>Jumlah</th>
    <th>Harga</th>
    <th>Total Biaya</th>
    <th id="tablenumber">Aksi</th>
  </tr>
</thead>
<tbody id="tambahrow">
</tbody><input type="text"    id="counter" value="'.$no.'" >
<tfood id="noborder" style="border-top:1px solid #000;">
      <tr>
        <td colspan="2" rowspan="2" valign="bottom">
          <input class="btn btn-success" type=submit value=Simpan>  
          <a class="btn btn-warning" type="button" href="media.php?module=hitunghpp">Batal</a>
        </td>
        <td colspan="3" style="text-align:right;"><b>Total :</b></td>
        <td><input type="text" name="total"   id="total"  class="numberhit form-control hitung" readonly></td>
        <td><input type="text" name="total_barang"  value=""  id="total_barang"  class="numberhit form-control hitung numberhit"></td>
      </tr>
      <tr>
        <!--td colspan="4"></td-->
        <td colspan="3" style="text-align:right;"><b>Hasil Hpp :</b></td>
        <td colspan="2"><b><input type="text" name="total_hpp"  value=""  id="total_hpp"  class="numberhit form-control hitung numberhit" readonly></b></td>
      </tr>
</tfood>
</table>
    <div style="float: right;">
            <input type="hidden" name="status" id="status">
            <a style="margin:2px 2px;display: none;" class="btn btn-success" data-toggle="modal"  id="confirm" data-toggle="modal" data-target="#confirmModal">Simpan</a>
            <button style="margin:2px 2px;display: none;" class="btn btn-success" data-toggle="modal"  id="simpan">Simpan</button>
            <a style="margin:2px 2px;display: none;" class="btn btn-success" data-toggle="modal"  id="tolak" data-toggle="modal" data-target="#tolakModal">Simpan</a>
            <a class="btn btn-warning" type="button" href="media.php?module=pembayaranpembelian" style="margin:2px 2px;">Batal</a>
    </div>';


echo'
      <div class="modal fade" id="confirmModal" role="dialog">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <p>Jika sisa alokasi masih ada, status dilabeli  "gantung". apakah anda mau tetap menyimpan atau sisa mau dimasukan kas ? </p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-warning"  id="hppOnly" style="float:left;" type="submit">Tetap Simpan </button> 
              <button type="button" class="btn btn-default" data-dismiss="modal">kembali, Masukan kas</button>
            </div>
          </div>      
        </div>
      </div>
      <div class="modal fade" id="tolakModal" role="dialog">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
                        <div class="modal-header">
                          <h4>sisa alokasi minus, nilai form tidak bisa disimpan</h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">perbaiki nominal</button>
                        </div>
          </div>      
        </div>
      </div>
       <input type="hidden" id="sementara" value="2">
  </form>';
    break;
    } 
    echo '
    <div class="modal fade" id="modalinvoice" role="dialog">
          <div class="modal-dialog modal-lg">                                                  
            <!-- Modal content-->
            <div class="modal-content">
                  <div class="modal-header">
                        <button type="button" class="close" style="color:red;" data-dismiss="modal">Batal &times;</button>
                        <h4 class="modal-title"><b>Pilih Barang Baku Tukang</b></h4>
                  </div>
                  <div class="modal-body">
                    <div role="tabpanel">
                        <div class="tab-content">
                            
                              <table id="modalbarang" border="1" class="table table-hover" style="width: 100%;">
                                  <thead>
                                        <tr style="background-color:#F5F5F5;">
                                                <th id="tablenumber">No</th>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th>Hpp</th>
                                                <th>Aksi</th>
                                        </tr>
                                  </thead>
                              </table>
                       
                        </div>
                    </div>
                    
                  </div><!-- ############## end Modal body -->
            </div><!-- ############## end Modal content -->      
          </div><!-- ############## end Modal dialog -->    
    </div><!-- ############## end Modal fade-->
    ';
}
}

?>
<script type="text/javascript">
 // code to get all records from table via select box
$("#sup").change(function()
 { 
  var id = $("#supplier").find(":selected").val();
  var dataString = 'supplier='+ id;
  $.ajax
  ({
    url: 'modul/purchaseinvoice/filter.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#txtHint").html(r);
   }  
  });
 });
$("#supplier").change(function()
 { 
  var id = $("#supplier").find(":selected").val();
  var dataString = 'text='+ id;
  $.ajax
  ({
    url: 'modul/hitunghpp/filter_pbj.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#tampil").html(r);
   } 
  });
 });

$('#my_modal').on('show.bs.modal', function(e) {
    var bookId = $(e.relatedTarget).data('book-id');
    $(e.currentTarget).find('input[name="bookId"]').val(bookId);
});

  $(document).on('focus', '.hitung', function() {
    var aydi = $('#noz').val()

    $(this).keydown(function() {
         setTimeout(function() {
          dihitung(aydi);
   }, 0);
     
    });
});

 //  $("#no_pbj").change(function()
 // { 
 //  var id = $("#no_pbj").find(":selected").val();
 //  var dataString = 'text='+ id;
 //  $.ajax
 //  ({
 //    url: 'modul/hitunghpp/filter_pbj.php',
 //   data: dataString,
 //   cache: false,
 //   success: function(r)
 //   {
 //    $("#tampil").html(r);
 //   } 
 //  });
 // })
$(document).ready(function()
{  

$(this).keydown(function() {
    setTimeout(function() {
       totalbiaya();
        }, 0);
});
function totalbiaya(){
  var total_barang = ($('#total_barang').val()),
      total = ($('#total').val()),
      total_hpp = parseFloat(total) / parseFloat(total_barang);
  if (!isNaN(total_hpp)) {
      $('#total_hpp').val(total_hpp);
  } 
}
function hitungan(a){
  setTimeout(function() {
    var satuan = ($('#jumlah-' + a).val() != '' ? $('#jumlah-' + a).val() : 0),
        harga = ($('#harga-' + a).val() != '' ? $('#harga-' + a).val() : 0),
        hpp = ($('#hpp-' + a).val() != '' ? $('#hpp-' + a).val() : 0),
        subtotal = (parseFloat(satuan) * parseFloat(harga).toFixed(2));          
    if (!isNaN(subtotal)) {
        $('#total-' + a).val(Math.round(subtotal));
        var alltotalpre = 0;
         $('.total').each(function(){
            alltotalpre += parseFloat($(this).val());
        });
    }
          var alltotal = alltotalpre ;
          $('#total').val(alltotal);
          totalbiaya();
  }, 0);
}

 $("#pbj").change(function()
 { 
  var id = $("#no_pbj").find(":selected").val();
  var dataString = 'no_pbj='+ id;
  $.ajax
  ({
    url: 'modul/purchaseinvoice/filter_lbm.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#alamat").val(r);
   } 
  });
  $("#myModal").modal('show');
 })
$("#sup").change(function()
 { 
  var id = $("#supplier").find(":selected").val();
  var dataString = 'supplier='+ id;
  $.ajax
  ({
    url: 'modul/purchaseinvoice/filter_lbm.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#alamat").val(r);
   } 
  });
  $("#myModal").modal('show');
 })
});
$('#my_modal').on('show.bs.modal', function(e) {
    var bookId = $(e.relatedTarget).data('book-id');
    $(e.currentTarget).find('input[name="bookId"]').val(bookId);
});

function nilaipo(kode) {
  var i = $('#counter').size() + 1;
  kode=kode.split('#');
    var kd1 = kode[0];
    var dataString = 'text='+ kd1+'&nox='+i;
      $("#no_po").val(kode[1]);
      $("#tukang").val(kode[2]);
      $("#no_expedisi").val(kode[1]);
      $("#total_barang").val(kode[3]*1);
      $("#total").val(kode[4]*1);
      $("#id_brg").val(kode[5]);
      $("#id_tukang").val(kode[6]);
       $("#myModal").modal("toggle");
      $.ajax({
    url: 'modul/hitunghpp/hitungdetail.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#tambahrow").html(r);
        var alltotalpbe = 0;
           var aydi = $('#noz').val()
           dihitung(aydi);
            grandtotal1();
   } 
 });
};
$('#titipancheckbox').click(function() {
  if ((this.checked)== true) {
      var datastring="data=supplier";
   $.ajax({  
        url: "modul/pembayaran/ajax_titipan.php",             
        data: datastring, 
        success: function(response){                    
            $("#ttitipancheckbox").html(response); 
        }
    });
  }else{ 

    $("#hapus").remove();
  };

});
$('#titipancheckbox2').click(function() {
  if ((this.checked)== true) {
      var datastring2="data=supplier2";
   $.ajax({  
        url: "modul/pembayaran/ajax_titipan.php",             
        data: datastring2, 
        success: function(response){                    
            $("#ttitipancheckbox2").html(response); 
        }
    });
  }else{ 

    $("#hapus2").remove();
  };

});
 $(document).ready(function() {
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
 
                var t = $('#hitung_hpp').DataTable({
                    "iDisplayLength": 25,
                       "aLengthMenu": [ [25, 50,100],[25,50,100]],
                      "pagingType" : "simple",
                      "ordering": false,
                      "info":     false,
                      "language": {
                            "decimal": ",",
                            "thousands": "."
                          },
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/hitunghpp/load-data.php",
                    "order": [[1, 'desc']],
                    "columns": [
                        { "searchable": false },
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        null,
                        { "searchable": false }
                      ],
                      "destroy": true,
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });
});

function detail(rt){
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
 
                // var t = $('#modalnoinvoice').DataTable({
                //     "iDisplayLength": 10,
                //        "aLengthMenu": [ [10, 20,50],[10,20,50]],
                //       "pagingType" : "simple",
                //       "ordering": false,
                //       "info":     false,
                //     "processing": true,
                //     "serverSide": true,
                //     "ajax": "modul/hitunghpp/load-data_stok.php",
                //     "order": [[1, 'asc']],
                //      "columns": [
                //         { "searchable": false },
                //         null,
                //         null,
                //         { "searchable": false },
                //         { "searchable": false },
                //         { "searchable": false }
                //       ],
                //       "destroy": true,
                //     "rowCallback": function (row, data, iDisplayIndex) {
                //         var info = this.fnPagingInfo();
                //         var page = info.iPage;
                //         var length = info.iLength;
                //         var index = page * length + (iDisplayIndex + 1);
                //         $('td:eq(0)', row).html(index);
                //     }
                // });  
 
                var t = $('#modalbarang').DataTable({
                    "iDisplayLength": 10,
                       "aLengthMenu": [ [10, 20,50],[10,20,50]],
                      "pagingType" : "simple",
                      "ordering": false,
                      "info":     false,
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/hitunghpp/load-data_barang.php",
                    "order": [[1, 'asc']],
                     "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false },
                        { "searchable": false }
                      ],
                      "destroy": true,
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });  
            $('#ini-'+rt).remove();
            $('#sementara').val(rt);
            $('#modalinvoice').modal('show');
              $('#jikaadainvoice-'+rt).append('<div id="ini-'+rt+'"> yang sudah bayar : <input type="text" name="sisa_jmlh[]"   id="sisajmlh-'+rt+'"  class=" form-control hitung numberhit" readonly></div>');
          };
 
datetimepiker();

 if ($('#counter').val()!='') {
              var p=parseInt($('#counter').val())-1;
  }else{
    var p = 1;
  }
function AddRow(rt){
            var i = $('input').size() + 1; 
            
            p += 1;
            var dataString = 'nor='+p;
                $.ajax({
                      url: "modul/hitunghpp/addrow.php",
                     data: dataString,
                     cache: false,
                     success: function(r){
                          $("#tambahrow").append(r);
                     } 
              });
            $('#label').val(p);
        }
function intorow(rt){ 
            $('#modalinvoice').modal('hide');
            var pi = $('#sementara').val();
            var i = $(rt).attr('data'); 
            $('#kode_barang-'+pi).val(i);
            var dataString = 'ajax='+ i;
                $.ajax({
                      url: "modul/hitunghpp/intorow.php",
                     data: dataString,
                     cache: false,
                    success: function(data){
                        var hasil = data.split("@");
                        // var sisa=(hasil[1]  != '' ? hasil[1] : 0);
                          $("#id_barang-"+pi).val(hasil[0]);
                          $("#kode_barang-"+pi).val(hasil[1]);
                          $("#nama_barang-"+pi).val(hasil[2]);
                          $("#harga-"+pi).val(hasil[3]);
                          $("#hpp-"+pi).val(hasil[3]);
                          //alert(r+' rty ' + s + 'hbchsbd' + nominalalokasi);
                     } 
              });
           //$('#bukti_bayar').removeAttr("onclick");
        }
function deleteRow(r) {
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("alokasi_tampil").deleteRow(i);

                var alltotal = 0;
                 $('.harga').each(function(){
                    alltotal += parseFloat($(this).val() != '' ? $(this).val() : 0);
                });
               $('#total').val(alltotal);

                var alltotalalokasi = 0; 
                 $('.alokasi').each(function(){
                    alltotalalokasi += parseFloat($(this).val() != '' ? $(this).val() : 0);
                });
              $('#total_alokasi').val(alltotalalokasi);

              if (!isNaN(alltotalalokasi)) {
                  var nominalbkk = $('#nominalbkk').attr("data"),
                  sisa = parseInt(nominalbkk) - parseInt(alltotalalokasi);            
                  $('#sisa_alokasi').val(sisa);
    }
}

function kode(r){
      var id =  $('#akun_kasdetail-'+r).find(":selected").attr("data");
            $('#viewakun-'+r).val(id); 
}

$(document).on('focus', '.hitung', function() {
    var aydi = $(this).attr('id'),
    berhitung = aydi.split('-');
    $(this).on('keydown click',function() {
         setTimeout(function() {
            var jumlah =  ($('#jumlah-'+berhitung[1]).val() != '' ? $('#jumlah-'+berhitung[1]).val() : 0),
                  hpp = ($('#hpp-'+berhitung[1]).val() != '' ? $('#hpp-'+berhitung[1]).val() : 0),
                  s =  ($('#sisajmlh-'+berhitung[1]).val() ),
                  harga = (parseInt(hpp) * parseInt(jumlah));
                  if (!isNaN(harga) ) {
                    $('#harga-'+berhitung[1]).val(harga);
                  }
                
                var alltotal = 0;
                 $('.harga').each(function(){
                    alltotal += parseFloat($(this).val() != '' ? $(this).val() : 0);
                });
               $('#total').val(alltotal);
        }, 0);     
    });
});

/* data: 'price',
    render: $.fn.dataTable.render.number( ',', '.', 2, '$' )*/
</script>