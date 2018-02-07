
<?php
 include "config/koneksi.php";
 //echo '<script type="text/javascript" src="modul/salesinvoice/salesinvoice.js"></script>';
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
$aksi="modul/salesinvoice/aksi_salesinvoice.php";
switch($_GET['act']){
  // Tampil Modul
  default:
    echo '
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#all">Customer</a></li>
      <li><a data-toggle="tab" href="#payment">List Payment</a></li>
    </ul>

    <div class="tab-content">
      <div id="all" class="tab-pane fade in active">
        <div class="table-responsive">
          <table class="tb_customer display table table-striped table-bordered table-hover">
          <thead>
          <tr style="background-color:#F5F5F5;"">
            <th id="tablenumber">No</th>
            <th>Customer</th>
            <th>Region</th>
            <th>Telp Customer</th>
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
            <th>Customer</th>
            <th>Tipe Customer</th>
            <th>Alamat</th>
            <th>Region</th>
            <th>Telp Customer</th>
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
  $judul = "<b>Tambah</b> Laporan Invoice";
  $desk = "Tambah Sales Invoice";
  headerDeskripsi($judul,$desk);

  echo "
    <form method='post' action='$aksi?module=salesinvoice&act=input'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
<td>Nama Customer</td> <td><strong>:</strong></td>
  <td id='sup'>";
   echo '<select  class="chosen-select form-control hitung" tabindex="2" id="customer" name="customer" required>';
$tampil=mysql_query("SELECT * FROM customer c RIGHT JOIN trans_LKB tl ON(c.id_customer = tl.id_customer)
 WHERE tl.is_void=0 AND c.is_void=0 AND tl.status_trans ='1' GROUP by c.id_customer");
            echo "<option value='' selected>- Pilih customer -</option>";
         while($w=mysql_fetch_array($tampil)){
              echo "<option value=$w[id_customer]>$w[nama_customer]</option>";
            }
echo '</select></td>
  <td > Alamat </td><td><strong>:</strong></td>
    <td  id="alamat"></td>
  </tr>
  <tr>
      <td>No SI</td><td><strong>:</strong></td>
  <td> <input name="kodesi" id="kodesi" value="'.kode_surat('SI','trans_sales_invoice','id_invoice','id').'" class="form-control" ></td>
<td>No LKB</td><td><strong>:</strong></td>
<td>
<input  name="no_so" id ="no_so" data-toggle="modal" data-target="#myModal" readonly class="form-control"/>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nomer LKB</h4>
        </div>
        <div class="modal-body">
    <table border="1" class="table table-hover">
    <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>No LKB</th>      
      <th>No Nota Customer</th>
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
   <td> No Nota customer  </td><td><strong>:</strong></td>
   <td><input name="no_nota" id="no_nota" class="form-control" readonly ></td>
  <td> No Expedisi   </td><td><strong>:</strong></td>
    <td> <input name="no_expedisi" id="no_expedisi" class="form-control"> </td>
 </tr>
 <tr>
  <td>Tanggal Transaksi</td> <td><strong>:</strong></td>
    <td><input class="datetimepicker form-control" value="'.date('Y-m-d').'" name="tgl_si" required></td>
    <td>Tanggal Jatuh Tempo</td> <td><strong>:</strong></td>
    <td><input class="datetimepicker form-control" name="tgl_jt"  value="'.date('Y-m-d').'" required></td>
 </tr>';
  echo "</table>";

echo '
<DIV class="btn-action float-clear">
<table id="header" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Nama Barang</th>
      <th>Qty Diminta - Satuan</th>
      <th>Harga Dari SO</th>
      <th>Total SO</th>
      <th>Qty Diterima</th>
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

       echo' </tbody>
        <tfoot id="foot" style="float=right">
                </tfoot>
          </table>
  </div> 
  <button class="btn btn-success"  type="submit"  name="save" value="Save" style="float:left;">Save</button> 
  <a class="btn btn-warning" type="button" href="media.php?module=salesinvoice" style="float:left;margin-left:10px;">Batal</a>
  </form>'
  ;

    break;

    //******************************  pay 
    
      case "pay":
    $judul = "<strong>History Pembayaran</strong> Sales Invoice";
    $desk = "History Invoice";
    headerDeskripsi($judul,$desk);
    $edit = mysql_query("SELECT *  FROM trans_sales_invoice i,customer s
where i.id_customer=s.id_customer and i.id_invoice='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

echo "
    <form method='post' action='$aksi?module=salesinvoice&act=update'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
     <td>Nama Customer</td><td><strong>:<strong></td>
    <input type='hidden' name='id_customer' value='$r[id_customer]' readonly class='form-control'>
    <td>$r[nama_customer]</td>
    <td> Alamat </td><td><strong>:<strong></td>
    <td>$r[alamat_customer]</td>
  </tr>
  <tr>
    <td>No SI</td><td><strong>:<strong></td>
    <input type='hidden' name='id_sales_invoice' value='$r[id_header_invoice]' class='form-control'>
    <input type='hidden' name='id_invoice_lama' value='$r[id_invoice]' class='form-control'>
    <td>$r[id_invoice]</td>
   <td>No LKB</td><td><strong>:<strong></td> <td>$r[id_lkb]</td>
  </tr>
    <tr>
  <td>No Nota Supplier</td><td><strong>:<strong></td><td>$r[no_nota]</td>
  <td>No. Expedis</td><td><strong>:<strong></td><td>$r[no_expedisi]</td>
  </tr>
  <tr>
    <td>Tanggal Transaksi</td><td><strong>:<strong></td>
<td>".tgl_indo($r[tgl_si])."</td>
    <td>Tanggal Jatuh Tempo</td><td><strong>:<strong></td>
<td>".tgl_indo($r[tgl])."</td>
 </tr>
 </table>";

echo '
<DIV class="btn-action float-clear">
</DIV>
<table id="header" class="table table-hover table-bordered" cellspacing="0">
           <thead>
  <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Nota Bukti Pembayaran</th>
      <th>Tanggal Pembayaran</th>
      <th>Keluar dari akun kas</th>
      <th>Jumlah Pembayaran</th>
      <th>Jenis Pembayaran</th>
      <th>keterangan</th>
      <!--<th>hapus</th>-->
        </tr>
        </thead>
 
        <tbody id="product">';
               $invoice = mysql_query("SELECT *, (tbd.ket_detail_jual) as ketd, (tbh.ket_jual) as keth, (tbd.nominal_alokasi_detail_jual) as nomdetail from trans_bayarjual_detail tbd LEFT JOIN trans_bayarjual_header tbh ON(tbd.bukti_bayarjual=tbh.bukti_bayarjual) LEFT JOIN akun_kas_perkiraan as a ON(tbh.id_akunkasperkiraan=a.id_akunkasperkiraan) where tbd.is_void='0' and tbd.nota_invoice='$r[id_invoice]' order by tbd.id_bayarjual_detail desc ;");
        $no=1;
        while($t =mysql_fetch_array($invoice)){
          echo '
          <tr>
          <td>'.$no.'</td>
           <td>'.$t[bukti_bayarjual].'</td>
             <td>'.date("d M Y", strtotime($t['tgl_pembayaranjual'])).'</td>             
          <td>'.$t[kode_akun].' - '.$t[nama_akunkasperkiraan].'</td>
          <td>'.format_rupiah($t[nomdetail]).'</td>';
        $nota = explode("-", $t['bukti_bayarjual']);
          $jp = array("BKM","BGM","BBM");
          if($nota[0]!= $jp[1]){
            echo '<td>'.$nota[0].'<br><small><b>'.$t[rek_asal].'</td>';
          }
          else{
            echo '<td>'.$nota[0].'<br>
          <small><b>'.$t[rek_asal].'<br>
          '.$t[jatuh_tempo_jual].'</b></small></td>';
          }
                 echo ' 
                      <td>- '.$t[ketd].' <br>- '.$t[keth].'</td>';
        echo '
          </tr>';
        $no++;}

echo'
        </tbody>
       
          </table>
  ';
    break;
      case "edit":
  $judul = "<b>Edit</b> Sales Invoice";
  $desk = "Edit Sales Invoice";
  headerDeskripsi($judul,$desk);

    $edit = mysql_query("select *,ti.id as id_header_invoice from trans_sales_invoice ti,trans_sales_invoice_detail tid,customer s,barang b 
      where ti.id_invoice=tid.id_invoice and s.id_customer=ti.id_customer and b.id_barang=tid.id_barang and ti.id_invoice='$_GET[id]'");
    $r    = mysql_fetch_array($edit);
  echo "
    <form method='post' action='$aksi?module=salesinvoice&act=update'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
     <td>Nama Customer</td><td><strong>:<strong></td>
    <input type='hidden' name='id_customer' value='$r[id_customer]' readonly class='form-control'>
    <td>$r[nama_customer]</td>
    <td> Alamat </td><td><strong>:<strong></td>
    <td>$r[alamat_customer]</td>
  </tr>
  <tr>
    <td>No SI</td><td><strong>:<strong></td>
    <input type='hidden' name='id_sales_invoice' value='$r[id_header_invoice]' class='form-control'>
    <input type='hidden' name='id_invoice_lama' value='$r[id_invoice]' class='form-control'>
    <td><input id='kodesi' name='id_invoice' value='$r[id_invoice]' class='form-control'> </td>
   <td>No LKB</td><td><strong>:<strong></td> <td>$r[id_lkb]</td>
  </tr>
    <tr>
  <td>No Nota Supplier</td><td><strong>:<strong></td><td>$r[no_nota]</td>
  <td>No. Expedis</td><td><strong>:<strong></td><td>$r[no_expedisi]</td>
  </tr>
  <tr>
    <td>Tanggal Transaksi</td><td><strong>:<strong></td>
<td><input class='datetimepicker form-control'' name='tgl_si' value='$r[tgl_si]' class='form-control' required></td>
    <td>Tanggal Jatuh Tempo</td><td><strong>:<strong></td>
<td><input class='datetimepicker form-control'' name='tgl_jt' value='$r[tgl]' class='form-control' required></td>
 </tr>
 </table>";
echo '
<FORM name="frmProduct" method="post" action="">
<DIV class="btn-action float-clear">
</DIV>
<table id="header" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Nama Barang</th>
      <th>Qty Diminta</th>
      <th>Harga Dari SO</th>
      <th>Total SO</th>
      <th>Qty Diterima</th>
      <th>Harga</th>
      <th width="5%" >Disc 1  </br> (%)</th>
      <th width="5%" >Disc 2  </br> (%)</th>
      <th width="5%" >Disc 3  </br> (%)</th>
      <th width="5%" >Disc 4  </br> (%)</th>
      <th>Pembulatan </br>  (Rp.)</th>
      <th>Total</th>
        </tr>
        </thead>
 
        <tbody id="product">';
$tampiltable=mysql_query("select *,tid.qty_si_convert as qty from trans_sales_invoice ti,trans_sales_invoice_detail tid,customer s,barang b where ti.id_invoice=tid.id_invoice and s.id_customer=ti.id_customer and b.id_barang=tid.id_barang and ti.id_invoice='$_GET[id]'");

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
       <input style="width:170px;" type="hidden" name="nama_barang['.$noz.']" value="'.$rst['nama_barang'].'" id="nama_barang-'.$noz.'"  readonly />'.$rst['nama_barang'].'
    </td>
   <td>
       <input type="hidden" name="qty_so['.$noz.']" value="'.$rst['qty_so'].'"  id="qty-'.$noz.'" readonly class="hitung numberhit" />'
.$rst['qty_so'].' '.$rst['qty_so_satuan'].'
       <input type="hidden" name="qty_so_satuan['.$noz.']" value="'.$rst['qty_so_satuan'].'"  id="qty_so_satuan-'.$noz.'" readonly class="hitung numberhit" />
    </td>
    <td>'.$rst['harga_so'].'
       <input type="hidden" name="harga_so['.$noz.']" value="'.$rst['harga_so'].'"  id="harga_so-'.$noz.'" readonly class="hitung numberhit" />
    </td>
     <td>'.$rst['total_so'].'
       <input type="hidden" name="total_so['.$noz.']" value="'.$rst['total_so'].'"  id="total_so-'.$noz.'" readonly class="hitung numberhit" />
    </td>
     <td>
       <input type="hidden" name="qty_si['.$noz.']" value="'.$rst['qty_si'].'"  id="qty_si-'.$noz.'" readonly class="hitung numberhit" />
         <input type="hidden" name="hpp['.$noz.']" value="'.$rst['hpp'].'"  id="hpp-'.$noz.'" readonly class="hitung numberhit" />
          <input type="hidden" name="qty_si_convert['.$noz.']" value="'.$rst['qty'].'" id="qty_si_convert-'.$noz.'" readonly class="hitung numberhit" />
        '.$rst['qty_si'].' - '.$rst['qty_si_satuan'].'
       <input type="hidden" name="qty_si_satuan['.$noz.']" value="'.$rst['qty_si_satuan'].'"  id="qty_si_satuan-'.$noz.'" readonly class="hitung numberhit" />
    </td>
     <td>
       <input  type="text" name="harga_si['.$noz.']" value="'.$rst['harga_si'].'"  id="harga_si-'.$noz.'"  class="hitung numberhit form-control" />
    </td>
    <td><input  type="text" name="disc1['.$noz.']"  id="disc1_barang-'.$noz.'" value="'.$rst[disc1].'"  class="hitung numberhit form-control" /></td>
    <td><input  type="text" name="disc2['.$noz.']" id="disc2_barang-'.$noz.'" value="'.$rst[disc2].'"  class="hitung numberhit form-control" /></td>
    <td><input  type="text" name="disc3['.$noz.']" id="disc3_barang-'.$noz.'" value="'.$rst[disc3].'" class="hitung numberhit form-control" /></td>
    <td><input  type="text" name="disc4['.$noz.']" id="disc4_barang-'.$noz.'" value="'.$rst[disc4].'" class="hitung numberhit form-control"  /></td>
    <td><input  type="text" name="disc5['.$noz.']" id="disc5_barang-'.$noz.'" value="'.$rst[disc5].'" class="hitung numberhit form-control"  /></td>
    <td><input type="text" name="total['.$noz.']" id="total-'.$noz.'"  class="total numberhit form-control" value="'.$rst[total].'"  /></td>
</tr>
';
$noz++;
$no++;
}
  echo '<input type="hidden" name="noz" id="noz"  value="'.$noz.'"   />';
$tampiltable=mysql_query("SELECT * FROM trans_lkb t, trans_sales_order tt where tt.id_sales_order=t.id_sales_order; ");
if ($r['alldiscpersen']==0) {
$discper="";
}else{
  $discper=$r['alldiscpersen'];
}
if ($r['allppnpersen']==0) {
$ppnper="";
}else{
  $ppnper=$r['allppnpersen'];
}
echo'
        </tbody>
        <tfoot>
        <tr id="productall">
    <td colspan="7" rowspan="4"> <br><button class="btn btn-success"  type="submit"  name="save" value="Save" style="float:left;">Save </button> 
          <a class="btn btn-warning" type="button" href="media.php?module=salesinvoice" style="float:left;margin-left:10px;">Batal</a>
          </td>

    <td colspan="5" style="text-align:right;" ><p><b>ToTal All SUb </b></p></td>
    <td><input  name="alltotal" type="text" class="hitung form-control numberhit" id="total" value="'.$r[alltotal].'" readonly></td>



<input name="alldiscpersen" type="hidden" id="persendisc" style="width:2em;"  value='.$discper.' > | (Rp) 
<input name="alldiscnominal" type="hidden" id="totaldisc" value='.$r[alldiscnominal].'  class="hitung numberhit form-control" >
<input name="allppnpersen" type="hidden" id="persenppn" style="width:2em;" value='.$ppnper.' > 
<input name="allppnnominal" type="hidden" id="totalppn" value='.$r[allppnnominal].'  class="hitung numberhit form-control" >
<input class="numberhit form-control" name="grandtotal" type="hidden" id="grandtotal" value='.$r[grand_total].'  readonly="readonly" >
<input  name="grandtotal123" type="hidden" id="grandtotal123" value='.$r[grand_total].'  ></b></td>
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
$('#si').DataTable();
    $("#tgl_awal, #tgl_akhir").keyup( function() {
        ot.draw();
    } );
     $("#jenispembayaran").change(function()
     { 
          var jpr = $("#jenispembayaran").find(":selected").val();
          var val = $("#valjenis").val();
          var nota = $("#valjenis2").val();
          var dataString = 'jpr='+jpr+'&val='+val+'&nota='+nota;
          $.ajax
          ({
              url: 'modul/salesinvoice/jenispembayaran.php',
              data: dataString,
              cache: false,
              success: function(r)
                  {
                  $("#tampilformpembayaran").html(r);
                  } 
          });
     });
$('#my_modal').on('show.bs.modal', function(e) {
    var bookId = $(e.relatedTarget).data('book-id');
    $(e.currentTarget).find('input[name="bookId"]').val(bookId);
});

  $(document).on('focus click', '.hitung', function() {
    var aydi = $('#noz').val()        
    $(this).keydown(function() {
         setTimeout(function() {
          dihitung(aydi);
      }, 0);
     
    });
});
function dihitung(aydi){
           for (var i = 0; i <= (aydi-1); i++) {
            var satuan = ($('#qty_si-'+i).val()),
                harga = ($('#harga_si-' + [i]).val() ),
                disc1 = ($('#disc1_barang-' + [i]).val() != '' ? $('#disc1_barang-' + [i]).val() :0 ),
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
                totaldisc4pre = parseFloat(total1).toFixed(2) - parseFloat(totaldisc1).toFixed(2) - parseFloat(totaldisc2).toFixed(2)- parseFloat(totaldisc3).toFixed(2),
                totaldisc4 = parseFloat(totaldisc4pre).toFixed(2) * parseFloat(disc4).toFixed(2) / 100,
                subtotal = parseFloat(total1).toFixed(2) - parseFloat(totaldisc1).toFixed(2) - parseFloat(totaldisc2).toFixed(2) - parseFloat(totaldisc3).toFixed(2)- parseFloat(totaldisc4).toFixed(2) - parseFloat(disc5);
                           
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
  $("#customer").change(function()
 { 
  var id = $("#customer").find(":selected").val();
  var dataString = 'text='+ id;
  $.ajax
  ({
    url: 'modul/salesinvoice/proses_customer_lbm.php',
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
    ajax_check("kodesi",'trans_sales_invoice','id_invoice');

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
  var id = $("#customer").find(":selected").val();
  var dataString = 'customer='+ id;
  $.ajax
  ({
    url: 'modul/salesinvoice/filter_lbm.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#alamat").html(r);
   } 
  });
  $("#myModal").modal('show');
 })
});
$('#my_modal').on('show.bs.modal', function(e) {
    var bookId = $(e.relatedTarget).data('book-id');
    $(e.currentTarget).find('input[name="bookId"]').val(bookId);
});

  function nilaiso(kode) {
  var i = $('input').size() + 1;
  kode= kode.split("#");
    var kd1 = kode[0];
    var dataString = 'text='+ kd1+'&nox='+i;
    $("#no_expedisi").val(kode[1]);
    $("#no_nota").val(kode[2]);
      $("#no_so").val(kd1);
       $("#myModal").modal("toggle");
      $.ajax({
    url: 'modul/salesinvoice/laporanbarangmasuk_detail.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#product").html(r);
              var alltotalpbe = 0;
              var aydi = $('#noz').val()    ;
              dihitung(aydi);
              grandtotal1();


   } 

 });
};

datetimepiker();

  function totaldisc1(){
            var persendisc = ($('#persendisc').val()),
                    persenppn = ($('#persenppn').val()),
                    total = ($('#total').val()),
                totaldisc = parseFloat(total) * parseFloat(persendisc)/100;
                totalppn = (parseFloat(total) - parseFloat($('#totaldisc').val() != '' ? $('#totaldisc').val() : 0)) * parseFloat(persenppn) / 100;
            if (!isNaN(totaldisc)) {
                $('#totaldisc').val(totaldisc);
            } 
  }

function totalppn1(){
                    persenppn = ($('#persenppn').val()),
                    total = ($('#total').val()),
                    totaldisc = ($('#totaldisc').val()),
                totalppn = (parseFloat(total) - parseFloat(totaldisc)) * parseFloat(persenppn) / 100;
             if (!isNaN(totalppn)) {
                $('#totalppn').val(totalppn);
            }
  }

function grandtotal1(){
//$('#productall').on('focus', '.hitung numberhit2', function() {
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
