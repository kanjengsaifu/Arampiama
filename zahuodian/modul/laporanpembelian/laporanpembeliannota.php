<?php
 include "config/koneksi.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();

$filenama = "laporanpembelian";
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
$judul = "Laporan Pembelian";
$desk =  "Laporan transaksi pembelian global per <b>Nota</b>";

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
        <th>PO</th>
        <th>LPB</th>
        <th>PI</th>
        <th>Tanggal</th>
        <th>GrandTotal</th>
        <th>Pelunasan</th>
        <th>Sisa Pelunasan</th>
        <th>Supplier</th>        
         <th>Telp</th>   
    </tr>
        </thead>
    </table>
    </div>";
  break;
case "detailpo":
echo "
<h2><b>Detail</b> Purchase Order</h2>
  <div class='table-responsive'>
    <table class='table table-hover' border=0 id=tambah>";
 $tampil44=mysql_query("SELECT * FROM `trans_pur_order`  WHERE id_pur_order = '$_GET[id]' order by id desc limit 1 ");
  $r    = mysql_fetch_array($tampil44);
echo " 
      <tr>
        <td>Nama Supplier</td> 
        <td id='sup'>";  
$tampil43=mysql_query("SELECT * FROM Supplier where is_void=0 AND id_supplier=$r[id_supplier] ");
      $w=mysql_fetch_array($tampil43);            
echo "$w[nama_supplier]";
echo '
<br>';
echo"
        </td>
      </tr>
        <td> Alamat </td>
        <td>$w[alamat_supplier]</td>
        <td> No tlp </td>
        <td>$w[telp1_supplier]</td>
      </tr>";
echo "<tr>
        <td>No PO</td>
        <td>$r[id_pur_order]";
echo "  </td>
        <td>Tanggal PO</td>
        <td>".date('d-m-Y',strtotime($r[tgl_po]))."</td>
      </tr>
    </table> ";
echo '
  <table id="header" class="table table-hover table-bordered" cellspacing="0" rules="rows">
    <thead>
      <tr style="background-color:#F5F5F5;">
        <th>Nama barang</th>
        <th>Harga</th>
        <!--th>Satuan</th-->
        <th>Qty</th>
        <th>Disc 1 (%)</th>
        <th>Disc 2 (%)</th>
        <th>Disc 3 (%)</th>
        <th>Disc 4 (%)</th> 
        <th>Disc 5 (Rp.)</th>
        <th>Total</th>
      </tr>
    </thead> 
    <tbody id="product">';
$select=mysql_query("SELECT * FROM trans_pur_order_detail od LEFT JOIN barang bg ON od.id_barang=bg.id_barang where od.id_pur_order= '$_GET[id]'");
  while ($data = mysql_fetch_array($select)) {
    echo "
      <tr  style='border-top:1px solid #000;border-bottom:1px solid #000'>  
        <td align='center'>$data[nama_barang]</td>
        <td align='center'>".format_rupiah($data[harga])."</td>
        <!--td align='center'></td-->
        <td align='center'>$data[jumlah]-$data[satuan]</td>
        <td align='center'>$data[disc1]</td>
        <td align='center'>$data[disc2]</td>
        <td align='center'>$data[disc3]</td>
        <td align='center'>$data[disc4]</td>
        <td align='center'>$data[disc5]</td>
        <td align='center'>".format_rupiah($data['total'])."</td>
      </tr>";
  }  
echo"
    </tbody>
    <tfoot>
      <tr>
        <td colspan=5 rowspan=4>&nbsp;</td>
        <td colspan=3 style=text-align:right;><p><b>ToTal All SUb </b></p></td>
        <td colspan=2 align=right>Rp. $r[alltotal]</td>
      </tr>
      <tr>
        <td colspan=3 style=text-align:right;><p> Disc (%) $r[discper] | (Rp) </p></td>
        <td colspan=2 style=nowrap:nowrap; align=right>".format_rupiah($r[disc])."</td>
      </tr>
      <tr>
        <td colspan=3 style=text-align:right;><p> Ppn (%) $r[ppnper] | (Rp) </p></td>
        <td colspan=2  style=nowrap:nowrap; align=right>".format_rupiah($r[ppn])."</td>
      </tr>
      <tr>
        <td colspan=3 style=text-align:right;><b>Grand total</b></td>
        <td colspan=2 align=right><b>".format_rupiah($r[grand_total])."</b></td>
      </tr>
    </tfoot>
  </table>
  </div>";
