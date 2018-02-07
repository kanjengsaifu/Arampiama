<?php
 include "config/koneksi.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();

$filenama = "laporanpenjualan";
$aksi="modul/$filenama/aksi_$filenama.php";
$judul = "Laporan Penjualan";
$desk = "Laporan transaksi penjualan customer";

 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{

  $_ck = (array_search("1",$_SESSION['lvl'], true))?'true':'false';
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{

echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:

  headerDeskripsi($judul,$desk);
  
    echo "  
  <div class='row' style='padding:5px 0px;'>
          <div class='col-md-5'>
    <label>Customer :</label>";
    echo '
<div class="input-group">
    <input type="hidden" class="form-control" id="caricustomerinput" name="customer" value=""> 
     <input  type="text" class="form-control" id="caricustomerinput1" name="customer1" value="" readonly> 
          <span class="input-group-btn">
              <button class="btn btn-success" id="caricustomerbutton"  type="button" style="padding:0px 12px;"><span class="glyphicon glyphicon-search" aria-hidden="true" title="Cari Customer"></span></button>
              <button class="btn btn-warning" id="caricustomernull"  type="button" style="padding:0px 12px;" title="Kosongkan Field Customer">( )</button>
          </span>
</div><br>
<label>Sales :</label>
<div class="input-group">
    <input type="hidden" class="form-control" id="carisalesinput" name="sales" value=""> 
     <input  type="text" class="form-control" id="carisalesinput1" name="sales1" value="" readonly> 
          <span class="input-group-btn">
              <button class="btn btn-success" id="carisalesbutton"  type="button" style="padding:0px 12px;"><span class="glyphicon glyphicon-search" aria-hidden="true" title="Cari Sales"></span></button>
              <button class="btn btn-warning" id="carisalesnull"  type="button" style="padding:0px 12px;" title="Kosongkan Field Sales">( )</button>
          </span>
</div>
';
echo '</div>';

echo "
          <div class='col-md-2'><label>Tanggal Mulai: </label><input id='minrangecus' name='min' class='form-control datepicker'></div>
           <div class='col-md-2'><label>Sampai :</label> 
                <div class='input-group'>
                           <input id='maxrangecus' name='max' class='form-control datepicker'>
                           <span class='input-group-btn'>
                                   <button class='btn btn-warning' id='tanggalnull'  type='button' style='padding:0px 12px;' title='Kosongkan Field Tanggal'>( )</button>
                           </span>
                </div>
           </div>
           <div class='col-md-1'>
          <button id='caricus' class='btn btn-lg btn-primary' ><b>Cari</b> <span class='glyphicon glyphicon-search' aria-hidden='true'></span></button></div>
           <div class='col-md-1'>
          <button id='allcus' class='btn btn-lg btn-success'><b>ALL</b></button></div>
      </div>

<div class='table-responsive'>
    <table id='laporanpenjualan' class='display table table-striped table-bordered table-hover' cellspacing='0' width='100%'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
        <th id='tablenumber'>No</th>
        <th>Tanggal</th>
        <th>No. Invoice</th>
        <th>No. SO</th>
        <th>SubTotal</th>
        <th>PPN</th>
        <th>Diskon</th>
        <th>GrandTotal</th>
        <th>Pembayaran</th>
        <th>Laba</th>
        <th>Customer</th>        
        <th>Sales</th>
    </tr>
        </thead>
    </table>
  </div>";
  echo '
<div class="modal fade" id="caricustomermodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Cari Customer</h4>
      </div>
      <div class="modal-body" id="showcaricustomer">';

      echo '<table id="modalcustomer" class="display table table-striped table-bordered table-hover" cellspacing="0" style="width: 100%;">
        <thead>
                <tr style="background-color:#F5F5F5;">
                    <th  id="tablenumber">No</th>
                    <th>Kode Customer</th>
                    <th>Nama Customer</th>
                    <th>Alamat</th>
                    <th class="tablenumber">Cari</th>
                </tr>
        </thead>
      </table>';

            echo '
</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';


  echo '
<div class="modal fade" id="carisalesmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Cari Sales</h4>
      </div>
      <div class="modal-body" id="showcarisales">';

      echo '<table id="modalsales" class="display table table-striped table-bordered table-hover" cellspacing="0" style="width: 100%;">
        <thead>
                <tr style="background-color:#F5F5F5;">
                    <th  id="tablenumber">No</th>
                    <th>Nama Sales</th>
                    <th>telpon1</th>
                    <th>telpon2</th>
                    <th class="tablenumber">Cari</th>
                </tr>
        </thead>
      </table>';

            echo '
</div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';
    break;

  }
}
}
function rupiah($nilai, $pecahan=0){
  return number_format($nilai,$pecahan,',','.');
}
?>


