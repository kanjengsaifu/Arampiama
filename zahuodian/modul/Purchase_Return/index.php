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
  
$aksi="modul/returpembelian/aksi_returpembelian.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
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

  echo "<h2><b>Tambah</b> Retur Pembelian</h2>
   <form method='post' action='$aksi?module=returpembelian&act=input'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No Retur Pembelian</td>
<td><strong>".kode_surat('RBB','trans_retur_pembelian','kode_rbb','id')."</strong></td>
    <td>Tanggal Retur Pembelian</td>
    <td><input id='tanggalpo' value='".date("Y-m-d")."' name='tgl_retur' class='form-control datetimepicker' required></td>
  </tr>
  <tr>
   <td>Supplier</td> <td id='sup'>";

   echo '<select  class="form-control" id="cb_supplier" name="cb_supplier" required>';
$tampil43=mysql_query("SELECT * FROM Supplier where is_void=0 ");
          if ($r[id_supplier]==0){
            echo "<option  selected>- Pilih Supplier -</option>";
          }   
         while($w=mysql_fetch_array($tampil43)){
            if ($r[id_supplier]==$w[id_supplier]){
              echo "<option value='$w[id_supplier]@$w[alamat_supplier]@$w[telp1_supplier]' selected>$w[kode_supplier] - $w[nama_supplier]</option>";
            }
            else{
              echo "<option value='$w[id_supplier]@$w[alamat_supplier]@$w[telp1_supplier]' >$w[kode_supplier] - $w[nama_supplier]</option>";
            }
          }
echo '</select><input type="hidden" id="supplier" name="supplier"/>
<td>Jenis Retur</td><td>
<select  class="form-control" id="jenis_retur" name="jenis_retur">
<option value="1" selected>Deposit</option>
</select>
</td>';
echo"
  </td>
  </tr>";
  echo '<tr id="txtHint">