break;
case "detaillpb":
echo "
<h2><b>Detail</b> Laporan Barang Masuk</h2>
<div class='table-responsive'>
  <table class='table table-hover' border=0 id=tambah>
    <tr>
      <td>No LBM</td>
      <td>";
  $select = mysql_query("SELECT * FROM trans_lpb lpb JOIN supplier spr ON lpb.id_supplier = spr.id_supplier WHERE id_lpb = '$_GET[id]' ORDER BY id DESC LIMIT 1");
  $row = mysql_fetch_array($select);
  echo "  
      $row[id_lpb]";
  echo "</td>
      <td>No Po</td>
      <td>$row[id_pur_order]</td>
    </tr>";
  echo "
    <tr>
     <td>Tanggal barang diterima</td>
      <td>".date('d-m-Y',strtotime($row[tgl_lpb]))."</td>
     <td> No Nota Supplier : </td>
      <td>$row[no_nota_supplier]</td>
    </tr>
    <tr>
      <td>Supplier</td>
      <td>$row[nama_supplier]</td>
      <td>No Expedisi </td>
      <td>$row[no_expedisi]</td>
    </tr>
    <tr>
      <td > Alamat </td>";
$tampil67=mysql_query("SELECT * FROM Supplier where is_void=0  AND id_supplier = '$row[id_supplier]'");
$y=mysql_fetch_array($tampil67);
echo "
      <td>$y[alamat_supplier]</td>
    </tr>";
echo "
  </table>";
