<?php
 include "../../config/koneksi.php";
  include "../../lib/input.php";
   $id_invoice= $_POST['id_invoice'];
   $id_barang = $_POST['id_barang'];
   $jenis_retur=$_POST['jenis_retur'];
   $noz=$_POST['no'];
   $query="SELECT *,a.`id_invoice`,no_nota,concat(qty_si,' ',qty_si_satuan) as Qty,round(total/qty_si_convert) as HargaPerUnit
  ,qty_si_convert  FROM `trans_sales_invoice_detail` a,`trans_sales_invoice` b, barang c
   WHERE a.`id_invoice`=b.`id_invoice` and a.id_barang=c.id_barang and a.id_invoice='$id_invoice' and c.id_barang='$id_barang'";
   echo $query;
   $result=mysql_query($query);
   $rst=mysql_fetch_array($result);
echo '  <tr >
    <td>'.$rst['id_invoice'].' <br>'.$rst['no_nota'].' 
              <input type="hidden" name="id_invoice[]" value="'.$rst['id_invoice'].'"  readonly  />
              <input type="hidden" name="no_nota[]" value="'.$rst['no_nota'].'"   readonly  /> 
    </td>
    <td>'.$rst['nama_barang'].'
     <input type="text" name="id_barang[]" value="'.$rst['id_barang'].'" id="id_barang-'.$noz.'"  readonly />
    </td>
     <td>'.$rst['Qty'].'
	<input type="hidden" name="qty_si[]" value="'.$rst['qty_si'].'"  id="qty_si-'.$noz.'" readonly class="hitung" />
	<input type="hidden" name="qty_si_convert[]" value="'.$rst['qty_si_convert'].'"  id="qty_si_convert-'.$noz.'" readonly class="hitung" /> 
	<input type="hidden" name="qty_si_satuan[]" value="'.$rst['qty_si_satuan'].'"  id="qty_si_satuan-'.$noz.'" readonly class="hitung" /> 
    </td>
    <td> '.format_rupiah($rst['HargaPerUnit']).'
       <input type="hidden" name="harga_si[]" value="'.($rst['total']/$rst['qty_si_convert']).'"  id="harga_si-'.$noz.'"  class="hitung" readonly />
    </td>
 <td><select id="gudang_lbm-'.$noz.'" name="id_gudang[]" class="form-control" required>';
$tampil_lbr=mysql_query("SELECT * FROM gudang where is_void=0 ");
         while($w=mysql_fetch_array($tampil_lbr)){
              echo "<option value=$w[id_gudang]>$w[nama_gudang]</option>";
          }
echo '</select></td>
 <td>
      <select  class="form-control rjb" id="jenis_satuan-'.$noz.'" name="jenis_satuan[]">';
      for ($i=5; $i >= 1 ; $i--) { 
        $val= "satuan".$i;
        $val_kali= "kali".$i;
        $val_harga= "harga_sat".$i;
        if ($rst[$val]!=""){
          if ($i==1){
            echo " <option value='".$rst[$val_harga].'-'.$rst[$val].'-'.$rst[$val_kali]."' selected >".$rst[$val].' ('.$rst[$val_kali].')'."</option>";
          }
          else
            echo " <option value='".$rst[$val_harga].'-'.$rst[$val].'-'.$rst[$val_kali]."' >".$rst[$val].' ('.$rst[$val_kali].')'."</option>";
        
        }
      }
    echo'
    
      </select>
      <input  class="form-control rjb  form-control numberhit" type="hidden" value="'.$rst['kali1'].'" name="qty_satuan[]" id="qty_satuan-'.$noz.'"  readonly="readonly" />
       <input  class="form-control rjb form-control  numberhit" type="text" name="qty_convert[]" id="qty_convert-'.$noz.'"  readonly="readonly" />
    </td>
    <td style="border: 2px dotted #ddd;"><input type="text" name="jml-rjb[]" id="jml_rjb-'.$noz.'"  class="rjb form-control numberhit" /></td>
 <td style="border: 2px dotted #ddd;">
       <input type="text" name="harga-rjb[]"  value="'.(floor($rst['HargaPerUnit'])).'" id="harga_rjb-'.$noz.'"  class="rjb totalrjb  form-control numberhit"  />
    </td>
<td style="border: 2px dotted #ddd;"><input type="text" class="sub_total  form-control numberhit" name="total-rjb[]" id="total_rjb-'.$noz.'"   readonly/></td>';?>
<td>  <div  class="btn btn-danger" name="del_item" onclick="deleteRow(this)"><span class="glyphicon glyphicon-trash"></span></div></td>
<?php
    echo'</tr>
';

?>