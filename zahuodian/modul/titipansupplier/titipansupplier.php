<?php
 include "config/koneksi.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();

$filenama = "titipansupplier";
$aksi="modul/$filenama/aksi_$filenama.php";
$module=$_GET['module'];


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
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
          $judul = "Titipan Pembayaran Supplier";
          $desk = "Pembayaran Titipan Supplier ";
          headerDeskripsi($judul,$desk);
          if(isset($_GET['errormsg'])){
            echo '<p style="font-weight:bold;color:red;"> *) '.$_GET['errormsg'].' </p> ';
          }
    echo '
       <!-- #### acording-->
        <div  style="border-radius: 0px 0px 25px 25px;">         
        <div class="panel panel-default" style="border-radius: 0px 0px 25px 25px;">
                        <form method="post" action="'.$aksi.'?module='.$module.'&act=input">
                                      <table class="table table-hover table_without_top" border=0 id=tambah>
                                  <tr style="border-bottom:1px solid #ddd;">
                                      <td class="kop_td">Akun Kas</td>
                                      <td id="sup">';
                                               echo '<select  class="chosen-select  form-control" id="akun_kas" name="akun_kas" required>';
                                              $tampil=mysql_query("SELECT * , CONCAT(kode_akunkasperkiraan,' - ', nama_akunkasperkiraan) as kode FROM akun_kas_perkiraan where is_void=0 ");
                                                          echo "<option value='' selected> - akun kas perkiraan - </option>";
                                                       while($w=mysql_fetch_array($tampil)){
                                                            echo "<option value=$w[id_akunkasperkiraan] data=$w[kode_akunkasperkiraan] >$w[kode]</option>";
                                                          }
                                              echo '</select>
                                              </td>
                                               <td colspan="2"></td>
                                     </tr>
                                       <tr>
                                              <td class="kop_td">No. Bukti </td>';

                                              echo'
                                              <td class="batas_header_form">

                                                  <div class="input-group">
                                                    <input name="no_bukti" class="form-control" value="" id="tampilBuktiBayarTitipan" required>
                                                        <span class="input-group-addon" style="padding:0px 12px;">
                                                             <b  style="border-right: 2px solid #000000;margin:-5px 2px;"> <input type="radio" name="optradio" id="buktibayarCash"> Cash </b>
                                                             <b  style="border-right: 2px solid #000000;margin:-5px 2px;"> <input type="radio" name="optradio" id="buktibayarGiro"> Giro </b>
                                                             <b style="margin:-5px 2px;"> <input type="radio" name="optradio" id="buktibayarTransfer"> Transfer </b>
                                                      </span>
                                                  </div><!-- /input-group -->
                                                      </td>
                                               <td class="kop_td">Tanggal bayar</td>
                                              <td><input name="tgl" value="'.date("Y-m-d").'" class="datepicker form-control"></td>
                                      </tr>
                                        <tr>
                                               <td class="kop_td">Nominal</td>
                                               <td class="batas_header_form">
                                               <input name="nominal" class="form-control numberhit"  required>
                                               <td class="kop_td" colspan="2"></td>
                                     </tr>
                                     <tr>
                                                  <td class="kop_td">Supplier</td>
                                                  <td id="supplierPilih">';
                                               echo '<select  class="chosen-select  form-control" id="supplier" name="supplier" required>';
                                              $tampil=mysql_query("SELECT id_supplier, CONCAT(nama_supplier, ' - ', alamat_supplier) AS ini_supplier FROM supplier where is_void=0 ");
                                                          echo "<option value='' selected> - Nama Supplier - </option>";
                                                       while($w=mysql_fetch_array($tampil)){
                                                            echo "<option value=$w[id_supplier]>$w[ini_supplier]</option>";
                                                          }
                                              echo '</select>
                                              </td>
                                               <td class="kop_td" colspan="2"></td>
                                     </tr>
                                      <tr>
                                                <td colspan="4">
                                                      <label>Keterangan</label>
                                                      <textarea id="ket" name="ket" class="form-control"></textarea>
                                                </td>
                                      </tr>
                            </table>
                            <input class="btn btn-success" type="submit" value=Simpan>
                        </form>
        </div>
    </div>';

    echo '
   <div class="table-responsive">
