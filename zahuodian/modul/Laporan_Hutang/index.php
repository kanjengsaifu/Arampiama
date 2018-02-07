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

  $_ck = (array_search("1",$_SESSION['lvl'], true))?'true':'false';
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
$aksi="modul/laporanhutang/aksi_laporanhutang.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  default:
  $judul = "Laporan Rekap Hutang";
  $desk = "Menampilkan Rekap Hutang Pada Supplier";
  headerDeskripsi($judul,$desk);
  
    echo "  
  <div class='row' style='padding:5px 0px;'>
          <div class='col-md-5'>
    <label>Pilih Nama Supplier :</label>";
    echo '
<div class="input-group">
    <input type="hidden" class="form-control" id="caribaranginput" name="barang" value=""> 
     <input  type="text" class="form-control" id="caribaranginput1" name="barang1" value="" readonly> 
          <span class="input-group-btn">
              <button class="btn btn-success" id="caribarangbutton"  type="button" style="padding:0px 12px;"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
              <button class="btn btn-warning" id="caribarangnull"  type="button" style="padding:0px 12px;">( )</button>
          </span>
</div>';
echo '</div>';

echo "
          <div class='col-md-2'><label>Tanggal Mulai: </label><input id='minrangekartubarang' name='min' class='datepicker'></div>
           <div class='col-md-2'><label>Sampai :</label> <input id='maxrangekartubarang' name='max' class='datepicker'></div>
           <div class='col-md-1'>
          <button id='carikartubarang' class='btn btn-primary allsupbuah' ><b>Cari</b> <span class='glyphicon glyphicon-search' aria-hidden='true'></span></button></div>
           <div class='col-md-1'>
          <button type='button' class='btn btn-success allsupbuah' data-toggle='modal' data-target='#myModal'>Kunci Hutang</button></div>

<!-- Modal -->
<div id='myModal' class='modal fade' role='dialog'>
  <div class='modal-dialog'>

    <!-- Modal content-->
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'>&times;</button>
        <h4 class='modal-title'>Kunci Hutang</h4>
      </div>
      <div class='modal-body'>
       <table></table>

      <table class='table table-hover'>
      <tr>
      <td>
      <form method='post' action='$aksi?module=laporanhutang&act=generate' enctype='multipart/form-data'>
      kunci bulan :</td>
      <td>
       <input  class='form-control datepicker' id='periode_awal' name='periode_awal' > 
      </td>
        <td>
       <input  class='form-control datepicker' id='periode_akhir' name='periode_akhir' > 
      </td>
      <td>
       <button class='btn btn-success' type=submit value=Simpan> Generate </button>
      </td>
      </tr>

      </table>
      </div>
      <div class='modal-footer'>
      </div>
    </div>

  </div>
</div>
      </div>
  <div class='modal fade' id='myModal' role='dialog'>
    <div class='modal-dialog'>
    

      <div class='modal-content'>
        <div class='modal-header'>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
          <h4 class='modal-title'>Modal Header</h4>
        </div>

        <div class='modal-body'>
          <p>Some text in the modal.</p>
        </div>

        <div class='modal-footer'>
          <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
        </div>
      </div>
      
    </div>
  </div>

<div class='table-responsive'>
    <table id='kartubarang'  class='display table table-striped table-bordered table-hover' cellspacing='0' width='100%'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
        <th id='tablenumber'>No</th>
      <th>Nama Supplier</th>
      <th>Alamat</th>
      <th>Saldo Awal</th>
      <th>Pembelian</th>
      <th>Pembayaran</th>
      <th>Saldo Akhir</th>
      <th>Aksi</th>
    </tr>
        </thead>

    </table>
  </div>";
  echo '
<div class="modal fade" id="caribarangmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Cari Barang</h4>
      </div>
      <div class="modal-body" id="showcaribarang">';

      echo '<table id="modalbarang" class="display table table-striped table-bordered table-hover" cellspacing="0" style="width: 100%;">
        <thead>
                <tr style="background-color:#F5F5F5;">
                    <th  id="tablenumber">No</th>
                    <th>Kode Supplier</th>
                    <th>Nama Supplier</th>
                    <th>Telp</th>
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
case "detail":
  $id=$_GET[id];
  $query=mysql_query("Select * From Supplier Where id_Supplier=$id");
  $r=mysql_fetch_array($query);
 $judul = "Kartu Hutang";
  $desk = "Menampilkan Rekap Hutang Pada Supplier $r[nama_supplier]";
  headerDeskripsi($judul,$desk);
  
    echo "  
  <div class='row' style='padding:5px 0px;'>
         ";
    echo '

