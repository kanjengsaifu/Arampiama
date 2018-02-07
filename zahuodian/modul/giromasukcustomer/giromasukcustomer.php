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
//$aksi="modul/kartubarang/aksi_kartubarang.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:

  $judul = "Laporan Giro Masuk Per Customer";
  $desk = "Laporan Giro Masuk Berdasarkan Nama Customer";
  headerDeskripsi($judul,$desk);
echo "
<div class='table-responsive'>
       <table class='table table-hover'>
                 <form method='post' action='?module=giromasukcustomer&act=search'>
                 Cari Customer <input type='text' id='customer' name='customer'> <input class='btn btn-success' type=submit value=Cari>
                </form>                
          </table>
</div>                  
      <div class='table-responsive'>
      <table class='table table-bordered'>
              <thead>
          <tr style='background-color:#F5F5F5;'>
            <th class='number' >No</th>
            <th>Nama Customer</th>
            <th>Total Giro Masuk</th>
            <th>Total Giro Cair</th>
            <th>Total Giro Dialokasikan</th>
            <th>Aksi</th>
          </tr>
                </thead>
                <tbody>";
                  $tampil = mysql_query("SELECT *, sum(tsi.grand_total) as grand_total1 FROM trans_sales_invoice tsi LEFT JOIN trans_lkb lkb ON tsi.id_lkb = lkb.id_lkb left join trans_sales_order tso on lkb.id_sales_order = tso.id_sales_order left join sales sls on tso.id_sales = sls.id_sales where tsi.is_void = 0 group by sls.nama_sales");
                  while ($r=mysql_fetch_array($tampil)){

                  echo "
                <tr>
                  <td>$no</td>
                  <td>$r[nama_sales]</td>";
                 $tot1+=$r['grand_total1'];
                    echo "
                  <td style='text-align:right'>".format_rupiah($r[grand_total1])."</td>";
            echo" 
            <td>          
            <a href='?module=omsetsales&act=detail&id=$r[id_sales]'class='btn btn-success'  title='detail'target='_blank'><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span></a>
            </td>";
                  
                  echo "
                </tr>"; 
                $no++;
                }
            echo "
        </tbody>
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
    $('#caribarangnull').click(function() {
            $('#caribaranginput').val('');
            $('#caribaranginput1').val('');
    });
  $('#caribarangbutton').click(function() {
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
                                   var t = $('#modalbarang').DataTable({
                                        "iDisplayLength": 15,
                                           "aLengthMenu": [ [15,20,50],[15,20,50]],
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
                                                "url": "modul/laporangiromasuk/load-data.php",
                                                "cache": false,
                                                "type": "GET",
                                                "data": {"caribarang": "cari" }
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
     $('#caribarangmodal').modal('show');
  });
  datakartubarang(null,null,null);

              $('#carikartubarang').click(function() {
                 var sup = $('#caribaranginput').val(),
                  ak = $('#maxrangekartubarang').val(),
                  aw = $('#minrangekartubarang').val();
                datakartubarang(sup,aw, ak);
                } );
              $('#allkartubarang').click(function() {
                  datakartubarang(null,null,null);
                $("#kartubarang thead tr  th").css({"background-color": "#275C8A","color": "#FFFFFF"});
                });

});
 function datakartubarang(s,a,k){
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
                                   var t = $('#kartubarang').DataTable({
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
                          "url": "modul/laporangiromasuk/load-data.php",
                          "cache": false,
                                "type": "GET",
                          "data": {"barang": s,
                                          "awal" : a,
                                          "akhir" : k }
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

function addMore(p,d){
  $('#caribaranginput').val(p);
 $('#caribaranginput1').val(d);
      $('#caribarangmodal').modal('hide');
}

$( function() {
    $( ".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd'
    });
  } );        
        </script>