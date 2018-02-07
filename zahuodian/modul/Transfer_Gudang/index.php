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
$aksi="modul/transfergudang/aksi_transfergudang.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
    echo "
    <h1>Transfer Gudang</h1>
      <a href='?module=transfergudang&act=tambah' class='btn btn-primary' >Transfer Barang <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a><span class='success'>";
echo '</span>
<div class="table-responsive">
<table id="gud" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
    <th  id="tablenumber">No</th>
    <th>No Transfer Gudang</th>
      <th>Nama Barang</th>
            <th>Jumlah</th>
      <th>Gudang Asal</th>
      <th>Gudang Tujuan</th>
      <th>Tanggal</th>
    </tr>
        </thead>
        <tbody>
        ';
  $tampil = mysql_query("SELECT id_transfer_gudang,tgl_transfer,jumlah,nama_barang,g.nama_gudang as `gudang_asal`,g2.nama_gudang as `gudang_tujuan` FROM `transfer_gudang` tg,barang b,gudang g,gudang g2 where tg.id_barang=b.id_barang and g.id_gudang=tg.gudang_asal and g2.id_gudang=tg.Gudang_tujuan order by tg.tgl_update");
  $no = 1;
      while ($r=mysql_fetch_array($tampil)){
echo "
<tr>
           <td>$no</td>
            <td>$r[id_transfer_gudang]</td>
            <td>$r[nama_barang]</td>
            <td>$r[jumlah]</td>
            <td>$r[gudang_asal]</td>
            <td>$r[gudang_tujuan]</td>
            <td>$r[tgl_transfer]</td>
</tr>"; 
$no++;
}
  echo "
        </tbody>
    </table>
  </div>";
    break;

  case "tambah":

  echo "<h2><b>Tambah</b>Transfer Gudang</h2>
   <form method='post' action='$aksi?module=transfergudang&act=input'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No Transfer</td>
    <td>";
 $tampil23=mysql_query("SELECT * FROM `transfer_gudang` order by id desc limit 1 ");
  $r    = mysql_fetch_array($tampil23);
  echo kodesurat($r['id_transfer_gudang'], TG, id_transfer_gudang, id_transfer_gudang );
   echo " </td>
    <td>Tanggal Transfer</td>
    <td><input id='tanggaltransfer' name='tanggaltransfer' value='".date('Y-m-d')."'class=' form-control datetimepicker' required></td>
  </tr>
  <tr>
   <td>No Surat Jalan</td>
    <td><input id='no_surat_jalan' name='no_surat_jalan'  class=' form-control' required></td>
     <td>No Expedisi</td>
    <td><input id='no_expedisi' name='no_expedisi' class=' form-control' ></td>
  </tr>
  <tr >
 <td>Gudang Tujuan</td> <td id='sup'>";
   echo '<select  class="form-control" id="id_gudang_tujuan" name="id_gudang_tujuan" required>';
$tampil=mysql_query("SELECT * FROM gudang where is_void=0 ");
            echo "<option  selected>- Pilih Gudang -</option>";
         while($w=mysql_fetch_array($tampil)){
              echo "<option value=$w[id_gudang]>$w[nama_gudang]</option>";
            }
echo "</select></td>
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
      <th>Kode barang</th>
      <th>Nama barang</th>
      <th>Gudang Asal</th>
      <th>Stok Gudang Asal</th>
      <th>Stok Gudang Tujuan</th>
      <th>Jumlah Di Transfer</th>
           <th>Hasil Transfer Gudang Asal</th>
      <th>Hasil Transfer Gudang Tujuan</th>
      <th>Aksi</th>
      </tr>
        </thead>
 
        <tbody id="product">
    
        </tbody>
        <tfoot>
     
  <tr id="productall">
    <td colspan="5" rowspan="4"><button class="btn btn-success"  type="submit"  name="save" value="Save" style="float:left;">Save ';

    echo '</button> 
          <a class="btn btn-warning" type="button" href="media.php?module=transfergudang" style="float:left;margin-left:10px;">Batal</a>
          </td>

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
        <h4 class="modal-title">cari Item</h4>
      </div>
      <div class="modal-body">
              <form method="post" action="modul/transfergudang/filter-barang.php">
              <label>Masukan
              nama barang <br>  atau kode barang</label>
              <input type="text" name="search" id="search_box" class="search_box"/>
               <button type="submit"  class="btn btn-primary search_button" id="search-item">
               <span class="glyphicon glyphicon-search"></span></button><br />
              </form>
      <table class="table table-hover table-bordered" cellspacing="0">
        <thead>
                <tr style="background-color:#F5F5F5;">
                    <th  id="tablenumber">No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Stok Gudang</th>
                     <th>Di Gudang </th>
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
                        null,
                        null,
                        null
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                    "order": [[1, 'asc']],
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
    $(this).keydown(function() {
        setTimeout(function() {
            var stok_sekarang_asal = ($('#stok_sekarang_asal-' + berhitung[1]).val() != '' ? $('#stok_sekarang_asal-' + berhitung[1]).val() : 0),
                stok_sekarang_tujuan = ($('#stok_sekarang_tujuan-' + berhitung[1]).val() != '' ? $('#stok_sekarang_tujuan-' + berhitung[1]).val() : 0),
                transfer = ($('#transfer-' + berhitung[1]).val() != '' ? $('#transfer-' + berhitung[1]).val() : 0),
                hasil_asal = (parseInt(stok_sekarang_asal) - parseInt(transfer)),
                hasil_tujuan = (parseInt(stok_sekarang_tujuan) + parseInt(transfer));
               

            if (!isNaN(hasil_asal)) {
               if (parseInt(hasil_asal)>=0){
                $('#total_asal-' + berhitung[1]).val(hasil_asal);
                $('#total_tujuan-' + berhitung[1]).val(hasil_tujuan);
                }
                else{
                  alert("Transfer Tidak Boleh Lebih dari Jumlah Stok yang ada");
                   $('#transfer-' + berhitung[1]).val(0);
                   $('#total_asal-' + berhitung[1]).val($('#stok_sekarang_asal-' + berhitung[1]).val());
                  $('#total_tujuan-' + berhitung[1]).val($('#stok_sekarang_tujuan-' + berhitung[1]).val());
                }
              
          }
        }, 0);
    });
});

datetimepiker();
function addMore(kode,kode_gudang) {
   var i = $('input').size() + 1;
    var gdg2= $('#id_gudang_tujuan').val() ;
    var kd1 = kode;
    var kd2 = kode_gudang;
      if(gdg2){
$("<tr>").load("modul/transfergudang/input.php?kd="+kd1+"&nox="+i+"&gdg2="+gdg2+"&kd2="+kd2+" ", function() {
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
    $(".search_button").click(function() {
        var searchString    = $("#search_box").val();
        var data            = 'search='+ searchString+'&gdg='+$("#id_gudang_tujuan").val();;
        if(searchString) {
            $.ajax({
                type: "GET",
                url: "modul/transfergudang/filter-barang.php",
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
        }
        return false;
    });
});
</script>