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
$aksi="modul/konfirmasi-giro/aksi_girojatuhtempo.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
    echo '
    <div class="row">
          <div class="col-md-6">
                <h2>Transaksi Konfirmasi Giro <a class="btn btn-success btn-xs" href="media.php?module=girojatuhtempo" title="Tampilkan BGM dan BGK"><span class="glyphicon glyphicon-refresh"></span></a></h2>
                 <p class="deskripsi">konfirmasi transaksi giro jatuh tempo</p>
           </div>
      </div>
            <hr class="deskripsihr" style="margin-bottom:0px;"><br>
      <div class="row">
       <!-- ###################### Bottom Kiri ################ -->
              <div class="col-md-6">
              <h4><b>Giro yang dibuat</b><small> (Bukti Giro Keluar)</small></h4>
                    <button class="btn btn-default"  id="btngiro" style="margin:0px 5px;">Giro Dibuka </button>
                    <button class="btn btn-success"  id="btngiroterima" style="margin:0px 5px;">Giro Telah Cair </button>  
                    <button class="btn btn-danger"  id="btngirotolak" style="margin:0px 5px;">Giro Tertolak </button>
              </div>
               <!-- ###################### end Bottom Kiri ################ -->


                <!-- ###################### Bottom Kanan ################ -->
              <div class="col-md-6">
              <div class="pull-right">
               <h4><b> Giro yang diterima</b><small> (Bukti Giro Masuk)</small></h4>
                    <button class="btn btn-info"  id="abtngiro" style="margin:0px 5px;">Giro diterima </button>
                    <button class="btn btn-primary"  id="abtngiroterima" style="margin:0px 5px;">Giro Sudah Dicairkan </button>  
                    <button class="btn btn-warning"  id="abtngirotolak" style="margin:0px 5px;">Giro ditolak </button>
              </div>
              </div>
               <!-- ###################### End Bottom Kiri ################ -->
        </div>';

    echo '
   <div class="table-responsive">
<table id="pay_tampil" class="tablefix table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
          <th id="tablenumber">No</th>
          <th>No. Bukti Pembayaran</th>
          <th>Nominal Pembayaran</th>
          <th>Rek Perusahaan</th>
          <th>No. Giro</th>
          <th>Ket</th>
          <th>Tgl Pembayaran</th>
          <th>Jatuh Tempo</th>
          <th>Aksi</th>
          </tr>
        </thead>
         <!-- ###################### tbody di load data girotransaksi-- if "semua" tmoil semua --   ################ -->
    </table>
  </div>';
    break;
    } 
    echo '
    <div class="modal fade" id="confirmgiro" role="dialog">
          <div class="modal-dialog modal-lg">                                                  
            <!-- Modal content-->
            <div class="modal-content">
                  <div class="modal-body" id="confirmgirodetail">';

                  // isi modul di ajax di file ajax_modal.php if 
                  echo '
                  </div> 
          </div><!-- ############## end Modal content -->      
        </div><!-- ############## end Modal dialog -->    
      </div><!-- ############## end Modal fade-->';

 
}
}

?>
<script type="text/javascript">
 $(document).ready(function() {
  datagiro("semua");
 		$('#btngiro').click(function() {
                      datagiro("belumterima");
                      $("#pay_tampil thead tr  th").css({"background-color": "#E4E4E4","color": "#000000"});
                        });
		$('#btngiroterima ').click(function() {
                      datagiro("diterima");
                       $("#pay_tampil thead tr  th").css({"background-color": "#429842","color": "#ffffff"});
                        });
		$('#btngirotolak ').click(function() {
                      datagiro("ditolak");
                       $("#pay_tampil thead tr  th").css({"background-color": "#C3312D","color": "#ffffff"});
                        });

              //################ Giro dari Penerimaan pembayaran
                  $('#abtngiro').click(function() {
                      datagiroa("abelumterima");
                      $("#pay_tampil thead tr  th").css({"background-color": "#31AED4","color": "#ffffff"});
                        });
                  $('#abtngiroterima ').click(function() {
                        datagiroa("aditerima");
                         $("#pay_tampil thead tr  th").css({"background-color": "#275C8A","color": "#03D3FF"});
                          });
                  $('#abtngirotolak ').click(function() {
                        datagiroa("aditolak");
                         $("#pay_tampil thead tr  th").css({"background-color": "#EB9419","color": "#ffffff"});
                          });

});
function datagiro(p){
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
               var t = $('#pay_tampil').DataTable({
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
      "url": "modul/konfirmasi-giro/load-data_girotransaksi.php",
      "cache": false,
            "type": "GET",
      "data": {"giro": p }
    },
                    "order": [[1, 'asc']],
                    "columns": [
                        { "searchable": false },
                        null,
                        { "searchable": false },
                        null,
                        null,
                        null,
                        null,
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
}
function datagiroa(p){
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
               var t = $('#pay_tampil').DataTable({
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
                  "url": "modul/konfirmasi-giro/load-data_girotransaksi.php",
                  "cache": false,
                  "type": "GET",
                  "data": {"giro": p, "aksen": "iya"}
                  },
                          "order": [[1, 'asc']],
                          "columns": [
                              { "searchable": false },
                              null,
                              { "searchable": false },
                              null,
                              null,
                              null,
                              null,
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
}
 function confirmgiro(r,p){
 	  var dataString = 'ajaxmanagiro='+ r +'&buktibayar='+p;
                $.ajax({
                      url: "modul/konfirmasi-giro/ajax_konfirmasi_giro.php",
                     data: dataString,
                     cache: false,
                     success: function(result){
                          $("#confirmgirodetail").html(result);
                     } 
              });
 	$('#confirmgiro').modal('show');
 }
/* data: 'price',
    render: $.fn.dataTable.render.number( ',', '.', 2, '$' )*/
</script>