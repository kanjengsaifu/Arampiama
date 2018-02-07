<?php
 include "../../config/koneksi.php";
$nmr_po=$_GET['text'];
$noz= $_GET['nox'];
$tampiltable=mysql_query("SELECT *,sum(qty_diterima) as diterima FROM `trans_lpb_detail` n LEFT JOIN barang b ON(n.id_barang=b.id_barang) LEFT JOIN trans_pur_order_detail po ON(n.id_pur_order=po.id_pur_order) 
LEFT JOIN trans_lpb m ON(m.id_lpb=n.id_lpb) WHERE  n.id_lpb ='$nmr_po'  group by kode_barang_po order by kode_barang_po
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
    <td colspan="2" style="nowrap:nowrap;"><input name="alldiscnominal" type="text" id="totaldisc" value='.$r['disc'].'  class="hitung2" ></td>
  </tr>
  <tr>
  <td colspan="7"></td>
    <td colspan="3" style="text-align:right;"><p> Ppn (%) <input name="allppnpersen" type="text" id="persenppn" value='.$r['ppnper'].' style="width:2em;"> | (Rp) </p></td>
    <td colspan="2"  style="nowrap:nowrap;"><input name="allppnnominal" type="text" id="totalppn" value='.$r['ppn'].'  class="hitung2" ></td>
  </tr>
  <tr>
  <td colspan="7"></td>
    <td colspan="3" style="text-align:right;"><b>Grand total </b></td>
    <td colspan="2"><b><input name="grandtotal" type="text" id="grandtotal" value='.$r['grand_total'].'  readonly="readonly" ></b></td>
  </tr>
';





?>