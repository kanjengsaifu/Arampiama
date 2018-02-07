<?php
 include "config/koneksi.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();

$filenama = "laporantitipansupplier";
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
$judul = "Laporan Titipan Supplier <button type='button' class='btn btn-success' id='generate' data-toggle='modal' data-target='#gencus' ><span class='glyphicon glyphicon-refresh'></span> Generate</button>";
$desk =  "Laporan transaksi titipan <b>Supplier</b>";

   headerDeskripsi($judul,$desk);

echo '
<div id="gencus" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Kunci Laporan Titipan Supplier</h4>
      </div>

      <div class="modal-body">
          <form method="post" action="'.$aksi.'?module='.$filenama.'&act=generate">
            <table class="table table-hover">
            <tr>
            <td><b>Tanggal : </b></td>
             <td><input  class="form-control datepicker xHeightForm-control" id="periode_awal" name="periode_awal" ></td> 
             <td><input  class="form-control datepicker xHeightForm-control" id="periode_akhir" name="periode_akhir" > </td>
             <td><button class="btn btn-success" type=submit value=Simpan> <span class="glyphicon glyphicon-refresh"></span> </button></td>
             </tr>
             </table>
           </form>
      </div>

    </div>
  </div>
</div>';
echo "<div class='row'>
          <div class='col-md-5'>
    <label>Pilih Nama Supplier :</label>";
    echo '
<div class="input-group">
    <input type="hidden" class="form-control" id="caribaranginput" name="barang" value="">';
        echo '<select  class="chosen-select  form-control" id="caribaranginput1" name="barang1" value="" readonly>';
            $tampil=mysql_query("SELECT id_supplier, CONCAT(kode_supplier, ' - ', nama_supplier) AS ini_supplier, alamat_supplier, telp1_supplier FROM supplier where is_void=0 ");
                        echo "<option value='' selected> - Nama Supplier - </option>";
                     while($w=mysql_fetch_array($tampil)){
                          echo "<option value=$w[id_supplier]>$w[ini_supplier] __$w[alamat_supplier] _ $w[telp1_supplier]</option>";
                        }
            echo '</select>
          <span class="input-group-btn">
              <button class="btn btn-warning" id="caribarangnull"  type="button">( )</button>
          </span>
</div>';
echo "</div>

          <div class='col-md-2'>
                  <label>Tanggal Mulai: </label><input id='minrangecus' name='min' class='form-control datepicker xHeightForm-control'>
          </div>
           <div class='col-md-2'><label>Sampai :</label> 
                <div class='input-group'>
                           <input id='maxrangecus' name='max' class='form-control datepicker xHeightForm-control'>
                           <span class='input-group-btn'>
                                   <button class='btn btn-warning' id='tanggalnull'  type='button' title='Kosongkan Field Tanggal'>( )</button>
                           </span>
                </div>
           </div>
            <button id='caricus' class='btn btn-primary iniganti' style='margin-top:25px;'><b>Cari</b> <span class='glyphicon glyphicon-search' aria-hidden='true'></span></button>
           </div>";

    
            echo "<div class='table-responsive'>
       <table id='laporantitipan' class='display table table-striped table-bordered table-hover' cellspacing='0' width='100%'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
        <th id='tablenumber'>No</th>
        <th>Kode</th>
        <th>Nama</th>
        <th>Regional</th>
        <th>Saldo Awal</th>
        <th>Pembayaran</th>
        <th>Pelunasan</th>
        <th>Saldo Akhir</th>
        <th>Sisa Hutang</th>  
    </tr>
        </thead>
    </table>
    </div>";
  


  break;



  }
}
}
?>


<script type="text/javascript">
 $(document).ready(function() {

      $('#caribarangnull').click(function() {
            $('#caribaranginput').val('');
            $('#caribaranginput1').val('');
            $('.chosen-single span').text(' - Nama Supplier - '); 
    });

    $('#tanggalnull').click(function() {
      $('#minrangecus').val('');
      $('#maxrangecus').val('');
    });

    <?php
  if(isset($_GET['id']) && !empty($_GET['id'])){
      $cuslodeh = $_GET['id'];
      echo '
                    var rt = {"startup" : "'.$cuslodeh.'"};
                    datakartubarang(rt);';
      echo "              
                $('#caricus').click(function() {
                 var ak = $('#maxrangecus').val(),
                 sal = $('#carisalesinput').val(),
                  aw = $('#minrangecus').val();
                    if(ak == ''  || aw =='' ){
                            if (ak == ''  && aw ==''){
                              var rt = {'startup' : $cuslodeh, 'sal' : sal};
                              datakartubarang(rt);
                              $('#kartubarang thead tr  th').css({'background-color': '#4FA84F','color': '#FFFFFF'});
                            } else {
                              alert('Tanggal harus diisi semua');
                            }
                    } else {
                     var rt = {'startup' : $cuslodeh,'sal' : sal, 'akhir' : ak, 'awal' : aw};
                      datakartubarang(rt);
                        $('#kartubarang thead tr  th').css({'background-color': '#4FA84F','color': '#FFFFFF'});
                    }
                } );";

  } else {
          echo '

                   var rt = {"titipan" : "awal"};
                   datakartubarang(rt);
                   ';
           echo "              
                $('#caricus').click(function() {
                 var ak = $('#maxrangecus').val(),
                  aw = $('#minrangecus').val();
                 
                    if(aw != ''){
                              var rt = {'nota' : 'yes', 'awal' : aw,'akhir' : ak};
                              datakartubarang(rt);
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
                                   var t = $('#laporantitipan').DataTable({
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
                                         "columnDefs": [{"className": "dt-right", "targets": [0]},{"className": "dt-left", "targets": [1]}],
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