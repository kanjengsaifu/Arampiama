
<?php
 include "config/koneksi.php";
 //echo '<script type="text/javascript" src="modul/purchaseinvoice/purchaseinvoice.js"></script>';
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
$aksi="modul/adjustmenttukang/aksi_adjustmenttukang.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:

  $judul = "Master Adjustment Tukang";
  $desk = "Modul untuk melakukan adjustment stok pada tukang";
  $button="   <a href='?module=adjustmenttukang&act=tambah' class='btn btn-primary' >Buat Adjustment Tukang
      <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a>";
  headerDeskripsi($judul,$desk,$button);
    
      echo '
 <div class="col-md-12">
<div class="table-responsive">
<table id="po" class="display table table-striped table-bordered table-hover" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
    <th id="tablenumber">No</th>
          <th>Nama Tukang</th>
          <th>Nama Barang</th>
          <th>Tanggal</th>
          <th>Plus / Minus Barang</th>
          <th>Keterangan</th>
          <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="lbm_tampil">'; 
  $tampil = mysql_query("SELECT * FROM adjustment_tukang at, supplier s, barang b WHERE s.id_supplier = at.id_supplier AND at.id_barang = b.id_barang AND at.is_void = 0 ORDER BY id_adjustmen_tukang;");
  $no = 1;
      while ($r=mysql_fetch_array($tampil)){
echo "
<tr>
                 <td>$no</td>
            <td>$r[nama_supplier]</td>
            <td>$r[nama_barang]</td>
            <td>".tgl_indo($r['tgl_adjustment'])."</td>
            <td>$r[plus_minus]</td>
            <td>$r[keterangan]</td>
            <td>
              <a href='?module=adjustmenttukang&act=edit&id=$r[id_adjustmen_tukang]' class='btn btn-sm btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></a>
              <a href='$aksi?module=adjustmenttukang&act=hapus&id=$r[id_adjustmen_tukang]' class='btn btn-sm btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
            </td>";
$no++;
}
  echo "</tr>
        </tbody>
    </table>
  </div></div>";
    break;
    case "tambah":
    $judul = "<h2><b>Tambah</b> Adjustment Tukang</h2>";
    $desk = " Tambah adjustment pada tukang";
    headerDeskripsi($judul,$desk);
  echo "
    <form method='post' action='".$aksi."?module=adjustmenttukang&act=input' id='addadjustment'>
    <input  name='hppId' id ='hppId' type='hidden'>
    <input type='hidden' name='id_barang' id='id_barang'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
     <td>Supplier</td> <td><strong>:</strong></td><td id='sup'>";
   echo '<select  id="supplier" name="supplier" class="chosen-select form-control" tabindex="2" required>';
$tampil=mysql_query("SELECT  s.nama_supplier AS nama_supplier, s.id_supplier AS id_supplier FROM supplier  s RIGHT JOIN stok_tukang st ON(s.id_supplier = st.id_supplier) GROUP BY st.id_supplier");
            echo "<option value='' selected>- Pilih Supplier -</option>";
         while($w=mysql_fetch_array($tampil)){
              echo "<option value=$w[id_supplier]>$w[nama_supplier]</option>";
            }
echo '</select></td>
      <td>Nama Barang</td><td><strong>:</strong></td>
<td><input  name="nama_barang" id="nama_barang" data-toggle="modal" class="form-control" data-target="#myModal" readonly/>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Stok Barang Tukang</h4>
        </div>
        <div class="modal-body">
    <table border="1" class="table table-hover">
    <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Nama Barang</th>
      <th>Stok Tukang</th>      
    </tr>

<tbody id="tampil">

</tbody>
    </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>      
    </div>
  </div>
  </td>
  </tr>

 <tr>
 <td>Tanggal Adjustment</td><td><strong>:</strong></td>
    <td><input class="datetimepicker form-control" value="'.date('Y-m-d').'"" name="tgl_adjustment" required></td>
    <td rowspan="2" style="vertical-align:middle;">Keterangan</td><td rowspan="2" style="vertical-align:middle;"><strong>:</strong></td>
    <td rowspan="2"><textarea type="text" name="keterangan" class=form-control rows="3"/></textarea></td>
 </tr>
 <tr>
  <td>Plus - Minus</td><td><strong>:</strong></td>
  <td><input type="text" name="plus_minus" id="plus_minus" class="form-control"></td>
 </tr>';
  echo "</table>";


       echo'
     <input class="btn btn-success" type=submit value=Simpan>  
  <a class="btn btn-warning" type="button" href="media.php?module=adjustmenttukang">Batal</a>
  </form>';

    break;

    
      case "edit":
    $judul = "<b> Edit </b> Adjustment Tukang";
    $desk = "Edit adjustment pada tukang";
    headerDeskripsi($judul,$desk);
    $s = mysql_query("SELECT * FROM adjustment_tukang at, barang b WHERE b.id_barang = at.id_barang AND at.id_adjustmen_tukang = '$_GET[id]'");
    $r = mysql_fetch_array($s);
      echo "
    <form method='post' action='".$aksi."?module=adjustmenttukang&act=update' id='updateadjustment'>
    <input  name='id' id ='id' value=$r[id_adjustmen_tukang] type='hidden'>
    <input type='hidden' name='id_barang' id='id_barang' value=$r[id_barang]>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
     <td>Supplier</td> <td><strong>:</strong></td><td id='sup'>";
   echo '<select  id="supplier" name="supplier" class="chosen-select form-control" tabindex="2" required>';
