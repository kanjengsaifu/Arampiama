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
$aksi="modul/laporankeluarbarang/aksi_laporankeluarbarang.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
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
  echo "<h2><b>Tambah</b> Laporan Barang Keluar</h2>
    <p class='deskripsi'>ini adalah modul untuk menambah laporan barang yang keluar</p>
    <hr class='deskripsihr'>
    <form method='post' action='$aksi?module=laporankeluarbarang&act=input'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No lkb</td>
    <td><strong>".kode_surat('LKB','trans_lkb','id_lkb','id')."</strong></td>";

   echo '
   <td>No SO</td>
<td>
<input  name="no_so" id ="no_so" class="form-control" data-toggle="modal" data-target="#myModal" readonly/>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nomer SO</h4>
        </div>
        <div class="modal-body">
    <table id="lkb_no" border="1" class="table table-hover">
    <thead>
    <tr style="background-color:#F5F5F5;">
      <th>No</th>
      <th>No Sales Order</th>
      <th>Customer </th>      
      <th>No Telp</th>      
    </tr>
    </thead>
<tbody>';
$tampil=mysql_query("SELECT * ,concat(alamat_customer,'  -----Telp  :',telp_customer) as kete FROM trans_sales_order LEFT JOIN customer on customer.id_customer=trans_sales_order.id_customer where (status_trans ='0' or status_trans ='1' ) ") ;
  $no=1;
       while ($r=mysql_fetch_array($tampil)){
      echo '
       <tr onclick="nilaiso(\''.$r['id_sales_order'].'\',\''.$r['id_customer'].'\',\''.$r['nama_customer'].'\',\''.$r['kete'].'\')">
      <td >'.$no.'</td>
      <td >'.$r['id_sales_order'].'</td>
      <td>'.$r['nama_customer'].'</td>
       <td>'.$r['telp_customer'].'</td>
      </tr>';
      $no++;
    }

echo '
</tbody>
    </table>
        </div>
      </div>      
    </div>
  </div>
  </td>
  </tr>
  <tr>
    <td> No Nota customer : </td>
    <td><input name="no_nota_customer" class="form-control" required></td>
    <td>No Expedisi </td>
    <td ><input id="no_expedisi" name="no_expedisi" class="form-control"></td>
  </tr>

  <tr>
     <td>Customer</td> <td id="sup">
 <input type="hidden" class="form-control " name="customer" id="customer" required> 
  <input class="form-control " name="customer_nama" id="customer_nama" readonly required> </td>
<td>Tanggal barang dikirim</td>
    <td><input class="form-control datetimepicker" value ="'.date('Y-m-d').'"name="tgl_lkb" required></td>
 </tr>
  <tr>
   </td>
    
<td > Alamat </td>
    <td  ><textarea id="alamat" class="form-control" disabled></textarea></td>
 </tr>';
  echo "</table>";

echo '
<DIV class="btn-action float-clear">

</DIV>
<table id="header" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Kode & Nama Barang</th>
      <th>Jumlah dalam SO</th>
      <th>Jumlah dikirim</th>
      <th>Satuan</th>
      <th>Gudang</th>
      </tr>
        </thead>
 
        <tbody id="product">

        </tbody>
        <tfoot>
                </tfoot>
          </table>
  </div> 
  <button class="btn btn-success"  type="submit"  name="save" value="Save" style="float:left;">Save</button> 
  <a class="btn btn-warning" type="button" href="media.php?module=laporankeluarbarang" style="float:left;margin-left:10px;">Batal</a>
  </form>
  ';

    break;

  case "edit":
   echo "<h2><b>Edit</b> Laporan Barang Keluar</h2>
       <p class='deskripsi'>ini adalah modul untuk Edit  laporan barang keluar </p>
    <hr class='deskripsihr'>
    <form method='post' action='$aksi?module=laporankeluarbarang&act=update'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No LKB</td>
    <td>";
  $query=mysql_query("SELECT * FROM `trans_lkb` t, customer s   WHERE t.id_customer=s.id_customer and t.id = '$_GET[id]' order by t.id desc limit 1 ");
 $r=mysql_fetch_array($query);
     echo "  <input type='hidden' name='id' value='$r[id]' id='id_lkb''>
  <input  name='no_lkb' value='$r[id_lkb]' id='id_lkb' readonly='readonly'  class='form-control' />";
   echo ' </td>
   <td>No SO</td>
