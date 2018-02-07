<?php
 include "config/koneksi.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();

$filenama = "laporanpenjualan";
$aksi="modul/$filenama/aksi_$filenama.php";


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

echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
$judul = "Laporan Penjualan Group By Customer";
$desk =  "Laporan transaksi Penjualan dari setiap <b>Customer</b>";

   headerDeskripsi($judul,$desk);

echo "<div class='row'>
          <div class='col-md-2'>
                  <label>Tanggal Mulai: </label><input id='minrangecus' name='min' class='form-control datepicker'>
          </div>
           <div class='col-md-2'><label>Sampai :</label> 
                <div class='input-group'>
                           <input id='maxrangecus' name='max' class='form-control datepicker'>
                           <span class='input-group-btn'>
                                   <button class='btn btn-warning' id='tanggalnull'  type='button' style='padding:0px 12px;' title='Kosongkan Field Tanggal'>( )</button>
                           </span>
                </div>
           </div>
           <div class='row'>
                      <button id='caricus' class='btn btn-primary iniganti' ><b>Cari</b> <span class='glyphicon glyphicon-search' aria-hidden='true'></span></button>
                      <button id='caricus3' class='btn btn-primary iniganti3' style='display:none;'><b>Cari</b> <span class='glyphicon glyphicon-search' aria-hidden='true'></span></button>
           </div>
           </div>";

    
            echo "<div class='table-responsive'>
       <table id='laporanpenjualan3' class='display table table-striped table-bordered table-hover' cellspacing='0' width='100%'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
        <th id='tablenumber'>No</th>
        <th>Nama Customer</th>
        <th>No Telp</th>
        <th class='dt-right'>Total Penjualan</th>
        <th>Total Terbayar</th>
        <th>Total HPP</th>
        <th>Total_laba</th>
        <th>Sisa Pembayaran</th>
        <th>Aksi</th>
    </tr>
        </thead>
    </table>
    </div>";
  


  break;
  case "detail":
  $id=$_GET[id];
  $query=mysql_query("Select * From Customer Where id_customer=$id");
  $r=mysql_fetch_array($query);
$judul = "Kartu Penjualan Detail Per Nota";
$desk =  "Kartu transaksi pembelian dari Customer $r[nama_customer] per <b>Nota </b>";

   headerDeskripsi($judul,$desk);

echo "<div class='row'>
          <div class='col-md-2'>
                  <label>Tanggal Mulai: </label><input id='minrangecus' name='min' class='form-control datepicker'>
          </div>
           <div class='col-md-2'><label>Sampai :</label> 
                <div class='input-group'>
                           <input id='maxrangecus' name='max' class='form-control datepicker'>
                           <span class='input-group-btn'>
                                   <button class='btn btn-warning' id='tanggalnull'  type='button' style='padding:0px 12px;' title='Kosongkan Field Tanggal'>( )</button>
                           </span>
                </div>
           </div>
           <div class='row'>
                      <button id='caricus' class='btn btn-primary iniganti' ><b>Cari</b> <span class='glyphicon glyphicon-search' aria-hidden='true'></span></button>
                      <button id='caricus3' class='btn btn-primary iniganti3' style='display:none;'><b>Cari</b> <span class='glyphicon glyphicon-search' aria-hidden='true'></span></button>
           </div>
           </div>";

    
            echo "<div class='table-responsive'>
       <table id='laporanpenjualan3' class='display table table-striped table-bordered table-hover' cellspacing='0' width='100%'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
        <th id='tablenumber'>No</th>
        <th>SO</th>
        <th>LKB</th>
        <th>SI</th>
        <th>Tanggal</th>
        <th>GrandTotal</th>
        <th>Pembayaran</th>
        <th>hpp</th>
        <th>Laba</th>
        <th>Sisa Pembayaran</th>
        <th>Customer</th>        
        <th>Sales</th>
    </tr>
        </thead>
    </table>
    </div>";
  break;