<script type="text/javascript">
 $(document).ready(function() {
    $('#caricustomernull').click(function() {
            $('#caricustomerinput').val('');
            $('#caricustomerinput1').val('');
    });
    $('#carisalesnull').click(function() {
            $('#carisalesinput').val('');
            $('#carisalesinput1').val('');
    });
    $('#tanggalnull').click(function() {
      $('#minrangecus').val('');
      $('#maxrangecus').val('');
    });
  $('#caricustomerbutton').click(function() {
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
                                   var t = $('#modalcustomer').DataTable({
                                        "iDisplayLength": 10,
                                           "aLengthMenu": [ [10,20,50],[10,20,50]],
                                          "pagingType" : "simple",
                                          "ordering": false,
                                          "info":     false,
                                          "language": {
                                                "decimal": ",",
                                                "thousands": "."
                                              },
                                        "processing": true,
                                        "serverSide": true,
                                       /* "ajax" : "modul/pembayaran/load-data_girotransaksi.php",*/
                                       "ajax": {
                                                "url": "modul/<?php echo $filenama ?>/load-data.php",
                                                "cache": false,
                                                "type": "GET",
                                                "data": {"customer" : "customer"}
                                              },
                                        "order": [[1, 'asc']],
                                        "destroy": true,
                                        "rowCallback": function (row, data, iDisplayIndex) {
                                            var info = this.fnPagingInfo();
                                            var page = info.iPage;
                                            var length = info.iLength;
                                            var index = page * length + (iDisplayIndex + 1);
                                        $('td:eq(0)', row).html(index);
                                        }
                                    });   
      $('#caricustomermodal').modal('show');
  });

  $('#carisalesbutton').click(function() {
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
                                   var t = $('#modalsales').DataTable({
                                        "iDisplayLength": 10,
                                           "aLengthMenu": [ [10,20,50],[10,20,50]],
                                          "pagingType" : "simple",
                                          "ordering": false,
                                          "info":     false,
                                          "language": {
                                                "decimal": ",",
                                                "thousands": "."
                                              },
                                        "processing": true,
                                        "serverSide": true,
                                       /* "ajax" : "modul/pembayaran/load-data_girotransaksi.php",*/
                                       "ajax": {
                                                "url": "modul/<?php echo $filenama ?>/load-data.php",
                                                "cache": false,
                                                "type": "GET",
                                                "data": {"sales" : "sales"}
                                              },
                                        "order": [[1, 'asc']],
                                        "destroy": true,
                                        "rowCallback": function (row, data, iDisplayIndex) {
                                            var info = this.fnPagingInfo();
                                            var page = info.iPage;
                                            var length = info.iLength;
                                            var index = page * length + (iDisplayIndex + 1);
                                        $('td:eq(0)', row).html(index);
                                        }
                                    });   
      $('#carisalesmodal').modal('show');
  });


  var rt = {"startup" : "awal"};
  datakartubarang(rt);

              $('#caricus').click(function() {
                 var cus = $('#caricustomerinput').val(),
                 sal = $('#carisalesinput').val(),
                  ak = $('#maxrangecus').val(),
                  aw = $('#minrangecus').val();
                    if(ak == ''  || aw =='' ){
                            if (ak == ''  && aw ==''){
                              var rt = {"cus" : cus, "sal" : sal};
                              datakartubarang(rt);
                              $("#kartubarang thead tr  th").css({"background-color": "#4FA84F","color": "#FFFFFF"});
                            } else {
                              alert("Tanggal harus diisi semua");
                            }
                    } else {
                     var rt = {"cus" : cus, "sal" : sal, "akhir" : ak, "awal" : aw};
                      datakartubarang(rt);
                        $("#kartubarang thead tr  th").css({"background-color": "#4FA84F","color": "#FFFFFF"});

                    }
                } );
              $('#allcus').click(function() {
                   var rt = {"startup" : "awal"};
                    datakartubarang(rt);
                $("#kartubarang thead tr  th").css({"background-color": "#275C8A","color": "#FFFFFF"});
                });

});
 function datakartubarang(p){
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
                                   var t = $('#laporanpenjualan').DataTable({
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
                                       /* "ajax" : "modul/pembayaran/load-data_girotransaksi.php",*/
                             "ajax": {
                          "url": "modul/<?php echo $filenama ?>/load-data.php",
                          "cache": false,
                                "type": "GET",
                          "data": p
                        },
                                        "order": [[1, 'asc']],
                                        "destroy": true,
                                        "rowCallback": function (row, data, iDisplayIndex) {
                                            var info = this.fnPagingInfo();
                                            var page = info.iPage;
                                            var length = info.iLength;
                                            var index = page * length + (iDisplayIndex + 1);
                                        $('td:eq(0)', row).html(index);
                                        }
                                    });          
                    }

function addMore(p,d,h){
  $('#cari'+h+'input').val(p);
 $('#cari'+h+'input1').val(d);
      $('#cari'+h+'modal').modal('hide');
}

$( function() {
    $( ".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd'
    });
  } );        
        </script>