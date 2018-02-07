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
  date_default_timezone_set("Asia/Jakarta");
$aksi = "modul/returpenjualan/aksi_returpenjualan.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
      $judul = "Retur Penjulan";
        $desk = "Modul Yang digunakan untuk meretur barang dengan memotong nota dari penjual";
        $button="<a href='?module=returpenjualan&act=tambah' class='btn btn-primary' >Buat Retur Penjualan
                      <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a>";
        headerDeskripsi($judul,$desk,$button);
   echo "

    <div class='table-responsive'>
   <table id='header' class='table table-hover table-bordered' cellspacing='0'>";
    ############################
echo "
          <tr>
            <th id='tablenumber'>No</th>
            <th>Nota Retur</th>
            <th>Tanggal Retur</th>
            <th>Nota Penjualan</th>
            <th>Customer</th>
            <th>Total Retur</th>
            <th>Keterangan Retur</th>
            <th width='10%' colspan='2'>Aksi</th>
          </tr>";
        $sql  = "SELECT * FROM `trans_retur_penjualan` trp left join customer c on (c.id_Customer=trp.id_Customer) WHERE trp.is_void= '0' order by kode_rjb desc limit 50";
        $query  = mysql_query($sql);
        $no=1;
        while($rows=mysql_fetch_array($query)){
          echo "<tr>
              <td align='center'>$no</td>
              <td>$rows[kode_rjb]</td>
              <td>".tgl_indo($rows['tgl_rjb'])."</td>
              <td>$rows[no_invoice]</td>
              <td>$rows[kode_customer] - $rows[nama_customer]</td>
              <td>Rp. ".number_format($rows['grandtotal_retur'])."</td>
              <td>$rows[ket]</td>";
            if ($rows['status']=='1' )  {
              echo "
              <td colspan='2'>
                  <a 
                  href='modul/returpenjualan/cetak.php?id=$rows[id]' 
                  class='btn btn-default'
                  title='Print'>
                  <span 
                  class='glyphicon glyphicon-print'></span></a>
              </td>";
              }else{
                echo "  
                <td>
                <a 
                href='modul/returpenjualan/cetak.php?id=$rows[id]' 
                class='btn btn-default'
                 title='Print'>
                 <span 
                 class='glyphicon glyphicon-print'></span></a>
                 </td>
                 <td>
                         <a 
                         class='btn btn-danger' 
  href='modul/returpenjualan/aksi_returpenjualan.php?module=returpenjualan&act=delete&id=$rows[id]&kode=$rows[kode_rjb]&jenisretur=$rows[jenis_retur]'>
                         <span 
                         class='glyphicon glyphicon-trash' 
                         title='hapus' aria-hidden='true'></span></a>   
                 </td>";
               }
echo" </tr>";
        $no++;
        }
    ############################
    echo"
    </table>
  </div>";
    break;

  case "tambah":

  echo "<h2><b>Tambah</b> Retur Penjualan</h2><hr>
<form method='post' action='modul/returpenjualan/aksi_returpenjualan.php?module=returpenjualan&act=input'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id='tambah'>
  <tr>
        <td>No Retur</td>
        <td >";
      $tampil=mysql_query("SELECT kode_rjb FROM `trans_retur_penjualan` order by id desc limit 1 ");
      $kode    = mysql_fetch_array($tampil);
      echo kodesurat($kode[kode_rjb],'RJB','koderjb','koderjb');
       echo " </td>
        <td>No Invoice</td>
        <td><input id='no_invoice' class='form-control' name='no_invoice' data-toggle='modal' data-target='#modalrjb' readonly ></td>
  </tr>
  <tr>
        <td>Customer</td> <td id='sup'>
        <input type='hidden' class='form-control' id='customer' name='customer' readonly/>
        <input type='text' class='form-control' id='customer2' readonly/></td>";
echo'
          <td>Jenis Retur</td> <td><select class="chosen-select form-control" name=jenis_retur id=jenis_retur>
           <option value="1">Retur Kembali Barang</option>
          <option value="2">Retur Kembali BS</option>
        </select></td>';
        echo'
    </tr>
    <tr>
    <td>Alasan Retur</td>
    <td><textarea class="form-control" id="ket" name="ket" required></textarea></td>
    <td>Tgl. Retur</td>
    <td><input class="form-control datetimepicker" value="'.date('Y-m-d').'"id="tgl_rjb" name="tgl_rjb" required></td>
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

  <div class="modal fade" id="returbarangmodal" role="dialog">
    <div class="modal-dialog modal-lg">    

      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Barang Retur Penjualan</h4>
        </div><!-- ############## end Modal header -->
        <div class="modal-body">
    <table id="modalbarangretur" border="1" class="table table-hover" style="width: 100%;">
    <thead>
        <tr style="background-color:#F5F5F5;">
            <th>Kode barang</th>
            <th>Nama barang</th>
            <th>Aksi</th>
        </tr>
  </thead> 
