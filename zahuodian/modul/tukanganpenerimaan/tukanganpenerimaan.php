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
$aksi="modul/tukanganpenerimaan/aksi_tukanganpenerimaan.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:

  $judul = "Penerimaan Tukangan";
  $desk = "Modul untuk mencatat bahan jadi yang diterima dari tukang";
  $button= "<a href='?module=tukanganpenerimaan&act=tambah' class='btn btn-primary' >Buat Nota <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a><span class='success'>";
  headerDeskripsi($judul,$desk,$button);
   
echo '
<div class="table-responsive">
  <table id="terimatukang" class="display table table-striped table-bordered table-hover">
  <thead>
  <tr style="background-color:#F5F5F5;"">
    <th id="tablenumber">No</th>
    <th>No PBJ</th>
    <th>No Nota</th>
    <th>Nama Tukang</th>
    <th>Tanggal Transaksi</th>
    <th>Grand Total</th>
    <th>Aksi</th>
  </tr></thead>
  </table>
</div>';

    break;

  case "tambah":
  $judul = "<b>Nota </b>Penerimaan Barang Jadi";
  $desk = "Modul digunakan untuk mencatat bahan jadi yang diberikan oleh tukang";
  headerDeskripsi($judul,$desk);

  echo "
   <form method='post' action='$aksi?module=tukanganpenerimaan&act=input'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No PBJ</td>
    <td>";
  echo "<b>".kode_surat('PBJ', 'trans_terima_tukang_header', 'id_terima_tukang', 'id_trans_terima_tukang_header' )."</b>";
   echo " </td>
    <td>Tanggal Terima</td>
    <td><input id='tanggaltrans' name='tgl_trans' value='".date('Y-m-d')."' class='datetimepicker form-control' required ></td>
  </tr>
  <tr>
   <td>Supplier</td> <td id='sup'>";

   echo '<select id="supplier" name = "supplier2" class="chosen-select form-control" tabindex="2" required>';
$tampil43=mysql_query("SELECT * FROM Supplier where is_void=0 AND jenis = 'A' ORDER BY id_supplier");
            echo "<option value='' selected>- Pilih Supplier -</option>";
         while($w=mysql_fetch_array($tampil43)){
              echo "<option value=$w[id_supplier]>$w[kode_supplier] - $w[nama_supplier]</option>";
          }
echo '</select>
<br>';
echo" 
  </td>
  <td>No Nota  </td>
  <td><input type='text' name='nonota' id='nonota' class='form-control'></td>
  </tr>";
  echo '<tr id="txtHint">
<td> Alamat </td>
    <td><textarea disabled class=form-control></textarea></td>
    <td> No tlp </td>
    <td><textarea disabled class=form-control></textarea></td>
  </tr>';
  echo "</table> ";
echo '
<div class="btn-action float-clear">
  <div class="btn btn-primary" type="button" id="search" data-toggle="modal" data-target="#search-md">Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
  </div>
</div>


