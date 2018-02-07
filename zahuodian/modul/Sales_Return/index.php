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
  
$aksi="modul/returpenjualan/aksi_returpenjualan.php";
switch($_GET['act']){
  // Tampil Modul
  default:
       echo '
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#all">Customer</a></li>
      <li><a data-toggle="tab" href="#payment">List Payment</a></li>
    </ul>

    <div class="tab-content">
      <div id="all" class="tab-pane fade in active">
        <div class="table-responsive">
          <table class="tb_customer display table table-striped table-bordered table-hover">
          <thead>
          <tr style="background-color:#F5F5F5;"">
            <th id="tablenumber">No</th>
            <th>Customer</th>
            <th>Region</th>
            <th>Telp Customer</th>
            <th>Telp Sales</th>
            <th>Aksi</th>
          </tr></thead>
          </table>
        </div>
      </div>
      <div id="payment" class="tab-pane fade">
        <div class="table-responsive">
          <table id="payment" class="display table table-striped table-bordered table-hover" style="width:100%;">
          <thead>
          <tr style="background-color:#F5F5F5;"">
            <th id="tablenumber">No</th>
            <th>Kode</th>
            <th>Customer</th>
            <th>Tipe Customer</th>
            <th>Alamat</th>
            <th>Region</th>
            <th>Telp Customer</th>
            <th>Telp Sales</th>
            <th>Aksi</th>
          </tr></thead>
          </table>
        </div>
      </div>
    </div>
    ';

    break;

  case "tambah":
  $id=$_GET['id'];
  $bs=$_GET['bs'];
  if ($bs=='1') {
    $ket='Retur Kembali BS';
  }else {
     $ket='Retur Kembali Barang';
  }
$query=mysql_query("Select * from customer where id_customer='".$id."'");
$r=mysql_fetch_array($query);

        $tampil=mysql_query("SELECT kode_rjb FROM `trans_retur_penjualan` order by id desc limit 1 ");
      $kode    = mysql_fetch_array($tampil);
?>
<h2><b>Tambah</b> Retur Penjualan</h2><hr>
<form method='post' action='modul/returpenjualan/aksi_returpenjualan.php?module=returpenjualan&act=input'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id='tambah'>
  <tr>
        <td>No Retur</td>
        <td><strong>:</strong></td>
        <td><strong><?= kode_surat('RJB','trans_retur_penjualan','kode_rjb','id') ?></strong></td>
        <td>Tgl. Retur</td>
        <td><strong>:</strong></td>
        <td><input class="form-control datetimepicker" value=" <?php echo date('Y-m-d') ?>" id="tgl_rjb" name="tgl_rjb" required></td>
  </tr>
  <tr>
        <td>Customer</td> 
        <td><strong>:</strong></td>
        <td><?= $r['kode_customer'] ?> - <?= $r['nama_customer'] ?>
        <input type="hidden" id='id_customer' name='id_customer' value="<?= $r[id_customer] ?>" ></td>
          <td>Jenis Retur</td> 
          <td><strong>:</strong></td>
          <td><?= $ket ?> <input type="hidden" id='jenis_retur' name='jenis_retur' value="<?= $bs ?>"></td>
    </tr>
    <tr>
    <td>Alasan Retur <br><div class="btn btn-primary" type="button"  data-toggle="modal" data-target="#view_barang">Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
 </div> </td>
    <td><strong>:</strong></td>
    <td colspan="4"><textarea class="form-control" id="ket" name="ket" required></textarea></td>
  
    </tr>
    </table>

<div class="modal fade" id="modalrjb" role="dialog">
    <div class="modal-dialog modal-lg">    
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nomer Invoice</h4>
        </div><!-- ############## end Modal header -->
        <div class="modal-body">
    <table id="modalnoinvoice" border="1" class="table table-hover" style="width: 100%;">
    <thead>
    <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Customer</th>
      <th>No Invoice</th>
      <th>Tanggal</th>
      <th>Grand Total</th>
      <th>Total Pembayaran</th>
      <th>Aksi</th>
    </tr>
    </thead>
<tbody id="tampilnota">
</tbody>
    </table>
        </div><!-- ############## end Modal body -->
      </div><!-- ############## end Modal content -->      
    </div><!-- ############## end Modal dialog -->    
  </div><!-- ############## end Modal fade-->

<table id="tblrjb" class="table table-hover table-bordered" cellspacing="0">
            <thead>
  <tr style="background-color:#F5F5F5;">
     <th rowspan="2" >Nomor Nota</th>
      <th rowspan="2" >Nama Barang</th>
      <th rowspan="2" >Qty</th>
      <th rowspan="2" >Harga per unit</th>

         <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  colspan="6"><b>RETUR</b></th>
        </tr>
        <tr>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff;  width: 100px"  >Gudang</th>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff; width: 100px;"  >Satuan</th>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff; width: 100px;"  >Jumlah</th>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  >Harga</th>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  >Total Harga</th>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  >Del</th>
        </tr>
        </thead> 
        <tbody id='body_table_tblrjb'>
          
        </tbody>
          <tfoot>
        <tr id="productall">
    <td colspan="4" rowspan="4"> <br><button class="btn btn-success"  type="submit"  name="save" value="Save" style="float:left;margin-left:20px;">Save </button> 
          <a class="btn btn-warning" type="button" href="media.php?module=returpembelian" style="float:left;margin-left:10px;">Batal</a>
          </td>
    <td colspan="4" style="text-align:right;" ><p><b>Total Retur </b></p></td>
    <td colspan="1"  ><input name="returtotal" type="text"  class="rjb2 form-control numberhit"  id="returtotal"  readonly></td>
  </tr>

  <tr>
     <td colspan="4" style="text-align:right;"><p> Disc (%) <input name="returdiscpersen" type="text" id="returdiscpersen" style="width:2em;"  class="rjb2 form-control numberhit" > | (Rp) </p></td>
    <td colspan="1" style="nowrap:nowrap;"><input name="returdiscnominal" type="text" id="returdiscnominal"   class="rjb2 form-control numberhit"  ></td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:right;"><p> Ppn (%) <input name="returppnpersen" type="text" id="returpersenppn"  style="width:2em;" class="rjb2 form-control numberhit" > | (Rp) </p></td>
    <td colspan="1"  style="nowrap:nowrap;"><input name="returppnnominal" type="text" id="returppnnominal"   class="rjb2 form-control numberhit"  ></td>
  </tr>
  <tr>
     <td colspan="4" style="text-align:right;"><b>Grand total retur</b></td>
    <td colspan="1"><b><input name="returgrandtotal" type="text" id="grandtotalretur"  readonly="readonly"  class="rjb2 form-control numberhit" ></b></td>
  </tr>
                </tfoot>
</table>

</form></div>






  <!-- ###########   Modal  ###############-->
  <div id="view_barang" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
      <table id="table_barang" class="table table-hover table-bordered" cellspacing="0" style="width: 100%;">
        <thead>
                <tr style="background-color:#F5F5F5;">
                    <th  id="tablenumber">No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Stok sekarang</th>
                    <th>Stok Min</th>
                    <th>Tambah</th>
                </tr>
        </thead>
      </table>
          </div>
      </div>
    </div>
  </div>
    <!-- ###########   Modal  ###############-->
  <div id="view_nota" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-body">
      <table id="table_nota" class="table table-hover table-bordered" cellspacing="0" style="width: 100%;">
        <thead>
                <tr style="background-color:#F5F5F5;">
                    <th  id="tablenumber">No</th>
                    <th>Tgl Invoice</th>
                    <th>Nota Invoice</th>
                    <th>Nota Customer</th>
                    <th>Jumlah</th>
                    <th>Harga Per Unit</th>
                    <th>Aksi</th>
                </tr>
        </thead>
        <tbody id='body_table_nota'>
          
        </tbody>

      </table>
          </div>
      </div>
    </div>
  </div>
  <?php

    break;

    
  }
}
}
function cari_kode(){
    $var = explode('-', date("Y-m-d"));
    $sql_cari="SELECT max(kode_rjb) as kode FROM `trans_retur_penjualan` where `kode_rjb` LIKE 'RJB/$var[0]$var[1]%' ";
    $result=mysql_query($sql_cari);
    $hasil=mysql_fetch_array($result);
    $kode = explode('/',$hasil["kode"]);
    $kode_ururt=100001+$kode[2];
   return 'RJB/'.implode('', $var).'/'.substr($kode_ururt,1);
}
?>

