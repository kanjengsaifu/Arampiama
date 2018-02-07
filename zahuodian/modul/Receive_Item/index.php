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
$aksi="modul/laporanbarangmasuk/aksi_laporanbarangmasuk.php";

switch($_GET['act']){
  // Tampil Modul
  default:
   echo '
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#all">Supplier</a></li>
      <li><a data-toggle="tab" href="#payment">List Payment</a></li>
    </ul>

    <div class="tab-content">
      <div id="all" class="tab-pane fade in active">
        <div class="table-responsive">
          <table class="tb_supplier display table table-striped table-bordered table-hover">
          <thead>
          <tr style="background-color:#F5F5F5;"">
            <th id="tablenumber">No</th>
            <th>Supplier</th>
            <th>Region</th>
            <th>Telp Supplier</th>
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
            <th>Supplier</th>
            <th>Tipe Supplier</th>
            <th>Alamat</th>
            <th>Region</th>
            <th>Telp Supplier</th>
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
  $judul = "<b>Tambah</b> Laporan Barang Masuk";
  $desk = "Modul list Barang - Barang Masuk";
  headerDeskripsi($judul,$desk);
            if(isset($_GET['errormsg'])){
            echo '<p style="font-weight:bold;color:red;"> *) '.$_GET['errormsg'].' </p> ';
          }

  echo "
    <form id='form-lpb'  method='post' action='$aksi?module=laporanbarangmasuk&act=input'>
     <div class='table-responsive'>
      <table class='table table-hover' border='0' id='tambah'>
  <tr>
    <td>No LBM</td>
    <td>";
   echo '<input type="text" id="id_lbm" name="no_lbm" class="form-control" value="'.kode_surat('LBM','trans_lpb','id_lpb','id').'">
   <td>No PO</td>
<td>
<input  class="form-control" name="no_po" id ="no_po" data-toggle="modal" data-target="#myModal" readonly/>
  </td>
  </tr>
  <tr>
    <td> No Nota Supplier : </td>
    <td><input class="form-control"  name="no_nota_supplier" required></td>
    <td>No Expedisi </td>
    <td ><input class="form-control"  id="no_expedisi" name="no_expedisi"></td>
  </tr>

  <tr>
     <td>Supplier</td> <td id="sup">
    <input class="form-control"  id="tampil_supplier" name="tampil_supplier" type="hidden">
   <select  id="supplier" name="supplier" class="form-control" tabindex="2" required>';
$tampil=mysql_query("SELECT s.id_supplier, s.nama_supplier FROM supplier s RIGHT JOIN trans_pur_order o ON(s.id_supplier=o.id_supplier) where s.is_void='0' AND o.is_void='0' GROUP by s.id_supplier");
            echo "<option value='' selected>- Pilih Supplier -</option>";
         while($w=mysql_fetch_array($tampil)){
              echo "<option value=$w[id_supplier]>$w[nama_supplier]</option>";
            }
echo '</select></td>
<td>Tanggal Barang Diterima</td>
    <td><input  class="datetimepicker form-control" value="'.date('Y-m-d').'" name="tgl_lbm" required></td>
 </tr>
  <tr>
   </td>
    
<td > Alamat </td>
    <td ><textarea class="form-control"  id="alamat" disabled></textarea></td>
 </tr></table>
<DIV class="btn-action float-clear">

</DIV>
<table id="header" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Kode & Nama Barang</th>
      <th>Jumlah Dalam PO</th>
      <th>Jumlah Diterima</th>
      <th>Satuan</th>
      <th>Gudang</th>
      </tr>
        </thead>
 
        <tbody id="product">

        </tbody>
        <tfoot>
                </tfoot>
          </table>
  </div> 
  <button class="btn btn-success"  type="submit"  name="save" value="Save" style="float:left;">Save</button> 
  <a class="btn btn-warning" type="button" href="media.php?module=laporanbarangmasuk" style="float:left;margin-left:10px;">Batal</a>
  </form>
<!------------------------------------------------------------------------ Modal Cari Nomor Po ------------------------------------------>
<div class="modal fade" id="carinomorpo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Cari Barang</h4>
      </div>
      <div class="modal-body" id="showcaribarang">
      <table id="modalbarang" class="display table table-striped table-bordered table-hover" cellspacing="0" style="width: 100%;">
        <thead>
                <tr style="background-color:#F5F5F5;">
                    <th  id="tablenumber">No</th>
                    <th>Nomor PO</th>
                    <th>Nama Supplier</th>
                    <th>Alamat Supplier</th>
                    <th class="tablenumber">Cari</th>
                </tr>
        </thead>
      </table>
<!-------------------------------------------------------------------------- Modal Cari Nomor Po ------------------------------------------>



      ';



    break;

  case "edit":
  $judul = "<b>Edit</b> Laporan Barang Masuk";
  $desk = "Modul list Barang - Barang Masuk";
  headerDeskripsi($judul,$desk);

   echo "
    <form method='post' action='$aksi?module=laporanbarangmasuk&act=update'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No LBM</td>
    <td>";
  $query=mysql_query("SELECT * FROM `trans_lpb` t, supplier s   WHERE t.id_supplier=s.id_supplier and id = '$_GET[id]' order by id desc limit 1 ");
 $r=mysql_fetch_array($query);
     echo "  <input type='hidden' name='id' value='$r[id]'>
  <input  name='no_lbm' value='$r[id_lpb]' id='id_lbm_edit'   class='form-control' readonly/>";
   echo ' </td>
   <td>No Po</td>
<td>
<input class="form-control" name="no_po" id ="no_po" value='.$r[id_pur_order].'  data-toggle="modal" data-target="#myModal" readonly/>
  </tr>';
  echo "
  <tr>
   <td>Tanggal barang diterima</td>
    <td><input class='datetimepicker form-control' name='tgl_lbm' value='".date('Y-m-d', strtotime($r['tgl_lpb']))."' required class='form-control'></td>
   <td> No Nota Supplier : </td>
    <td><input name='no_nota_supplier' value='$r[no_nota_supplier]' required class='form-control'>
  </tr>
  <tr>
   <td>Supplier</td> <td id='sup_edit'>
     <input type=hidden id='supplier_edit' name='supplier' value='$r[id_supplier]' >
     <input value='$r[nama_supplier]' readonly class='form-control'></td>
    <td>No Expedisi </td>
    <td ><input id='no_expedisi' name='no_expedisi' value='$r[no_expedisi]' class='form-control'></td>
 </tr>
 <tr><td > Alamat </td>";
$tampil67=mysql_query("SELECT * FROM Supplier where is_void=0  AND id_supplier = '$r[id_supplier]'");
$y=mysql_fetch_array($tampil67);
echo "
    <td ><textarea disabled id='alamat' class='form-control'>$y[alamat_supplier]</textarea></td>
    </tr>";
  echo "</table>";

echo '
<DIV class="btn-action float-clear">
<!-- <div class="btn btn-primary" type="button" id="search-edit" data-toggle="modal" data-target="#search-md">Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
 </div>-->

</DIV>
<table id="header" class="display table table-striped table-bordered table-hover" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Jumlah dalam Po</th>
      <th>Jumlah Diterima</th>
      <th>Satuan</th>
      <th>Gudang</th>
      </tr>
        </thead>
 
        <tbody id="product">';
$noz= 100;
$tampiltable=mysql_query("SELECT *,concat(qty,'-',qty_satuan) as jumlah_dlm_po  FROM
  `trans_lpb_detail` d,gudang g WHERE d.id_gudang=g.id_gudang and id_lpb = '$r[id_lpb]' order by kode_barang_po,id");
 $no=1;
while ($rst = mysql_fetch_array($tampiltable)){

  echo '
  <!--<tr class="inputtable">-->
 <tr>
    <input type=hidden name="id_lbm[]" value="'. $rst['id'].'" id="id_lbm-'.$noz.'" >
      <td>
       '.$no.'
    </td>
 <td style="text-align:left !important">';
  $tampiltablebarang=mysql_query("SELECT * FROM `barang` WHERE id_barang = '$rst[id_barang]' ");
  //$selisih = $rst['qty_diterima'] - $rst['qty'];
   $rst1 = mysql_fetch_array($tampiltablebarang);
  echo'
       <input type="text" name="kode_barang[]" value="'.$rst1[kode_barang].'"   id="kode_barang-'.$noz.'" readonly="readonly"  />
       <input type="hidden" name="id_barang[]" value="'.$rst[id_barang].'"   id="id_barang-'.$noz.'"  />
    </td>
    <td>
       <input type="text" name="nama_barang[]" value="'.$rst1[nama_barang].'" id="nama_barang-'.$noz.'"  disabled />
    </td>
   <td>
       <input type="text" name="jumlah_diminta[]" value="'.$rst[jumlah_dlm_po].'"  id="jumlahdiminta-'.$noz.'" readonly="readonly" class="hitung" />
    </td>
       
<td id="checkjumlah"><input type="text" name="selisih[]" id="selisih-'.$noz.'"  value="'.$rst['qty_diterima'].'" class="selisih"  /><br>
<input  type="hidden" name="selisih2[]" id="selisih2-'.$noz.'"  value="'.$rst['qty_diterima_convert'].'" class="selisih"  />

      <!--<input type="checkbox" name="lbr_gudang[]"  id="lbr_checkbox-'.$noz.'" onclick="checkjumlah(this,'.$noz.','.$rst[qty].','.$rst['qty_diterima'].')" unchecked="unchecked"> Jumlah Sesuai--></td>

   <input type="hidden"  class="form-control" id="gudang_lbm-'.$noz.'" name="gudang_lbm[] " value="'.$rst[id_gudang].'"  readonly="readonly" />
       <td>';
             echo '<select id="jenis_satuan-'.$noz.'" name="jenis_satuan[]" required>';
$tampil_lbr=mysql_query("SELECT * FROM barang where is_void=0 and id_barang='".$rst['id_barang']."' ");
   $sat_minim=0;
$data=mysql_fetch_array($tampil_lbr);
            for ($i=5; $i >= 1 ; $i--) { 
        $val= "satuan".$i;
        $val_kali= "kali".$i;
        $val_harga= "harga_sat".$i;

        if ($data[$val]!=""){
          if ($data[$val]==$rst[qty_diterima_satuan]){
            $sat_minim=$data[$val_kali];
             echo " <option value='".$data[$val_harga].'-'.$data[$val].'-'.$data[$val_kali]."' selected>".$data[$val].' ('.$data[$val_kali].')'."</option>";
          }
        }
      }
echo '</select>  </td>
   <td>
   <input   class="form-control" id="gudang_nama-'.$noz.'" name="gudang_nama[] " value="'.$rst[nama_gudang].'"  readonly="readonly" /></td>
</tr>';
$no++;
$noz++;
}

        echo '
        </tbody>
        <tfoot>
                </tfoot>
          </table>
  </div> 
  <button class="btn btn-success"  type="submit"  name="save" value="Save" style="float:left;">Save</button> 
  <a class="btn btn-warning" type="button" href="media.php?module=laporanbarangmasuk" style="float:left;margin-left:10px;">Batal</a>
  </form>
  ';

    break;
  }
}
}
?>

