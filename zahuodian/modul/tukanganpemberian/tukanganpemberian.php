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
$aksi="modul/tukanganpemberian/aksi_tukanganpemberian.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:

  $judul = "Pemberian Tukangan";
  $desk = "Modul untuk mencatat bahan baku apa yang diberikan pada tukang";
  $button= "<a href='?module=tukanganpemberian&act=tambah' class='btn btn-primary' >Buat Nota <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a><span class='success'>";
  headerDeskripsi($judul,$desk,$button);
   
echo '
<div class="table-responsive">
  <table id="beritukang" class="display table table-striped table-bordered table-hover">
  <thead>
  <tr style="background-color:#F5F5F5;"">
    <th id="tablenumber">No</th>
    <th>No PBB</th>
    <th>No Nota</th>
    <th>Nama Tukang</th>
    <th>Tanggal Transaksi</th>
    <th>Nominal</th>
    <th>Aksi</th>
  </tr></thead>
  </table>
</div>
<!--table class="table table-striped table-bordered table-hover"> 
    <thead>
        <tr>
            <th>No</th>     
            <th>No Nota</th>      
            <th>Nama Tukang</th>      
            <th> Tanggal Transaksi</th>
            <th>Nominal</th>  
            <th>Aksi</th> 
        </tr>
    </thead>
    <tbody>
        
    </tbody>          
</table-->';

    break;

  case "tambah":
  $judul = "<b>Nota </b>Penyerahan Bahan Baku";
  $desk = "Modul digunakan untuk mencatat bahan baku yang diberikan pada tukang";
  headerDeskripsi($judul,$desk);

  echo "
   <form method='post' action='$aksi?module=tukanganpemberian&act=input'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No PBB</td>
    <td>";
   echo "<b>".kode_surat('PBB', 'trans_beri_tukang_header', 'id_beri_tukang', 'id_trans_beri_tukang_header' )."</b>";
   echo " </td>
    <td>Tanggal</td>
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

';
echo "
<!--table class='table table-hover table' id='tambah' >
    <tr>
        <td width='15%'>No Nota</td>
        <td width='5%'><Strong>:</Strong></td>
        <td width='30%'>NPB/00001</td> 
        <td width='15%'>Tanggal Transaksi</td>
        <td width='5%'><Strong>:</Strong></td>
        <td width='30%'></td>
    </tr>
    <tr>
        <td>Nama Tukang</td>
        <td><Strong>:</Strong></td>
        <td></td>
        <td>Total Transaksi</td>
        <td><strong>:</strong></td>
        <td></td>
    </tr>
    <tr>
    <td colspan='3'><textarea name='' id='' class='form-control' readonly></textarea></td>
     <td colspan='3'><textarea name='' id='' class='form-control' readonly></textarea></td>
    </tr>
</table-->



<table id='header' class='display table table-striped table-bordered table-hover' cellspacing='0'> 
<thead>
  <th colspan='2'>Nama barang - Kode barang</th>
      <th width='10%'>Gudang</th>
      <th width='10%' >Satuan</th>
      <th width='15%'>Harga</th> 
      <th width='15%'>Qty</th>
      <th>Total</th>
      <th>Aksi</th>
</thead>";
echo '
<tbody id="product">    
        </tbody>
        <tfoot>
     
  <tr id="productall">
    <td colspan="3" rowspan="4"><button class="btn btn-success"  type="submit"  name="save" vaue="Save" style="float:left;">Save 
    </button> 
      <!--a class="btn btn-default" type="button" href="modul/purchaseorder/cetak.php?id=$no_po "  target="_blank" >Cetak</a-->
          <a class="btn btn-warning" type="button" href="media.php?module=tukanganpemberian" style="float:left;margin-left:10px;">Batal</a>
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

















