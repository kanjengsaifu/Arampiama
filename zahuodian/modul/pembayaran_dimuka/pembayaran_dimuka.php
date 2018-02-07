<?php
 include "config/koneksi.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "<center>Untuk mengakses modul, Anda harus login <br><a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
$aksi="modul/pembayaran_dimuka/aksi_pembayran_dimuka.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
  $judul = "Pembayaran Dimuka";
  $desk = "Berfungsi untuk menambahkan biaya-biaya yang dibayarkan dimuka l";
  $button='  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal_cari_nota">Cari Nota</button>';
  headerDeskripsi($judul,$desk,$button);
    ?>
    <?php switch ($_GET['act']) {
      default:?>
          <div class="table-responsive">
            <table id="po" class="display table table-striped table-bordered table-hover" cellspacing="0">
                    <thead>
                        <tr style="background-color:#F5F5F5;">
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>No Nota Transaksi</th>
                                <th>No Nota Invoice</th>
                                <th>Nominal</th>
                                <th>Keterangan</th>
                                <th colspan="2">Aksi</th>
                          </tr>
                    </thead>
                    <tbody>
            <?php $tampil = mysql_query("SELECT id_pembayaran_dimuka, `no_nota_pembayaran_dimuka`, `id_invoice`, `keterangan`, DATE_FORMAT(date(`tgl_transaksi`),'%d %b %Y') as tanggal,sum(nominal_alokasi) as nominal_alokasi FROM `trans_pembayaran_dimuka` t left join `trans_bayarbeli_detail` td on (no_nota_pembayaran_dimuka=bukti_bayar_dimuka) WHERE t.`is_void`=0 group by no_nota_pembayaran_dimuka order by t.tgl_transaksi desc");
                        $no = 0;
                            while ($r=mysql_fetch_array($tampil)):
                            $no++;?>
                                    <tr>
                                          <td><?= $no; ?></td>
                                          <td><?= $r['tanggal']; ?></td>
                                          <td><?= $r['no_nota_pembayaran_dimuka']; ?></td>
                                          <td><?= $r['id_invoice']; ?></td>
                                          <td><?= format_jumlah($r['nominal_alokasi']); ?></td>
                                          <td><?= $r['keterangan']; ?></td>
                                          <td><a  onclick="delete_pembayaran_dimuka(<?= $r['id_pembayaran_dimuka']; ?>)" class='btn btn-danger' ><i class="fa fa-trash text-red"></i></a></td>
                                          <td><a  href="?module=pembayaran_dimuka&act=tambah&edit=<?= $r['no_nota_pembayaran_dimuka']; ?>" class='btn btn-warning' ><i class="fa fa-trash text-red"></i></a></td>
                                    </tr>
            <?php endwhile; ?>
                   </tbody>
           </table>
      </div>



<!--========================================== TAMBAH ==============================  -->
<!--========================================== TAMBAH ==============================  -->

      <?php  break; ?> <?php   case 'tambah':  ?>

<!--========================================== TAMBAH ==============================  -->
<!--========================================== TAMBAH ==============================  -->
<?php 
if ($_GET['edit']) {
  $id_edit=$_GET['edit'];
    $edit="SELECT id_pembayaran_dimuka, `no_nota_pembayaran_dimuka`, `id_invoice`, `keterangan`, `tgl_transaksi` as tanggal,sum(nominal_alokasi) as nominal_alokasi FROM `trans_pembayaran_dimuka` t left join `trans_bayarbeli_detail` td on (no_nota_pembayaran_dimuka=bukti_bayar_dimuka) WHERE no_nota_pembayaran_dimuka='$id_edit' and t.`is_void`=0 group by no_nota_pembayaran_dimuka order by t.tgl_transaksi desc";
    $result=mysql_query($edit);
    $t=mysql_fetch_array($result);
    $id=$t['id_invoice'];
    $kode=$t['no_nota_pembayaran_dimuka'];
    $nominal_dimuka=$t['nominal_alokasi'];
    $tgl_transaksi=$t['tanggal'];
}else{
  $id=$_GET['id'];
  $kode=cari_kode();
  $tgl_transaksi=date('Y-m-d');
  $nominal_dimuka=0;
}
  $query="
  SELECT s.id_supplier,alamat_supplier as alamat,`id_invoice`,(`grand_total` - ifnull(sum(nominal_alokasi),0)) as nominal_invoice,'Supplier' as keterangan,`nama_supplier` as nama,`no_expedisi`,`no_nota` 
  FROM `trans_invoice` t left join trans_bayarbeli_detail tbd on (tbd.nota_invoice=t.id_invoice),`supplier` s 
  WHERE t.id_supplier=s.id_supplier and t.`is_void`= 0 and `status_lunas`='0' and  id_invoice='$id' group by id_invoice
";
$result=mysql_query($query);
$h=mysql_fetch_array($result);
 ?>
  <form id='form_pembayaran_dimuka'>
<table id="tableheader" class="table table-hover table_without_top" border=0>
  <tr>
    <td>Kode Transksi</td>
    <td><strong>:</strong></td>
    <td><input type="hidden" id='' name='' value="<?= $kode ?>"><?= $kode; ?></td>
    <td>Tanggal Transaksi</td>
    <td><strong>:</strong></td>
    <td><input id='tgl_trans' name='tgl_trans' value='<?=  $tgl_transaksi ?>' class='datetimepicker form-control' required ></td>
  </tr>
  <tr>
    <td>Nomor Invoice</td>
    <td><strong>:</strong></td>
    <td><input type="hidden" id='nota_invoice' name='nota_invoice' value="<?= $h['id_invoice'] ?>"><?= $h['id_invoice'] ?></td>
    <td>Nomor Supplier</td>
    <td><strong>:</strong></td>
    <td><input type="hidden" id='id_supplier' name='id_supplier' value="<?= $h['id_supplier'] ?>"><?= $h['no_nota'].' - '. $h['nama'] ?></td>
  </tr>
  <tr>
    <td>Nominal Invoice</td>
      <td><strong>:</strong></td>
    <td><input type="hidden"  class='form-control' id='total-invoice' name='total-invoice'   value="<?= $h['nominal_invoice']+$nominal_dimuka ?>" > <?= format_jumlah($h['nominal_invoice']+$nominal_dimuka) ?></td>
     <td>Nominal Alokasi</td>
         <td><strong>:</strong></td>
    <td><input type="text"  class='form-control' id='total-alokasi' name='total-alokasi'  readonly></td>
  </tr>
</table>
<DIV class="btn-action float-clear">
<div class="rows">
  <div class="btn btn-primary" type="button" id="search" data-toggle="modal" data-target="#search-md">Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span></div>
 <button id='simpan' class="btn btn-success" type="submit">Simpan <span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
 </div>
</div>
<table class='table table-hover' id='alokasi'>
<thead>
  <tr>
    <th>No</th>
    <th>Bukti Pembayaran</th>
    <th>Nominal Saldo</th>
    <th>Nominal</th>
    <th>Aksi</th>
  </tr>
</thead>
<tbody id='product'>
<?php if ($_GET['edit']) :
$detail="SELECT `id_akunkasperkiraan`,a.`bukti_bayar`,c.nominal_alokasi,(`nominal`-IFNULL(sum(a.`nominal_alokasi`),0)) as sisa  FROM `trans_bayarbeli_detail` a,`trans_bayarbeli_header` b,
(SELECT bukti_bayar,`bukti_bayar_dimuka`,`nominal_alokasi` FROM `trans_bayarbeli_detail` WHERE `bukti_bayar_dimuka`='TPD/20170706/00003') c
WHERE a.`bukti_bayar`=c.`bukti_bayar` and a.`bukti_bayar`=b.`bukti_bayar` group by `bukti_bayar`";
$result=mysql_query($detail);
$noz=1;
while ($data=mysql_fetch_array($result)) :?>
    
<tr>
  <td><input type="hidden" id='id_akunkasperkiraan-<?= $noz ?>' name='id_akunkasperkiraan[]' value='<?= $data['id_akunkasperkiraan']  ?>' ><?= $noz ?></td>
  <td><input type="hidden" id='bukti_bayar-<?= $noz ?>' name='bukti_bayar[]' value='<?= $data['bukti_bayar']  ?>' ><?= $data['bukti_bayar'] ?></td>
  <td><input type="hidden" id='nominal-<?= $noz ?>' name='nominal[]' value='<?= ($data['sisa']+ $data['nominal_alokasi'])  ?>' ><?= format_jumlah($data['sisa']+ $data['nominal_alokasi']); ?></td>
  <td><input type="text" class='hitung numberhit form-control' id='nominal-alokasi-<?= $noz ?>' name='nominal-alokasi[]' value='<?= $data['nominal_alokasi']  ?>' ></td>
  <td>  <div  class=" btn-danger" name="del_item" onclick="deleteRow(this,<?= $noz-1 ?>)"><span class='glyphicon glyphicon-trash'></span></div></td>
</tr>

<?php $noz++; endwhile?>
<?php endif ?>
</tbody>
</table>
</form>
      <?php break; ?>
   <?php  } ?>