echo "
  <table id='header' class='table table-hover table-bordered' cellspacing='0'>
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
$tampiltable=mysql_query("SELECT *,concat(qty,'-',qty_satuan) as jumlah_dlm_po, concat(qty_diterima,'-',qty_diterima_satuan) as trima FROM
  `trans_lpb_detail` d,gudang g WHERE d.id_gudang=g.id_gudang and id_pur_order = '$row[id_pur_order]' order by kode_barang_po,id");
$no=1;
while ($rst = mysql_fetch_array($tampiltable)){
  echo "
      <tr>
        <td>$no</td>
        <td>";
$tampiltablebarang=mysql_query("SELECT * FROM `barang` WHERE id_barang = '$rst[id_barang]' ");
$rst1 = mysql_fetch_array($tampiltablebarang);
  echo"
        $rst1[kode_barang]</td>
        <td>$rst1[nama_barang]</td>
        <td>$rst[jumlah_dlm_po]</td>
        <td>$rst[trima]</td>
        <td>$rst[nama_gudang]</td>";
$no++;
}
echo "
    </tbody>
  </table>
</div>";
break;
case "detailpi":
$edit = mysql_query("select * from trans_invoice ti, supplier s where s.id_supplier=ti.id_supplier and ti.id_invoice='$_GET[id]'");
$r    = mysql_fetch_array($edit);
echo "
<h2><b> Detail </b>Purchase Invoice</h2>
<div class='table-responsive'>
  <table class='table table-hover' border=0 id=tambah>
    <tr>
      <td>No Pembayaran</td>
      <td>$r[id_invoice]</td>
      <td>No LBM</td> 
      <td>$r[id_lpb]</td>
    </tr>
    <tr>
      <td>Supplier</td>
      <td>$r[nama_supplier]</td>
      <td>No Nota Invoice</td>
      <td>$r[no_nota]</td>
    </tr>
    <tr>
      <td> Alamat </td>
      <td>$r[alamat_supplier]</td>
      <td>Tanggal Pembayaran</td>
      <td>".date('d-m-Y',strtotime($r[tgl]))."</td>
    </tr>
  </table>
  <table id='header' class='table table-hover table-bordered' cellspacing='0'>
    <thead>
      <tr style='background-color:#F5F5F5;'>
        <th id='tablenumber'>No</th>
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
        <th>Disc 5 (Rp.)</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody id='product'>";
$tampiltable=mysql_query("select *,CONCAT(qty_po,'-',qty_po_satuan) AS minta from trans_invoice_detail tid, barang b where b.id_barang=tid.id_barang  and tid.id_invoice='$r[id_invoice]'");
$no=1;
$rst_jumlah = mysql_num_rows($tampiltable);
while ($rst = mysql_fetch_array($tampiltable)){
  echo "
      <tr>
        <td>$no</td>
        <td>$rst[nama_barang]</td>
        <td align=center>$rst[minta]</td>
        <td>".format_rupiah($rst['harga_po'])."</td>
        <td align=right>$rst[total_po]</td>
        <td align=center>".convt_satuan($rst['qty_pi_convert'],$rst['id_barang'])." [$rst[qty_pi_convert]]</td>
        <td>".format_rupiah($rst['harga_pi'])."</td>
        <td align=center>$rst[disc1]</td>
        <td align=center>$rst[disc2]</td>
        <td align=center>$rst[disc3]</td>
        <td align=center>$rst[disc4]</td>
        <td align=center>$rst[disc5]</td>
        <td>".format_rupiah($rst['total'])."</td>
      </tr>";
$no++;
}
$tampiltable=mysql_query("SELECT * FROM trans_lpb t, trans_pur_order tt where tt.id_pur_order=t.id_pur_order; ");

echo"
    </tbody>
    <tfoot>
      <tr>
        <td colspan=9 rowspan=4><br></td>
        <td colspan=3 style=text-align:right; ><p><b>ToTal All SUB </b></p></td>
        <td colspan=2>".format_rupiah($r['alltotal'])."</td>
      </tr>
      <tr>
        <td colspan=3 style=text-align:right;><p> Disc (%) $r[alldiscpersen] | </p></td>
        <td colspan=2 style=nowrap:nowrap;>".format_rupiah($r['alldiscnominal'])."</td>
      </tr>
      <tr>
        <td colspan=3 style=text-align:right;><p> Ppn (%) $r[allppnpersen] | </p></td>
        <td colspan=2 style=nowrap:norwap;>".format_rupiah($r['allppnnominal'])."</td>
      </tr>  
      <tr>
        <td colspan=3 style=text-align:right;><b>Grand total</b></td>
        <td colspan=2><b>".format_rupiah($r['grand_total'])."</b></td>
      </tr>
    </tfoot>
  </table>
</div>";
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
      $cuslodeh = $_GET['id'];
      echo '
                    var rt = {"startup" : "'.$cuslodeh.'"};
                    datakartubarang(rt);';
      echo "              
                $('#caricus').click(function() {
                 var ak = $('#maxrangecus').val(),
                 sal = $('#carisalesinput').val(),
                  aw = $('#minrangecus').val();
                    if(ak == ''  || aw =='' ){
                            if (ak == ''  && aw ==''){
                              var rt = {'startup' : $cuslodeh, 'sal' : sal};
                              datakartubarang(rt);
                              $('#kartubarang thead tr  th').css({'background-color': '#4FA84F','color': '#FFFFFF'});
                            } else {
                              alert('Tanggal harus diisi semua');
                            }
                    } else {
                     var rt = {'startup' : $cuslodeh,'sal' : sal, 'akhir' : ak, 'awal' : aw};
                      datakartubarang(rt);
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
                          "url": "modul/<?php echo $filenama ?>/load-data-nota.php",
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
                                         "columnDefs": [{"className": "dt-right", "targets": [0]},{"className": "dt-left", "targets": [1]}],
                                       /* "ajax" : "modul/pembayaran/load-data_girotransaksi.php",*/
                             "ajax": {
                          "url": "modul/<?php echo $filenama ?>/load-data-nota.php",
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