//   echo "
//    <form method='post' action='$aksi?module=purchaseorder&act=input'>
//      <div class='table-responsive'>
//       <table class='table table-hover' border=0 id=tambah>
//   <tr>
//     <td>No PO</td>
//     <td>";
//  $tampil23=mysql_query("SELECT * FROM `trans_pur_order` order by id desc limit 1 ");
//   $r    = mysql_fetch_array($tampil23);
//   echo kodesurat($r['id_pur_order'], PO, no_po, id_pur_order );
//    echo " </td>
//     <td>Tanggal PO</td>
//     <td><input id='tanggalpo' name='tgl_po' value='".date('Y-m-d')."' class='datetimepicker form-control' required ></td>
//   </tr>
//   <tr>
//    <td>Supplier</td> <td id='sup'>";

//    echo '<select id="supplier" name = "supplier2" class="chosen-select form-control" tabindex="2" required>';
// $tampil43=mysql_query("SELECT * FROM Supplier where is_void=0 ");
//             echo "<option value='' selected>- Pilih Supplier -</option>";
//          while($w=mysql_fetch_array($tampil43)){
//               echo "<option value=$w[id_supplier]>$w[nama_supplier]</option>";
//           }
// echo '</select>
// <br>';
// echo"
//   </td>
//   </tr>";
//   echo '<tr id="txtHint">
// <td> Alamat </td>
//     <td><textarea disabled class=form-control></textarea></td>
//     <td> No tlp </td>
//     <td><textarea disabled class=form-control></textarea></td>
//   </tr>';
//   echo "</table> ";

// echo '
// <DIV class="btn-action float-clear">
// <!-- <div class="btn btn-primary" name="add_item"  onClick="addMore();" >Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
//  </div>-->
// <div class="btn btn-primary" type="button" id="search" data-toggle="modal" data-target="#search-md">Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
//  </div>

// </DIV>
// <table id="header" class="display table table-striped table-bordered table-hover" cellspacing="0">
//         <thead>
//   <tr style="background-color:#F5F5F5;">
//       <th colspan="2">Nama barang - Kode barang</th>
//       <th width="8%" >Satuan</th>
//       <th width="8%">Harga</th> 
//       <th width="8%">Qty</th>
//       <th width="5%">Disc 1 </br>(%)</th>
//       <th width="5%">Disc 2 </br>(%)</th>
//       <th width="5%">Disc 3 </br>(%)</th>
//       <th width="5%">Disc 4 </br>(%)</th>
//       <th>Pembulatan </br>(Rp.)</th>
//       <th>Total</th>
//       <th>Aksi</th>
//       </tr>
//         </thead> 
//         <tbody id="product">    
//         </tbody>
//         <tfoot>
     
//   <tr id="productall">
//     <td colspan="7" rowspan="4"><button class="btn btn-success"  type="submit"  name="save" vaue="Save" style="float:left;">Save ';

//     echo '</button> 
//       <!--a class="btn btn-default" type="button" href="modul/purchaseorder/cetak.php?id=$no_po "  target="_blank" >Cetak</a-->
//           <a class="btn btn-warning" type="button" href="media.php?module=purchaseorder" style="float:left;margin-left:10px;">Batal</a>
//           </td>

//     <td colspan="3" style="text-align:right;" ><p>ToTal All SUb </p></td>
//     <td colspan="1"  ><input name="alltotal" type="text" class="hitung2 form-control numberhit" id="total" readonly="readonly" ></td>
//     <td></td>
//   </tr>

//   <tr>
//     <td colspan="3" style="text-align:right;"><p> Disc (%) <input name="persendisc" type="text" id="persendisc" style="width:2em;" > | (Rp) </p></td>
//     <td colspan="1" style="nowrap:nowrap;"><input name="discalltotal" type="text" id="totaldisc" value="0" class="hitung2 form-control numberhit" ></td>
//     <td></td>
//   </tr>
//   <tr>
//     <td colspan="3" style="text-align:right;"><p> Ppn (%) <input name="persenppn" type="text" id="persenppn" style="width:2em;"> | (Rp) </p></td>
//     <td colspan="1"  style="nowrap:nowrap;"><input name="totalppn" type="text" id="totalppn" value="0" class="hitung2 form-control numberhit" ></td>
//     <td></td>
//   </tr>
//   <tr>
//     <td colspan="3" style="text-align:right;"><b>Grand total</b></td>
//     <td colspan="1"><b><input name="grandtotal" type="text" id="grandtotal" readonly="readonly" class="form-control numberhit" ></b></td>
//     <td></td>
//   </tr>
//                 </tfoot>
//           </table>
//   </div> 
//   </form>
// <div id="search-md" class="modal fade" role="dialog">
//   <div class="modal-dialog modal-lg">

