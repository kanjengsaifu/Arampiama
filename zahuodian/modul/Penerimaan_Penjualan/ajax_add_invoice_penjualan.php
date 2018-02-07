
<?php 
 include "../../config/koneksi.php";
 include "../../lib/input.php";
  $no= $_POST['no'];
  $invoice = $_POST['kode'];
  $c_tipe=explode('/', $_POST['kode']);
  $tipe=$c_tipe[0];
  $value=$c_tipe[1];
 if ($tipe=='Pembayaran') {
    $c_tipe=explode('@', $_POST['kode']);
    $value=$c_tipe[1];
        $detail_alokasi = ("
          SELECT *,ket_detail_jual as keterangan_detail FROM `trans_bayarjual_detail` a,`akun_kas_perkiraan` b where a.`id_akunkasperkiraan_detail`= b.`id_akunkasperkiraan` and  `bukti_bayarjual`='$value'");
      $result = mysql_query($detail_alokasi);
    while ($r = mysql_fetch_array($result)) {
  $c_tipe=explode('/', $r['nota_invoice']);
  $tipe_invoice=$c_tipe[0];
$id_bayarjual_detail            =$r['id_bayarjual_detail'];
$nama_akun                      =$r ['kode_akun'].' - '. $r ['nama_akunkasperkiraan'];
$id_akun                            =$r['id_akunkasperkiraan'];
$nama_customer                  =$r['nama'];
$id_customer                        =$r['id_customer'];
 $kode_user                       =$tipe_invoice;
$no_invoice                       =$r['nota_invoice'];
$nominal_invoice               =$r['nominal_alokasi_detail_jual'];
$sisa_invoice                     =$r['nominal_alokasi_detail_jual'];
$readonly                           ='readonly';
$nominal_alokasi_detail_jual               =$r['nominal_alokasi_detail_jual'];
$ketdetail                           =$r['keterangan_detail'];
$bukti_bayar_dimuka         =$r['bukti_bayar_dimuka'];
body_pembayaran($no,$id_bayarjual_detail,$nama_akun,$id_akun,$nama_customer,$id_customer,$kode_user,$no_invoice,$nominal_invoice,$sisa_invoice,$readonly,$nominal_alokasi_detail_jual,$ketdetail ,$bukti_bayar_dimuka);
$no++;
      }
 }
   $query2 = ("
    SELECT sum(nominal_alokasi_detail_jual) as sisa 
    FROM trans_bayarjual_detail 
    WHERE nota_invoice = '$invoice'");
      $tampilsisa = mysql_query($query2);
      $ss = mysql_fetch_array($tampilsisa);


  if ($tipe=='akun') {

      $query = "SELECT * FROM `akun_kas_perkiraan` WHERE `is_void`='0' and id_akunkasperkiraan = '$value'";
      echo $query;
      $result=mysql_query($query);
      $akun = mysql_fetch_array($result);

$id_bayarjual_detail       ='';
$nama_akun                      =$akun ['kode_akun'].' - '. $akun ['nama_akunkasperkiraan'];
$id_akun                              =$akun ['id_akunkasperkiraan'];
$nama_customer                ='';
$id_customer                        ='';
 $kode_user                        ='';
$no_invoice                         ='';
$nominal_invoice              ='0';
$sisa_invoice                      =(100000000000);
$readonly                              ='';
$nominal_alokasi_detail_jual              ='';
$ketdetail                              ='';
$bukti_bayar_dimuka                              ='';
body_pembayaran($no,$id_bayarjual_detail,$nama_akun,$id_akun,$nama_customer,$id_customer,$kode_user,$no_invoice,$nominal_invoice,$sisa_invoice,$readonly,$nominal_alokasi_detail_jual ,$ketdetail ,$bukti_bayar_dimuka);
  }
    if ($tipe=='SI') {
           $c_tipe=explode('@', $_POST['kode']);
       $invoice=$c_tipe[0];
    $value=$c_tipe[1];
        $query = ("
          SELECT ti.id_invoice,s.id_customer, s.nama_customer, ti.grand_total,kode_customer 
          FROM trans_sales_invoice ti ,customer s
          WHERE  ti.id_customer=s.id_customer and ti.is_void='0' and ti.id_invoice = '$invoice'");
      $result=mysql_query($query);
      $SI = mysql_fetch_array($result);

      $query = ("SELECT * FROM `akun_kas_perkiraan` WHERE `id_akunkasperkiraan` = '$value'");
      $result=mysql_query($query);
      $akun = mysql_fetch_array($result);

$id_bayarjual_detail       ='';
$nama_akun                      =$akun ['kode_akun'].' - '. $akun ['nama_akunkasperkiraan'];
$id_akun                              =$akun ['id_akunkasperkiraan'];
$nama_customer                =$SI['nama_customer'];
$id_customer                        =$SI['id_customer'];
 $kode_user                        =$tipe;
$no_invoice                         =$SI['id_invoice'];
$nominal_invoice              =$SI['grand_total'];
$sisa_invoice                      =($SI['grand_total']-$ss['sisa']);
$readonly                              ='';
$nominal_alokasi_detail_jual              =($SI['grand_total']-$ss['sisa']);
$ketdetail                              ='';
$bukti_bayar_dimuka                              ='';
body_pembayaran($no,$id_bayarjual_detail,$nama_akun,$id_akun,$nama_customer,$id_customer,$kode_user,$no_invoice,$nominal_invoice,$sisa_invoice,$readonly,$nominal_alokasi_detail_jual,$ketdetail ,$bukti_bayar_dimuka);
  }

    if ($tipe=='RBB') {
       $query = ("
        SELECT * 
        FROM `trans_retur_pembelian` trp,customer s 
        WHERE s.id_customer=trp.id_customer and trp.is_void='0' and `kode_rbb`= '$invoice'");
      $retur=mysql_query($query);
      $RBB = mysql_fetch_array($retur);

       $query = ("SELECT * FROM `akun_kas_perkiraan` WHERE `id_akunkasperkiraan` = '95'");
      $result=mysql_query($query);
      $akun = mysql_fetch_array($result);

$id_bayarjual_detail       ='';
$nama_akun                      =$akun ['kode_akun'].' - '. $akun ['nama_akunkasperkiraan'];
$id_akun                              =$akun ['id_akunkasperkiraan'];
$nama_customer                =$RBB['nama_customer'];
$id_customer                        =$RBB['id_customer'];
 $kode_user                        =$tipe;
$no_invoice                         =$RBB['kode_rbb'];
$nominal_invoice              =$RBB['grandtotal_retur'];
$sisa_invoice                      =($RBB['grandtotal_retur']-$ss['sisa']);
$readonly                              ='readonly';
$nominal_alokasi_detail_jual              =($RBB['grandtotal_retur']-$ss['sisa']);
$ketdetail                              ='';
$bukti_bayar_dimuka                              ='';
body_pembayaran($no,$id_bayarjual_detail,$nama_akun,$id_akun,$nama_customer,$id_customer,$kode_user,$no_invoice,$nominal_invoice,$sisa_invoice,$readonly,$nominal_alokasi_detail_jual,$ketdetail ,$bukti_bayar_dimuka);
  }

    if ($tipe=='RJB') {
     $query = ("
      SELECT * 
      FROM `trans_retur_penjualan` trp,customer c 
      WHERE c.id_customer=trp.id_customer and trp.is_void='0' and  `kode_rjb`= '$invoice'");
      $retur=mysql_query($query);
      $RJB = mysql_fetch_array($retur);

      $query = ("SELECT * FROM `akun_kas_perkiraan` WHERE `id_akunkasperkiraan` = '91'");
      $result=mysql_query($query);
      $akun = mysql_fetch_array($result);

$id_bayarjual_detail       ='';
$nama_akun                      =$akun ['kode_akun'].' - '. $akun ['nama_akunkasperkiraan'];
$id_akun                              =$akun ['id_akunkasperkiraan'];
$nama_customer                =$RJB['nama_customer'];
$id_customer                        =$RJB['id_customer'];
 $kode_user                        =$tipe;
$no_invoice                         =$RJB['kode_rjb'];
$nominal_invoice              =$RJB['grandtotal_retur'];
$sisa_invoice                      =(-$RJB['grandtotal_retur']+$ss['sisa']);
$readonly                              ='readonly';
$nominal_alokasi_detail_jual              =(-$RJB['grandtotal_retur']+$ss['sisa']);
$ketdetail                              ='';
$bukti_bayar_dimuka                              ='';
body_pembayaran($no,$id_bayarjual_detail,$nama_akun,$id_akun,$nama_customer,$id_customer,$kode_user,$no_invoice,$nominal_invoice,$sisa_invoice,$readonly,$nominal_alokasi_detail_jual,$ketdetail ,$bukti_bayar_dimuka);
  }

    if ($tipe=='NTT') {
     $query = ("
      SELECT * FROM `trans_totalan_tukang` t,customer s 
        WHERE s.id_customer=t.id_customer and t.is_void='0' and no_totalan_tukang='$invoice'");
     echo $query;
      $retur=mysql_query($query);
      $NTT = mysql_fetch_array($retur);

      $query = ("SELECT * FROM `akun_kas_perkiraan` WHERE `id_akunkasperkiraan` = '71'");
      $result=mysql_query($query);
      $akun = mysql_fetch_array($result);

$id_bayarjual_detail       ='';
$nama_akun                      =$akun['kode_akun'].' - '. $akun ['nama_akunkasperkiraan'];
$id_akun                              =$akun['id_akunkasperkiraan'];
$nama_customer                =$NTT['nama_customer'];
$id_customer                        =$NTT['id_customer'];
 $kode_user                        =$tipe;
$no_invoice                         =$NTT['no_totalan_tukang'];
$nominal_invoice              =$NTT['nominal_totalan'];
$sisa_invoice                      =($NTT['nominal_totalan']-$ss['sisa']);
$readonly                              ='';
$nominal_alokasi_detail_jual              =($NTT['nominal_totalan']-$ss['sisa']);
$ketdetail                              ='';
$bukti_bayar_dimuka                              ='';
body_pembayaran($no,$id_bayarjual_detail,$nama_akun,$id_akun,$nama_customer,$id_customer,$kode_user,$no_invoice,$nominal_invoice,$sisa_invoice,$readonly,$nominal_alokasi_detail_jual,$ketdetail ,$bukti_bayar_dimuka);
  }
  
      ?>
      <?php function body_pembayaran($no,$id_bayarjual_detail,$nama_akun,$id_akun,$nama_customer,$id_customer,$kode_user,$no_invoice,$nominal_invoice,$sisa_invoice,$readonly,$nominal_alokasi_detail_jual,$ketdetail ,$bukti_bayar_dimuka){?>
          
<tr>
<td><?=$no?>
          <input type="hidden" name="id_bayarjual_detail[]" value="<?= $id_bayarjual_detail  ?>">
</td> 
<td style="text-align: left;"><?= $nama_akun ?>
          <input type="hidden" name="id_akunkas[]" value='<?= $id_akun ?>' class="form-control" >
</td>
<td><?= $nama_customer ?>
          <input type="hidden" name="nama_customer[]" value='<?= $nama_customer ?>' class="form-control" >
          <input type="hidden" name="id_customer[]" value='<?= $id_customer ?>' class="form-control" >
          <input type="hidden" name="kode_user[]"  value="<?= $kode_user  ?>">
</td>
<td><?= $no_invoice ?>
          <input type="hidden" name="no_invoice[]" value='<?= $no_invoice ?>' class="form-control" >
         <input type="hidden" name="bukti_bayar_dimuka[]" value='<?= $bukti_bayar_dimuka ?>' class="form-control" >
</td>
<td><?= format_jumlah($nominal_invoice) ?>
        <input type="hidden" name="nominal_invoice[]"     value="<?= $nominal_invoice ?>" class="sisainvoice form-control hitung numberhit" readonly> 
</td>
<td><?= format_jumlah($sisa_invoice) ?>
        <input type="hidden" id="sisa_invoice-<?= $no?>" name="sisa_invoice[]"     value="<?= $sisa_invoice ?>" class="sisainvoice form-control hitung numberhit" readonly> 
</td>
<td>
<table width="100%">
  <tr>
  <td>    <input type="text" id="nominal_alokasi-<?= $no?>" name="nominal_alokasi_detail_jual[]" placeholder="Jumlah Pengalokasian"    value="<?=  $nominal_alokasi_detail_jual ?>" class="alokasi form-control hitung numberhit" <?= $readonly ?> required></td>
  </tr>
  <tr>
       <td><textarea type="text" name="ketdetail[]"   placeholder="Keterangan" class="form-control"><?= $ketdetail  ?></textarea></td>
  </tr>
</table>
</td>             
<td>
           <a  class="btn btn-warning" name="del_item" onclick="deleteRow(this)"><span class="glyphicon glyphicon-remove"></span></a>
</td>
</tr>';

      <?php } ?>




<script type='text/javascript'>
  $(function () {
    var showPopover = function () {
        $(this).popover('show');
    }
    , hidePopover = function () {
        $(this).popover('hide');
    };   
function num1(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ', ');
}
       $('.numberhit').popover({
        content:  function () {
           return num1($(this).val());
        },
        placement: 'top',
        trigger: 'manual'
    })
    .keyup(showPopover)
    .blur(hidePopover)
    .hover(showPopover,hidePopover);

});
</script>