case 'so':
$select=mysql_query("SELECT * FROM trans_sales_order tso LEFT JOIN customer ctr ON tso.id_customer = ctr.id_customer LEFT JOIN sales sls  ON sls.id_sales = tso.id_sales WHERE tso.id_sales_order = '$_GET[id]' order by tso.id desc limit 1");
$row=mysql_fetch_array($select);
echo "<h2><b>Detail</b> Sales Order</h2>
  <div class='table-responsive'>
    <table class='table table-hover' border=0 id=tambah>
      <tr>
        <td>No. SO</td>
        <td>$row[id_sales_order]</td>
        <td>Tanggal SO</td>
        <td>".date('d-m-Y',strtotime($row[tgl_so]))."</td>
      </tr>
      <tr>
        <td>Customer</td>
        <td>$row[nama_customer]</td>
        <td>Sales</td>
        <td>$row[nama_sales]</td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td>$row[alamat_customer]</td>
        <td>Tlp./Hp</td>
        <td>$row[telp1_customer]</td>
      </tr>
    </table>
    <table id=header class=table table-hover table-bordered cellspacing=0>
      <thead>
        <tr style=background-color:#F5F5F5;>
          <th>Kode Barang</th>
          <th>Nama barang</th>
          <th>Harga</th>
          <!--th>satuan</th-->
          <th>Qty</th>
          <th>Disc 1 (%)</th>
          <th>Disc 2 (%)</th>
          <th>Disc 3 (%)</th>
          <th>Disc 4 (%)</th>
          <th>Disc 5 (Rp.)</th>
          <th>Total</th>
        </tr>
      </thead>";
      $select = mysql_query("SELECT * FROM trans_sales_order_detail tso LEFT JOIN barang brg ON tso.id_barang=brg.id_barang   WHERE tso.id_sales_order = '$row[id_sales_order]'");
      while ($rew=mysql_fetch_array($select)) {
        echo "
        <tbody id=product>
          <tr>
            <td>$rew[kode_barang]</td>
            <td>$rew[nama_barang]</td>
            <td>".format_rupiah($rew['harga'])."</td>
            <!--td></td-->
            <td>$rew[jumlah]-$rew[satuan]</td>
            <td align='center'>$rew[disc1]</td>
            <td align='center'>$rew[disc2]</td>
            <td align='center'>$rew[disc3]</td>
            <td align='center'>$rew[disc4]</td>
            <td align='center'>$rew[disc5]</td>
            <td>".format_rupiah($rew['total'])."</td>
          </tr>";
      }
      echo "
      </tbody>
      <tfoot>
        <tr>
          <td colspan=6 rowspan=4></td>
          <td colspan=3 style=text-align:right; ><p><b>ToTal All SUb </b></p> </td>
          <td colspan=2  >".format_rupiah($row['alltotal'])."</td>
        </tr>
        <tr>
          <td colspan=3 style=text-align:right;><p> Disc (%) $row[discper] | (Rp) </p></td>
          <td colspan=2 style=nowrap:nowrap;>$row[disc]</td>
        </tr>
        <tr>
          <td colspan=3 style=text-align:right;><p> Ppn (%) $row[ppnper] | (Rp) </p></td>
          <td colspan=2  style=nowrap:nowrap;>$row[ppn]</td>
        </tr>
        <tr>
          <td colspan=3 style=text-align:right;><b>Grand Total</b></td>
          <td colspan=2><b>".format_rupiah($row['grand_total'])."</b></td>
        </tr>
      </tfoot>
    </table>
  </div>";  
  break;
case 'lkb':
  echo "<h2><b>Detail</b> Laporan Barang Keluar</h2>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No LKB</td>
    <td>";
  $query=mysql_query("SELECT * FROM `trans_lkb` t, customer s   WHERE t.id_customer=s.id_customer and t.id_lkb = '$_GET[id]' order by t.id desc limit 1 ");
 $r=mysql_fetch_array($query);
     echo "  
  $r[id_lkb]";
   echo " </td>
   <td>No SO</td>