//     <div class="modal-content">
//       <div class="modal-header">
//         <button type="button" class="close" data-dismiss="modal">&times;</button>
//         <h4 class="modal-title">cari Item</h4>
//       </div>
//       <div class="modal-body">
//       <table id="tambahitem" class="table table-hover table-bordered" cellspacing="0" style="width: 100%;">
//         <thead>
//                 <tr style="background-color:#F5F5F5;">
//                     <th  id="tablenumber">No</th>
//                     <th>Kode Barang</th>
//                     <th>Nama Barang</th>
//                     <th>Stok sekarang</th>
//                     <th>Stok Min</th>
//                     <th>Tambah</th>
//                 </tr>
//         </thead>
//       </table>

//           </div>
//       </div>
//     </div>

//   </div>
// </div>
//   ';
    break;

    case "edit":
  $judul = "<b>Edit</b> Penyerahan Bahan";
  $desk = "Modul digunakan untuk mencatat bahan yang diberikan pada tukang";
  headerDeskripsi($judul,$desk);

   
  echo "
   <form method='post' action='$aksi?module=tukanganpemberian&act=update'>
     <div class='table-responsive'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No PBB</td>
    <td>";
 $tampil44=mysql_query("SELECT * FROM `trans_beri_tukang_header`tpr,supplier s   WHERE tpr.id_supplier=s.id_supplier and id_trans_beri_tukang_header = '$_GET[id]'");
  $r    = mysql_fetch_array($tampil44);

  echo "
   <input type='hidden' name='id' value='$r[id_trans_beri_tukang_header]' id='id_purchaseorder''>
  <input  name='no_npb' value='$r[id_beri_tukang]' id='id_pur_order' readonly='readonly'  class='form-control' />";
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
  <td><input type='text' name='nonota' value='$r[nonota_beri_tukang]' class='form-control'></td>
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
      <th width="10%"">Gudang</th>
      <th width="10%">Satuan</th>
      <th width="15%">Harga</th>
      <th width="15%">Qty</th>
      <!--th>Disc 1 (%)</th>
      <th>Disc 2 (%)</th>
      <th>Disc 3 (%)</th>
      <th>Disc 4 (%)</th>
      <th>Pembulatan <br> (Rp.)</th-->
      <th>Total</th>
      <th>Aksi</th>
      </tr>
        </thead>
 
        <tbody id="product">';
