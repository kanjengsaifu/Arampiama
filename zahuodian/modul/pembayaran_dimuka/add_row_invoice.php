  <?php
 include "../../config/koneksi.php";
  include "../../lib/input.php";
 $filter = $_GET['kd'];
  $noz= $_GET['nox'];


 $sup= mysql_query("SELECT `id_bayarbeli`,nama_supplier,`id_akunkasperkiraan`,t.`bukti_bayar`,(`nominal`-IFNULL(sum(td.`nominal_alokasi`),0)) as sisa FROM `trans_bayarbeli_header` t left join `trans_bayarbeli_detail` td on (td.bukti_bayar=t.bukti_bayar),`supplier` s WHERE t.`id_supplier`=s.`id_supplier` and `status_titipan`='T' and t.`is_void`='0'  and `giro_ditolak`='0' and id_bayarbeli= '$filter'  group by `bukti_bayar`  having sisa > 0");
  $data = mysql_fetch_array($sup)
   ?>
<tr>
  <td><input type="hidden" id='id_akunkasperkiraan-<?= $noz ?>' name='id_akunkasperkiraan[]' value='<?= $data['id_akunkasperkiraan']  ?>' ><?= $noz ?></td>
  <td><input type="hidden" id='bukti_bayar-<?= $noz ?>' name='bukti_bayar[]' value='<?= $data['bukti_bayar']  ?>' ><?= $data['bukti_bayar'] ?></td>
  <td><input type="hidden" id='nominal-<?= $noz ?>' name='nominal[]' value='<?= $data['sisa']  ?>' ><?= format_jumlah($data['sisa']); ?></td>
  <td><input type="text" class='hitung numberhit form-control' id='nominal-alokasi-<?= $noz ?>' name='nominal-alokasi[]' ></td>
  <td>  <div  class=" btn-danger" name="del_item" onclick="deleteRow(this,<?= $noz-1 ?>)"><span class='glyphicon glyphicon-trash'></span></div></td>
</tr>
 


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