<td>
<input  class="form-control" name="no_so" id ="no_so" value='.$r[id_sales_order].'  data-toggle="modal" data-target="#myModal" readonly/>
  </tr>';
  echo "
  <tr>
   <td>Tanggal Barang Dikirim</td>
    <td><input class='datepicker form-control' name='tgl_lkb' value='$r[tgl_lkb]' required></td>
   <td> No Nota Customer : </td>
    <td><input class='form-control' name='no_nota_customer' value='$r[no_nota_customer]' required>
  </tr>
  <tr>
   <td>Customer</td> <td id='sup_edit'>
     <input type=hidden class='form-control' id='customer_edit' name='customer' value='$r[id_customer]'>
     <input  class='form-control' value='$r[nama_customer]' readonly>
    <td>No Expedisi </td>
    <td ><input id='no_expedisi' class='form-control' name='no_expedisi' value='$r[no_expedisi]'></td></td>
 </tr>
 <tr><td > Alamat </td>
    <td id='alamat'><textarea class='form-control' disabled>$r[alamat_customer]</textarea></td>
    </tr>";
  echo "</table>";

echo '
<DIV class="btn-action float-clear">
<!-- <div class="btn btn-primary" type="button" id="search-edit" data-toggle="modal" data-target="#search-md">Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
 </div>-->

</DIV>
<table id="header" class="table  table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Kode & Nama Barang</th>
      <th>Jumlah dalam SO</th>
      <th>Jumlah diterima</th>
      <th>Satuan</th>
      <th>Gudang</th>
      </tr>
        </thead>
 
        <tbody id="product">';
$noz= 100;
$tampiltable=mysql_query("SELECT *, concat(qty,' - ',qty_satuan) as jumlah_dlm_so  FROM `trans_lkb_detail` d,gudang g WHERE d.id_gudang=g.id_gudang and d.id_lkb = '$r[id_lkb]' order by d.kode_barang_so, d.id");
 $no=1;
