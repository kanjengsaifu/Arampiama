<?php
 include "config/koneksi.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();

$filenama = "laporanpembelian";
$aksi="modul/$filenama/aksi_$filenama.php";


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
$judul = "Laporan Pembelian Group By Barang";
$desk =  "Laporan transaksi Pembelian dari setiap <b>Barang</b>";

   headerDeskripsi($judul,$desk);

echo "<div class='row'>
          <div class='col-md-2'>
                  <label>Tanggal Mulai: </label><input id='minrangecus' name='min' class='form-control datepicker'>
          </div>
           <div class='col-md-2'><label>Sampai :</label> 
                <div class='input-group'>
                           <input id='maxrangecus' name='max' class='form-control datepicker'>
                           <span class='input-group-btn'>
                                   <button class='btn btn-warning' id='tanggalnull'  type='button' style='padding:0px 12px;' title='Kosongkan Field Tanggal'>( )</button>
                           </span>
                </div>
           </div>
           <div class='row'>
                      <button id='caricus' class='btn btn-primary iniganti' ><b>Cari</b> <span class='glyphicon glyphicon-search' aria-hidden='true'></span></button>
                      <button id='caricus3' class='btn btn-primary iniganti3' style='display:none;'><b>Cari</b> <span class='glyphicon glyphicon-search' aria-hidden='true'></span></button>
           </div>
           </div>";

    
            echo "<div class='table-responsive'>
       <table id='laporanpenjualan3' class='display table table-striped table-bordered table-hover' cellspacing='0' width='100%'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
        <th id='tablenumber'>No</th>
        <th>Nama Supplier</th>
        <th>No Telp</th>
        <th class='dt-right'>Total Pembelian</th>
        <th>Total Terbayar</th>
        <th>Sisa Pembayaran</th>
        <th>Aksi</th>
    </tr>
        </thead>
    </table>
    </div>";
  


  break;

case "detail":
$judul = "Laporan Pembelian Detail Pernota";
$desk =  "Laporan transaksi pembelian dari supplier per <b>Nota</b>";

   headerDeskripsi($judul,$desk);

echo "<div class='row'>
          <div class='col-md-2'>
                  <label>Tanggal Mulai: </label><input id='minrangecus' name='min' class='form-control datepicker'>
          </div>
           <div class='col-md-2'><label>Sampai :</label> 
                <div class='input-group'>
                           <input id='maxrangecus' name='max' class='form-control datepicker'>
                           <span class='input-group-btn'>
                                   <button class='btn btn-warning' id='tanggalnull'  type='button' style='padding:0px 12px;' title='Kosongkan Field Tanggal'>( )</button>
                           </span>
                </div>
           </div>
           <div class='row'>
                      <button id='caricus' class='btn btn-primary iniganti' ><b>Cari</b> <span class='glyphicon glyphicon-search' aria-hidden='true'></span></button>
                      <button id='caricus3' class='btn btn-primary iniganti3' style='display:none;'><b>Cari</b> <span class='glyphicon glyphicon-search' aria-hidden='true'></span></button>
           </div>
           </div>";

    
            echo "<div class='table-responsive'>
       <table id='laporanpenjualan3' class='display table table-striped table-bordered table-hover' cellspacing='0' width='100%'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
        <th id='tablenumber'>No</th>
        <th>Nama Barang</th>
        <th>Kode Barang</th>
        <th>Merk</th>
        <th>Jumlah Barang Yang Diterima</th>
        <th>aksi</th>

    </tr>
        </thead>
    </table>
    </div>";
  


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



    $('#tanggalnull').click(function() {
      $('#minrangecus').val('');
      $('#maxrangecus').val('');
    });

    <?php
  if(isset($_GET['id']) && !empty($_GET['id'])){
      $id_supplierh = $_GET['id'];
      $periode_awal= $_GET['periode_awal'];
      if (empty($_GET['periode_akhir'])){
         $periode_akhir= $_GET['periode_akhir'];
      }else{
         $periode_akhir= "";
      }

      echo '
                    var rt = {"id_detail" : "'.$id_supplierh.'","awal" : "'.$periode_awal.'","akhir" : "'.$periode_akhir.'"};
                    datakartubarang3(rt);
                    ';
      echo "              
                $('#caricus').click(function() {
                 var ak = $('#maxrangecus').val(),
                 sal = $('#carisalesinput').val(),
                  aw = $('#minrangecus').val();
                    if(ak == ''  || aw =='' ){
                            if (ak == ''  && aw ==''){
                              var rt = {'id_detail' : $id_supplierh, 'awal' : sal};
                              datakartubarang3(rt);
                              $('#kartubarang thead tr  th').css({'background-color': '#4FA84F','color': '#FFFFFF'});
                            } else {
                              alert('Tanggal harus diisi semua');
                            }
                    } else {
                     var rt = {'id_detail' : $id_supplierh,'sal' : sal, 'akhir' : ak, 'awal' : aw};
                      datakartubarang3(rt);
                        $('#kartubarang thead tr  th').css({'background-color': '#4FA84F','color': '#FFFFFF'});
                    }
                } );";
  } else {
          echo '

                   var rt = {"nota" : "yes"};
                   datakartubarang3(rt);
                   ';
           echo "              
                $('#caricus').click(function() {
                 var ak = $('#maxrangecus').val(),
                  aw = $('#minrangecus').val();
                 
                    if(aw != ''){
                              var rt = {'nota' : 'yes', 'awal' : aw,'akhir' : ak};
                              datakartubarang3(rt);
                              $('#laporanpenjualan thead tr  th').css({'background-color': '#4FA84F','color': '#FFFFFF'});
                            } else {
                              alert('Tanggal harus diisi semua');
                            }
                } );

                ";
  } 
  ?>



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
                          "url": "modul/<?php echo $filenama ?>/load-data-Supplier.php",
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

function datakartubarang3(p){
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
                                   var t = $('#laporanpenjualan3').DataTable({
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
                          "url": "modul/<?php echo $filenama ?>/load-data-Supplier.php",
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