<td> Alamat </td>
    <td><textarea id="alamat" class="form-control" disabled></textarea></td>
    <td> No tlp </td>
    <td><textarea id="telp" class="form-control" disabled></textarea></td>
  </tr>';
  echo "</table> ";

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
      <th width="20%">Nama barang</th>
      <th width="8%">Gudang</th>
      <th width="7%">Satuan</th>
      <th width="10%">Harga</th>
      <th width="5%">Jumlah</th>
      <th width="5%">Disc 1 <br> (%)</th>
      <th width="5%">Disc 2 <br> (%)</th>
      <th width="5%">Disc 3 <br> (%)</th>
      <th width="5%">Disc 4 <br> (%)</th>
      <th width="10%">Disc 5<br> (Rp) </th>
      <th width="15%">Total</th>
      <th width="5%">Aksi</th>
      </tr>
        </thead> 
        <tbody id="product">    
        </tbody>
        <tfoot>
     
  <tr id="productall">



    <td colspan="4" rowspan="4"><button class="btn btn-success"  type="submit"  name="save" value="Save" style="float:left;">Save ';

    echo '</button> 
          <a class="btn btn-warning" type="button" href="media.php?module=returpembelian" style="float:left;margin-left:10px;">Batal</a>
          </td>

    <td colspan="6" style="text-align:right;" ><p>ToTal All SUb </p></td>
    <td colspan="1"  ><input name="alltotal" type="text" class="numberhit hitung2 form-control" id="total" readonly="readonly" ></td>
  </tr>

  <tr>
    <td colspan="6" style="text-align:right;"><p> Disc (%) <input name="persendisc" type="text" id="persendisc" style="width:2em;" > | (Rp) </p></td>
    <td colspan="1" style="nowrap:nowrap;"><input name="discalltotal" type="text" id="totaldisc" value="0" class="numberhit hitung2 form-control" ></td>
  </tr>
  <tr>
    <td colspan="6" style="text-align:right;"><p> Ppn (%) <input name="persenppn" type="text" id="persenppn" style="width:2em;"> | (Rp) </p></td>
    <td colspan="1"  style="nowrap:nowrap;"><input name="totalppn" type="text" id="totalppn" value="0" class="numberhit hitung2 form-control" ></td>
  </tr>
  <tr>
    <td colspan="6" style="text-align:right;"><b>Grand total</b></td>
    <td colspan="1"><b><input name="grandtotal" type="text" id="grandtotal" class="numberhit form-control" readonly="readonly" ></b></td>
  </tr>
                </tfoot>
          </table>
  </div> 
  </form>
  ';
   echo'
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

   
echo "<h2><b>Edit</b> Retur Pembelian</h2>
<form method='post' action='$aksi?module=returpembelian&act=update'>
<div class='table-responsive'>
  <table class='table table-hover' border=0 id=tambah>
      <tr>
        <td>No PO</td>
        <td>";
        $tampil44=mysql_query("SELECT * FROM `trans_retur_pembelian`   WHERE id = '$_GET[id]' order by id desc limit 1 ");
        $r    = mysql_fetch_array($tampil44);

        echo "
        <input type='hidden' name='id' value='$r[id]' id='id_retur''>
        <input  name='no_retur' value='$r[kode_rbb]' id='no_retur' class='form-control' />
        </td>
        <td>Tanggal Retur Pembelian</td>
        <td><input id='tanggalpo' name='tgl_retur' value='".date('Y-m-d', strtotime($r['tgl_retur']))."' class='form-control datetimepicker' required></td>
    </tr>
    <tr>
        <td>Supplier</td> 
        <td id='sup'>";
        echo '<select class="form-control" id="supplier" name="supplier">';
        $tampil43=mysql_query("SELECT * FROM Supplier where is_void=0 ");
        if ($r[id_supplier]==0){
        echo "<option value='' selected>- Pilih Supplier -</option>";
        }   
        while($w=mysql_fetch_array($tampil43)){
        if ($r[id_supplier]==$w[id_supplier]){
        echo "<option value=$w[id_supplier] selected>$w[nama_supplier]</option>";
        $alamat= $r[alamat_supplier];
        $tlp = $r[telp1_supplier];
        }
        else{
        echo "<option value=$w[id_supplier]>$w[nama_supplier]</option>";
        }
        }
        echo '</select>
        <br>
        </td>
    </tr> 
    <tr id="txtHint">
    <td> Alamat </td>
    <td><textarea disabled >'.$alamat.' wdf</textarea></td>
    <td> No tlp </td>
    <td><textarea disabled>'.$tlp.' </textarea></td>
    </tr>';
    echo "</table> ";

    echo '
    <DIV class="btn-action float-clear">
    </DIV>
  <table id="header" class="table table-hover table-bordered" cellspacing="0">
    <thead>
      <tr style="background-color:#F5F5F5;">
        <th>Kode barang</th>
        <th>Nama barang</th>
        <th>Harga</th>
        <th>Qty</th>
        <th>Total</th>
        <th>Aksi</th>
      </tr>
  </thead>
  <tbody id="product">';
      $tampiltable=mysql_query("SELECT * FROM `trans_retur_pembelian_detail`   WHERE kode_rbb = '$r[kode_rbb]'  ");
      $noz = 1000;
      $rst_jumlah = mysql_num_rows($tampiltable);
      while ($rst = mysql_fetch_array($tampiltable)){
      $sql= mysql_query("SELECT * FROM barang WHERE id_barang = '$rst[id_barang]' ");
      $data = mysql_fetch_array($sql);
      echo '
      <tr class="inputtable">
          <input type=hidden name="id_po_barang[]" value="'. $data['id_barang'].'" id="id_po_barang-'.$noz.'" >
          <input type=hidden name="id_po[]" value="'. $rst['id'].'" id="id_po-'.$noz.'" >
          <td>
          <input type="text" name="kode_barang[]" value="'.$data['kode_barang'].'"   id="kode_barang-'.$noz.'"  disabled />
          </td>
          <td>
          <input type="text" name="nama_barang[]" value="'.$data['nama_barang'].'" id="nama_barang-'.$noz.'"  disabled />
          </td>
          <td>
          <input type="text" name="harga_sat1[]" value="'.$rst['harga'].'"  id="harga-'.$noz.'" class="hitung" />
          </td>
          <td><input type="text" name="jumlah[]" id="satuan-'.$noz.'"  value="'.$rst[jumlah].'" class="hitung" />
          <input type="hidden" name="jumlah_u[]" id="jumlah_u-'.$noz.'"  value="'.$rst[jumlah].'" class="hitung" />
          <input type="hidden" name="qty_convert_u[]" id="satuan-'.$noz.'"  value="'.$rst[qty_convert].'" class="hitung" /></td>
          <td>
          <select  class="form-control hitung" id="jenis_satuan-'. $noz.'" name="jenis_satuan[]">';
          for ($i=1; $i <= 5 ; $i++) { 
          $val= "satuan".$i;
          $val_harga= "harga_sat".$i;
          $val_kali= "kali".$i;
          if ($data[$val]!=""){
          echo $data[$val];
          if ($data[$val]==$rst['satuan']){
          echo " <option value='".$data[$val_harga].'-'.$data[$val].'-'.$data[$val_kali]."' selected>".$data[$val].' ('.$data[$val_kali].')'."</option>";
          }
          else
          echo " <option value='".$data[$val_harga].'-'.$data[$val].'-'.$data[$val_kali]."' >".$data[$val].' ('.$data[$val_kali].')'."</option>";
          }
          }
          echo ' </select></td>
          <td><input type="text" name="total[]" id="total-'.$noz.'"  class="total" value="'.$rst[total].'" readonly="readonly" />
            <input type="hidden" name="id_gudang_asal[]" id="id_gudang_asal-'.$noz.'"  value="'.$rst[id_gudang].'" readonly="readonly" /></td>

          <input type="hidden" class="form-control hitung2" type="text" name="stok_sekarang[]" id="stok_sekarang-'.$noz.'" value='.$rst[kali].' readonly="readonly" />
          <td> <select class="form-control hitung2" id="gudang-'.$noz.'" name="gudang[]" required>';
      $tampil_lbr=mysql_query("SELECT * FROM gudang g,stok s where s.id_gudang=g.id_gudang  and id_barang='$data[id_barang]' and is_void=0 ");
              if ($rst[id_gudang]=='0'){
        echo "<option value='' selected>- Pilih Supplier -</option>";
        }   
         while($w=mysql_fetch_array($tampil_lbr)){
          if ($w[id_gudang]==$rst[id_gudang]){
           echo "<option value='".$w[id_gudang].'-'.$w[stok_sekarang]."'] selected>$w[nama_gudang] ($w[stok_sekarang])</option>";
          }
          else{
              echo "<option value='".$w[id_gudang].'-'.$w[stok_sekarang]."']>$w[nama_gudang] ($w[stok_sekarang])</option>";}
          }
echo'</select></td></tr>';
      $noz++;
      }
    echo'
      </tbody>
      <tfoot>
          <tr id="productall">
            <td colspan="1" rowspan="4"> 
            <button class="btn btn-success"  type="submit"  name="update" value="update" style="float:left;">Update</button> 
            <a class="btn btn-warning" type="button" href="media.php?module=returpembelian" style="float:left;margin-left:10px;">Batal</a>
            </td>
            <td colspan="3" style="text-align:right;" ><p><b>ToTal All SUb </b></p></td>
            <td colspan="2"  ><input name="alltotal" type="text" class="hitung2" id="total" value='.$r[alltotal].' readonly="readonly" ></td>
          </tr>
          <tr>
            <td colspan="3" style="text-align:right;"><p> Disc (%) <input name="persendisc" value='.$r[discper].' type="text" id="persendisc" style="width:2em;" > | (Rp) </p></td>
            <td colspan="2" style="nowrap:nowrap;"><input name="discalltotal" type="text" id="totaldisc" value='.$r[disc].'  class="hitung2" ></td>
          </tr>
          <tr>
            <td colspan="3" style="text-align:right;"><p> Ppn (%) <input name="persenppn" value='.$r[ppnper].' type="text" id="persenppn" style="width:2em;"> | (Rp) </p></td>
            <td colspan="2"  style="nowrap:nowrap;"><input name="totalppn" type="text" id="totalppn" value='.$r[ppn].'  class="hitung2" ></td>
          </tr>
          <tr>
            <td colspan="3" style="text-align:right;"><b>Grand total</b></td>
            <td colspan="2"><b><input name="grandtotal" type="text" id="grandtotal" value='.$r[grand_total].'  readonly="readonly" ></b></td>
          </tr>
      </tfoot>
  </table>