<script type="text/javascript">
$('#lpb').DataTable();
$('#form-lpb').submit(function() {
  var $kode='kode='+($('#id_lbm').val());
      $.ajax({
      type: 'POST',
      url:'modul/laporanbarangmasuk/ajax_check_kode.php',
      data:$kode ,
      success: function(data) {
        if (data=='0') {
          submit($('#form-lpb').serialize(),$('#form-lpb').attr('action'));     
          alert('Data Telah Tersimpan');
          location.reload(); 
             }else{
          alert('Data Belum Tersimpan Karena Nomor Nota Sudah Terpakai');
        }
       
      }
    })
    return false;
  });
function submit(data,url) {
    $.ajax({
      type: 'POST',
      url: url,
      data: data,
      success: function(data) {
      }
    })
}

$(document).ready(function()
{  
    ajax_check('id_lbm','trans_lpb','id_lpb');
    datetimepiker();

 $("#supplier").change(function()
 { 
  var id = $("#tampil_supplier").val();
  document.getElementById("supplier").value = id;

 })



 $("#supplier_edit").change(function()
 { 
  var id = $("#supplier_edit").find(":selected").val();
  var dataString = 'status=edit&text='+ id ;
  $.ajax
  ({
    url: 'modul/laporanbarangmasuk/proses_supplier_lbm.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#tampil").html(r);
   } 
  });
 })
  $("#sup_edit").change(function()
 { 
  var id = $("#supplier_edit").find(":selected").val();
  var dataString = 'supplier='+ id;
  $.ajax
  ({
    url: 'modul/laporanbarangmasuk/filter_lbm.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#alamat").html(r);
   } 
  });
 })
 // code to get all records from table via select box
});
$('#my_modal').on('show.bs.modal', function(e) {
    var bookId = $(e.relatedTarget).data('book-id');
    $(e.currentTarget).find('input[name="bookId"]').val(bookId);
});

    


  function nilaipo(id_supplier,nomor_po,alamat,nama_supplier) {

  var i = $('input').size() + 1;

    var nomor_po = nomor_po;
    var id_supplier = id_supplier;
    var alamat = alamat;
    var nama_supplier = nama_supplier;
    var dataString = 'text='+nomor_po+'&nox='+i;
    document.getElementById("supplier").value = id_supplier;
    document.getElementById("tampil_supplier").value = id_supplier;
     $("#alamat").text(alamat);
      $("#no_po").val(nomor_po);
       $("#carinomorpo").modal("toggle");
      $.ajax({
    url: 'modul/laporanbarangmasuk/laporanbarangmasuk_detail.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#product").html(r);
   } 
 });
};
/*function deleteRow(r) {
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("header").deleteRow(i);
}*/

