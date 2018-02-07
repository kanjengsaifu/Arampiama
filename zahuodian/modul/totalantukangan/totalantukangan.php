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
$aksi="modul/totalantukangan/aksi_totalantukangan.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
  $judul = "Totalan Tukangan";
  $desk = "Modul untuk mencatat hasil totalan setiap tukang";
  $button= "<a href='?module=totalantukangan&act=tambah' class='btn btn-primary' >Buat baru <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a><span class='success'>";
  headerDeskripsi($judul,$desk,$button);
echo '
<div class="table-responsive">
<table id="gud" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
    <th  id="tablenumber">No</th>
    <th>No Totalan Tukang</th>
      <th>Nama Tukang</th>
      <th>Tanggal</th>
            <th>Keterangan</th>
      <th>Nominal Tukangan</th>
      <th>Status</th>
      <!--th>Tanggal</th-->
    </tr>
        </thead>
        <tbody>
        ';
  $tampil = mysql_query("SELECT * FROM trans_totalan_tukang tt, supplier s WHERE s.id_supplier = tt.id_supplier order by tt.tgl_totalan DESC");
  $no = 1;
      while ($r=mysql_fetch_array($tampil)){
echo "
<tr>
           <td>$no</td>
            <td>$r[no_totalan_tukang]</td>
            <td>$r[nama_supplier]</td>
            <td>".tgl_indo($r['tgl_totalan'])."</td>
            <td>$r[ket]</td>
            <td>$r[nominal_totalan]</td>
            <td>"; 
            if ($r['status_terbayar'] == 0) {
              echo "Belum Terbayar";
            }
            else {
              echo "Sudah Terbayar";
            }
            echo "</td>
</tr>"; 
$no++;
}
  echo "
        </tbody>
    </table>
  </div>";
    break;

  case "tambah":
  $judul = "<b>Tambah</b>Totalan Tukangan";
  $desk = "Modul untuk mencatat hasil totalan setiap tukang";
  headerDeskripsi($judul,$desk);

  echo "
   <form method='post' action='$aksi?module=totalantukangan&act=input'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No Totalan Tukang</td>
    <td>";
 $tampil23=mysql_query("SELECT * FROM trans_totalan_tukang ORDER BY id_totalan_tukang DESC LIMIT 1");
  $r    = mysql_fetch_array($tampil23);
  echo kodesurat($r['no_totalan_tukang'], NTT, no_totalan_tukang, no_totalan_tukang );
   echo " </td>
    <td>Tanggal</td>
    <td><input id='tanggal' name='tanggal' value='".date('Y-m-d')."'class=' form-control datetimepicker' required></td>
  </tr>
 <td>Nama Supplier</td> <td id='sup'>";
   echo '<select  class="chosen-select form-control" id="id_gudang_tujuan" name="id_gudang_tujuan" required>';
$tampil=mysql_query("SELECT s.* FROM supplier s, hitung_hpp_header t WHERE s.id_supplier = t.id_supplier AND t.status = 0");
            echo "<option  selected>- Pilih Supplier -</option>";
         while($w=mysql_fetch_array($tampil)){
              echo "<option value=$w[id_supplier]>$w[nama_supplier]</option>";
            }
echo "</select></td>
  <td>Keterangan</td>
  <td><textarea type='text' name='keterangan' class=form-control rows='3'></textarea></td>
  </tr>
  </table> ";

echo '
<DIV class="btn-action float-clear">
<!-- <div class="btn btn-primary" name="add_item"  onClick="addMore();" >Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
 </div>-->
<div class="btn btn-primary" type="button" id="search" data-toggle="modal" data-target="#search-md">Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
 </div>