<!--========================================== MODAL ==============================  -->
<!--========================================== MODAL ==============================  -->


  <div id="modal_cari_nota" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
      <input id="pencarian_nota" type="text" placeholder="Pencarian" class="close">
        <h4 class="modal-title">Tambah Pembayran Dimuka</h4>
      </div>
      <div class="modal-body">
          <table class="table table-hover table-bordered dataTable"> 
          <thead> 
                <tr style="background-color:#F5F5F5;">
                      <th>Keterangan</th>
                      <th>Nama</th>
                      <th>No Nota Melati</th>
                      <th>Grand Total</th>
                      <th>No Expedisi</th>
                      <th>No Nota Sumber</th>
                       <th>Aksi</th>
                </tr>
          </thead>
            <tbody id='body_cari_nota'>
                <?php 
  $query="
SELECT alamat_supplier as alamat,`id_invoice`,`grand_total`,'Supplier' as keterangan,`nama_supplier` as nama,`no_expedisi`,`no_nota` FROM `trans_invoice` t,`supplier` s WHERE t.id_supplier=s.id_supplier and t.`is_void`= 0 and `status_lunas`='0' 
order by case 
    when nama LIKE 'a%' then 1 
    when nama LIKE '%a%'  then 2 
    when id_invoice LIKE 'a%'  then 3
    when id_invoice LIKE '%a%'  then 4 
    when no_nota LIKE 'a%'  then 5
    when no_nota LIKE '%a%'  then 6 
