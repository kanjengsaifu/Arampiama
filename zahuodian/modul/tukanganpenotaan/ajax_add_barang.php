<?php
 include "../../config/koneksi.php";
  include "../../lib/input.php";
   $id_supplier = $_POST['id_supplier'];
   $id_barang = $_POST['id_barang'];
   $noz=$_POST['no'];
   $query="Select *
   from barang a,stok_tukang b where a.id_barang=b.id_barang and b.id_supplier='".$id_supplier."' 
   and stok_tukang!='0' and a.is_void='0' and b.id_barang='".$id_barang."'";
   $result=mysql_query($query);
   $rst=mysql_fetch_array($result);
echo '  <tr >
    <td>'.$rst['kode_barang'].' <br> '.$rst['nama_barang'].'
     <input type="hidden" name="id_barang[]" value="'.$rst['id_barang'].'" id="id_barang-'.$noz.'"  readonly />
    </td>
     <td>'.$rst['stok_tukang'].' '.$rst['satuan1'].' 
        <input type="hidden" name="stok_tukang[]" value="'.$rst['stok_tukang'].'" id="stok_tukang-'.$noz.'"  readonly />
    </td>
    <td> '.format_rupiah($rst['harga']).'
    </td>
  <td>
      <select  class="form-control NTT" id="jenis_satuan-'.$noz.'" name="jenis_satuan[]">';
      for ($i=5; $i >= 1 ; $i--) { 
        $val= "satuan".$i;
        $val_kali= "kali".$i;
        $val_harga= "harga_sat".$i;
        if ($rst[$val]!=""){
          if ($i==1){
            echo " <option value='".$rst[$val_harga].'-'.$rst[$val].'-'.$rst[$val_kali]."' selected >".$rst[$val].' ('.$rst[$val_kali].')'."</option>";
          }else
            echo " <option value='".$rst[$val_harga].'-'.$rst[$val].'-'.$rst[$val_kali]."' >".$rst[$val].' ('.$rst[$val_kali].')'."</option>";
        }
      }
    echo'
    
      </select>
      <input name="qty_satuan[]" id="qty_satuan-'.$noz.'" value="'.$rst['kali1'].'" class="NTT"  type="hidden" readonly="readonly" />
       <input  class="form-control NTT form-control  numberhit" type="text" name="convert[]" id="convert-'.$noz.'"  readonly="readonly" />
    </td>
     <td>
  <input type="text" name="harga[]"  value="'.(floor($rst['harga'])).'" id="harga_NTT-'.$noz.'"  class="NTT totalNTT  form-control numberhit"  />
     </td>
 <td>
     
       <input type="text" name="jumlah[]" id="jumlah_NTT-'.$noz.'"  class="NTT form-control numberhit" />
    </td>
<td colspan="3"><input type="text" class="sub_total  form-control numberhit" name="total[]" id="total_NTT-'.$noz.'"   readonly/></td>';?>
<td>  <div  class="btn btn-danger" name="del_item" onclick="deleteRow(this)"><span class="glyphicon glyphicon-trash"></span></div></td>
<?php
    echo'</tr>
';

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