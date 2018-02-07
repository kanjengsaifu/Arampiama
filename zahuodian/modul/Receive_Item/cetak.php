<?php
  include "../../config/koneksi.php";
  include "../../lib/input.php";
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
//$aksi="modul/purchaseorder/aksi_purchaseorder.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
$tgl=date('d/m/Y');
  echo "<h2><b>Cetak</b> Laporan Barang Masuk</h2>
   <form method='post' action='$aksi?module=purchaseorder&act=update'>
     <div class='table-responsive'>
     ";
  $query=mysql_query("SELECT * FROM `trans_lpb` t, supplier s   WHERE t.id_supplier=s.id_supplier and id = '$_GET[id]' order by id desc limit 1 ");
 $r=mysql_fetch_array($query);
     echo "  
      <table class='table table-hover' border=0 id=tambah>
        <tr>
   <td>Supplier</td><td><strong>:</strong></td>
     <td><strong>$r[nama_supplier]</strong></td>

 </tr>
  <tr><td > Alamat </td><td><strong>:</strong></td>
    <td>$r[alamat_supplier]</td>
    </tr>
  <tr>
     <td>No Po</td><td><strong>:</strong></td><td>$r[id_pur_order]</td>
    <td>No LBM</td><td><strong>:</strong></td><td>$r[id_lpb] </td>
  </tr>
  <tr>
   <td>Tanggal barang diterima</td><td><strong>:</strong></td><td>".date("d/m/Y", strtotime($r[tgl_lpb]))."</td>
   <td>Tanggal Cetak  </td><td><strong>:</strong></td><td>$tgl</td>
  </tr>
  <tr>
    <td>No Expedisi </td><td><strong>:</strong></td><td>$r[no_expedisi]</td>
   <td> No Nota Supplier  </td><td><strong>:</strong></td><td>$r[no_nota_supplier]</td>
  </tr>
";
  echo "</table>";

echo "
<DIV class='btn-action float-clear'>
</DIV>
<table id='header' class='table table-hover table-bordered' cellspacing='0' border= 1px solid black>
        <thead>
  <tr style='background-color:#F5F5F5;''>
      <th id='tablenumber'>No</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Jumlah dalam Po</th>
      <th>Jumlah Diterima</th>
      <th>Gudang</th>
      </tr>
        </thead>
 
        <tbody id='product'>";
$noz= 100;
$tampiltable=mysql_query("SELECT *,concat(qty,'-',qty_satuan) as jumlah_dlm_po, concat(qty_diterima,'-',qty_diterima_satuan) as trima FROM
  `trans_lpb_detail` d,gudang g WHERE d.id_gudang=g.id_gudang and id_lpb = '$r[id_lpb]' order by kode_barang_po,id");
 $no=1;
while ($rst = mysql_fetch_array($tampiltable)){

  echo "
  
 <tr>
      <td>
       $no
    </td>
  <td>";
  $tampiltablebarang=mysql_query("SELECT * FROM `barang` WHERE id_barang = '$rst[id_barang]' ");
  //$selisih = $rst['qty_diterima'] - $rst['qty'];
   $rst1 = mysql_fetch_array($tampiltablebarang);
  echo"
       $rst1[kode_barang]
    </td>
    <td>
       $rst1[nama_barang]
    </td>
   <td>
       $rst[jumlah_dlm_po]
    </td>
       
<td>$rst[trima]</td>
<td>
   $rst[nama_gudang]</td>";
$no++;
$noz++;
}

        echo "
        </tbody>
        <tfoot>
                </tfoot>
          </table>
  </div> 
  </form>
  ";
    echo tanda_tangan("$r[nama_supplier]");
}
}
?>
<script type="text/javascript">
  $(document).ready(function() {
    $('#po').DataTable();

    $("#sup").change(function()
            { 
            var id = $("#supplier2").find(":selected").val();
            var dataString = 'supplier='+ id;
            $.ajax
                      ({
                      url: 'modul/purchaseorder/filter.php',
                      data: dataString,
                      cache: false,
                      success: function(r)
                                {
                                       $("#txtHint").html(r);
                                } 
                      });
            });
    $(this).keydown(function() {
        setTimeout(function() {
           totaldisc1();
            }, 0);
    });
   $(this).keydown(function() {
        setTimeout(function() {
           totalppn1();
            }, 0);
    });
      $(this).keydown(function() {
        setTimeout(function() {
           grandtotal1();
            }, 0);
    });      
} );

  // end ready document
  function totaldisc1(){
            var persendisc = ($('#persendisc').val()),
                    persenppn = ($('#persenppn').val()),
                    total = ($('#total').val()),
                totaldisc = parseInt(total) * parseInt(persendisc)/100;
                totalppn = (parseInt(total) - parseInt($('#totaldisc').val() != '' ? $('#totaldisc').val() : 0)) * parseInt(persenppn) / 100;
            if (!isNaN(totaldisc)) {
                $('#totaldisc').val(totaldisc);
            } 
  }