<table id="pay_tampil" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
          <th id="tablenumber">No</th>
          <th>No. Bukti Pembayaran</th>
          <th>Supplier</th>
          <th>Nominal Pembayaran</th>
          <th>Nominal Alokasi</th>
          <th>Sisa</th>
          <th title="correct">akun kas</th>
          <th>Ket</th>
          <th>Status</th>
          <th style="width:100px;">Alokasi</th>
          </tr>
        </thead>
    </table>
  </div>';

  echo '
    <div class="modal fade" id="editTitipan" role="dialog">
          <div class="modal-dialog modal-lg">                                                  
            <!-- Modal content-->
            <div class="modal-content">
                  <div class="modal-body" id="editTitipanDetail">';

                  echo '
                  </div> 
          </div><!-- ############## end Modal content -->      
        </div><!-- ############## end Modal dialog -->    
      </div><!-- ############## end Modal fade-->';
    break;
 
//#################################  Alokasi
    case "alokasi":
    if(isset($_GET['id'])){
        $id_pay =$_GET['id'];
      }

      $query67 = mysql_query("SELECT *, tb.ket as ket, tb.nominal_alokasi as total_alokasi FROM trans_bayarbeli_header tb left join akun_kas_perkiraan ak on(tb.id_akunkasperkiraan=ak.id_akunkasperkiraan) LEFT JOIN supplier s ON(s.id_supplier=tb.id_supplier) where tb.id_bayarbeli='$id_pay' ");
      $my = mysql_fetch_array($query67);
      $jenisbayar= explode(" - ", $my['bukti_bayar']);

          $judul = "Alokasi Pembayaran Titipan Supplier";
          $desk = "List alokasi pembayaran Titipan Supplier";
          headerDeskripsi($judul,$desk);
      echo ' 
            <form method="post" action="'.$aksi.'?module='.$filenama.'&act=inputdetail&id='.$id_pay.'">
        <table id="tableheader" class="table table-hover table_without_top" border=0>
              <tr style="text-align:left;border-bottom:1px solid #ddd;">
                      <td class="kop_td">Akun Kas</td>
                      <td>';
                               echo '<input class="form-control" name="akun_kas" value="'.$my['kode_akunkasperkiraan'].'-'.$my['nama_akunkasperkiraan'].'" readonly>
                              </td>
                              <td colspan="2"></td>';
                    echo'
              </tr>
             <tr>
                    <td class="kop_td">No. Bukti </td>
                    <td class="batas_header_form"><input name="no_bukti" class="form-control" value="'.$my['bukti_bayar'].'"   readonly></td>
                     <td class="kop_td">Tanggal bayar</td>
                    <td><input id="datepicker" name="tgl" value="'.$my['tgl_pembayaran'].'"  class="form-control" readonly></td>
            </tr>';
            echo'
              <tr>
                     <td class="kop_td">Nominal</td>
                     <td class="batas_header_form" id="sup">
                     <input name="nominal" class="form-control numberhit"  id="nominalbkk" data="'.$my['nominal'].'" value="'.format_rupiah($my['nominal']).'" readonly>
                     <td colspan="2"></td>
               </tr>
                 <tr>
                            <td class="kop_td">Supplier</td>
                            <td id="supplierPilih"><input class="form-control" name="Supplier" value="'.$my['nama_supplier'].'" readonly><input  type="hidden" class="form-control" id="id_supplier" name="id_supplier" value="'.$my['id_supplier'].'"> ';
                        echo '
                        </td>
                         <td class="kop_td" colspan="2"></td>
               </tr>
            <tr>
                      <td colspan="4">
                            <label>Keterangan</label>
                            <textarea id="ket" name="ket" class="form-control" readonly>'.$my['ket'].'</textarea>
                      </td>
            </tr>
  </table>
   <a class="btn btn-success" title="tambah row" onclick="AddRow('.$no.')"><span class="glyphicon glyphicon-plus"></span> Alokasi</a>';
//####################################################  ALOKAISI DETAIL ##########
   echo'
<table id="alokasi_tampil" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
          <th id="tablenumber">No</th>
          <th>Akun Kas Perkiraan</th>
          <th>No. Invoice</th>
          <th>Keterangan</th>
          <th>Sisa dari Invoice</th>
          <th>Total</th>
         <th id="tablenumber">Aksi</th>
          </tr>
        </thead>
        <tbody id="tambahrow">';

           #### JIKA ALOKASI ADA isi
