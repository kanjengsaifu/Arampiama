<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";

 $no =$_GET['nor'];
echo '<tr>
    <td>
       '.$no.'
    </td>
    <td>
     <input type="hidden" name="id_barang[]" value="" id="id_barang-'.$no.'"  readonly />
     <input style="text-align:center;" type="text" name="kode_barang[]"   id="kode_barang-'.$no.'"  class="form-control" onclick="detail('.$no.')" readonly>
    </td>
    <td>
       <input type="text" name="nama_barang[]" value="" id="nama_barang-'.$no.'"/>
    </td>
   <td>
       <input type="text" name="jumlah[]" value=""  id="jumlah-'.$no.'" class="numberhit hitung" />
    </td>
    <td>
       <input type="text" name="hpp[]" value="" id="hpp-'.$no.'"  class="numberhit hitung" />
    </td>
    <td>
       <input type="text" name="harga[]" value=""  id="harga-'.$no.'" class="harga numberhit hitung" />
    </td>          
        <td>
            <a  class="btn btn-warning" name="del_item" onclick="deleteRow(this)"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
</tr>';
echo "<script type='text/javascript'>
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
</script>";
echo "<script type='text/javascript'>
      $(function() {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
      });
</script>";
?>