$tampil=mysql_query("SELECT  s.nama_supplier AS nama_supplier, s.id_supplier AS id_supplier FROM supplier  s RIGHT JOIN stok_tukang st ON(s.id_supplier = st.id_supplier) GROUP BY st.id_supplier");
            if ($r[id_supplier]==0){
            echo "<option value='' selected>- Pilih Supplier -</option>";
          }   
         while($w=mysql_fetch_array($tampil)){
            if ($r[id_supplier]==$w[id_supplier]){
              echo "<option value=$w[id_supplier] selected>$w[nama_supplier]</option>";
            }
            else{
              echo "<option value=$w[id_supplier]>$w[nama_supplier]</option>";
            }
          }
echo '</select></td>
      <td>Nama Barang</td><td><strong>:</strong></td>
<td><input  name="nama_barang" id="nama_barang" data-toggle="modal" class="form-control" data-target="#myModal" value="'.$r['nama_barang'].'" readonly/>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Stok Barang Tukang</h4>
        </div>
        <div class="modal-body">
    <table border="1" class="table table-hover">
    <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Nama Barang</th>
      <th>Stok Tukang</th>      
    </tr>

<tbody id="tampil">

</tbody>
    </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>      
    </div>
  </div>
  </td>
  </tr>

 <tr>
 <td>Tanggal Adjustment</td><td><strong>:</strong></td>
    <td><input class="datetimepicker form-control" value="'.$r['tgl_adjustment'].'" name="tgl_adjustment" required></td>
    <td rowspan="2" style="vertical-align:middle;">Keterangan</td><td rowspan="2" style="vertical-align:middle;"><strong>:</strong></td>
    <td rowspan="2"><textarea type="text" name="keterangan" class=form-control rows="3"/>'.$r['keterangan'].'</textarea></td>
 </tr>
 <tr>
  <td>Plus - Minus</td><td><strong>:</strong></td>
  <td><input type="text" name="plus_minus" id="plus_minus" class="form-control" value="'.$r['plus_minus'].'"></td>
 </tr>';
  echo "</table>";


       echo'
     <input class="btn btn-success" type=submit value=Simpan>  
  <a class="btn btn-warning" type="button" href="media.php?module=adjustmenttukang">Batal</a>
  </form>';

    break;
  }
    }
}
?>
<script type="text/javascript">
datetimepiker();
 var ot = $('#po').DataTable({
      "iDisplayLength": 20,
      "aLengthMenu": [ 
      [20, 50,100],
      [20,50, 100]
      ]
    });

 $("#hppUpdate").click(function (){
         $('#hppId').val(1);
});

  $("#hppOnly").click(function (){
         $('#hppId').val();
});


 $("#sup").change(function()
 { 
  var id = $("#supplier").find(":selected").val();
  var dataString = 'supplier='+ id;
  $.ajax
  ({
    url: 'modul/purchaseinvoice/filter.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#txtHint").html(r);
   }  
  });
 });


 // code to get all records from table via select box

$('#my_modal').on('show.bs.modal', function(e) {
    var bookId = $(e.relatedTarget).data('book-id');
    $(e.currentTarget).find('input[name="bookId"]').val(bookId);
});

  $(document).on('focus', '.hitung', function() {
    var aydi = $('#noz').val()

    $(this).keydown(function() {
         setTimeout(function() {
          dihitung(aydi);
   }, 0);
     
    });
});

  $("#supplier").change(function()
 { 
  var id = $("#supplier").find(":selected").val();
  var dataString = 'text='+ id;
  $.ajax
  ({
    url: 'modul/adjustmenttukang/stok_tukang.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#tampil").html(r);
   } 
  });
 })
$(document).ready(function()
{  

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
       $(this).keydown(function() {
        setTimeout(function() {
            sisa_harga();
            }, 0);
         });  
 $("#sup").change(function()
 { 
  var id = $("#supplier").find(":selected").val();
  var dataString = 'supplier='+ id;
  $.ajax
  ({
    url: 'modul/purchaseinvoice/filter_lbm.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#alamat").val(r);
   } 
  });
  $("#myModal").modal('show');
 })
 
});
$('#my_modal').on('show.bs.modal', function(e) {
    var bookId = $(e.relatedTarget).data('book-id');
    $(e.currentTarget).find('input[name="bookId"]').val(bookId);
});

  function nilaipo(kode) {
  var i = $('input').size() + 1;
  kode=kode.split('#');
    var kd1 = kode[0];
    var dataString = 'text='+ kd1+'&nox='+i;
      $("#nama_barang").val(kd1);
      $("#id_barang").val(kode[1]);
       $("#myModal").modal("toggle");
 
};

</script>