</DIV>
<table id="header" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th>No. Transaksi</th>
      <!--th>Tanggal</th-->
      <th>Nama Barang</th>
      <th>Harga</th>
      <th>Jumlah</th>
           <th>Total</th>
      <!--th>Aksi</th-->
      </tr>
        </thead>
 
        <tbody id="product">
    
        </tbody>
        <tfoot>
     
  <tr id="productall">
    <td colspan="3" rowspan="4"><button class="btn btn-success"  type="submit"  name="save" value="Save" style="float:left;">Save ';

    echo '</button> 
          <a class="btn btn-warning" type="button" href="media.php?module=transfertukang" style="float:left;margin-left:10px;">Batal</a>
          </td>
          <td><b>Total :</b></td>
          <td><input type="text" name="grandtotal" id="grandtotal" class="form-control munberhit" readonly> </td>
          </tr>
                </tfoot>
          </table>
  </div> 
  </form>
  ';
  echo'
<div id="search-md" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Pilih Nomor Transaksi</h4>
      </div>
      <div class="modal-body">
              <!--form method="post" action="modul/totalantukangan/filter-nota.php">
              <label>Masukan
              nama barang <br>  atau kode barang</label>
              <input type="text" name="search" id="search_box" class="search_box"/>
               <button type="submit"  class="btn btn-primary search_button" id="search-item">
               <span class="glyphicon glyphicon-search"></span></button><br />
              </form-->
      <table class="table table-hover table-bordered" cellspacing="0">
        <thead>
                <tr style="background-color:#F5F5F5;">
                    <th  id="tablenumber">No</th>
                    <th width="30%">No. Transaksi</th>
                    <th width="15%">Tanggal</th>
                    <th width="30%">Nama Barang</th>
                    <th width="20%">Total</th>
                </tr>
        </thead>
      <tbody  id="results" class="update">
        </tbody>
      </table>

          </div>
      </div>
    </div>

  </div>
</div>
  ';
    break;

    
  }
}
}
?>
<script type="text/javascript">
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
 
                var t =   $('#gud').DataTable({
                      "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        null,
                        null
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                    "order": [[1, 'desc']],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });
            });

$('#product').on('focus', '.hitung', function() {
    var aydi = $(this).attr('id'),
    berhitung = aydi.split('-');
    alltotal = 0;
    $(this).keydown(function() {
        //setTimeout(function() {
          var alltotal = 0;

             $('.hitung').each(function(){
                alltotal += parseFloat($(this).val() != '' ? $(this).val() : 0);
            });
           $('#grandtotal').val(alltotal);
            var alltotal = 0;
          
        //}, 0);
    });
});

datetimepiker();
function addMore(kode) {
   var i = $('input').size() + 1;
    var gdg2= $('#id_gudang_tujuan').val() ;
    var kd1 = kode;
    //var kd2 = kode_gudang;
      if(gdg2){
$("<tr>").load("modul/totalantukangan/input.php?kd="+kd1+"&nox="+i+"&gdg2="+gdg2+" ", function() {
      $("#product").append($(this).html());

       $('option:not(:selected)').attr('disabled', true);
       $("#search-md").modal('toggle');
           i++;
    return false;
  }); 
      
 

    }
}

function deleteRow(r) {
  //var qwe =  document.getElementById("del_item").getAttribute("data-id").deleteRow(i);
    /*$("#del_item").click(function() {
   var qwe = $("#del_item").attr("data-id");

        $.ajax({
          type: "GET",
          url: "modul/purchaseorder/aksi_purchaseorder.php",
          data: "module=purchaseorder&act=hapussub&id="+qwe, 
          success: function (data) {
                  if(data) {

                  } else {
                  $("body").load(window.location + ".inputtable");
                }
                  }
            });
          });*/
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

$(function() {
    $("#search-md").on('shown.bs.modal', function () {
        var searchString    = $("#search_box").val();
        var data            = 'gdg='+$("#id_gudang_tujuan").val();;
        // if(searchString) {
            $.ajax({
                type: "GET",
                url: "modul/totalantukangan/filter-nota.php",
                data: data,
                beforeSend: function(html) { // this happens before actual call
                    $("#results").html(''); 
                    $("#searchresults").show();
                    $(".word").html(searchString);
               },
               success: function(html){ // this happens after we get results
                    $("#results").show();
                    $("#results").append(html);
              }
            });    
        // }
        // return false;
    });
});
</script>