<tbody id="tampilreturbarang">
</tbody>
    </table>
        </div><!-- ############## end Modal body -->
      </div><!-- ############## end Modal content -->      
    </div><!-- ############## end Modal dialog -->    
  </div><!-- ############## end Modal fade-->




  <form name="rjbtambah" method="post" action="">
<div class="btn-action float-clear"></div>
<table id="tblrjb" class="table table-hover table-bordered" cellspacing="0">
</table>
  </div>
  </form>';
    break;


  }
}
}
?>
<script type="text/javascript">
$(document).ready(function(){
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
 
                var t = $('#modalnoinvoice').DataTable({
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
                    "ajax": "modul/returpenjualan/load-data_returpenjualan.php",
                    "order": [[1, 'asc']],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    },
                });// datatable 
                  $("#sup").change(function()
                     {
                      t.search($("#Customer").find(":selected").text(), 1).draw();
                      });

    $('#tblrjb').on('focus', '.rjb', function() {
    var aydi = $(this).attr('id'),
    berhitung = aydi.split('-');
    if (berhitung[0]=='gudang'){
      $(this).change(function(){
          var jenis_satuan = ($('#gudang-' + berhitung[1]).val() != '' ? $('#gudang-' + berhitung[1]).val() : 0),
          jenis_satuan=jenis_satuan.split('-'),
          jenis_satuan=jenis_satuan[1]
          $('#stok_sekarang-'+berhitung[1]).val(jenis_satuan);
      });
    }
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
          }else{

          }
                 
        }, 0);
    });
});

  }); //ready document


 $("#jenis_retur").change(function()
 { 
  var id = $("#jenis_retur").find(":selected").val();
  var customer = $("#customer").find(":selected").text();
  var id_customer = $("#customer").find(":selected").val();
  var kode = $("#no_invoice").val();
  addrjbno(kode,customer,id_customer)
 })

function addrjbno (kode,customer,id_customer) {
                  var kd1 = kode;
                  var jenis_retur =($('#jenis_retur').val() );
                  var dataString = 'kd='+kd1+'&jenis_retur='+jenis_retur;
                  $.ajax
                      ({
                      url: 'modul/returpenjualan/ajax_returpenjualan.php',
                      data: dataString,
                      cache: false,
                      success: function(r)
                                {
                                       $("#tblrjb").html(r);
                                } 
                      });
      $("#modalnoinvoice").append($(this).html());
      $("#no_invoice").val(kode);
      $("#customer").val(id_customer);
      $("#customer2").val(customer);
      $("#modalrjb").modal('toggle');
     };

     function deleteRow(r) {
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
                    if (result) {
              var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("tblrjb").deleteRow(i);
    var alltotal = 0;
        $('.totalrjb').each(function(){
          alltotal += parseFloat($(this).val());
        });
            $('#returtotal').val(alltotal);
           returdisc();
           returppn();
           grandtotalretur();        
         }
        }
    });
$('.bootbox .btn-success').click(function() {
  bootbox.hideAll();
});
}; // fungcition deleterow
$(document).ready(function(){
        $(this).keydown(function() {
        setTimeout(function() {
           returdisc();
           returppn();
           grandtotalretur();
            }, 0);
                    });
  }); //ready document
 function returdisc(){
            var persendisc = ($('#returdiscpersen').val()),
                    persenppn = ($('#returpersenppn').val()),
                    total = ($('#returtotal').val()),
                totaldisc = parseFloat(total) * parseFloat(persendisc)/100;
                totalppn = (parseFloat(total) - parseFloat($('#returdiscnominal').val() != '' ? $('#returdiscnominal').val() : 0)) * parseFloat(persenppn) / 100;
            if (!isNaN(totaldisc)) {
                $('#returdiscnominal').val(totaldisc);
            } 
  }

function returppn(){
                    var persenppn = ($('#returpersenppn').val()),
                    total = ($('#returtotal').val()),
                    totaldisc = ($('#returdiscnominal').val()),
                totalppn = (parseFloat(total) - parseFloat(totaldisc)) * parseFloat(persenppn) / 100;
             if (!isNaN(totalppn)) {
                $('#returppnnominal').val(totalppn);
            }
  }

function converqty(a){
             setTimeout(function() {
                    var qty_satuan = ($('#qty_satuan-'+a).val()),
                    jml_rjb = ($('#jml_rjb-'+a).val()),stok_sekarang = ($('#stok_sekarang-'+a).val()),jn=($('#jenis_retur').val()),qty_si_convert=($('#qty_si_convert-'+a).val())
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

function grandtotalretur(){
//$('#productall').on('focus', '.hitung2', function() {
               var subtotal = ($('#returtotal').val() != '' ? $('#returtotal').val() : 0),
                disc = ($('#returdiscnominal').val() != '' ? $('#returdiscnominal').val() : 0),
                ppn = ($('#returppnnominal').val() != '' ? $('#returppnnominal').val() : 0),
                grandtotal = parseFloat(subtotal) - parseFloat(disc) + parseFloat(ppn);
            if (!isNaN(grandtotal)) {
                $('#grandtotalretur').val(grandtotal);               
            }
       }
datetimepiker();

</script>