end
  ";
  $result = mysql_query($query);
  while ($r=mysql_fetch_array($result)) :
      ?>

          <tr>
          <td><?= $r['keterangan']; ?></td>
          <td><?= $r['nama']; ?></td>
          <td><?= $r['id_invoice']; ?></td>
          <td text-align="right"><?= format_jumlah($r['grand_total']); ?></td>
          <td><?= $r['no_expedisi']; ?></td><td><?= $r['no_nota']; ?></td>
          <td>    <a href="?module=pembayaran_dimuka&act=tambah&id=<?= $r['id_invoice']; ?>"><button type="submit"></button>        </a></td>
          </tr>

    <?php  endwhile; ?>
              </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!--========================================== MODAL ==============================  -->
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
                    <th>Bukti Transaksi</th>
                    <th>Nama Supplier</th>
                    <th>Jumlah Titipan</th>
                    <th>Aksi</th>
                </tr>
        </thead>
      </table>

          </div>
      </div>
    </div>

  </div>
</div>
<input type="hidden" id="counter" value="1">
 <?php    
}
 function cari_kode(){
    $var = explode('-', date("Y-m-d"));
    $sql_cari="SELECT max(`no_nota_pembayaran_dimuka`) as kode FROM `trans_pembayaran_dimuka` WHERE `no_nota_pembayaran_dimuka` LIKE 'TPD/$var[0]$var[1]%' ";
    $result=mysql_query($sql_cari);
    $hasil=mysql_fetch_array($result);
    $kode = explode('/',$hasil["kode"]);
    $kode_urut=100001+$kode[2];
   return 'TPD/'.implode('', $var).'/'.substr($kode_urut,1);
}
?>
<script type='text/javascript'>
$('#product').on('keyup', '.hitung', function() {
      var id = $(this).attr('id'),
    tamp = id.split('-');
    nominal=Number($('#nominal-'+tamp[2]).val());
    nominal_alokasi=Number($('#nominal-alokasi-'+tamp[2]).val());
    if (nominal < nominal_alokasi) {
       $('#nominal-alokasi-'+tamp[2]).val('');
       alert('Nominal Yang di Masukan Terlalu Besar Check Dan Ulangi kembali');
    }
    grandtotal(tamp[2]);
});
function grandtotal(id='') {
   var jumlah = 0;
  $('.hitung').each(function(i, obj) {
     jumlah +=(isNaN(parseFloat($(this).val())) ?  0 : parseFloat($(this).val())) ;
});
var total_invoice=$('#total-invoice').val();
if (total_invoice<=jumlah) {
   $('#nominal-alokasi-'+id).val('');
   alert('Nominal Melebihi Total Invoice');
    grandtotal();
}else{
  $('#total-alokasi').val(jumlah);
}
}
  function delete_pembayaran_dimuka($id) {
    var result =   confirm('Apakah anda yakin ingin Menghapus Transaksi ini ?');
      if (result) {
              var $data='data='+$id;
                 $.ajax({
                   url: 'modul/pembayaran_dimuka/delete_pembayaran_dimuka.php',
                   type: 'POST',
                   dataType: 'html',
                   data: $data,
                 })
                 .done(function() {
                   console.log("success");
                   // location.reload(); 
                 })
                 .fail(function() {
                   console.log("error");
                   // location.reload(); 
                 })
                 .always(function() {
                   console.log("complete");
                   // location.reload(); 
                 });
      }
  }
