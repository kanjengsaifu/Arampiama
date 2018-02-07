  <?php
 include "../../config/koneksi.php";
 $filter = $_GET['kd'];
  $noz= $_GET['nox'];
  $supp= $_GET['supp'];
  echo $supp;

 $sup= mysql_query("SELECT * FROM barang WHERE id_barang = '$filter' ");
  $data = mysql_fetch_array($sup)
   ?>
  <tr class=" numberhit inputtable">
  <input type=hidden name="id_po_barang[]" value="<?php echo $data['id_barang']?>"  id="id_po_barang-<?php echo $noz; ?>" >
    <input type=hidden name="id[]" value="" id="id-<?php echo $noz; ?>" >
	<td>
	     <input type="hidden" name="kode_barang[]" value="<?php echo $data['kode_barang']?>"   id="kode_barang-<?php echo $noz; ?>"  disabled />
<?= $data['kode_barang'].'<br>'.$data['nama_barang']?>
	     <input type="hidden" name="nama_barang[]" value="<?php echo $data['nama_barang']?>" id="nama_barang-<?php echo $noz; ?>"  disabled />
	  </td>
                <td> <select class=" numberhit form-control hitung2" id="gudang-<?php echo $noz; ?>" name="gudang[]" required><?php
$tampil_lbr=mysql_query("SELECT * FROM gudang g,stok s where s.id_gudang=g.id_gudang and stok_sekarang <>0 and id_barang='$data[id_barang]' and is_void=0 ");
            echo "<option value='0-0' selected>- Gudang -</option>";
         while($w=mysql_fetch_array($tampil_lbr)){
              echo "<option value='".$w[id_gudang].'-'.$w[stok_sekarang]."']>$w[nama_gudang] ($w[stok_sekarang])</option>";
          }
          ?>
</select>
<input class=" numberhit form-control hitung hitung2" type="text" name="stok_sekarang[]" id="stok_sekarang-<?php echo $noz; ?>"  readonly="readonly" /></td>
                 <td>
      <select  class=" numberhit form-control hitung" id="jenis_satuan-<?php echo $noz; ?>" name="jenis_satuan[]">
      <?php
      for ($i=1; $i <= 5 ; $i++) { 
        $val= "satuan".$i;
        $val_kali= "kali".$i;
        $val_harga= "harga_sat".$i;
        if ($data[$val]!=""){
          echo $data[$val];
          if ($i==1){
             echo " <option value='".$data[$val_harga].'-'.$data[$val].'-'.$data[$val_kali]."' selected>".$data[$val].' ('.$data[$val_kali].')'."</option>";
          }
         
        }
      }
      ?>
      </select>
    </td>

    <td>
       <input type="text" name="harga_sat1[]"  value="<?php echo $data['harga_sat1']?>"  id="harga-<?php echo $noz; ?>"  class=" numberhit hitung" />

    </td>
	   <td><input type="text" name="jumlah[]" id="satuan-<?php echo $noz; ?>"  class=" numberhit hitung stock-<?php echo $noz; ?>" /></td>
   <td><input type="text" name="disc1[]"  id="disc1_barang-<?php echo $noz; ?>"  class="hitung form-control  numberhit" /></td>
            <td><input type="text" name="disc2[]" id="disc2_barang-<?php echo $noz; ?>"  class="hitung form-control numberhit " /></td>
            <td><input type="text" name="disc3[]" id="disc3_barang-<?php echo $noz; ?>" class="hitung form-control  numberhit" /></td>
            <td><input type="text" name="disc4[]" id="disc4_barang-<?php echo $noz; ?>" class="hitung form-control  numberhit"  /></td>
            <td><input type="text" name="disc5[]" id="disc5_barang-<?php echo $noz; ?>" class="hitung form-control  numberhit"  /></td>
 <td><input type="text" name="total[]" id="total-<?php echo $noz; ?>"  class=" numberhit total" readonly="readonly" /></td>
  <td>
    <div class="btn-xs btn btn-primary" type="button" id="search" data-toggle="modal" data-target="#search-md"><span class=" numberhit glyphicon glyphicon-plus" aria-hidden="true"></span></div>
  <div  class="btn-xs btn btn-danger" name="del_item" onclick="deleteRow(this)"><span class='glyphicon glyphicon-trash'></span></div>
  </td>
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