<script type="text/javascript">

   $('#table_nota').DataTable();

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
                 var h =  $('#view_customer').DataTable({
                    "iDisplayLength": 10,
                       "aLengthMenu": [ [10, 20,50],[10,20,50]],
                      "pagingType" : "simple",
                      "ordering": false,
                      "info":     false,
                      "language": {
                            "decimal": ",",
                            "thousands": "."
                          },
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/returpenjualan/load-data-customer.php",
                    "order": [[1, 'asc']],
                     "columns": [
                     null,
                     null,
                     null,
                     null,
                     null,
                      ],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });
                var t =  $('#table_barang').DataTable({
                    "iDisplayLength": 10,
                       "aLengthMenu": [ [10, 20,50],[10,20,50]],
                      "pagingType" : "simple",
                      "ordering": false,
                      "info":     false,
                      "language": {
                            "decimal": ",",
                            "thousands": "."
                          },
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/returpenjualan/load-data.php",
                    "order": [[1, 'asc']],
                     "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                      ],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });
            });
function click_view_nota(id_barang) {
var id_customer=$('#id_customer').val();
var data='id_barang='+id_barang+'&id_customer='+id_customer;
$.ajax({
   url: 'modul/returpenjualan/ajax_view_nota.php',
  type: 'POST',
  dataType: 'HTML',
  data: data,
})
.done(function(data) {
  $('#body_table_nota').html(data);
    $("#view_nota").modal('show');
})
}
var no=1;
function add_barang(id_barang,id_invoice) {
   no=no+1;
  var jenis_retur=$('#jenis_retur').val();
  var data='id_barang='+id_barang+'&id_invoice='+id_invoice+'&jenis_retur='+jenis_retur+'&no='+no;
$.ajax({
   url: 'modul/returpenjualan/ajax_add_barang.php',
  type: 'POST',
  dataType: 'HTML',
  data: data,
})
.done(function(data) {
  no=no+1;
$('#body_table_tblrjb').append(data);
   $("#view_nota").modal('hide');
})
}
datetimepiker();
function deleteRow(r) {
    grandtotalretur();   
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("tblrjb").deleteRow(i);
            var alltotalpre = 0;
        $('.sub_total').each(function(){
          alltotalpre += parseFloat($(this).val()!= '' ? $(this).val() : 0);
        });
          if(!isNaN(alltotalpre)){
             var alltotal = alltotalpre;
              $('#returtotal').val(alltotal);
              grandtotalretur()
          }
                 
}

    $('#tblrjb').on('focus', '.rjb', function() {
         var aydi = $(this).attr('id'),
       berhitung = aydi.split('-');
    if (berhitung[0]=='jenis_satuan'){
      $(this).change(function(){
          var jenis_satuan = ($('#jenis_satuan-' + berhitung[1]).val()),
          jenis_satuan=jenis_satuan.split('-'),
          jenis_satuan=jenis_satuan[2]
          $('#qty_satuan-'+berhitung[1]).val(jenis_satuan);
          converqty(berhitung[1]);
      });
    }
    $(this).on("change keydown",function() {
        setTimeout(function() {
            var satuanretur = ($('#qty_convert-' + berhitung[1]).val() != '' ? $('#qty_convert-' + berhitung[1]).val() : 0),
                hargaretur = ($('#harga_rjb-' + berhitung[1]).val() != '' ? $('#harga_rjb-' + berhitung[1]).val() : 0),
                totalretur = (parseFloat(hargaretur) * parseFloat(satuanretur));
                converqty(berhitung[1]);
            if (!isNaN(totalretur)) {
                $('#total_rjb-' + berhitung[1]).val(totalretur);
        var alltotalpre = 0;
        $('.sub_total').each(function(){
          alltotalpre += parseFloat($(this).val()!= '' ? $(this).val() : 0);
        });
          }
          if(!isNaN(alltotalpre)){
             var alltotal = alltotalpre;
              $('#returtotal').val(alltotal);
              grandtotalretur()
          }
                 
        }, 0);
    });
    });
 function converqty(a){
             setTimeout(function() {
                    var qty_satuan = ($('#qty_satuan-'+a).val()),
                    jml_rjb = ($('#jml_rjb-'+a).val()),
                    stok_sekarang = ($('#stok_sekarang-'+a).val()),
                    jn=($('#jenis_retur').val()),
                    qty_si_convert=($('#qty_si_convert-'+a).val())
                totalconvert = (parseFloat(qty_satuan) * parseFloat(jml_rjb)),
                jml_gudang=parseFloat(stok_sekarang)-parseFloat(totalconvert);
                if (parseFloat(totalconvert)<=parseFloat(qty_si_convert)) {
                              if (parseFloat(jml_gudang)>=0){
                              $('#qty_convert-'+a).val(totalconvert);
                              $('#totalbarang-'+a).val(jml_gudang);
                               }
                              else{
                                      $('#qty_convert-'+a).val(totalconvert);

                                  }
                                                                  }else{ 
                                                                               
                                                                              $('#jml_rjb-'+a).val("");
                                                                              $('#totalbarang-'+a).val("");
                                                                              $('#qty_convert-'+a).val("");
                                                                        };
                                                                        },0)
  }
