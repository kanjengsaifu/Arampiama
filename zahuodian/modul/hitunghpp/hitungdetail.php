<?php
 include "../../config/koneksi.php";
  include "../../lib/input.php";
$id_d=$_GET['text'];
$noz= $_GET['nox'];
$tampiltable=mysql_query("SELECT * FROM `trans_terima_tukang_detail` t, barang b WHERE t.id_barang = b.id_barang AND id_trans_terima_tukang_detail = '$id_d' ");
$noz = 1;
$no=1;
while ($rst = mysql_fetch_array($tampiltable)){


  echo '
  <tr class="inputtable">
    <td>
       '.$no.'
    </td>
    <td>
     <input type="hidden" name="id_barang[]" value="" id="id_barang-'.$noz.'"  readonly />
     <input type="hidden" name="kode_barang[]" value="'.$rst['kode_barang'].'" id="kode_barang-'.$noz.'"  readonly />
       '.$rst['kode_barang'].'
    </td>
    <td>
       <input type="hidden" name="nama_barang[]" value="'.$rst['nama_barang'].'" id="nama_barang-'.$noz.'"  readonly />
       '.$rst['nama_barang'].'
    </td>
   <td>
       <input type="hidden" name="jumlah[]" value="'.$rst['jumlah'].'"  id="jumlah-'.$noz.'" readonly class="hitung" />
       '.($rst['jumlah']*1).' - '.$rst['satuan'].'
    </td>
    <td>
       <input type="text" name="hpp[]" value="'.($rst['biaya_tukang']/$rst['jumlah']).'" id="hpp-'.$noz.'"  class="numberhit hitung" />
    </td>
    <td>
       <input type="hidden" name="harga[]" value="'.$rst['biaya_tukang'].'"  id="harga-'.$noz.'" readonly class="harga hitung" />
       '.$rst['biaya_tukang'].'
    </td>
</tr>
';
$noz++;
$no++;
}
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



?>