$query23 =( "SELECT *,(trans_bayarbeli_detail.ket) as ket1, CONCAT(akun_kas_perkiraan.kode_akunkasperkiraan,' - ', akun_kas_perkiraan.nama_akunkasperkiraan) as kode FROM trans_bayarbeli_detail left join akun_kas_perkiraan on(trans_bayarbeli_detail.id_akunkasperkiraan_detail=akun_kas_perkiraan.id_akunkasperkiraan) where trans_bayarbeli_detail.bukti_bayar = '$my[bukti_bayar]' AND trans_bayarbeli_detail.is_void='0' ");
        $tampil23=mysql_query($query23);

if(!empty($tampil23)){
        $no = 1;
          while($wp=mysql_fetch_array($tampil23)){
            $query32 =mysql_query( "SELECT  s.id_supplier, s.nama_supplier, ti.grand_total  FROM trans_invoice ti LEFT JOIN supplier s ON(ti.id_supplier=s.id_supplier) where ti.id_invoice = '$wp[nota_invoice]'");
            $gr = mysql_fetch_array($query32);
    echo '<tr>
        <td>'.$no.'</td> <input type="hidden" name="id_bayarbeli_detail[]" value="'.$wp['id_bayarbeli_detail'].'">
        <td>
                  <select id="akun_kasdetail-'.$no.'" name="akun_kasdetail[]" onchange="kode('.$no.')" class="chosen-select form-control" tabindex="2" required>';
                  $tampil=mysql_query("SELECT *, CONCAT(kode_akunkasperkiraan,' - ', nama_akunkasperkiraan) as kode FROM akun_kas_perkiraan where is_void=0 ");
                  echo "<option value='$wp[id_akunkasperkiraan_detail]' data='$wp[kode_akunkasperkiraan]' selected> $wp[kode] </option>"; 

                  while($w=mysql_fetch_array($tampil)){
                    echo "<option value=$w[id_akunkasperkiraan] data=$w[kode_akunkasperkiraan] >$w[kode]</option>";
                  }
      echo '</select><b>Kode akun : </b><input type="text" title="'.$no.'" id="viewakun-'.$no.'" value="'.$wp['kode_akunkasperkiraan'].'" readonly></td>
      <td>';
       if(!empty($wp['nota_invoice']) && $wp['sisa_invoice']<=0){
            echo '<input type="text" name="no_invoice[]"   id="bukti_bayar-'.$no.'"  class="form-control"  value="'.$wp['nota_invoice'].'" readonly>';
       } else {
            echo ' <input type="text" name="no_invoice[]"   id="bukti_bayar-'.$no.'"  class="form-control" onclick="detail('.$no.')"  value="'.$wp['nota_invoice'].'">';
       }
      echo '
             <b>Nominal Invoice : </b><input type="text" name="nominal_detail[]"   id="nominal-'.$no.'"  value="'.$gr['grand_total'].'" class="form-control numberhit" readonly>
              <b><input type="text" class="form-control" id="viewakuntext-'.$no.'" value="'.$gr['nama_supplier'].'" style="text-align:center;" readonly>
              <input type="hidden"  id="viewakuntextsave-'.$no.'" value="'.$gr['id_supplier'].'" name="viewakuntextsave[]"></b>
      </td>
      <td>
              <textarea type="text" name="ketdetail[]"   id="ket-'.$no.'"  class="form-control"  value="'.$wp['ket1'].'" ></textarea>
      </td>
      <td>
              <input type="text" name="sisa_invoice[]"   id="sisainvoice-'.$no.'"  class="sisainvoice form-control hitung numberhit"   value="'.$wp['sisa_invoice'].'" readonly><span id="jikaadainvoice-'.$no.'">
              </span>
      </td>      
      <td>';
                  if(!empty($wp['nota_invoice']) && $wp['sisa_invoice']<=0){
                          echo '
              <input type="text" name="nominal_alokasi[]"   id="nominalalokasi-'.$no.'"  value="'.$wp['nominal_alokasi'].'" class="alokasi form-control hitung numberhit" readonly>';
                } else{
                  echo '
              <input type="text" name="nominal_alokasi[]"   id="nominalalokasi-'.$no.'"  value="'.$wp['nominal_alokasi'].'" class="alokasi form-control hitung numberhit " >';
                  }
      echo '
      </td><input type="hidden" name="nominal_alokasi123[]"   value="'.$wp['nominal_alokasi'].'">             
      <td>
             <a  class="btn btn-xs btn-danger" name="del_item" onclick="deleterecord(this)" title="hapus data" data="'.$wp['id_bayarbeli_detail'].'" data2="'.$wp['nota_invoice'].'"><span class="glyphicon glyphicon-trash"></span></a>
              <a  class="btn btn-xs btn-warning" name="del_item" onclick="deleteRow(this)" title="hapus row"><span class="glyphicon glyphicon-remove"></span></a>
      </td>
  </tr>'; 
      $no++;
    }
      echo'
        </tbody>
        <tfood id="noborder" style="border-top:1px solid #000;">
            <tr>
                    <td colspan="3"></td>
                    <td><b>Total :</b></td>
                    <td><input type="text" name="sisa_totalinvoice"   id="total_sisainvoice"  class="form-control hitung" readonly></td>
                    <td colspan="2"><input type="text" name="total_alokasi"  value="'.$my['total_alokasi'].'"  id="total_alokasi"  class="form-control hitung numberhit" readonly></td>
            </tr>
              <tr>
                    <td colspan="4"></td>
                    <td><b>Sisa Alokasi :</b></td>
                    <td colspan="2"><input type="text" name="sisa_alokasi"  value="'.$my['sisa_alokasi'].'"  id="sisa_alokasi"  class="form-control hitung numberhit" readonly></td>
            </tr>
        </tfood>
    </table>
    <div style="float: right;">
            <input type="hidden" name="status" id="status">
            <a style="margin:2px 2px;display: none;" class="btn btn-success" data-toggle="modal"  id="confirm" data-toggle="modal" data-target="#confirmModal">Simpan</a>
            <button style="margin:2px 2px;display: none;" class="btn btn-success" data-toggle="modal"  id="simpan">Simpan</button>
            <a style="margin:2px 2px;display: none;" class="btn btn-success" data-toggle="modal"  id="tolak" data-toggle="modal" data-target="#tolakModal">Simpan</a>
            <a class="btn btn-warning" type="button" href="media.php?module='.$filenama.'" style="margin:2px 2px;">Batal</a>
    </div>';
}

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

  </div>      <input type="hidden" id="sementara" value="1">
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
                        <h4 class="modal-title"><b>Pilih No Invoice</b></h4>
                  </div>
                  <div class="modal-body">
                  <table id="modalnoinvoice" border="1" class="table table-hover" style="width: 100%;">
                      <thead>
                            <tr style="background-color:#F5F5F5;">
                                    <th id="tablenumber">No</th>
                                    <th>Supplier</th>
                                    <th>No Invoice</th>
                                    <th>Tanggal</th>
                                    <th>Grand Total</th>
                                    <th>Aksi</th>
                            </tr>
                      </thead>
    
                  </table>
                  </div><!-- ############## end Modal body -->
                </div><!-- ############## end Modal content -->      
              </div><!-- ############## end Modal dialog -->    
            </div><!-- ############## end Modal fade-->
    ';
}
}