</div>';
echo '</div>';

echo "
          <div class='col-md-2'><label>Tanggal Mulai: </label><input id='minrangekartubarang' name='min' class='datepicker'></div>
           <div class='col-md-2'><label>Sampai :</label> <input id='maxrangekartubarang' name='max' class='datepicker'></div>
           <div class='col-md-1'>
          <button id='carikartubarang' class='btn btn-primary allsupbuah' ><b>Cari</b> <span class='glyphicon glyphicon-search' aria-hidden='true'></span></button></div>
         
      </div>
  

<div class='table-responsive'>
    <table id='kartubarang'  class='display table table-striped table-bordered table-hover' cellspacing='0' width='100%'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
        <th id='tablenumber'>No</th>
       <th>Nota</th> 
      <th>Tanggal Transaksi</th>
      <th>Pembelian</th>
      <th>Pembayaran</th>
      <th>Saldo Akhir</th>

    </tr>
        </thead>

    </table>
  </div>";
  echo '
<div class="modal fade" id="caribarangmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Cari Barang</h4>
      </div>
      <div class="modal-body" id="showcaribarang">';

      echo '<table id="modalbarang" class="display table table-striped table-bordered table-hover" cellspacing="0" style="width: 100%;">
        <thead>
                <tr style="background-color:#F5F5F5;">
                    <th  id="tablenumber">No</th>
                    <th>Kode Supplier</th>
                    <th>Nama Supplier</th>
                    <th>Telp</th>
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
    $('#caribarangnull').click(function() {
            $('#caribaranginput').val('');
            $('#caribaranginput1').val('');
    });

      <?php
  if(isset($_GET['id']) && !empty($_GET['id'])){
      $id_supp = $_GET['id'];
      echo "
                data_detail('$id_supp',null,null);
                $('#carikartubarang').click(function() {
                 var sup = $id_supp,
                  ak = $('#maxrangekartubarang').val(),
                  aw = $('#minrangekartubarang').val();
                data_detail(sup,aw, ak);
                } );";}
                    else {
                      echo "
                      datakartubarang(null,null,null);

              $('#carikartubarang').click(function() {
                 var sup = $('#caribaranginput').val(),
                  ak = $('#maxrangekartubarang').val(),
                  aw = $('#minrangekartubarang').val();
                datakartubarang(sup,aw, ak);
                } );
              $('#allkartubarang').click(function() {
                  datakartubarang(null,null,null);";
                  echo '
                $("#kartubarang thead tr  th").css({"background-color": "#275C8A","color": "#FFFFFF"});
                });';

                    }
                    ?>
  $('#caribarangbutton').click(function() {
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
                                   var t = $('#modalbarang').DataTable({
                                        "iDisplayLength": 15,
                                           "aLengthMenu": [ [15,20,50],[15,20,50]],
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
                                                "url": "modul/laporanhutang/load-data.php",
                                                "cache": false,
                                                "type": "GET",
                                                "data": {"caribarang": "cari" }
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
     $('#caribarangmodal').modal('show');
  });
  
});
 function datakartubarang(s,a,k){
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
                                   var t = $('#kartubarang').DataTable({
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
                          "url": "modul/laporanhutang/load-data.php",
                          "cache": false,
                                "type": "GET",
                          "data": {"barang": s,
                                          "awal" : a,
                                          "akhir" : k }
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
 function data_detail(s,a,k){
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
                                   var t = $('#kartubarang').DataTable({
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
                                           "columnDefs": [{"className": "dt-right", "targets": [3,4,5]},{"className": "dt-left", "targets": [1,2]}],
                                       /* "ajax" : "modul/pembayaran/load-data_girotransaksi.php",*/
                             "ajax": {
                          "url": "modul/laporanhutang/load-data_detail.php",
                          "cache": false,
                                "type": "GET",
                          "data":  {"barang": s,
                                          "awal" : a,
                                          "akhir" : k }
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

function addMore(p,d){
  $('#caribaranginput').val(p);
 $('#caribaranginput1').val(d);
      $('#caribarangmodal').modal('hide');
}

$( function() {
    $( ".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd'
    });
  } );        
        </script>