while ($rst = mysql_fetch_array($tampiltable)){

  echo '
  <!--<tr class="inputtable">-->
 <tr>
    <input type=hidden name="id_lkb[]" value="'. $rst['id'].'" id="id_lkb-'.$noz.'" >
      <td>
       '.$no.'
    </td>
  <td style="text-align:left !important">';
  $tampiltablebarang=mysql_query("SELECT * FROM `barang` WHERE id_barang = '$rst[id_barang]' ");
  //$selisih = $rst['qty_diterima'] - $rst['qty'];
   $rst1 = mysql_fetch_array($tampiltablebarang);
  echo'
       <input type="hidden" name="kode_barang[]" value="'.$rst1[kode_barang].'"   id="kode_barang-'.$noz.'" readonly="readonly"  />
       <input type="hidden" name="id_barang[]" value="'.$rst[id_barang].'"   id="id_barang-'.$noz.'"  />'.$rst1['kode_barang'].' - '.$rst1['nama_barang'].'

       <input type="hidden" name="nama_barang[]" value="'.$rst1[nama_barang].'" id="nama_barang-'.$noz.'"  readonly />
    </td>
   <td>
       <input type="hidden" name="jumlah_diminta[]" value="'.$rst[jumlah_dlm_so].'"  id="jumlahdiminta-'.$noz.'" readonly="readonly" class="hitung" />
       '.$rst[jumlah_dlm_so].'
    </td>
      <td id="checkjumlah"><input type="text" name="selisih[]" id="selisih-'.$noz.'"  value="'.$rst['qty_diterima'].'" class="selisih"  /><br>
      <input  type="hidden" name="selisih2[]" id="selisih2-'.$noz.'"  value="'.$rst['qty_diterima_convert'].'" class="selisih"  />

      <!--<input type="checkbox" name="lbr_gudang[]"  id="lbr_checkbox-'.$noz.'" onclick="checkjumlah(this,'.$noz.','.$rst[qty].','.$rst['qty_diterima'].')" unchecked="unchecked"> Jumlah Sesuai-->
      </td>

   <input type="hidden"  class="form-control" id="gudang_lkb-'.$noz.'" name="gudang_lkb[] " value="'.$rst[id_gudang].'"  readonly="readonly" />
   <td>';

echo '<select id="jenis_satuan-'.$noz.'" name="jenis_satuan[]" required>';
//diloop karena satuan banyaknya lima
$tampil_lbr=mysql_query("SELECT * FROM barang where is_void=0 and id_barang='".$rst['id_barang']."' ");
   $sat_minim=0;
$data=mysql_fetch_array($tampil_lbr);
            for ($i=5; $i >= 1 ; $i--) { 
        $val= "satuan".$i;
        $val_kali= "kali".$i;
        $val_harga= "harga_sat".$i;

        if ($data[$val]!=""){
          echo $data[$val] ."   ".$rst[qty_diterima_satuan]."<br>";
          if ($data[$val]==$rst[qty_diterima_satuan]){
            $sat_minim=$data[$val_kali];
             echo " <option value='".$data[$val_harga].'-'.$data[$val].'-'.$data[$val_kali]."' selected>".$data[$val].' ('.$data[$val_kali].')'."</option>";
          }

        }
      }
echo '</select>  </td>
   <td>
   <input   class="form-control" id="gudang_nama-'.$noz.'" name="gudang_nama[] " value="'.$rst[nama_gudang].'"  readonly="readonly" /></td>
</tr>';
$no++;
$noz++;
}

        echo '
        </tbody>
        <tfoot>
                </tfoot>
          </table>
  </div> 
  <button class="btn btn-success"  type="submit"  name="save" value="Save" style="float:left;">Save</button> 
  <a class="btn btn-warning" type="button" href="media.php?module=laporankeluarbarang" style="float:left;margin-left:10px;">Batal</a>
  </form>
  ';

    break;
case "pengiriman":
   echo "<h2><b>Kurangan</b> Laporan Barang Keluar</h2>
    <form method='post' action='$aksi?module=laporankeluarbarang&act=pengiriman'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No LKB</td>
    <td>";
  $query=mysql_query("SELECT * FROM `trans_lkb` t, customer s   WHERE t.id_customer=s.id_customer and id = '$_GET[id]' order by id desc limit 1 ");
 $r=mysql_fetch_array($query);
 $querykode=mysql_query("SELECT * FROM `trans_lkb`  order by id desc limit 1");
 $kode=mysql_fetch_array($querykode);
   echo kodesurat($kode['id_lkb'], LKB, no_lkb, no_lkb );
     echo "  
   <td>No SO</td>
<td>
<input  name='no_so' id ='no_so' value='$r[id_sales_order]'  data-toggle='modal' data-target='#myModal' readonly/>
  </td> </tr>
  <tr>
    <td>Tanggal Barang Dikirim</td>
    <td><input class='datepicker' name='tgl_lkb' value='$r[tgl_lkb]' required></td>
     <td> No Nota Customer : </td>
    <td><input name='no_nota_customer'  required></td>
  </tr>
  <tr>
   <td>Customer</td> <td id='sup_edit'>
     <input type=hidden id='customer_edit' name='customer' value='$r[id_customer]'>
     <input value='$r[nama_customer]' readonly>
  <td>No Expedisi </td>
    <td ><input id='no_expedisi' name='no_expedisi'></td></td>
 </tr>
 <tr>
 <td > Alamat </td>";
$tampil67=mysql_query("SELECT * FROM customer where is_void=0  AND id_customer = '$r[id_customer]' ");
$y=mysql_fetch_array($tampil67);
echo "
    <td id='alamat'><textarea disabled>'$y[alamat_customer]'</textarea></td>
 </tr>";
  echo "</table>";

echo '
<DIV class="btn-action float-clear">
<!-- <div class="btn btn-primary" type="button" id="search-edit" data-toggle="modal" data-target="#search-md">Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
 </div>-->