<td>
$r[id_sales_order]</td>
  </tr>";
  echo "
  <tr>
   <td>Tanggal Barang Dikirim</td>
    <td>".date('d-m-Y',strtotime($r[tgl_lkb]))."</td>
   <td> No Nota Customer : </td>
    <td>$r[no_nota_customer]</td>
  </tr>
  <tr>
   <td>Customer</td> 
     <td>$r[nama_customer]</td>
    <td>No Expedisi </td>
    <td>$r[no_expedisi]</td>
 </tr>
 <tr><td > Alamat </td>";
$tampil67=mysql_query("SELECT * FROM customer where is_void=0  AND id_customer = '$r[id_customer]'");
$y=mysql_fetch_array($tampil67);
echo "
    <td>$y[alamat_customer]</td>
    </tr>";
  echo "</table>";

echo "
<table id=header class=table table-hover table-bordered cellspacing=0>
        <thead>
  <tr style=background-color:#F5F5F5;>
      <th>No</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Jumlah dalam SO</th>
      <th>Jumlah diterima</th>
      <th>Gudang</th>
      </tr>
        </thead>
 
        <tbody>";
$noz= 100;
$tampiltable=mysql_query("SELECT *, concat(qty,'-',qty_satuan) as jumlah_dlm_so, CONCAT (qty_diterima,'-',qty_diterima_satuan) AS terima FROM `trans_lkb_detail` d,gudang g WHERE d.id_gudang=g.id_gudang and d.id_sales_order = '$r[id_sales_order]' order by d.kode_barang_so, d.id");
 $no=1;
while ($rst = mysql_fetch_array($tampiltable)){

  echo "
 <tr>
      <td>
       $no
    </td>
  <td>";
  $tampiltablebarang=mysql_query("SELECT * FROM `barang` WHERE id_barang = '$rst[id_barang]' ");
   $rst1 = mysql_fetch_array($tampiltablebarang);
  echo"
       $rst1[kode_barang]
    </td>
    <td>
       $rst1[nama_barang]
    </td>
   <td>
       $rst[jumlah_dlm_so]
    </td>       
      <td>$rst[terima]
      </td>
   <td>
   $rst[nama_gudang]</td>
</tr>";
$no++;
$noz++;
}        echo "
        </tbody>
        <tfoot>
                </tfoot>
          </table>
  </div> ";
  break;
case 'si':
  $edit = mysql_query("select * from trans_sales_invoice ti,trans_sales_invoice_detail tid,customer s,barang b where ti.id_invoice=tid.id_invoice and s.id_customer=ti.id_customer and b.id_barang=tid.id_barang and ti.id_invoice='$_GET[id]'");
    $r    = mysql_fetch_array($edit);
  echo "
  <h2>
    <b>
      Detail
    </b> 
      Purchase Invoice
  </h2>
    <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
        <tr>
          <td>
            No Pembayaran
          </td>
          <td>
            $r[id_invoice]
          </td>
          <td>
            No LKB
          </td> 
          <td>
            $r[id_lkb]
          </td>
        </tr>
        <tr>
          <td>customer</td>
          <td>$r[nama_customer]</td>
           <td>No Nota Invoice</td>
           <td>$r[no_nota]</td>
        </tr>
        <tr>
          <td> Alamat </td>
          <td>$r[alamat_customer]</td>
          <td>Tanggal Pembayaran</td>
          <td>".date('d-m-Y',strtotime($r[tgl]))."</td>
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
      <th>Harga SO</th>
      <th>Total SO</th>
      <!--th>Qty Diterima</th>
      <th>Satuan</th-->
      <th>Haraga</th>
      <th>Disc 1 (%)</th>
      <th>Disc 2 (%)</th>
      <th>Disc 3 (%)</th>
      <th>Disc 4 (%)</th>
      <th>Disc 5 (Rp.)</th>
      <th>Total</th>
        </tr>
        </thead>
 
        <tbody id="product">';
