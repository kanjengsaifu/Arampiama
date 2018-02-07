<?php
 error_reporting(E_ALL ^ E_NOTICE);
$aksi="modul/purchaseorder/aksi_purchaseorder.php";
switch($_GET['act']){
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
  $judul = "<b>Tambah</b> Purchase Order";
  $desk = "Modul Purchase Order";
  headerDeskripsi($judul,$desk);

  echo "
   <form method='post' action='$aksi?module=purchaseorder&act=input'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No PO</td>
    <td><strong>".kode_surat('PO','trans_pur_order','id_pur_order','id')."</strong></td>
    <td>Tanggal PO</td>
    <td><input id='tanggalpo' name='tgl_po' value='".date('Y-m-d')."' class='datetimepicker form-control' required ></td>
  </tr>
  <tr>
   <td>Supplier</td> <td id='sup'>";

   echo '<select id="supplier" name = "supplier2" class="chosen-select form-control" tabindex="2" required>';
$tampil43=mysql_query("SELECT * FROM Supplier where is_void=0 ");
            echo "<option value='' selected>- Pilih Supplier -</option>";
         while($w=mysql_fetch_array($tampil43)){
              echo "<option value=$w[id_supplier]>$w[kode_supplier] - $w[nama_supplier]</option>";
          }
echo '</select>
<br>';
echo"
  </td>
  </tr>";
  echo '<tr id="txtHint">
<td> Alamat </td>
    <td><textarea disabled class=form-control></textarea></td>
    <td> No tlp </td>
    <td><textarea disabled class=form-control></textarea></td>
  </tr>';
  echo "</table> ";

echo '
<DIV class="btn-action float-clear">
<!-- <div class="btn btn-primary" name="add_item"  onClick="addMore();" >Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
 </div>-->
<div class="btn btn-primary" type="button" id="search" data-toggle="modal" data-target="#search-md">Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
 </div>

</DIV>
<table id="header" class="display table table-striped table-bordered table-hover" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th colspan="2">Nama barang - Kode barang</th>
      <th width="8%">Satuan</th>
      <th width="8%">Harga</th> 
      <th width="8%">Qty</th>
      <th width="5%">Disc 1 </br>(%)</th>
      <th width="5%">Disc 2 </br>(%)</th>
      <th width="5%">Disc 3 </br>(%)</th>
      <th width="5%">Disc 4 </br>(%)</th>
      <th width="7%">Pembulatan </br>(Rp.)</th>
      <th >Total</th>
      <th>Aksi</th>
      </tr>
        </thead> 
        <tbody id="product">    
        </tbody>
        <tfoot>
     
  <tr id="productall">
    <td colspan="7" rowspan="4"><button class="btn btn-success"  type="submit"  name="save" vaue="Save" style="float:left;">Save ';

    echo '</button> 
      <!--a class="btn btn-default" type="button" href="modul/purchaseorder/cetak.php?id=$no_po "  target="_blank" >Cetak</a-->
          <a class="btn btn-warning" type="button" href="media.php?module=purchaseorder" style="float:left;margin-left:10px;">Batal</a>
          </td>

    <td colspan="3" style="text-align:right;" ><p>ToTal All SUb </p></td>
    <td colspan="1"  ><input name="alltotal" type="text" class="hitung2 form-control numberhit" id="total" readonly="readonly" ></td>
    <td></td>
  </tr>

  <tr>
    <td colspan="3" style="text-align:right;"><p> Disc (%) <input name="persendisc" type="text" id="persendisc" style="width:2em;" > | (Rp) </p></td>
    <td colspan="1" style="nowrap:nowrap;"><input name="discalltotal" type="text" id="totaldisc" value="0" class="hitung2 form-control numberhit" ></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:right;"><p> Ppn (%) <input name="persenppn" type="text" id="persenppn" style="width:2em;"> | (Rp) </p></td>
    <td colspan="1"  style="nowrap:nowrap;"><input name="totalppn" type="text" id="totalppn" value="0" class="hitung2 form-control numberhit" ></td>
    <td></td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:right;"><b>Grand total</b></td>
    <td colspan="1"><b><input name="grandtotal" type="text" id="grandtotal" readonly="readonly" class="form-control numberhit" ></b></td>
    <td></td>
  </tr>
                </tfoot>
          </table>
  </div> 
  </form>
<div id="search-md" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">cari Item</h4>
      </div>
      <div class="modal-body">
      <table id="tambahitem" class="table table-hover table-bordered" cellspacing="0" style="width: 100%;">
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
</div>
  ';
    break;

    case "edit":
  $judul = "<b>Edit</b> Purchase Order";
  $desk = "Modul Purchase Order";
  headerDeskripsi($judul,$desk);

   
  echo "
   <form method='post' action='$aksi?module=purchaseorder&act=update'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No PO</td>
    <td>";
 $tampil44=mysql_query("SELECT * FROM `trans_pur_order`tpr,supplier s   WHERE tpr.id_supplier=s.id_supplier and id = '$_GET[id]' order by id desc limit 1 ");
  $r    = mysql_fetch_array($tampil44);

  echo "
   <input type='hidden' name='id' value='$r[id]' id='id_purchaseorder''>
  <input  name='no_po' value='$r[id_pur_order]' id='id_pur_order' readonly='readonly'  class='form-control' />";
   echo " </td>
    <td>Tanggal PO</td>
    <td><input id='tanggalpo' name='tgl_po' value='".date('Y-m-d', strtotime($r['tgl_po']))."' required  class='form-control datetimepicker' ></td>
  </tr>
  <tr>
   <td>Supplier</td> <td id='sup'>";
   echo '<select class="form-control chosen-select" id="supplier" name="supplier">';
$tampil43=mysql_query("SELECT * FROM Supplier where is_void=0 ");
          if ($r[id_supplier]==0){
            echo "<option value='' selected>- Pilih Supplier -</option>";
          }   
         while($w=mysql_fetch_array($tampil43)){
            if ($r[id_supplier]==$w[id_supplier]){
              echo "<option value=$w[id_supplier] selected>$w[kode_supplier] - $w[nama_supplier]</option>";
            }
            else{
              echo "<option value=$w[id_supplier]>$w[kode_supplier] - $w[nama_supplier]</option>";
            }
          }
echo '</select>
<br>';
echo"
  </td>
  </tr>";
  echo '<tr id="txtHint">
<td> Alamat </td>
    <td><textarea disabled  class="form-control">'.$r[alamat_supplier].'</textarea></td>
    <td> No tlp </td>
    <td><textarea disabled  class=form-control>'.$r[telp1_supplier].' </textarea></td>
  </tr>';
  echo "</table> ";

echo '
<div class="btn-action float-clear">

<div class="btn btn-primary" type="button" id="search-edit" data-toggle="modal" data-target="#search-md">Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
 </div>

</div>
<table id="header" class="display table table-striped table-bordered table-hover" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th colspan="2">Nama barang - Kode barang</th>
      <th>Satuan</th>
      <th>Harga</th>
      <th>Qty</th>
      <th>Disc 1 (%)</th>
      <th>Disc 2 (%)</th>
      <th>Disc 3 (%)</th>
      <th>Disc 4 (%)</th>
      <th>Pembulatan <br> (Rp.)</th>
      <th>Total</th>
      <th>Aksi</th>
      </tr>
        </thead>
 
        <tbody id="product">';
$tampiltable=mysql_query("SELECT * FROM `trans_pur_order_detail`   WHERE id_pur_order = '$r[id_pur_order]'  ");
$noz = 100000;
$rst_jumlah = mysql_num_rows($tampiltable);
while ($rst = mysql_fetch_array($tampiltable)){
  $sql= mysql_query("SELECT * FROM barang WHERE id_barang = '$rst[id_barang]' ");
  $data = mysql_fetch_array($sql);
  echo '
  <tr class="inputtable">
  <input type=hidden name="id_po_barang[]" value="'. $data['id_barang'].'" id="id_po_barang-'.$noz.'" >
    <input type=hidden name="id_po[]" value="'. $rst['id'].'" id="id_po-'.$noz.'" >
  <td colspan="2">
   <input type="text" class="namabarang" name="nama_barang[]" value="'.$data['nama_barang'].'" id="nama_barang-'.$noz.'"  disabled />
   </br>
       <input type="text"  class="namabarang"  name="kode_barang[]" value="'.$data['kode_barang'].'"   id="kode_barang-'.$noz.'"  disabled />
    </td>
   <td>
       <select  class="form-control hitung" id="jenis_satuan-'. $noz.'" name="jenis_satuan[]">';
      for ($i=1; $i <= 5 ; $i++) { 
        $val= "satuan".$i;
        $val_harga= "harga_sat".$i;
        $val_kali= "kali".$i;
        if ($data[$val]!=""){
          if ($data[$val]==$rst['satuan']){
             echo " <option value='".$data[$val_harga].'-'.$data[$val].'-'.$data[$val_kali]."' selected>".$data[$val].' ('.$data[$val_kali].')'."</option>";
          }
          else
             echo " <option value='".$data[$val_harga].'-'.$data[$val].'-'.$data[$val_kali]."' >".$data[$val].' ('.$data[$val_kali].')'."</option>";
        
        }
      }
    
      echo ' </select></td>
   <td>
       <input type="text" name="harga_sat1[]" value="'.$rst['harga'].'"  id="harga-'.$noz.'" class="hitung numberhit" />
    </td>
    
   <td><input  type="text" name="jumlah[]" id="satuan-'.$noz.'"  value="'.$rst[jumlah].'" class="hitung numberhit form-control" /></td>
            <td><input  type="text" name="disc1[]"  id="disc1_barang-'.$noz.'" value="'.$rst[disc1].'"  class="hitung numberhit form-control" /></td>
            <td><input  type="text" name="disc2[]" id="disc2_barang-'.$noz.'" value="'.$rst[disc2].'"  class="hitung numberhit form-control" /></td>
            <td><input  type="text" name="disc3[]" id="disc3_barang-'.$noz.'" value="'.$rst[disc3].'" class="hitung numberhit form-control" /></td>
            <td><input  type="text" name="disc4[]" id="disc4_barang-'.$noz.'" value="'.$rst[disc4].'" class="hitung numberhit form-control"  /></td>
            <td><input  type="text" name="disc5[]" id="disc5_barang-'.$noz.'" value="'.$rst[disc5].'" class="hitung numberhit"  /></td>
            <td><input type="text" name="total[]" id="total-'.$noz.'"  class="total numberhit form-control" value="'.$rst[total].'" readonly="readonly" /></td>
  <td>
    <div class="btn btn-xs btn-primary" type="button" id="search" data-toggle="modal" data-target="#search-md"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></div>
  <div  class="btn btn-xs btn-danger" id="del_item" data-id="'. $rst['id'].'" onclick="deleteRow(this)"><span class="glyphicon glyphicon-trash"></span></div>
  <!--<a href="'.$aksi.'?module=purchaseorder&act=hapusub&id='. $rst['id'].'" class="btn btn-xs btn-danger" name="del_item" onclick="deleteRow(this)" ><span class="glyphicon glyphicon-trash"></span></a>-->
  </td>
</tr>
';
$noz++;
}

if ($r['discper']==0) {
$discper="";
}else{
  $discper=$r['discper'];
}
if ($r['ppnper']==0) {
$ppnper="";
}else{
  $ppnper=$r['ppnper'];
}
echo'
        </tbody>
        <tfoot>
        <tr id="productall">
    <td colspan="5" rowspan="4"> <button class="btn btn-success"  type="submit"  name="update" value="update" style="float:left;">Update';

    echo '</button> 
          <a class="btn btn-warning" type="button" href="media.php?module=purchaseorder" style="float:left;margin-left:10px;">Batal</a>
          </td>

    <td colspan="5" style="text-align:right;" ><p><b>ToTal All SUb </b></p></td>
    <td><input name="alltotal" type="text" class="hitung2 numberhit form-control" id="total" value='.$r[alltotal].' readonly="readonly" ></td>
  </tr>

  <tr>
    <td colspan="5" style="text-align:right;"><p> Disc (%) <input name="persendisc"  type="text" id="persendisc" style="width:2em;" value='.$discper.' > | (Rp) </p></td>
    <td style="nowrap:nowrap;"><input name="discalltotal" type="text" id="totaldisc" value='.$r[disc].'  class="hitung2 numberhit form-control" ></td>
  </tr>
  <tr>
    <td colspan="5" style="text-align:right;"><p> Ppn (%) <input name="persenppn"  type="text" id="persenppn" style="width:2em;" value='.$ppnper.'> | (Rp) </p></td>
    <td style="nowrap:nowrap;"><input name="totalppn" type="text" id="totalppn" value='.$r[ppn].'  class="hitung2 numberhit form-control" ></td>
  </tr>
  <tr>
    <td colspan="5" style="text-align:right;"><b>Grand total</b></td>
    <td><b><input name="grandtotal" type="text" id="grandtotal" value='.$r[grand_total].'  readonly="readonly" class="numberhit"></b></td>
  </tr>
                </tfoot>
          </table>
  </div> 
  </form>

<div id="search-md" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">cari Item</h4>
      </div>
      <div class="modal-body">
      <table id="tambahitem" class="table table-hover table-bordered" cellspacing="0" style="width: 100%;">
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
</div>
  ';
    break;
}
?>
<script type="text/javascript">
  $(document).ready(function() {
    $('#po').DataTable();

    $("#sup").change(function()
            { 
            var id = $("#supplier").find(":selected").val();
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
                totaldisc = parseFloat(total) * parseFloat(persendisc)/100;
                totalppn = (parseFloat(total).toFixed(2) - parseFloat($('#totaldisc').val() != '' ? $('#totaldisc').val() : 0)).toFixed(2) * parseFloat(persenppn).toFixed(2) / 100;
            if (!isNaN(totaldisc)) {
                $('#totaldisc').val(totaldisc);
            } 
  }

function totalppn1(){
                    persenppn = ($('#persenppn').val()),
                    total = ($('#total').val()),
                    totaldisc = ($('#totaldisc').val()),
                totalppn = (parseFloat(total).toFixed(2) - parseFloat(totaldisc).toFixed(2)) * parseFloat(persenppn).toFixed(2) / 100;
             if (!isNaN(totalppn)) {
                $('#totalppn').val(totalppn);
            }
  }

function grandtotal1(){
              var subtotal = ($('#total').val() != '' ? $('#total').val() : 0),
                disc = ($('#totaldisc').val() != '' ? $('#totaldisc').val() : 0),
                ppn = ($('#totalppn').val() != '' ? $('#totalppn').val() : 0),
                grandtotal = parseFloat(subtotal) - parseFloat(disc) + parseFloat(ppn);
            if (!isNaN(grandtotal)) {
                $('#grandtotal').val(Math.round(grandtotal));
               
            }
       }

function hitungan(a){
   setTimeout(function() {
            var satuan = ($('#satuan-' + a).val() != '' ? $('#satuan-' + a).val() : 0),
                harga = ($('#harga-' + a).val() != '' ? $('#harga-' + a).val() : 0),
                disc1 = ($('#disc1_barang-' + a).val() != '' ? $('#disc1_barang-' + a).val() : 0),
                disc2 = ($('#disc2_barang-' + a).val() != '' ? $('#disc2_barang-' + a).val() : 0),
                disc3 = ($('#disc3_barang-' + a).val() != '' ? $('#disc3_barang-' + a).val() : 0),
                disc4 = ($('#disc4_barang-' + a).val() != '' ? $('#disc4_barang-' + a).val() : 0),
                disc5 = ($('#disc5_barang-' + a).val() != '' ? $('#disc5_barang-' + a).val() : 0),
                total1 = (parseFloat(satuan) * parseFloat(harga).toFixed(2)),
                totaldisc1 = parseFloat(total1).toFixed(2) * parseFloat(disc1).toFixed(2) / 100,
                totaldisc2pre = parseFloat(total1).toFixed(2) - parseFloat(totaldisc1).toFixed(2),
                totaldisc2 = parseFloat(totaldisc2pre).toFixed(2) * parseFloat(disc2).toFixed(2) / 100,
                totaldisc3pre = parseFloat(total1).toFixed(2) - parseFloat(totaldisc1).toFixed(2) - parseFloat(totaldisc2).toFixed(2),
                totaldisc3 = parseFloat(totaldisc3pre).toFixed(2) * parseFloat(disc3).toFixed(2) / 100,
                totaldisc4pre = parseFloat(total1).toFixed(2) - parseFloat(totaldisc1).toFixed(2) - parseFloat(totaldisc2).toFixed(2)-parseFloat(totaldisc3).toFixed(2),
                totaldisc4 = parseFloat(totaldisc4pre).toFixed(2) * parseFloat(disc4).toFixed(2) / 100,
                subtotal = parseFloat(total1).toFixed(2) - parseFloat(totaldisc1).toFixed(2) - parseFloat(totaldisc2).toFixed(2) - parseFloat(totaldisc3).toFixed(2)-parseFloat(totaldisc4).toFixed(2) - parseFloat(disc5);          
            if (!isNaN(subtotal)) {
                $('#total-' + a).val(Math.round(subtotal));
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

$('#product').on('focus', '.hitung', function() {
    var aydi = $(this).attr('id'),
    berhitung = aydi.split('-');
    if (berhitung[0]=='jenis_satuan'){
      $(this).change(function(){
          var jenis_satuan = ($('#jenis_satuan-' + berhitung[1]).val() != '' ? $('#jenis_satuan-' + berhitung[1]).val() : 0),
          jenis_satuan=jenis_satuan.split('-'),
          jenis_satuan=jenis_satuan[2] *  ($('#hpp-' + berhitung[1]).val())
          $('#harga-'+berhitung[1]).val(Math.round(jenis_satuan));
           hitungan(berhitung[1]);
      });
    }
    $(this).keydown(function() {
     hitungan(berhitung[1]);
    });
});

datetimepiker();
var i=0;
function addMore(kode) {
  i=i+1;
    var kd1 = kode;
    var supp =$('#supplier2 option:selected').val();
    var data ="kd="+kd1+"&nox="+i+"&supp="+supp
    $.ajax({
      url: "modul/purchaseorder/input.php",
      data: data,
    })
    .done(function(data) {
        $("#product").append(data);
        i=i+1;
    })

    

    

}

function deleteRow(r) {
    var r = r.parentNode.parentNode.rowIndex;
    document.getElementById("header").deleteRow(r);
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
