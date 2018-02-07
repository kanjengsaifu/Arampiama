  <?php
 include "../../config/koneksi.php";
 $filter = $_GET['kd'];
  //$gudang = $_GET['kd2'];
  $noz= $_GET['nox'];
  $gdg2= $_GET['gdg2'];

  if (strpos($filter,"THP") !== FALSE) {
    $select_trans = mysql_query("SELECT * FROM hitung_hpp_detail d JOIN hitung_hpp_header h ON d.no_hitung_hpp = h.no_hitung_hpp WHERE d.no_hitung_hpp = '$filter'");
    $i = 1;
    while ($trans = mysql_fetch_array($select_trans)) {
      if ($i==1) {
        echo "
        <tr>
          <input type='hidden' name='jenis[]' id='jenis-$noz' value='THP'>
          <input type='hidden' name='id_trans[]' id='id_trans-$noz' value='$trans[id_hitung_hpp_heder]' class='form-control'>
          <td><input type='text' name='no_trans[]' id='no_trans-$noz' value='$trans[no_hitung_hpp]' class='form-control' readonly /></td>
          <td><input type='text' name='nama[]' id='nama-$noz' value='$trans[nama_barang]' class='form-control' readonly /></td>
          <td><input type='text' name='harga[]' id='harga-$noz' value='$trans[harga_barang]' class='form-control numberhit' readonly /></td>
          <td><input type='text' name='jumlah[]' id='jumlah-$noz' value='$trans[jumlah_barang]' class='form-control numberhit' readonly /></td>
          <td><input type='text' name='total[]' id='total-$noz' value='$trans[total_biaya]' class='form-control hitung numberhit' readonly /> </td>
        </tr>
        ";
      } else {
        echo "
        <tr>
          <input type='hidden' name='jenis[]' id='jenis-$noz' value='THP'>
          <input type='hidden' name='id_trans[]' id='id_trans-$noz' value='$trans[id_hitung_hpp_heder]'>
          <td><input type='text' name='no_trans[]' id='no_trans-$noz' value='$trans[no_hitung_hpp]' class='form-control' readonly /></td>
          <td><input type='text' name='nama[]' id='nama-$noz' value='$trans[nama_barang]' class='form-control' readonly /></td>
          <td><input type='text' name='harga[]' id='harga-$noz' value='$trans[harga_barang]' class='form-control numberhit' readonly /></td>
          <td><input type='text' name='jumlah[]' id='jumlah-$noz' value='$trans[jumlah_barang]' class='form-control numberhit' readonly /></td>
          <td><input type='text' name='total[]' id='total-$noz' value='-$trans[total_biaya]' class='form-control hitung numberhit' readonly /> </td>
        </tr>
        ";
      }
      $i++;
      $noz++;
    }
  } else if (strpos($filter,"TBT") !== FALSE) {
    $select_trans = mysql_query("SELECT * FROM bon_tukang WHERE no_bon_tukang = '$filter'");
    while ($trans = mysql_fetch_array($select_trans)) {
      echo "
      <tr>
        <input type='hidden' name='jenis[]' id='jenis-$noz' value='TBT'>
        <input type='hidden' name='id_trans[]' id='id_trans-$noz' value='$trans[id_bon_tukang]'>
        <td><input type='text' name='no_trans[]' id='no_trans-$noz' value='$trans[no_bon_tukang]' class='form-control' readonly /></td>
        <td><input type='text' name='nama[]' id='nama-$noz' value='Pembayaran Dimuka' class='form-control' readonly /></td>
        <td><input type='text' name='harga[]' id='harga-$noz' value='$trans[nominal]' class='form-control numberhit' readonly /></td>
        <td><input type='text' name='jumlah[]' id='jumlah-$noz' value='1' class='form-control numberhit' readonly /></td>
        <td><input type='text' name='total[]' id='total-$noz' value='$trans[nominal]' class='form-control hitung numberhit' readonly /> </td>
      </tr>
      ";
    }
  }
?>
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