function totalppn1(){
                    persenppn = ($('#persenppn').val()),
                    total = ($('#total').val()),
                    totaldisc = ($('#totaldisc').val()),
                totalppn = (parseInt(total) - parseInt(totaldisc)) * parseInt(persenppn) / 100;
             if (!isNaN(totalppn)) {
                $('#totalppn').val(totalppn);
            }
  }

function grandtotal1(){
//$('#productall').on('focus', '.hitung2', function() {
               var subtotal = ($('#total').val() != '' ? $('#total').val() : 0),
                disc = ($('#totaldisc').val() != '' ? $('#totaldisc').val() : 0),
                ppn = ($('#totalppn').val() != '' ? $('#totalppn').val() : 0),
                grandtotal = parseInt(subtotal) - parseInt(disc) + parseInt(ppn);
            if (!isNaN(grandtotal)) {
                $('#grandtotal').val(grandtotal);
               
            }
       }

function hitungan(a){
//$('#productall').on('focus', '.hitung2', function() {
    setTimeout(function() {
            var satuan = ($('#satuan-' + a).val() != '' ? $('#satuan-' + a).val() : 0),
                harga = ($('#harga-' + a).val() != '' ? $('#harga-' + a).val() : 0),
                disc1 = ($('#disc1_barang-' + a).val() != '' ? $('#disc1_barang-' + a).val() : 0),
                disc2 = ($('#disc2_barang-' + a).val() != '' ? $('#disc2_barang-' + a).val() : 0),
                disc3 = ($('#disc3_barang-' + a).val() != '' ? $('#disc3_barang-' + a).val() : 0),
                disc4 = ($('#disc4_barang-' + a).val() != '' ? $('#disc4_barang-' + a).val() : 0),
                disc5 = ($('#disc5_barang-' + a).val() != '' ? $('#disc5_barang-' + a).val() : 0),
                total1 = (parseInt(satuan) * parseInt(harga)),
                totaldisc1 = parseInt(total1) * parseInt(disc1) / 100,
                totaldisc2pre = parseInt(total1) - parseInt(totaldisc1),
                totaldisc2 = parseInt(totaldisc2pre) * parseInt(disc2) / 100,
                totaldisc3pre = parseInt(total1) - parseInt(totaldisc1) - parseInt(totaldisc2),
                totaldisc3 = parseInt(totaldisc3pre) * parseInt(disc3) / 100,
                totaldisc4pre = parseInt(total1) - parseInt(totaldisc1) - parseInt(totaldisc2)-parseInt(totaldisc3),
                totaldisc4 = parseInt(totaldisc4pre) * parseInt(disc4) / 100,
                subtotal = parseInt(total1) - parseInt(totaldisc1) - parseInt(totaldisc2) - parseInt(totaldisc3)-parseInt(totaldisc4) - parseInt(disc5);          
            if (!isNaN(subtotal)) {
                $('#total-' + a).val(subtotal);
                var alltotalpre = 0;
                 $('.total').each(function(){
                    alltotalpre += parseFloat($(this).val());
                });
            }
                  var alltotal = alltotalpre ;
                  $('#total').val(alltotal);
                  grandtotal1();
                    }, 0);
       }
//});

$('#product').on('focus', '.hitung', function() {
    var aydi = $(this).attr('id'),
    berhitung = aydi.split('-');
    if (berhitung[0]=='jenis_satuan'){
      $(this).change(function(){
          var jenis_satuan = ($('#jenis_satuan-' + berhitung[1]).val() != '' ? $('#jenis_satuan-' + berhitung[1]).val() : 0),
          jenis_satuan=jenis_satuan.split('-'),
          jenis_satuan=jenis_satuan[0]
          $('#harga-'+berhitung[1]).val(jenis_satuan);
           hitungan(berhitung[1]);
      });
    }
    $(this).keydown(function() {
     hitungan(berhitung[1]);
    });
});

  $( function() {
    $( "#tanggalpo" ).datepicker({
        dateFormat:"yy-mm-dd",
      changeMonth:true,
    changeYear:true}
      );
  } );
function addMore(kode) {
   var i = $('input').size() + 1;
    var kd1 = kode;
    var supp =$('#supplier2 option:selected').val();
  $("<tr>").load("modul/purchaseorder/input.php?kd="+kd1+"&nox="+i+"&supp="+supp+" ", function() {
      $("#product").append($(this).html());
       $("#search-md").modal('toggle');
        $('#supplier').val( $('#supplier2 option:selected').val());
        $('#supplier2').attr('disabled', true);
           i++;
    return false;
  }); 
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
 
                var t = $('#tambahitem').DataTable({
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
                    "ajax": "modul/purchaseorder/load-data.php",
                    "order": [[1, 'asc']],
                     "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false }
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

</script>
