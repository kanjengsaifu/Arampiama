
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
$aksi="modul/purchaseinvoice/aksi_purchaseinvoice.php";
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
    $judul = "<h2><b>Tambah</b> Laporan Invoice</h2>";
    $desk = " Tambah Invoice";
    headerDeskripsi($judul,$desk);
  echo "
    <form method='post' action='".$aksi."?module=purchaseinvoice&act=input' id='addinvoice'>
    <input  name='hppId' id ='hppId' type='hidden'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
     <td>Supplier</td> <td><strong>:</strong></td><td id='sup'>";
   echo '<select  id="supplier" name="supplier" class="chosen-select form-control" tabindex="2" required>';
$tampil=mysql_query("SELECT  s.nama_supplier AS nama_supplier, s.id_supplier AS id_supplier FROM Supplier  s RIGHT JOIN trans_lpb tl ON(s.id_supplier=tl.id_supplier) where tl.is_void='0' AND tl.status_trans = '1' GROUP BY tl.id_supplier");
            echo "<option value='' selected>- Pilih Supplier -</option>";
         while($w=mysql_fetch_array($tampil)){
              echo "<option value=$w[id_supplier]>$w[nama_supplier]</option>";
            }
echo '</select></td>
      <td > Alamat </td><td><strong>:</strong></td>
    <td ><textarea class="form-control" id="alamat" disabled></textarea></td>
  </tr>
  <tr>
  <td>No PI</td><td><strong>:</strong></td>
    <td><strong>'.kode_surat('PI','trans_invoice','id_invoice','id').'</strong></td>
<td>No LBM</td><td><strong>:</strong></td>
<td><input  name="no_po" id ="no_po" data-toggle="modal" class="form-control" data-target="#myModal" readonly/>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nomor LBM</h4>
        </div>
        <div class="modal-body">
    <table border="1" class="table table-hover">
    <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>No LBM</th>      
      <th>Nota Supplier</th>
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
    <td> No Nota Supplier : </td> <td><strong>:</strong></td>
    <td><input name="no_nota" id="no_nota" class="hitung form-control" readonly></td>
     <td>No Nota Expedisi : </td><td><strong>:</strong></td>
    <td><input name="no_expedisi" id="no_expedisi" class="hitung form-control"></td>
 </tr>
 <tr>
 <td>Tanggal Transaksi</td><td><strong>:</strong></td>
    <td><input class="datetimepicker form-control" value="'.date('Y-m-d').'"" name="tgl_pi" required></td><td>Tanggal Jatuh Tempo</td><td><strong>:</strong></td>
    <td><input class="datetimepicker form-control"  value="'.date('Y-m-d').'"" name="tgl_jt" required></td>
 </tr>';
  echo "</table>";

echo '
<DIV class="btn-action float-clear">
<table id="header" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Nama Barang</th>
      <th>Qty diminta</th>
      <th>Harga Dari PO</th>
      <th>Total PO</th>
      <th>Qty diterima</th>
      <th>Harga</th>
      <th>Disc 1 (%)</th>
      <th>Disc 2 (%)</th>
      <th>Disc 3 (%)</th>
      <th>Disc 4 (%)</th>
      <th>Pembulatan (Rp.)</th>
      <th>Total</th>
        </tr>
        </thead>
 
        <tbody id="product">';

       echo' </tbody>
        <tfoot id="foot" style="float=right">
                </tfoot>
          </table>
  <a class="btn btn-success" style="float:left;" data-toggle="modal" data-target="#logModal">Save</a> 

