<?php
 include "../../config/koneksi.php";
$nmr_so=$_GET['text'];
$tampiltable=mysql_query("SELECT *,sum(qty_diterima) as diterima FROM `trans_lkb_detail` n,barang b,trans_sales_order_detail so,trans_sales_order p WHERE p.id_sales_order=so.id_sales_order and so.id=n.kode_barang_so and n.id_barang=b.id_barang and so.id_sales_order = '$nmr_so'  group by kode_barang_so order by kode_barang_so
");
$r = mysql_fetch_array($tampiltable);
  echo '
  <tr id="productall">
  <td colspan="7"></td>
    <td colspan="3" style="text-align:right;" ><p><b>ToTal All SUb </b></p></td>
    <td colspan="2"  ><input name="alltotal" type="text" class="hitung2" id="total" value="'.$r['alltotal'].'" readonly></td>
  </tr>
  <tr>
  <td colspan="7"></td>
    <td colspan="3" style="text-align:right;"><p> Disc (%) <input name="alldiscpersen" type="text" id="persendisc" value='.$r['discper'].' style="width:2em;" > | (Rp) </p></td>
    <td colspan="2" style="nowrap:nowrap;">'; 
echo '
<div class="input-group">
     <input name="alldiscnominal" type="text" id="totaldisc" value='.$r['disc'].'  class="hitung2 form-control" >
          <span class="input-group-btn">
              <button class="btn btn-warning hitung" id="carisuppliernull"  type="button" style="padding:0px 12px;" title="kosongkan" onclick="$(\'#totaldisc\').val(\'0\');$(\'#persendisc\').val(\'\');">( )</button>
          </span>
</div>';
    echo '</td>
  </tr>
  <tr>
  <td colspan="7"></td>
    <td colspan="3" style="text-align:right;"><p> Ppn (%) <input name="allppnpersen" type="text" id="persenppn" value='.$r['ppnper'].' style="width:2em;"> | (Rp) </p></td>
    <td colspan="2"  style="nowrap:nowrap;">'; 
echo'
    <div class="input-group">
     <input name="allppnnominal" type="text" id="totalppn" value='.$r['ppn'].'  class="hitung2 form-control" >
          <span class="input-group-btn">
              <button class="btn btn-warning hitung" id="carisuppliernull"  type="button" style="padding:0px 12px;" title="kosongkan" onclick="$(\'#totalppn\').val(\'0\');$(\'#persenppn\').val(\'\');">( )</button>
          </span>
</div>';
    echo '</td>
  </tr>
  <tr>
  <td colspan="7"></td>
    <td colspan="3" style="text-align:right;"><b>Grand total </b></td>
    <td colspan="2"><b><input name="grandtotal" type="text" id="grandtotal" value='.$r['grand_total'].'  readonly="readonly" ></b></td>
  </tr>
';

?>