$tampiltable=mysql_query("SELECT * FROM `trans_beri_tukang_detail`   WHERE id_beri_tukang = '$r[id_beri_tukang]'  ");
$noz = 1000;
$rst_jumlah = mysql_num_rows($tampiltable);
while ($rst = mysql_fetch_array($tampiltable)){
  $sql= mysql_query("SELECT * FROM barang WHERE id_barang = '$rst[id_barang]' ");
  $data = mysql_fetch_array($sql);
  echo '
  <tr class="inputtable">
  <input type=hidden name="id_po_barang[]" value="'. $data['id_barang'].'" id="id_po_barang-'.$noz.'" >
    <input type=hidden name="id_po[]" value="'. $rst['id_trans_beri_tukang_detail'].'" id="id_po-'.$noz.'" >
    <input type="hidden" name="id_akunkasperkiraan[]" value="'.$data['id_akunkasperkiraan'].'" id="id_akunkasperkiraan-'.$noz.'" >
    <input type=hidden value="'.$data['hpp'].'" name="hpp[]" value="" id="hpp-'.$noz.'" >
  <td colspan="2">
   <input type="text" class="namabarang" name="nama_barang[]" value="'.$data['nama_barang'].'" id="nama_barang-'.$noz.'"  disabled />
   </br>
       <input type="text"  class="namabarang"  name="kode_barang[]" value="'.$data['kode_barang'].'"   id="kode_barang-'.$noz.'"  disabled />
    </td>
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
       <input type="text" name="harga_sat1[]" value="'.$rst['harga'].'"  id="harga-'.$noz.'" class="hitung numberhit" />
    </td>
    
   <td><input type="text" name="jumlah[]" id="satuan-'.$noz.'"  value="'.$rst[jumlah].'" class="hitung form-control numberhit" /></td>
            <input style="width:50px;" type="hidden" name="disc1[]"  id="disc1_barang-'.$noz.'" value="'.$rst[disc1].'"  class="hitung numberhit form-control" />
            <input style="width:50px;" type="hidden" name="disc2[]" id="disc2_barang-'.$noz.'" value="'.$rst[disc2].'"  class="hitung numberhit form-control" />
            <input style="width:50px;" type="hidden" name="disc3[]" id="disc3_barang-'.$noz.'" value="'.$rst[disc3].'" class="hitung numberhit form-control" />
            <input style="width:50px;" type="hidden" name="disc4[]" id="disc4_barang-'.$noz.'" value="'.$rst[disc4].'" class="hitung numberhit form-control"  />
            <input style="width:60px;" type="hidden" name="disc5[]" id="disc5_barang-'.$noz.'" value="'.$rst[disc5].'" class="hitung numberhit"  />
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

if ($r['all_discpersen']==0) {
$discper="";
}else{
  $discper=$r['all_discpersen'];
}
if ($r['all_ppnpersen']==0) {
$ppnper="";
}else{
  $ppnper=$r['all_ppnpersen'];
}
echo'
        </tbody>
        <tfoot>
        <tr id="productall">
    <td colspan="5" rowspan="4"> <button class="btn btn-success"  type="submit"  name="update" value="update" style="float:left;">Update';

    echo '</button> 
          <a class="btn btn-warning" type="button" href="media.php?module=tukanganpemberian" style="float:left;margin-left:10px;">Batal</a>
          </td>

    <td colspan="" style="text-align:right;" ><p><b>ToTal All SUb </b></p></td>
    <td><input name="alltotal" type="text" class="hitung2 numberhit form-control" id="total" value='.$r[all_total].' readonly="readonly" ></td>
  </tr>

  <tr>
    <td colspan="" style="text-align:right;"><p> Disc (%) <input name="persendisc"  type="text" id="persendisc" style="width:2em;" value='.$discper.' > | (Rp) </p></td>
    <td style="nowrap:nowrap;"><input name="discalltotal" type="text" id="totaldisc" value='.$r[all_discnominal].'  class="hitung2 numberhit form-control" ></td>
  </tr>
  <tr>
    <td colspan="" style="text-align:right;"><p> Ppn (%) <input name="persenppn"  type="text" id="persenppn" style="width:2em;" value='.$ppnper.'> | (Rp) </p></td>
    <td style="nowrap:nowrap;"><input name="totalppn" type="text" id="totalppn" value='.$r[all_ppnnominal].'  class="hitung2 numberhit form-control" ></td>
  </tr>
  <tr>
    <td colspan="" style="text-align:right;"><b>Grand total</b></td>
    <td><b><input name="grandtotal" type="text" id="grandtotal" value='.$r[grand_total].'  readonly="readonly" class="numberhit form-control"></b></td>
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
                      url: 'modul/tukanganpemberian/filter.php',
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
function addMore(kode) {
   var i = $('input').size() + 1;
    var kd1 = kode;
    //var supp =$('#supplier2 option:selected').val(); &supp="+supp+"
  $("<tr>").load("modul/tukanganpemberian/input.php?kd="+kd1+"&nox="+i+" ", function() {
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
                    "ajax": "modul/tukanganpemberian/load-data.php",
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
                var u = $('#beritukang').DataTable({
                      "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false }
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                       "pagingType" : "simple_numbers",
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/tukanganpemberian/load-data-a.php",
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