datetimepiker();
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
                    "ajax": "modul/pembayaran_dimuka/load-data.php",
                    "order": [[1, 'asc']],
                     "columns": [
                        { "searchable": false },
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
            });
      var array=[];
  function addMore(kode) {
    var i = $('#counter').val();;
    var kd1 = kode;
    if (array.indexOf(kode)==-1) {
                  array.push(kode);
        $("<tr>").load("modul/pembayaran_dimuka/add_row_invoice.php?kd="+kd1+"&nox="+i+"", function() {
            $("#product").append($(this).html());
             $('#counter').val(Number(i)+1);
          return false;
        }); 
    }else{
      alert("Data Sudah Ada");
    }

}
function deleteRow(r,no_array) {
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("alokasi").deleteRow(i);
           var alltotal = 0;
           delete array[no_array];   
           grandtotal();
}
$('#form_pembayaran_dimuka').submit(function() {
  if ( ($('#total-alokasi').val()!= 0 )  ) {
      document.getElementById("simpan").style.visibility = "hidden";
          $.ajax({
              type: 'POST',
              url: 'modul/pembayaran_dimuka/insert_pembayaran_dimuka.php',
              data: $(this).serialize(),
              success: function(data) { 
                alert(data);
                location.reload(); 
              }
            })
    }else{
      alert('Data Harus Memiliki Nominal Alokasi');
    }
    return false;
    })

</script>