<table id="header" class="display table table-striped table-bordered table-hover" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th width="15%">Nama barang</th>
      <th width="20%">Kode barang</th>
      <th width="15%">Ke Gudang</th>
      <th width="10%" >Satuan</th>
      <th width="10%">Harga</th> 
      <th width="10%">Qty</th>
      <th width="10%">Total</th>
      <th width="10%">Aksi</th>
      </tr>
        </thead> 
        <tbody id="product">    
        </tbody>
        <tfoot>
     
  <tr id="productall">
    <td colspan="2" rowspan="4"><button class="btn btn-success"  type="submit"  name="save" vaue="Save" style="float:left;">Save ';

    echo '</button> 
          <a class="btn btn-warning" type="button" href="media.php?module=tukanganpenerimaan" style="float:left;margin-left:10px;">Batal</a>
          </td>

    <td colspan="4" style="text-align:right;"><b>Grand total</b></td>
    <td colspan="1"  ><input name="alltotal" type="text" class="hitung2 form-control numberhit" id="total" readonly="readonly" ></td>
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
  $judul = "<b>Edit</b> Penerimaan Barang Jadi";
  $desk = "Modul digunakan untuk mencatat bahan jadi yang diberikan oleh tukang";
  headerDeskripsi($judul,$desk);

   
  echo "
   <form method='post' action='$aksi?module=tukanganpenerimaan&act=update'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No PBJ</td>
    <td>";
 $tampil44=mysql_query("SELECT * FROM `trans_terima_tukang_header`tpr,supplier s   WHERE tpr.id_supplier=s.id_supplier and id_trans_terima_tukang_header = '$_GET[id]'");
  $r    = mysql_fetch_array($tampil44);
  if ($r['status']==1) {
    header('location:media.php?module=tukanganpenerimaan&act=detail&id='.$_GET['id']);
  }
  echo "
   <input type='hidden' name='idpbj' value='$r[id_trans_terima_tukang_header]' id='idpbj'>
  <input  name='no_npb' value='$r[id_terima_tukang]' id='id_pur_order' readonly='readonly'  class='form-control' />";
   echo " </td>
    <td>Tanggal</td>
    <td><input id='tanggal' name='tgl_trans' value='".date('Y-m-d', strtotime($r['tgl_trans']))."' required  class='form-control datetimepicker' ></td>
  </tr>
  <tr>
   <td>Supplier</td> <td id='sup'>";
   echo '<select class="form-control chosen-select" id="supplier" name="supplier">';
$tampil43=mysql_query("SELECT * FROM Supplier where is_void=0 AND jenis = 'A' ORDER BY id_supplier");
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
  <td>No Nota</td>
  <td><input type='text' name='nonota' value='$r[nonota_terima_tukang]' class='form-control'></td>
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
  <div class="btn btn-primary" type="button" id="search" data-toggle="modal" data-target="#search-md">Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
  </div>
</div>
<table id="header" class="display table table-striped table-bordered table-hover" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
     <th width="15%">Nama barang</th>
      <th width="20%">Kode barang</th>
      <th width="15%">Ke Gudang</th>
      <th width="10%" >Satuan</th>
      <th width="10%">Harga</th> 
      <th width="10%">Qty</th>
      <th width="10%">Total</th>
      <th width="10%">Aksi</th>
      </tr>
        </thead>
 
        <tbody id="product">';
