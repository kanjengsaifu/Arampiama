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
  
$aksi="modul/tukanganpenotaan/aksi_tukanganpenotaan.php";
switch($_GET['act']){
  // Tampil Modul
  default:
       $judul = "Penotaan Tukangan";
        $desk = "Modul Yang digunakan untuk Melakukan Penghitungan Nota Barang Jadi dan Bahan yang digunakan";
        $button="<a data-toggle='modal' data-target='#cari_nota_tukang' class='btn btn-primary' >Penotaan Tukangan
                      <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a>";
        headerDeskripsi($judul,$desk,$button);
?>
    <div class='table-responsive'>
   <table id='NTT' width="100%" class='table table-hover table-bordered' cellspacing='0'>
   <thead>
        <tr>
            <th id='tablenumber'>No</th>
            <th>No NTT</th>
            <th>No PBJ</th>
            <th>Nota Supplier</th>
            <th>Supplier</th>
            <th>Total Penotaan</th>
            <th>Tgl Penotaan</th>
            <th width='10%'>Edit</th>
            <th width='10%'>Print</th>
          </tr>
   </thead>
       
    </table>
  </div>




  <!--################### Modal ###########################-->
  <div class="modal fade" id="cari_nota_tukang" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body">
          <table id="view_nota_tukang" class="table table-hover" border="1" style="width: 100%;">
            <thead>
              <tr>
              <th>No</th>
                <th>Kode & Nama Tukang</th>
                <th>Kode Nota Penerimaan Barang</th>
                <th>Nomor Nota Tukang</th>
                <th>Nominal Nota</th>
                <th>Aksi Proses</th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>


<?php

    break;

  case "tambah":
  $id=$_GET['id'];
$query=mysql_query("SELECT * FROM trans_terima_tukang_header a, supplier b WHERE a.id_supplier=b.id_supplier and status = 0 AND a.is_void = 0 and id_trans_terima_tukang_header='".$id."'");
$r=mysql_fetch_array($query);
?>
<h2><b>Tambah</b> Penotaan Tukang</h2><hr>
<form method='post' action='modul/tukanganpenotaan/aksi_tukanganpenotaan.php?module=tukanganpenotaan&act=input'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id='tambah'>
  <tr>
        <td>No Penotaan</td>
        <td><strong>:</strong></td>
        <td><strong><?= kode_surat('NTT','trans_totalan_tukang','no_totalan_tukang','id') ?></strong></td>
        <td>Tgl. Penotaan</td>
        <td><strong>:</strong></td>
        <td><input class="form-control datetimepicker" value=" <?php echo date('Y-m-d') ?>" id="tgl_NTT" name="tgl_NTT" required></td>
  </tr>
  <tr>
        <td>Supplier</td> 
        <td><strong>:</strong></td>
        <td><?= $r['kode_supplier'] ?> - <?= $r['nama_supplier'] ?>
        <input type="hidden" id='id_supplier' name='id_supplier' value="<?= $r[id_supplier] ?>" ></td>
        <td>No. PBJ</td> 
        <td><strong>:</strong></td>
        <td><?= $r['id_terima_tukang'] ?>
           <input type="hidden" id='id_terima_tukang' name='id_terima_tukang' value="<?= $r[id_terima_tukang] ?>" >
        </td>
    </tr>
    <tr>
      <td>Nominal      </td> 
        <td><strong>:</strong></td>
        <td><?= format_rupiah($r['grandtotal']) ?></td>
      <td>No. Nota Tukang</td> 
        <td><strong>:</strong></td>
        <td><?= $r['nonota_terima_tukang'] ?>
           <input type="hidden" id='nonota_terima_tukang' name='nota_tukang' value="<?= $r[nonota_terima_tukang] ?>" >
        </td>
    </tr>
<tr>
  <td>
    <div class="btn btn-primary" type="button"  data-toggle="modal" data-target="#view_barang">Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
 </div>
  </td>
   <td><strong>:</strong></td>
  <td colspan="4">
    <textarea name="ket" class="form-control" placeholder="Keterangan"></textarea>
  </td>
</tr>
    </table>



<table id="table_penotaan" class="table table-hover table-bordered" cellspacing="0">
            <thead>
      <tr style="background-color:#F5F5F5;">
      <th >Nama barang - Kode barang</th>
      <th width="8%">Stok <br> Sekarang </th>
      <th width="8%">Harga <br> Terakhir </th>  
      <th width="8%">Satuan</th>
      <th width="8%">Harga <br> Bahan</th>
      <th width="8%">Qty</th>
      <th colspan="3">Total</th>
      <th>Aksi</th>
        </tr>
        </thead> 
        <tbody id='body_table_penotaan'>
          
        </tbody>
          <tfoot>
        <tr id="productall">
    <td colspan="2" rowspan="5"> <br><button class="btn btn-success"  type="submit"  name="save" value="Save" style="float:left;margin-left:20px;">Save </button> 
          <a class="btn btn-warning" type="button" href="media.php?module=returpembelian" style="float:left;margin-left:10px;">Batal</a>
          </td>
    <td colspan="4" style="text-align:right;" ><p><b>Total    :  </b></p></td>
    <td colspan="1">
    <input name="nominal_hutang" type="hidden"  class="NTT2 form-control numberhit"  id="nominal_hutang" value="<?= $r['grandtotal'] ?>"  readonly><strong><?= format_rupiah($r['grandtotal']) ?></strong></td>
    <td width="30px"><center><strong><b><span>  -   </span></b></strong></center></td>
    <td ><input name="penotaantotal" type="text"  class="NTT2 form-control numberhit"  id="penotaantotal"  readonly></td>
  </tr>
  <tr>
       <td colspan="5" style="text-align:right;"><center><strong><b><span></span></b></strong></center></td> 
       <td width="30px"><center><strong><b><span><p>  =   </p></span></b></strong></center></td>
    <td colspan="1" style="nowrap:nowrap;">
    <input name="hasiltotal" id="hasiltotal"   class="NTT2 form-control numberhit" readonly></td>

  </tr>

  <tr>
     <td colspan="6" style="text-align:right;"><p> Disc (%) <input name="penotaandiscpersen" type="text" id="penotaandiscpersen" style="width:2em;"  class="NTT2 form-control numberhit" > | (Rp) </p></td>
    <td colspan="1" style="nowrap:nowrap;"><input name="penotaandiscnominal" type="text" id="penotaandiscnominal"   class="NTT2 form-control numberhit"  ></td>
  </tr>
  <tr>
    <td colspan="6" style="text-align:right;"><p> Ppn (%) <input name="returppnpersen" type="text" id="penotaanpersenppn"  style="width:2em;" class="NTT2 form-control numberhit" > | (Rp) </p></td>
    <td colspan="1"  style="nowrap:nowrap;"><input name="penotaanppnnominal" type="text" id="penotaanppnnominal"   class="NTT2 form-control numberhit"  ></td>
  </tr>
  <tr>
     <td colspan="6" style="text-align:right;"><b>Grand total </b></td>
    <td colspan="1"><b><input name="grandtotalpenotaan" type="text" id="grandtotalpenotaan"  readonly="readonly"  class="NTT2 form-control numberhit" ></b></td>
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
                    <th>Tambah</th>
                </tr>
        </thead>
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
                 var h =  $('#view_nota_tukang').DataTable({
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
                    "ajax": "modul/tukanganpenotaan/load-data-nota-tukang.php",
                    "order": [[1, 'asc']],
                     "columns": [
                     null,
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
              var h =  $('#NTT').DataTable({
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
                    "ajax": "modul/tukanganpenotaan/load-data-penotaan.php",
                    "order": [[1, 'asc']],
                     "columns": [
                     null,
                     null,
                     null,
                     null,
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
                    "ajax": {
                      "url" : "modul/tukanganpenotaan/load-data.php",
                      "cache": false,
                      "type": "GET",
                      "data": {"id_supplier": $('#id_supplier').val() }
                    },
                    "order": [[1, 'asc']],
                     "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false },
                        { "searchable": false },
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
var no=1;
function add_barang(id_barang) {
  var id_supplier=$('#id_supplier').val();
  var data='id_barang='+id_barang+'&no='+no+'&id_supplier='+id_supplier;
$.ajax({
   url: 'modul/tukanganpenotaan/ajax_add_barang.php',
  type: 'POST',
  dataType: 'HTML',
  data: data,
})
.done(function(data) {
  no=no+1;
$('#body_table_penotaan').append(data);
})
}
datetimepiker();
function deleteRow(r) {
    grandtotalpenotaan();   
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("table_penotaan").deleteRow(i);
           disc();
           ppn();
           totalpenotaan()
           grandtotalpenotaan();
                           
}

    $('#table_penotaan').on('focus', '.NTT', function() {
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
            var satuan = ($('#jumlah_NTT-' + berhitung[1]).val() != '' ? $('#jumlah_NTT-' + berhitung[1]).val() : 0),
                harga = ($('#harga_NTT-' + berhitung[1]).val() != '' ? $('#harga_NTT-' + berhitung[1]).val() : 0),
                total = (parseFloat(harga) * parseFloat(satuan));
                converqty(berhitung[1]);
            if (!isNaN(total)) {
                $('#total_NTT-' + berhitung[1]).val(total);
               }
         totalpenotaan()
                 
        }, 0);
    });
    });
 function converqty(a){
             setTimeout(function() {
                    var qty_satuan = ($('#qty_satuan-'+a).val()),
                    jumlah_NTT = ($('#jumlah_NTT-'+a).val()),
                    stok_sekarang = ($('#stok_tukang-'+a).val()),
                totalconvert = (parseFloat(qty_satuan) * parseFloat(jumlah_NTT)),
                batas_stok=parseFloat(stok_sekarang)-parseFloat(totalconvert);
                if (0<=parseFloat(batas_stok)) {
                              $('#convert-'+a).val(totalconvert);
                }else{ 
                             
                            //$('#jumlah_NTT-'+a).val("");
                            $('#convert-'+a).val("");
                                                                        };
                                                                        },0)
  }
function disc(){
            var persendisc = ($('#penotaandiscpersen').val()),
                    total = ($('#hasiltotal').val()),
                totaldisc = parseFloat(total) * parseFloat(persendisc)/100;
            if (!isNaN(totaldisc)) {
                $('#penotaandiscnominal').val(Math.round(totaldisc));
            } 
  }

function ppn(){
                    var persenppn = ($('#penotaanpersenppn').val()),
                    total = ($('#hasiltotal').val()),
                    persendisc = ($('#penotaandiscpersen').val()),
                    totaldisc = parseFloat(total) * parseFloat(persendisc)/100;
                totalppn = (parseFloat(total) - parseFloat(totaldisc)) * parseFloat(persenppn) / 100;
             if (!isNaN(totalppn)) {
                $('#penotaanppnnominal').val(Math.round(totalppn));
            }
  }
$(document).ready(function(){
        $(this).keydown(function() {
        setTimeout(function() {
           disc();
           ppn();
           totalpenotaan()
           grandtotalpenotaan();
            }, 0);
                    });
  });
function  totalpenotaan(){
   var alltotalpre = 0;
        $('.sub_total').each(function(){
          alltotalpre += parseFloat($(this).val()!= '' ? $(this).val() : 0);
        });
          if(!isNaN(alltotalpre)){
             var alltotal = alltotalpre;
              $('#penotaantotal').val(alltotal);
              var nominal_hutang = $('#nominal_hutang').val();
              $('#hasiltotal').val(parseFloat(nominal_hutang)-parseFloat(alltotal));
              grandtotalpenotaan()
              }

}
function grandtotalpenotaan(){
               var subtotal = ($('#hasiltotal').val() != '' ? $('#hasiltotal').val() : 0),
                disc = ($('#penotaandiscnominal').val() != '' ? $('#penotaandiscnominal').val() : 0),
                ppn = ($('#penotaanppnnominal').val() != '' ? $('#penotaanppnnominal').val() : 0),
                grandtotal = parseFloat(subtotal) - parseFloat(disc) + parseFloat(ppn);
            if (!isNaN(grandtotal)) {
                $('#grandtotalpenotaan').val(Math.round(grandtotal));               
            }
       }
</script>