</div> 
</form>';
    break;
  }
}
}
?>
<script type="text/javascript">
  $(document).ready(function() {

    $("#sup").change(function()
            { 
            var id = $("#cb_supplier").find(":selected").val();
            var dataString = 'supplier='+ id;
            $.ajax
                      ({
                      url: 'modul/retur/filter.php',
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
                totaldisc=parseFloat(totaldisc).toFixed(2);     
            if (!isNaN(totaldisc)) {
                $('#totaldisc').val(totaldisc);
            } 
  }

function totalppn1(){
                    persenppn = ($('#persenppn').val()),
                    total = ($('#total').val()),
                    totaldisc = ($('#totaldisc').val()),
                totalppn = (parseFloat(total) - parseFloat(totaldisc)) * parseFloat(persenppn) / 100;
                totalppn=parseFloat(totalppn).toFixed(2);     
             if (!isNaN(totalppn)) {
                $('#totalppn').val(totalppn);
            }
  }

function grandtotal1(){
//$('#productall').on('focus', '.hitung2', function() {
               var subtotal = ($('#total').val() != '' ? $('#total').val() : 0),
                disc = ($('#totaldisc').val() != '' ? $('#totaldisc').val() : 0),
                ppn = ($('#totalppn').val() != '' ? $('#totalppn').val() : 0),
                grandtotal = parseFloat(subtotal) - parseFloat(disc) + parseFloat(ppn);
                grandtotal=parseFloat(grandtotal).toFixed(2);     
            if (!isNaN(grandtotal)) {
                $('#grandtotal').val(grandtotal);
               
            }
       }

function hitungan(a){
//$('#productall').on('focus', '.hitung2', function() {
    setTimeout(function() {
                  var rt =  ($('#satuan-'+a).val());
                  var ri1 =  ($('#stok_sekarang-'+a).val());
                  if (parseInt(rt)>parseInt(ri1)) {
                    $('#satuan-'+a).val(0)
                  }else{
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
                subtotal=parseFloat(subtotal).toFixed(2);             
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
                 }
                    }, 0);
       }
//});

$('#product').on('focus', '.hitung', function() {
    var aydi = $(this).attr('id'),
    berhitung = aydi.split('-')
    /* Start Perhitungan Satuan */
    if (berhitung[0]=='jenis_satuan'){
      $(this).change(function(){
          var jenis_satuan = ($('#jenis_satuan-' + berhitung[1]).val() != '' ? $('#jenis_satuan-' + berhitung[1]).val() : 0),
          jenis_satuan=jenis_satuan.split('-'),
          jenis_satuan=jenis_satuan[0]
          $('#harga-'+berhitung[1]).val(jenis_satuan);
           hitungan(berhitung[1]);
        
      });
    }
    /* end Perhitungan Satuan */
    $(this).keydown(function() {
      hitungan(berhitung[1]);
    
    });
});

$('#product').on('focus', '.hitung2', function() {
    var aydi = $(this).attr('id'),
    berhitung = aydi.split('-');
    if (berhitung[0]=='gudang'){
      $(this).change(function(){
          var jenis_satuan = ($('#gudang-' + berhitung[1]).val() != '' ? $('#gudang-' + berhitung[1]).val() : 0),
          jenis_satuan=jenis_satuan.split('-'),
          jenis_satuan=jenis_satuan[1]
          $('#stok_sekarang-'+berhitung[1]).val(jenis_satuan);
            hitungan(berhitung[1]);
      });
    }
});

datetimepiker();
add_newitemcombobox('cb_supplier');
add_newitemcombobox('jenis_retur');
var no=0;
function addMore(kode) {
   no=no+1;
    var kd1 = kode;
    var supp =$('#cb_supplier option:selected').val();
    var supp =supp.split("@");
    var data = "kd="+kd1+"&nox="+no+"&supp="+supp[0]
    $.ajax({
      url: 'modul/returpembelian/input.php',
      data: data,
    })
    .done(function(data) {
       no=no+1;
      $("#product").append(data);
       $("#search-md").modal('toggle');
        $('#supplier').val( $('#cb_supplier option:selected').val());
           i++;
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
                    "ajax": "modul/returpembelian/load-data.php",
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


 $("#cb_supplier").change(function()
 { 
  var id = $("#cb_supplier").find(":selected").val();
  var id = id.split("@");
  var dataString = 'text='+ id;
  $("#alamat").val(id[1]);
  $("#telp").val(id[2]);

 })

</script>