$tampiltable=mysql_query("SELECT * FROM `trans_terima_tukang_detail`   WHERE id_terima_tukang = '$r[id_terima_tukang]'  ");
$noz = 1000;
$rst_jumlah = mysql_num_rows($tampiltable);
while ($rst = mysql_fetch_array($tampiltable)){
  $sql= mysql_query("SELECT * FROM barang WHERE id_barang = '$rst[id_barang]' ");
  $data = mysql_fetch_array($sql);
  echo '
  <tr class="inputtable">
  <input type=hidden name="id_npj_barang[]" value="'. $data['id_barang'].'" id="id_po_barang-'.$noz.'" >
    <input type=hidden name="id[]" value="'. $rst['id_trans_terima_tukang_detail'].'" id="id-'.$noz.'" >
    <input type="hidden" name="id_akunkasperkiraan[]" value="'.$data['id_akunkasperkiraan'].'" id="id_akunkasperkiraan-'.$noz.'">
    <input type=hidden value="'.$data['hpp'].'" name="hpp[]" value="" id="hpp-'.$noz.'" >
   <td><input type="text"  class="namabarang"  name="kode_barang[]" value="'.$data['kode_barang'].'"   id="kode_barang-'.$noz.'"  disabled /></td>
  <td><input type="text" class="namabarang" name="nama_barang[]" value="'.$data['nama_barang'].'" id="nama_barang-'.$noz.'"  disabled /></td>
    <td>
      <select id="gudang_lkb-'.$noz.'" name="gudang_lkb[]" class=" form-control" required>';
      $tampil43=mysql_query("SELECT * FROM gudang where is_void=0 ");
          if ($rst[id_gudang]==0){
            echo "<option value='' selected>- Pilih Gudang -</option>";
          }   
         while($w=mysql_fetch_array($tampil43)){
            if ($rst[id_gudang]==$w[id_gudang]){
              echo "<option value=$w[id_gudang] selected>$w[nama_gudang]</option>";
            }
            else{
              echo "<option value=$w[id_gudang]>$w[nama_gudang]</option>";
            }
          }
    echo '
      </select>
    </td>
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
      <td>
       <input type="text" name="harga_sat1[]" value="'.($rst[harga]*1).'"  id="harga-'.$noz.'" class="hitung numberhit" />
    </td>
  <td><input type="text" name="jumlah[]" id="satuan-'.$noz.'"  value="'.($rst[jumlah]*1).'" class="hitung" /></td>
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
echo'
        </tbody>
        <tfoot>
        <tr id="productall">
    <td colspan="2" rowspan="4"> <button class="btn btn-success"  type="submit"  name="update" value="update" style="float:left;">Update';

    echo '</button> 
          <a class="btn btn-warning" type="button" href="media.php?module=tukanganpenerimaan" style="float:left;margin-left:10px;">Batal</a>
          </td>
          <td colspan="4" style="text-align:right;"><b>Grand total</b></td>
    <td><input name="alltotal" type="text" class="hitung2 numberhit form-control" id="total" value='.$r[grandtotal].' readonly="readonly" ></td>
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

  case 'detail':
    $judul = "<b>Edit</b> Penerimaan Barang Jadi";
  $desk = "Modul digunakan untuk mencatat bahan jadi yang diberikan oleh tukang";
  headerDeskripsi($judul,$desk);

   
  echo "
   <form method='post' action='$aksi?module=tukanganpenerimaan&act=update'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No PBJ</td>
    <td>";
 $tampil44=mysql_query("SELECT * FROM `trans_terima_tukang_header`tpr,supplier s   WHERE tpr.id_supplier=s.id_supplier and id_trans_terima_tukang_header = '$_GET[id]'");
  $r    = mysql_fetch_array($tampil44);
  // if ($r['status']==1) {
  //   header('location:media.php?module=tukanganpenerimaan&act=detail&id='.$_GET['id']);
  // }
  echo "
   <input type='hidden' name='id' value='$r[id_trans_terima_tukang_header]' id='id_purchaseorder''>
  <input  name='no_npb' value='$r[id_terima_tukang]' id='id_pur_order' readonly='readonly'  class='form-control' />";
   echo " </td>
    <td>Tanggal</td>
    <td><input id='tanggal' name='tgl_trans' value='".tgl_indo($r['tgl_trans'])."' readonly='readonly'  class='form-control' ></td>
  </tr>
  <tr>
   <td>Supplier</td> <td id='sup'>";
   echo '<select class="form-control chosen-select" id="supplier" name="supplier">';
$tampil43=mysql_query("SELECT * FROM supplier where is_void=0 ");
          if ($r[id_supplier]==0){
            echo "<option value='' selected>- Pilih Supplier -</option>";
          }   
         while($w=mysql_fetch_array($tampil43)){
            if ($r[id_supplier]==$w[id_supplier]){
              echo "<option value=$w[id_supplier] selected>$w[nama_supplier]</option>";
            }
            // else{
            //   echo "<option value=$w[id_supplier]>$w[nama_supplier]</option>";
            // }
          }
echo '</select>
<br>';
echo"
  </td>
  <td>No Nota</td>
  <td><input type='text' name='nonota' value='$r[nonota_terima_tukang]' class='form-control' readonly='readonly'></td>
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
  <!--div class="btn btn-primary" type="button" id="search" data-toggle="modal" data-target="#search-md">Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
  </div-->
</div>
<table id="header" class="display table table-striped table-bordered table-hover" cellspacing="0">
        <thead>
      <tr style="background-color:#F5F5F5;">
        <th width="15%">Nama barang</th>
        <th width="20%">Kode barang</th>
        <th width="15%">Ke Gudang</th>
        <th width="10%" >Satuan</th>
        <th width="10%">Harga</th> 
        <th width="10%">Qty</th>
        <th width="10%">Total</th>
      </tr>
        </thead>
 
        <tbody id="product">';
$tampiltable=mysql_query("SELECT * FROM `trans_terima_tukang_detail`   WHERE id_terima_tukang = '$r[id_terima_tukang]'  ");
$noz = 1000;
$rst_jumlah = mysql_num_rows($tampiltable);
while ($rst = mysql_fetch_array($tampiltable)){
  $sql= mysql_query("SELECT * FROM barang WHERE id_barang = '$rst[id_barang]' ");
  $data = mysql_fetch_array($sql);
  echo '
  <tr class="inputtable">
  <input type=hidden name="id_po_barang[]" value="'. $data['id_barang'].'" id="id_po_barang-'.$noz.'" >
    <input type=hidden name="id_po[]" value="'. $rst['id_trans_terima_tukang_detail'].'" id="id_po-'.$noz.'" >
   <td><input type="text"  class="namabarang"  name="kode_barang[]" value="'.$data['kode_barang'].'"   id="kode_barang-'.$noz.'"  disabled /></td>
  <td><input type="text" class="namabarang" name="nama_barang[]" value="'.$data['nama_barang'].'" id="nama_barang-'.$noz.'"  disabled /></td>
    <td>';
      $tampil43=mysql_query("SELECT * FROM gudang where is_void=0 AND id_gudang='$rst[id_gudang]'");
      $w=mysql_fetch_array($tampil43);
      echo '<input type="text" name="gudang_lkb[]" value="'.$w['nama_gudang'].'"  id="gudang_lkb-'.$noz.'"  disabled/>
          
    </td>
   <td><input type="text" name="jenis_satuan[]" id="jenis_satuan-'.$noz.'"  value="'.$rst['satuan'].' ('.($rst[kali]*1).')'.'" class="hitung"  disabled/></td>
   <td><input type="text" name="harga[]" id="harga-'.$noz.'"  value="'.number_format($rst[harga]*1).'" class="hitung"  disabled/></td>
  <td><input type="text" name="jumlah[]" id="satuan-'.$noz.'"  value="'.($rst[jumlah]*1).'" class="hitung"  disabled/></td>
   <td>
       <input type="text" name="total[]" value="'.number_format($rst['total']*1).'"  id="total-'.$noz.'" class="hitung numberhit" disabled/>
    </td>
  
</tr>
';
$noz++;
}
echo'
        </tbody>
        <tfoot>
        <tr id="productall">
    <td colspan="2" rowspan="4">
          <a class="btn btn-warning" type="button" href="media.php?module=tukanganpenerimaan" style="float:left;margin-left:10px;">Kembali</a>
          </td>
          <td colspan="4" style="text-align:right;"><b>Grand total</b></td>
          <td><input name="alltotal" type="text" class="hitung2 numberhit form-control" id="total" value="Rp '.number_format($r[grandtotal]).'" readonly="readonly" ></td>
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
    $('#po').DataTable();

    $("#sup").change(function()
            { 
            var id = $("#supplier").find(":selected").val();
            var dataString = 'supplier='+ id;
            $.ajax
                      ({
                      url: 'modul/tukanganpenerimaan/filter.php',
                      data: dataString,
                      cache: false,
                      success: function(r)
                                {
                                       $("#txtHint").html(r);
                                } 
                      });
            });
} );

// end ready document

function hitungan(a){
   setTimeout(function() {
            var satuan = ($('#satuan-' + a).val() != '' ? $('#satuan-' + a).val() : 0),
                harga = ($('#harga-' + a).val() != '' ? $('#harga-' + a).val() : 0),
                subtotal = (parseFloat(satuan) * parseFloat(harga).toFixed(2));          
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
function addMore(kode) {
   var i = $('input').size() + 1;
    var kd1 = kode;
    //var supp =$('#supplier2 option:selected').val(); &supp="+supp+"
  $("<tr>").load("modul/tukanganpenerimaan/input.php?kd="+kd1+"&nox="+i+" ", function() {
      $("#product").append($(this).html());
       //$("#search-md").modal('toggle');

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
                    "ajax": "modul/tukanganpenerimaan/load-data.php",
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
                var u = $('#terimatukang').DataTable({
                      "columns": [
                        { "searchable": false },
                        null,
                        null,
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false }
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                       "pagingType" : "simple_numbers",
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/tukanganpenerimaan/load-data-a.php",
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

</script>