</DIV>
<table id="header" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Sisa Terakhir</th>
      <th>Jumlah Dikirim</th>
      <th>Satuan</th>
      <th>Gudang</th>
      </tr>
        </thead>
 
        <tbody id="product">';
$noz= 100;
$tampiltable=mysql_query("SELECT *,qty_convert- sum( qty_diterima_convert ) as jumlah ,
if((qty_convert -  sum(qty_diterima_convert) )mod kali5=0, concat(((qty_convert - sum(qty_diterima_convert) )div kali5),',',((qty_convert -  sum(qty_diterima_convert) )div kali5)*kali5,',',satuan5) ,
if((qty_convert -  sum(qty_diterima_convert) )mod kali4=0, concat(((qty_convert - sum(qty_diterima_convert) )div kali4),',',((qty_convert -  sum(qty_diterima_convert) )div kali4)*kali4,',',satuan4) ,
if((qty_convert -  sum(qty_diterima_convert) )mod kali3=0, concat(((qty_convert - sum(qty_diterima_convert) )div kali3),',',((qty_convert -  sum(qty_diterima_convert) )div kali3)*kali3,',',satuan3) ,
if((qty_convert -  sum(qty_diterima_convert) )mod kali2=0, concat(((qty_convert - sum(qty_diterima_convert) )div kali2),',',((qty_convert -  sum(qty_diterima_convert) )div kali2)*kali2,',',satuan2) ,
if((qty_convert -  sum(qty_diterima_convert) )mod kali1=0, concat(((qty_convert - sum(qty_diterima_convert) )div kali1),',',((qty_convert -  sum(qty_diterima_convert) )div kali1)*kali1,',',satuan1),'' )  )  )  )  )  as sisa 
FROM trans_lkb_detail tld, barang b
WHERE b.id_barang = tld.id_barang and id_sales_order = '$r[id_sales_order]' GROUP BY kode_barang_so");
 $no=1;
while ($rst = mysql_fetch_array($tampiltable)){
  echo $rst[jumlah];
  if (($rst[jumlah])!=0){
    $sisa=explode(',',$rst[sisa]);
     echo '
  <!--<tr class="inputtable">-->
 <tr>
    <input type=hidden name="id_lkb[]" value="'. $rst['kode_barang_so'].'" id="id_lkb-'.$noz.'" >
      <td>
       '.$no.'
    </td>
  <td>';
  $tampiltablebarang=mysql_query("SELECT * FROM `barang` WHERE id_barang = '$rst[id_barang]' ");
  //$selisih = $rst['qty_diterima'] - $rst['qty'];
   $rst1 = mysql_fetch_array($tampiltablebarang);
  echo'
       <input type="text" name="kode_barang[]" value="'.$rst1[kode_barang].'"   id="kode_barang-'.$noz.'" readonly="readonly"  />
       <input type="hidden" name="id_barang[]" value="'.$rst[id_barang].'"   id="id_barang-'.$noz.'"  />
    </td>
    <td>
       <input type="text" name="nama_barang[]" value="'.$rst1[nama_barang].'" id="nama_barang-'.$noz.'"  disabled />
    </td>
    <td>
       <input type="text" name="jumlah_diminta[]" value="'.$sisa[0].'-'.$sisa[2].'"  id="jumlahdiminta-'.$noz.'" readonly="readonly" class="hitung" />
       <input type="hidden" name="qty_convert[]" value="'.$sisa[1].'"  id="qty_convert-'.$noz.'" readonly="readonly" class="hitung" />
       <input type="hidden" name="qty_satuan[]" value="'.$sisa[2].'"  id="qty_satuan-'.$noz.'" readonly="readonly" class="hitung" />
    </td>
   
<td id="checkjumlah"><input type="text" name="selisih[]" id="selisih-'.$noz.'"  class="selisih"  /><br>';
   
  echo'</td>  <td>';
          echo '<select id="jenis_satuan-'.$noz.'" name="jenis_satuan[]" required>';
$tampil_lbr=mysql_query("SELECT * FROM barang where is_void=0 and id_barang='".$rst['id_barang']."' ");
   $sat_minim=0;
$data=mysql_fetch_array($tampil_lbr);
            for ($i=1; $i <= 5 ; $i++) { 
        $val= "satuan".$i;
        $val_kali= "kali".$i;
        $val_harga= "harga_sat".$i;

        if ($data[$val]!=""){
          if ($data[$val]==$rst[qty_satuan]){
            $sat_minim=$data[$val_kali];
             echo " <option value='".$data[$val_harga].'-'.$data[$val].'-'.$data[$val_kali]."' selected>".$data[$val].' ('.$data[$val_kali].')'."</option>";
          }
          else if ($sat_minim<=$data[$val_kali])
             echo " <option value='".$data[$val_harga].'-'.$data[$val].'-'.$data[$val_kali]."' >".$data[$val].' ('.$data[$val_kali].')'."</option>";
        
        }
      }

            echo'</select>';
     echo '<td><select class="form-control" id="gudang_lkb-'.$noz.'" name="gudang_lkb[]" required>';
$tampilgudanglbr=mysql_query("SELECT * FROM gudang where is_void=0 ");
          if ($rst[id_gudang]==0){
            echo "<option value='' selected>- Pilih Customer -</option>";
          }   
         while($w=mysql_fetch_array($tampilgudanglbr)){
            if ($rst[id_gudang]==$w[id_gudang]){
              echo "<option value=$w[id_gudang] selected>$w[nama_gudang]</option>";
            }
            else{
              echo "<option value=$w[id_gudang]>$w[nama_gudang]</option>";
            }
          }
echo '</select></td>
</tr>';

$no++;
$noz++;

  }

}

        echo '
        </tbody>
        <tfoot>
                </tfoot>
          </table>
  </div> 
  <button class="btn btn-success"  type="submit"  name="save" value="Save" style="float:left;">Save</button> 
  <a class="btn btn-warning" type="button" href="media.php?module=laporankeluarbarang" style="float:left;margin-left:10px;">Batal</a>
  </form>
  ';

    break;
  }
}
}
?>