function addMore(kode) {
   var i = $('input').size() + 1;
    var kd1 = kode;
  $("<tr>").load("modul/purchaseorder/input_lbm.php?kd="+kd1+"&nox="+i+" ", function() {
      $("#product").append($(this).html());
       $("#search-md").modal('toggle');
           i++;
    return false;
  }); 
}

function deleteRow(r) {
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("header").deleteRow(i);
           var alltotal = 0;
        $('.total').each(function(){
          alltotal += parseFloat($(this).val());
        });
                $('#total').val(alltotal);
           totaldisc1();
           totalppn1();
           grandtotal1();

}

// check input disable
function checkjumlah(r,t,p,q){
    //set initial state.
    $('#lbr_checkbox-'+t).change (function() {
        if ($(this).is(':checked')) {
       $("#selisih-"+t).attr('readonly','readonly');
       $("#selisih-"+t).val(p);
        }
        else if (($(this).is(':unchecked'))) {
       $("#selisih-"+t).removeAttr('readonly');
       $("#selisih-"+t).val(q);
        }
    });
}

   
///////////////////////////////////////////////////////////////////////  Modal Cari Nomor Po
 $('#no_po').click(function() {
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
                                                "url": "modul/laporanbarangmasuk/load-data.php",
                                                "cache": false,
                                                "type": "GET",
                                                "data": {"pencarian": "po" }
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
     $('#carinomorpo').modal('show');
  });
///////////////////////////////////////////////////////////////////////  Modal Cari Nomor Po
 
</script>