?>
<script type="text/javascript">
 $(document).ready(function() {
      $("#buktibayarCash").click(function () {
            var dataString = 'jenisbuktibayar=BKK';
                $.ajax({
                      url: "modul/<?php echo $filenama.'/ajax_'.$filenama.'.php' ?>",
                     data: dataString,
                     cache: false,
                     success: function(r){
                          $("#tampilBuktiBayarTitipan").val(r);
                          $("#tampilBuktiBayarTitipan").css({"background-color":"#4AA14A", "color":"#ffffff","font-weight":"bold"});
                     } 
              });
        });
           $("#buktibayarGiro").click(function () {
            var dataString = 'jenisbuktibayar=BGK';
                $.ajax({
                      url: "modul/<?php echo $filenama.'/ajax_'.$filenama.'.php' ?>",
                     data: dataString,
                     cache: false,
                     success: function(r){
                          $("#tampilBuktiBayarTitipan").val(r);
                          $("#tampilBuktiBayarTitipan").css({"background-color":"#EC9923", "color":"#ffffff","font-weight":"bold"});
                     } 
              });
        });
          $("#buktibayarTransfer").click(function () {
            var dataString = 'jenisbuktibayar=BBK';
                $.ajax({
                      url: "modul/<?php echo $filenama.'/ajax_'.$filenama.'.php' ?>",
                     data: dataString,
                     cache: false,
                     success: function(r){
                          $("#tampilBuktiBayarTitipan").val(r);
                          $("#tampilBuktiBayarTitipan").css({"background-color":"#C53430", "color":"#ffffff","font-weight":"bold"});
                     } 
              });
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
                                   var t = $('#pay_tampil').DataTable({
                                        "iDisplayLength": 15,
                                           "aLengthMenu": [ [15,20,50],[15,20,50]],
                                          "pagingType" : "simple",
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
                                                "data": {"titipansupplierglobal": "awal" }
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

                var idSupplier = $('#id_supplier').val();
                var t = $('#modalnoinvoice').DataTable({
                    "iDisplayLength": 10,
                       "aLengthMenu": [ [10, 20,50],[10,20,50]],
                      "pagingType" : "simple",
                      "ordering": false,
                      "info":     false,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                                                "url": "modul/<?php echo $filenama ?>/load-data.php",
                                                "cache": false,
                                                "type": "GET",
                                                "data": {"invoicesupplier": idSupplier }
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
            $('#ini-'+rt).remove();
            $('#sementara').val(rt);
            $('#modalinvoice').modal('show');
              $('#jikaadainvoice-'+rt).append('<div id="ini-'+rt+'"> yang sudah bayar : <input type="text" name="sisa_jmlh[]"   id="sisajmlh-'+rt+'"  class=" form-control hitung numberhit" readonly></div>');
          };
 

var p = 0;
function AddRow(rt){
            var i = $('input').size() + 1;
            p += 1;
            var dataString = 'ajaxmana='+ i +'&nor='+p;
                $.ajax({
                      url: "modul/titipansupplier/ajax_titipansupplier.php",
                     data: dataString,
                     cache: false,
                     success: function(r){
                          $("#tambahrow").append(r);
                     } 
              });
            $('#label').val(p);
        }
function editTitipan (rt){
              var dataString = 'editTitipanSupplier='+ rt;
              $.ajax({
                      url: "modul/<?php echo $filenama.'/ajax_'.$filenama ?>.php",
                     data: dataString,
                     cache: false,
                    success: function(data){
                      $("#editTitipanDetail").html(data);
                     } 
              });
  $('#editTitipan').modal('show');
}
function intorow(rt){
            $('#modalinvoice').modal('hide');
            var pi = $('#sementara').val();
            var i = $(rt).attr('data');
            $('#bukti_bayar-'+pi).val(i);
            var dataString = 'ajaxmana1='+ i;
                $.ajax({
                      url: "modul/pembayaran/ajax_modal.php",
                     data: dataString,
                     cache: false,
                    success: function(data){
                        var res = data.split("-");
                        var r= res[0], 
                        supplier = res[2], 
                        sup = res[3],
                        s =(res[1]  != '' ? res[1] : 0);
                          $("#nominal-"+pi).val(r);
                          $("#sisajmlh-"+pi).val(s);
                            $("#viewakuntext-"+pi).val(supplier);
                            $("#viewakuntextsave-"+pi).val(sup);
                          var nominalalokasi =  ($('#nominalalokasi-'+pi).val() != '' ? $('#nominalalokasi-'+pi).val() : 0),
                          selisih = (parseInt(r) - (parseInt(nominalalokasi)+parseInt(s)));
                          if (!isNaN(selisih) ) {
                              $('#sisainvoice-'+pi).val(selisih);
                          } 
                          //alert(r+' rty ' + s + 'hbchsbd' + nominalalokasi);
                     } 
              });
           //$('#bukti_bayar').removeAttr("onclick");
        }
function deleteRow(r) {
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("alokasi_tampil").deleteRow(i);

                var alltotalalokasi = 0;
                 $('.alokasi').each(function(){
                    alltotalalokasi += parseFloat($(this).val() != '' ? $(this).val() : 0);
                });
                 $('#total_alokasi').val(alltotalalokasi);

                var alltotalpre = 0;
                 $('.sisainvoice').each(function(){
                    alltotalpre += parseFloat($(this).val() != '' ? $(this).val() : 0);
                });
               $('#total_sisainvoice').val(alltotalpre);

              if (!isNaN(alltotalalokasi)) {
                  var nominalbkk = $('#nominalbkk').attr("data"),
                  sisa = parseInt(nominalbkk) - parseInt(alltotalalokasi);              
                  $('#sisa_alokasi').val(sisa);
                }
                if(sisa <= 0){
                  $('#status').val('klop');
                } else {
                  $('#status').val('gantung');
                }
    }

function deleterecord(r) {
  bootbox.confirm({
        message: "Apakah kamu yakin menghapus item tersebut?",
        size: "small",
        closeButton: false,
        buttons: {
            cancel: {
                label: 'Tidak',
                className: 'btn-danger'
            },
            confirm: {
                label: 'Ya',
                className: 'btn-success'
            }
        },
callback: function (result) {
 var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("alokasi_tampil").deleteRow(i);
            var i = $(r).attr('data');
            var i2 = $(r).attr('data2');
            var dataString = 'module=pembayaranpembelian&act=hapusdetail&id='+ i+'&id_pi='+i2;
                $.ajax({
                      url: "modul/pembayaran/aksi_pembayaranpembelian.php",
                     data: dataString,
                     cache: false
                      //success: function(r){
                      //    $("#rowdelete").append(r);
                     //} 
              });

                var alltotalalokasi = 0;
                 $('.alokasi').each(function(){
                    alltotalalokasi += parseFloat($(this).val() != '' ? $(this).val() : 0);
                });
                 $('#total_alokasi').val(alltotalalokasi);

                var alltotalpre = 0;
                 $('.sisainvoice').each(function(){
                    alltotalpre += parseFloat($(this).val() != '' ? $(this).val() : 0);
                });
               $('#total_sisainvoice').val(alltotalpre);

              if (!isNaN(alltotalalokasi)) {
                  var nominalbkk = $('#nominalbkk').attr("data"),
                  sisa = parseInt(nominalbkk) - parseInt(alltotalalokasi);              
                  $('#sisa_alokasi').val(sisa);
                }
                if(sisa <= 0){
                  $('#status').val('klop');
                } else {
                  $('#status').val('gantung');
                }
        }
    });
$('.bootbox .btn-success').click(function() {
  bootbox.hideAll();
});
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
            var nominalalokasi =  ($('#nominalalokasi-'+berhitung[1]).val() != '' ? $('#nominalalokasi-'+berhitung[1]).val() : 0),
                  invoice = ($('#nominal-'+berhitung[1]).val() != '' ? $('#nominal-'+berhitung[1]).val() : 0),
                  s =  ($('#sisajmlh-'+berhitung[1]).val() != '' ? $('#sisajmlh-'+berhitung[1]).val() : 0),
                  selisih = (parseInt(invoice) - (parseInt(nominalalokasi)+parseInt(s)));
                  if (!isNaN(selisih) ) {
                    $('#sisainvoice-'+berhitung[1]).val(selisih);
                  }
                
                var alltotalpre = 0;
                 $('.sisainvoice').each(function(){
                    alltotalpre += parseFloat($(this).val() != '' ? $(this).val() : 0);
                });
               $('#total_sisainvoice').val(alltotalpre);

                var alltotalalokasi = 0;
                 $('.alokasi').each(function(){
                    alltotalalokasi += parseFloat($(this).val() != '' ? $(this).val() : 0);
                });
              $('#total_alokasi').val(alltotalalokasi);

              if (!isNaN(alltotalalokasi)) {
                  var nominalbkk = $('#nominalbkk').attr("data"),
                  sisa = parseInt(nominalbkk) - parseInt(alltotalalokasi);              
                  $('#sisa_alokasi').val(sisa);

         /* if($('#tambahrow').find('.sisainvoice').val() >= '0'){*/
          if(alltotalpre >= '0'){
                  if((sisa > '0')){
                      $('#confirm').show();
                      $('#simpan').hide();
                      $('#tolak').hide();
                      $('#sisa_alokasi').css('border-left','10px solid yellow');
                    } else if((sisa == '0') ) {
                              $('#simpan').show();
                              $('#confirm').hide();
                              $('#tolak').hide();
                              $('#sisa_alokasi').css('border-left','10px solid green');
                    } else  if((sisa < '0') ){
                      $('#tolak').show(); 
                      $('#simpan').hide();
                      $('#confirm').hide();
                      $('#sisa_alokasi').css('border-left','10px solid red');
                    }
                  } else {
                      $('#tolak').show(); 
                      $('#simpan').hide();
                      $('#confirm').hide();
                      $('#sisa_alokasi').css('border-left','10px solid red');
                  }

                }
                if(sisa <= 0){
                  $('#status').val('klop');
                } else {
                  $('#status').val('gantung');
                }

        }, 0);     
    });
});
/* data: 'price',
    render: $.fn.dataTable.render.number( ',', '.', 2, '$' )*/
</script>