<div class="modal fade" id="logModal" role="dialog">
    <div class="modal-dialog modal-md">

      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Apakah anda mau mengupdate hpp barang ?</h4>
        </div>
        <div class="modal-footer">
         <button class="btn btn-sm btn-danger"  id="hppUpdate" style="float:left;" type="submit">Save & Update</button> 
          <button class="btn btn-sm btn-warning"  id="hppOnly" style="float:left;" type="submit">Save Saja </button> 
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>      
    </div>
  </div>

  <a class="btn btn-warning" type="button" href="media.php?module=purchaseinvoice" style="float:left;margin-left:10px;">Batal</a>
  </form>';

    break;

    //******************************  pay 
    
      case "pay":
    $judul = "<strong>History Pembayaran</strong> Purchase Invoice";
    $desk = "History Invoice";
    headerDeskripsi($judul,$desk);

    $edit = mysql_query("SELECT *  FROM trans_invoice i,supplier s
where i.id_supplier=s.id_supplier and i.id_invoice='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

  echo "
    <form method='post' action='$aksi?module=purchaseinvoice&act=update'>
    <input  name='hppId' id ='hppId' type='hidden'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>Supplier</td><td><strong>:<strong></td>
  <input type='hidden' name='id_supplier' value='$r[id_supplier]' class='form-control'>
  <input type='hidden' name='id_invoice' value='$r[id_invoice]' class='form-control'>
  <td>$r[nama_supplier]</td>
  <td> Alamat </td><td><strong>:<strong></td><td>$r[alamat_supplier]</td>
  </tr>
  <tr>
  <td>No Pembayaran</td><td><strong>:<strong></td><td>$r[id_invoice]</td>
  <td>No LPB</td><td><strong>:<strong></td> <td>$r[id_lpb]</td>
  </tr>
  <tr>
  <td>No Nota Supplier</td><td><strong>:<strong></td><td>$r[no_nota]</td>
  <td>No. Expedis</td><td><strong>:<strong></td><td>$r[no_expedisi]</td>
  </tr>
    <td>Tanggal Transaksi</td><td><strong>:<strong></td>
<td>$r[tgl_pi]'</td>
  <td>Tanggal Jatuh Tempo</td><td><strong>:<strong></td>
<td>$r[tgl]</td>
 </tr>
 </table>";



echo '
<DIV class="btn-action float-clear">
</DIV>
<table id="header" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color : #F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Nota Bukti Pembayaran</th>
      <th>Tanggal Pembayaran</th>
      <th>Keluar dari akun kas</th>
      <th>Sisa Pembayaran</th>
      <th>Jumlah Pembayaran</th>
      <th>Jenis Pembayaran</th>
      <th>keterangan</th>
      <!--<th>hapus</th>-->
        </tr>
        </thead>
 
        <tbody id="product">';
        /*$invoice= mysql_query("SELECT * FROM `trans_pembayaran` WHERE id_invoice='$r[id_invoice]' and is_void =0 order by id desc");*/
        $invoice = mysql_query("SELECT *, (tbd.ket) as ketd, (tbh.ket) as keth, (tbd.nominal_alokasi) as nomdetail from trans_bayarbeli_detail tbd LEFT JOIN trans_bayarbeli_header tbh ON(tbd.bukti_bayar=tbh.bukti_bayar) LEFT JOIN akun_kas_perkiraan as a ON(tbh.id_akunkasperkiraan=a.id_akunkasperkiraan) where tbd.is_void='0' and tbd.nota_invoice='$r[id_invoice]' order by tbd.id_bayarbeli_detail desc ;");
        $no=1;
        //$ccount= mysql_num_rows($invoice);
        while($t =mysql_fetch_array($invoice)){
        /*$jenispay= mysql_query("SELECT * FROM `jenis_pembayaran` WHERE id='$t[jenispembayaran]'");
        $j =mysql_fetch_array($jenispay);*/
          echo '
          <tr>
          <td>'.$no.'</td>
          <td>'.$t[bukti_bayar].'</td>
          <td>'.date("d M Y", strtotime($t['tgl_pembayaran'])).'</td>
          <td>'.$t[kode_akun].' - '.$t[nama_akunkasperkiraan].'</td>
           <td>'.format_rupiah($t[sisa_invoice]).'</td>
          <td>'.format_rupiah($t[nomdetail]).'</td>';
          $nota = explode("-", $t['bukti_bayar']);
          $jp = array("BKK","BGK","BBK");
          if($nota[0] != $jp[1] ){
            echo '<td>'.$nota[0].'<br><small><b>'.$t[rek_tujuan].'</td>';
          }
          else{
            echo '<td>'.$nota[0].'<br>
          <small><b>'.$t[rek_tujuan].'<br>
          '.$t[jatuh_tempo].'</b></small></td>';
          }
           echo ' 
                      <td>- '.$t[ketd].' <br>- '.$t[keth].'</td>';
        echo '
          </tr>';
        $no++;}

echo'
        </tbody>
       
          </table>
  </div> 
  ';
    break;
      case "edit":
    $judul = "<b> Edit </b>Purchase Invoice";
    $desk = "Edit Invoice";
    headerDeskripsi($judul,$desk);

   $edit = mysql_query("select * from trans_invoice ti, supplier s where s.id_supplier=ti.id_supplier and ti.id_invoice='$_GET[id]'");
    $r    = mysql_fetch_array($edit);
  echo "
    <form method='post' action='$aksi?module=purchaseinvoice&act=update'>
    <input  name='hppId' id ='hppId' type='hidden'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>Supplier</td><td><strong>:<strong></td>
  <input type='hidden' name='id_supplier' value='$r[id_supplier]' class='form-control'>
  <input type='hidden' name='id_invoice' value='$r[id_invoice]' class='form-control'>
  <td>$r[nama_supplier]</td>
  <td> Alamat </td><td><strong>:<strong></td><td>$r[alamat_supplier]</td>
  </tr>
  <tr>
  <td>No Pembayaran</td><td><strong>:<strong></td><td>$r[id_invoice]</td>
  <td>No LPB</td><td><strong>:<strong></td> <td>$r[id_lpb]</td>
  </tr>
  <tr>
  <td>No Nota Supplier</td><td><strong>:<strong></td><td>$r[no_nota]</td>
  <td>No. Expedis</td><td><strong>:<strong></td><td>$r[no_expedisi]</td>
  </tr>
    <td>Tanggal Transaksi</td><td><strong>:<strong></td>
<td><input class='datetimepicker form-control' name='tgl_pi' value='$r[tgl_pi]' class='form-control' required></td>
  <td>Tanggal Jatuh Tempo</td><td><strong>:<strong></td>
<td><input class='datetimepicker form-control' name='tgl_jt' value='$r[tgl]' class='form-control' required></td>
 </tr>
 </table>";
echo '
<DIV class="btn-action float-clear">
</DIV>
<table id="header" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Nama Barang</th>
      <th>Qty diminta</th>
      <th>Harga Dari PO</th>
      <th>Total PO</th>
      <th>Qty diterima</th>
      <th>Harga</th>
      <th width="5%" >Disc 1 </br>(%)</th>
      <th width="5%" >Disc 2 </br>(%)</th>
      <th width="5%" >Disc 3 </br>(%)</th>
      <th width="5%" >Disc 4 </br>(%)</th>
      <th>Pembulatan </br>(Rp.)</th>
      <th>Total</th>
        </tr>
        </thead>
 
        <tbody id="product">';
$tampiltable=mysql_query("select * from trans_invoice_detail tid, barang b where b.id_barang=tid.id_barang  and tid.id_invoice='$r[id_invoice]'");

$noz = 0;
$no=1;
$rst_jumlah = mysql_num_rows($tampiltable);

while ($rst = mysql_fetch_array($tampiltable)){
  echo '
  <tr class="inputtable">
    <td>
     <input type="hidden" name="id['.$noz.']" value="'.$rst['id'].'" id="id-'.$noz.'"  readonly />
       '.$no.'
    </td>
    <td>
     <input type="hidden" name="id_barang['.$noz.']" value="'.$rst['id_barang'].'" id="nama_barang-'.$noz.'"  readonly />
       <input type="hidden" style="width:80px;" type="text" name="nama_barang['.$noz.']" value="'.$rst['nama_barang'].'" id="nama_barang-'.$noz.'"  readonly />'.$rst['nama_barang'].'
    </td>
   <td>
       <input type="hidden" style="width:50px;" type="text" name="qty_po['.$noz.']" value="'.$rst['qty_po'].'"  id="qty-'.$noz.'" readonly class="hitung numberhit" />'.$rst['qty_po'].'
    </td>
    <td>
       <input type="hidden" type="text" name="harga_po['.$noz.']" value="'.$rst['harga_po'].'"  id="harga_po-'.$noz.'" readonly class="hitung numberhit" />'.$rst['harga_po'].'
    </td>
     <td>
       <input type="hidden" type="text" name="total_po['.$noz.']" value="'.$rst['total_po'].'"  id="total_po-'.$noz.'" readonly class="hitung numberhit" />'.$rst['total_po'].'
    </td>
     <td>
     '.$rst['qty_pi'].' - '.$rst['qty_pi_satuan'].'
       <input style="width:50px;" type="hidden" name="qty_pi['.$noz.']" value="'.$rst['qty_pi'].'"  id="qty_pi-'.$noz.'" readonly class="hitung" />
        <input style="width:50px;" type="hidden" name="qty_pi_satuan['.$noz.']" value="'.$rst['qty_pi_satuan'].'"  id="qty_pi_satuan-'.$noz.'" readonly class="hitung numberhit" />
    </td>
     <td>
       <input type="text" name="harga_pi['.$noz.']" value="'.$rst['harga_pi'].'"  id="harga_pi-'.$noz.'"  class="hitung numberhit" />
    </td>
    <td><input type="text" name="disc1['.$noz.']"  id="disc1_barang-'.$noz.'" value="'.$rst[disc1].'" class="hitung numberhit" /></td>
    <td><input type="text" name="disc2['.$noz.']" id="disc2_barang-'.$noz.'" value="'.$rst[disc2].'" class="hitung numberhit" /></td>
    <td><input type="text" name="disc3['.$noz.']" id="disc3_barang-'.$noz.'" value="'.$rst[disc3].'" class="hitung numberhit" /></td>
    <td><input type="text" name="disc4['.$noz.']" id="disc4_barang-'.$noz.'" value="'.$rst[disc4].'" class="hitung numberhit"  /></td>
    <td><input type="text" name="disc5['.$noz.']" id="disc5_barang-'.$noz.'" value="'.$rst[disc5].'" class="hitung numberhit"  /></td>
    <td><input class="numberhit total" type="text" name="total['.$noz.']" id="total-'.$noz.'"   value="'.$rst[total].'"  /></td>
</tr>
';
$noz++;
$no++;
}
  echo '<input type="hidden" id="noz" name="noz"  value="'.$noz.'"   />';
$tampiltable=mysql_query("SELECT * FROM trans_lpb t, trans_pur_order tt where tt.id_pur_order=t.id_pur_order; ");
if ($r['alldiscpersen']==0) {
$discper='';
}else{
  $discper=$r['alldiscpersen'];
}
if ($r['allppnpersen']==0) {
$ppnper='';
}else{
  $ppnper=$r['allppnpersen'];
}
echo'
        </tbody>
        <tfoot>
        <tr id="productall">
    <td colspan="7" rowspan="4"> <br>
     <a class="btn btn-success" style="float:left;" data-toggle="modal" data-target="#logModal">Save</a> 

<div class="modal fade" id="logModal" role="dialog">
    <div class="modal-dialog modal-md">

      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Apakah anda mau mengupdate hpp barang ?</h4>
        </div>
        <div class="modal-footer">
         <button class="btn btn-sm btn-danger"  id="hppUpdate" style="float:left;" type="submit">Save & Update</button> 
          <button class="btn btn-sm btn-warning"  id="hppOnly" style="float:left;" type="submit">Save Saja </button> 
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>      
    </div>
  </div>
          <a class="btn btn-warning" type="button" href="media.php?module=purchaseinvoice" style="float:left;margin-left:10px;">Batal</a>
          </td>

<td colspan="5" style="text-align:right;" ><p><b>ToTal All SUb </b></p></td>
    <td colspan="1"  ><input name="alltotal" type="text" class="hitung2 numberhit" id="total" value="'.$r[alltotal].'" readonly></td>
  </tr>

  <tr>
<td colspan="5" style="text-align:right;"><p> Disc (%) <input name="alldiscpersen" type="text" id="persendisc" style="width:2em;"  value='.$discper.' > | (Rp) </p></td>
   <td colspan="1" style="nowrap:nowrap;"><input name="alldiscnominal" type="text" id="totaldisc" value='.$r[alldiscnominal].'  class="hitung2 numberhit" ></td>
  </tr>
  <tr>
<td colspan="5" style="text-align:right;"><p> Ppn (%) <input name="allppnpersen" type="text" id="persenppn" style="width:2em;" value='.$ppnper.' > | (Rp) </p></td>
    <td colspan="1"  style="nowrap:nowrap;"><input name="allppnnominal" type="text" id="totalppn" value='.$r[allppnnominal].'  class="hitung2 numberhit" ></td>
  </tr>
  <tr>
<td colspan="5" style="text-align:right;"><b>Grand total</b></td>
    <td colspan="1"><b><input name="grandtotal" type="text" id="grandtotal" value='.$r[grand_total].'  readonly="readonly" class="numberhit" ></b><input name="grandtotal123" id="grandtotal123" type="hidden" value='.$r[grand_total].' ></td>
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
              <form method="post" action="modul/purchaseorder/filter-barang.php">
              <label>Masukan
              nama barang <br>  atau kode barang</label>
              <input type="text" name="search" id="search_box" class="search_box"/>
               <button type="submit"  class="btn btn-primary search_button" id="search-item">
               <span class="glyphicon glyphicon-search"></span></button><br />
              </form>
      <table class="table table-hover table-bordered" cellspacing="0">
        <thead>
                <tr style="background-color:#F5F5F5;">
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga 1</th>
                    <th>Stok Min</th>
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
datetimepiker();
$('#pi').DataTable();


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

     $("#jenispembayaran").change(function()
     { 
          var jpr = $("#jenispembayaran").find(":selected").val();
          var val = $("#valjenis").val();
          var nota = $("#valjenis2").val();
          var dataString = 'jpr='+jpr+'&val='+val+'&nota='+nota;
          $.ajax
          ({
              url: 'modul/purchaseinvoice/jenispembayaran.php',
              data: dataString,
              cache: false,
              success: function(r)
                  {
                  $("#tampilformpembayaran").html(r);
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
function dihitung(aydi){
            for (var i = 0; i <= (aydi-1); i++) {
            var satuan = ($('#qty_pi-'+i).val()),
                harga = ($('#harga_pi-' + [i]).val() ),
                disc1 = ($('#disc1_barang-' + [i]).val() != '' ? $('#disc1_barang-' + [i]).val() : 0 ),
                disc2 = ($('#disc2_barang-' + [i]).val() != '' ? $('#disc2_barang-' + [i]).val() : 0),
                disc3 = ($('#disc3_barang-' + [i]).val() != '' ? $('#disc3_barang-' + [i]).val() : 0),
                disc4 = ($('#disc4_barang-' + [i]).val() != '' ? $('#disc4_barang-' + [i]).val() : 0),
                disc5 = ($('#disc5_barang-' + [i]).val() != '' ? $('#disc5_barang-' + [i]).val() : 0),
                total1 = (parseFloat(satuan) * parseFloat(harga).toFixed(2)),
                totaldisc1 = parseFloat(total1).toFixed(2) * parseFloat(disc1).toFixed(2) / 100,
                totaldisc2pre = parseFloat(total1).toFixed(2) - parseFloat(totaldisc1).toFixed(2),
                totaldisc2 = parseFloat(totaldisc2pre).toFixed(2) * parseFloat(disc2).toFixed(2) / 100,
                totaldisc3pre = parseFloat(total1).toFixed(2) - parseFloat(totaldisc1).toFixed(2) - parseFloat(totaldisc2).toFixed(2),
                totaldisc3 = parseFloat(totaldisc3pre).toFixed(2) * parseFloat(disc3).toFixed(2) / 100,
                totaldisc4pre = parseFloat(total1).toFixed(2) - parseFloat(totaldisc1).toFixed(2) - parseFloat(totaldisc2).toFixed(2)-parseFloat(totaldisc3).toFixed(2),
                totaldisc4 = parseFloat(totaldisc4pre) * parseFloat(disc4) / 100,
                subtotal = parseFloat(total1).toFixed(2) - parseFloat(totaldisc1).toFixed(2) - parseFloat(totaldisc2).toFixed(2) - parseFloat(totaldisc3).toFixed(2) - parseFloat(totaldisc4).toFixed(2) - parseFloat(disc5);


            if (!isNaN(subtotal)) {
                $('#total-' + [i]).val(Math.round(subtotal));
        var alltotalpbe = 0;
        $('.total').each(function(){
          alltotalpbe += parseFloat($(this).val());
        });
          }
                  var alltotal = alltotalpbe ;
                  $('#total').val(alltotal);
        }

}
  $("#supplier").change(function()
 { 
  var id = $("#supplier").find(":selected").val();
  var dataString = 'text='+ id;
  $.ajax
  ({
    url: 'modul/purchaseinvoice/proses_supplier_lbm.php',
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
      $("#no_po").val(kd1);
      $("#no_nota").val(kode[2]);
      $("#no_expedisi").val(kode[1]); 
       $("#myModal").modal("toggle");
      $.ajax({
    url: 'modul/purchaseinvoice/laporanbarangmasuk_detail.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#product").html(r);
        var alltotalpbe = 0;
           var aydi = $('#noz').val()
           dihitung(aydi);
            grandtotal1();
   } 
 });
};

  function totaldisc1(){
            var persendisc = ($('#persendisc').val()),
                    persenppn = ($('#persenppn').val()),
                    total = ($('#total').val()),
                totaldisc = parseFloat(total).toFixed(2) * parseFloat(persendisc).toFixed(2)/100;
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
//$('#productall').on('focus', '.hitung2', function() {
               var subtotal = ($('#total').val() != '' ? $('#total').val() : 0),
                disc = ($('#totaldisc').val() != '' ? $('#totaldisc').val() : 0),
                ppn = ($('#totalppn').val() != '' ? $('#totalppn').val() : 0),
                grandtotal = parseFloat(subtotal) - parseFloat(disc) + parseFloat(ppn);
            if (!isNaN(grandtotal)) {
                $('#grandtotal').val(Math.round(grandtotal));
               
            }
       }
 function sisa_harga(){
            var jsisa = ($('#jumlah_sisa').val()),
                jdibayar = ($('#jumlah_dibayarkan').val()),
                jhasil = parseFloat(jsisa) - parseFloat(jdibayar);
            if (!isNaN(jhasil)) {
                $('#sisa_dipembayaran').val(jhasil);
            } 
  }
</script>