$tampiltable=mysql_query("select *,CONCAT(qty_so,'-',qty_so_satuan) AS minta from trans_sales_invoice ti,trans_sales_invoice_detail tid,customer s,barang b where ti.id_invoice=tid.id_invoice and s.id_customer=ti.id_customer and b.id_barang=tid.id_barang and ti.id_invoice='$_GET[id]'");

$noz = 0;
$no=1;
$rst_jumlah = mysql_num_rows($tampiltable);

while ($rst = mysql_fetch_array($tampiltable)){
  echo "
  <tr>
    <td>
       $no
    </td>
    <td>
     $rst[nama_barang]
    </td>
   <td align='center'>
       $rst[minta]
    </td>
    <td>
       ".format_rupiah($rst['harga_so'])."
    </td>
     <td>
       ".format_rupiah($rst['total_so'])."
    </td>
     <!--td align='center'>
       $rst[qty_si]
    </td>
     <td>
        $rst[qty_si_satuan]
     </td-->
     <td>
       ".format_rupiah($rst['harga_si'])."
    </td>
    <td align='center'>$rst[disc1]</td>
    <td align='center'>$rst[disc2]</td>
    <td align='center'>$rst[disc3]</td>
    <td align='center'>$rst[disc4]</td>
    <td align='center'>$rst[disc5]</td>
    <td>".format_rupiah($rst['total'])."</td>
</tr>
";
$noz++;
$no++;
}
$tampiltable=mysql_query("SELECT * FROM trans_lkb t, trans_sales_order tt where tt.id_sales_order=t.id_sales_order; ");

echo"
        </tbody>
        <tfoot>
        <tr>
    <td colspan=8 rowspan=4> <br>
          </td>

    <td colspan=3 style=text-align:right; ><p><b>ToTal All SUb </b></p></td>
    <td colspan=2  >".format_rupiah($r['alltotal'])."</td>
  </tr>

  <tr>
    <td colspan=3 style=text-align:right;><p> Disc (%) $r[alldiscpersen] | (Rp) </p></td>
    <td colspan=2 style=nowrap:nowrap;>$r[alldiscnominal]</td>
  </tr>
  <tr>
    <td colspan=3 style=text-align:right;><p> Ppn (%)$r[allppnpersen] | (Rp) </p></td>
    <td colspan=2  style=nowrap:nowrap;>$r[allppnnominal]</td>
  </tr>
  <tr>
    <td colspan=3 style=text-align:right;><b>Grand total</b></td>
    <td colspan=2><b>".format_rupiah($r['grand_total'])."</b></td>
  </tr>
                </tfoot>
          </table>
  </div> ";
  break;

  }
}
}
function rupiah($nilai, $pecahan=0){
  return number_format($nilai,$pecahan,',','.');
}
?>