<script type="text/javascript">
$('#lkb').DataTable();
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
 
                var t = $('#lkb_no').DataTable({
                    "iDisplayLength": 10,
                       "aLengthMenu": [ [10, 20,50],[10,20,50]],
                      "pagingType" : "simple",
                      "ordering": false,
                      "info":     false,
                      "language": {
                            "decimal": ",",
                            "thousands": "."
                          },
                    "order": [[1, 'asc']],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    },
                });// datatable 


 datetimepiker();
  function nilaiso(kode,id_customer,nama_customer,kete='') {
  var i = $('input').size() + 1;
    var kd1 = kode;
    var dataString = 'text='+ kd1+'&nox='+i;
      $("#no_so").val(kd1);
       $("#myModal").modal("toggle");
      $("#customer").val(id_customer);
      $('#customer_nama').val(nama_customer);
        $('#alamat').val(kete);
      $.ajax({
    url: 'modul/laporankeluarbarang/laporankeluarbarang_detail.php',
   data: dataString,
   cache: false,
   success: function(r)
   {
    $("#product").html(r);
   } 
 });

};
/*function deleteRow(r) {
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("header").deleteRow(i);
}*/

function addMore(kode) {
   var i = $('input').size() + 1;
    var kd1 = kode;
  $("<tr>").load("modul/purchaseorder/input_lkb.php?kd="+kd1+"&nox="+i+" ", function() {
      $("#product").append($(this).html());
       $("#search-md").modal('toggle');
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

// check input disable
function checkjumlah(r,t,p,q){
    //set initial state.
    $('#lbr_checkbox-'+t).change (function() {
        if ($(this).is(':checked')) {
       $("#selisih-"+t).attr('readonly','readonly');
       $("#selisih-"+t).val(p);
        }
        else if (($(this).is(':unchecked'))) {
       $("#selisih-"+t).removeAttr('readonly');
       $("#selisih-"+t).val(q);
        }
    });
}
</script>