function returdisc(){
            var persendisc = ($('#returdiscpersen').val()),
                    persenppn = ($('#returpersenppn').val()),
                    total = ($('#returtotal').val()),
                totaldisc = parseFloat(total) * parseFloat(persendisc)/100;
                totalppn = (parseFloat(total) - parseFloat($('#returdiscnominal').val() != '' ? $('#returdiscnominal').val() : 0)) * parseFloat(persenppn) / 100;
            if (!isNaN(totaldisc)) {
                $('#returdiscnominal').val(Math.round(totaldisc));
            } 
  }

function returppn(){
                    var persenppn = ($('#returpersenppn').val()),
                    total = ($('#returtotal').val()),
                    totaldisc = ($('#returdiscnominal').val()),
                totalppn = (parseFloat(total) - parseFloat(totaldisc)) * parseFloat(persenppn) / 100;
             if (!isNaN(totalppn)) {
                $('#returppnnominal').val(Math.round(totalppn));
            }
  }
$(document).ready(function(){
        $(this).keydown(function() {
        setTimeout(function() {
           returdisc();
           returppn();
           grandtotalretur();
            }, 0);
                    });
  });

function grandtotalretur(){
//$('#productall').on('focus', '.hitung2', function() {
               var subtotal = ($('#returtotal').val() != '' ? $('#returtotal').val() : 0),
                disc = ($('#returdiscnominal').val() != '' ? $('#returdiscnominal').val() : 0),
                ppn = ($('#returppnnominal').val() != '' ? $('#returppnnominal').val() : 0),
                grandtotal = parseFloat(subtotal) - parseFloat(disc) + parseFloat(ppn);
            if (!isNaN(grandtotal)) {
                $('#grandtotalretur').val(Math.round(grandtotal));               
            }
       }
</script>