<script type="text/javascript">

 $(document).ready(function() {



    $('#tanggalnull').click(function() {
      $('#minrangecus').val('');
      $('#maxrangecus').val('');
    });

    <?php
  if(isset($_GET['id']) && !empty($_GET['id'])){
      $id_customerh = $_GET['id'];
      $periode_awal= $_GET['periode_awal'];
      if (empty($_GET['periode_akhir'])){
         $periode_akhir= $_GET['periode_akhir'];
      }else{
         $periode_akhir= "";
      }

      echo '
                    var rt = {"id_detail" : "'.$id_customerh.'","awal" : "'.$periode_awal.'","akhir" : "'.$periode_akhir.'"};
                    datakartubarang3(rt);
                    ';
      echo "              
                $('#caricus').click(function() {
                 var ak = $('#maxrangecus').val(),
                 sal = $('#carisalesinput').val(),
                  aw = $('#minrangecus').val();
                    if(ak == ''  || aw =='' ){
                            if (ak == ''  && aw ==''){
                              var rt = {'id_detail' : $id_customerh, 'awal' : sal};
                              datakartubarang3(rt);
                              $('#kartubarang thead tr  th').css({'background-color': '#4FA84F','color': '#FFFFFF'});
                            } else {
                              alert('Tanggal harus diisi semua');
                            }
                    } else {
                     var rt = {'id_detail' : $id_customerh,'sal' : sal, 'akhir' : ak, 'awal' : aw};
                      datakartubarang3(rt);
                        $('#kartubarang thead tr  th').css({'background-color': '#4FA84F','color': '#FFFFFF'});
                    }
                } );";

  } else {
          echo '

                   var rt = {"nota" : "yes"};
                   datakartubarang3(rt);
                   ';
           echo "              
                $('#caricus').click(function() {
                 var ak = $('#maxrangecus').val(),
                  aw = $('#minrangecus').val();
                 
                    if(aw != ''){
                              var rt = {'nota' : 'yes', 'awal' : aw,'akhir' : ak};
                              datakartubarang3(rt);
                              $('#laporanpenjualan thead tr  th').css({'background-color': '#4FA84F','color': '#FFFFFF'});
                            } else {
                              alert('Tanggal harus diisi semua');
                            }
                } );

                ";
  } 
  ?>



});
 function datakartubarang(p){
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
                                   var t = $('#laporanpenjualan').DataTable({
                                        "iDisplayLength": 25,
                                           "aLengthMenu": [ [25, 50,100],[25,50,100]],
                                          "pagingType" : "simple",
                                          "ordering": false,
                                          "info":     false,
                                          "language": {
                                                "decimal": ",",
                                                "thousands": "."
                                              },
                                        "processing": true,
                                        "serverSide": true,
                                       /* "ajax" : "modul/pembayaran/load-data_girotransaksi.php",*/
                             "ajax": {
                          "url": "modul/<?php echo $filenama ?>/load-data-customer.php",
                          "cache": false,
                                "type": "GET",
                          "data": p
                        },
                                        "order": [[1, 'asc']],
                                        "destroy": true,
                                        "rowCallback": function (row, data, iDisplayIndex) {
                                            var info = this.fnPagingInfo();
                                            var page = info.iPage;
                                            var length = info.iLength;
                                            var index = page * length + (iDisplayIndex + 1);
                                        $('td:eq(0)', row).html(index);
                                        }
                                    });          
                    }

function datakartubarang3(p){
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
                                   var t = $('#laporanpenjualan3').DataTable({
                                        "iDisplayLength": 25,
                                           "aLengthMenu": [ [25, 50,100],[25,50,100]],
                                          "pagingType" : "simple",
                                          "ordering": false,
                                          "info":     false,
                                          "language": {
                                                "decimal": ",",
                                                "thousands": "."
                                              },
                                        "processing": true,
                                        "serverSide": true,
                                       /* "ajax" : "modul/pembayaran/load-data_girotransaksi.php",*/
                             "ajax": {
                          "url": "modul/<?php echo $filenama ?>/load-data-customer.php",
                          "cache": false,
                                "type": "GET",
                          "data": p
                        },
                                        "order": [[1, 'asc']],
                                        "destroy": true,
                                        "rowCallback": function (row, data, iDisplayIndex) {
                                            var info = this.fnPagingInfo();
                                            var page = info.iPage;
                                            var length = info.iLength;
                                            var index = page * length + (iDisplayIndex + 1);
                                        $('td:eq(0)', row).html(index);
                                        }
                                    });          
                    }

function addMore(p,d,h){
  $('#cari'+h+'input').val(p);
 $('#cari'+h+'input1').val(d);
      $('#cari'+h+'modal').modal('hide');
}

$( function() {
    $( ".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd'
    });
  } );        
        </script>