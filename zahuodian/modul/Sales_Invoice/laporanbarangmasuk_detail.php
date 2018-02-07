<?php
 include "../../config/koneksi.php";
   include "../../lib/input.php";
$nmr_so=$_GET['text'];
$noz= $_GET['nox'];
$tampiltable=mysql_query("

SELECT b.id_barang,nama_barang,jumlah,kali,satuan,harga,t.qty_diterima,t.qty_diterima_satuan,total,qty_convert ,qty_diterima_convert as qty,qty_satuan,disc1,disc2,disc4,disc3,disc5  FROM trans_sales_order_detail tpod,trans_lkb_detail t,barang b
where b.id_barang=t.id_barang and tpod.id=kode_barang_so and id_lkb='$nmr_so' ");
$noz = 0;
$no=1;
$rst_jumlah = mysql_num_rows($tampiltable);
echo $rst_jumlah;
while ($rst = mysql_fetch_array($tampiltable)){
  echo '  <tr class="inputtable">
    <td>
       '.$no.'
    </td>
    <td>
            <input type="hidden" name="id_barang['.$noz.']" value="'.$rst['id_barang'].'" id="nama_barang-'.$noz.'"  readonly />
            <input style="width:200px;"  type="hidden" name="nama_barang['.$noz.']" value="'.$rst['nama_barang'].'" id="nama_barang-'.$noz.'"  readonly />
            '.$rst['nama_barang'].'
    </td>
   <td >
       <input type="hidden" name="qty_so['.$noz.']" value="'.$rst['jumlah'].'"  id="qty-'.$noz.'" readonly class="hitung" />
       '.$rst['jumlah'].' '.$rst['satuan'].''.'</br> ['.$rst['qty_convert'].']
       <input type="hidden" name="qty_so_satuan['.$noz.']" value="'.$rst['satuan'].'"  id="qty_so_satuan-'.$noz.'" readonly class="hitung" />
    </td>
    <td>
       <input style="width:70px;" type="hidden" name="harga_so['.$noz.']" value="'.$rst['harga'].'"  id="harga_so-'.$noz.'" readonly class="hitung" />
       '.format_ribuan($rst['harga']).'
    </td>
     <td>
       <input type="hidden" name="total_so['.$noz.']" value="'.$rst['total'].'"  id="total_so-'.$noz.'" readonly class="hitung" />
       '.format_ribuan($rst['total']).'
    </td>
     <td>
       <input type="hidden" name="qty_si['.$noz.']" value="'.$rst['qty_diterima'].'"  id="qty_si-'.$noz.'" readonly class="hitung" />
        '.$rst['qty_diterima'].' - '.$rst['qty_diterima_satuan'].'
    </td>
       <input type="hidden" name="qty_si_satuan['.$noz.']" value="'.$rst['qty_satuan'].'"  id="qty_si_satuan-'.$noz.'" readonly class="hitung" />
       '.$rst['qty_satuan'].'

       <input type="hidden" name="qty_si_convert['.$noz.']" value="'.$rst['qty'].'" id="qty_si_convert-'.$noz.'" readonly class="hitung" />
     <td>
       <input type="text" name="harga_si['.$noz.']" value="'.$rst['harga'].'"  id="harga_si-'.$noz.'"  class="hitung numberhit" />
    </td>
    <td><input  type="text" name="disc1['.$noz.']"  id="disc1_barang-'.$noz.'" value="'.$rst['disc1'].'"  class="hitung numberhit" /></td>
    <td><input  type="text" name="disc2['.$noz.']" id="disc2_barang-'.$noz.'" value="'.$rst['disc2'].'"  class="hitung numberhit" /></td>
    <td><input  type="text" name="disc3['.$noz.']" id="disc3_barang-'.$noz.'" value="'.$rst['disc3'].'" class="hitung numberhit" /></td>
    <td><input   type="text" name="disc4['.$noz.']" id="disc4_barang-'.$noz.'" value="'.$rst['disc4'].'" class="hitung numberhit"  /></td>
     <td><input  type="text" name="disc5['.$noz.']" id="disc5_barang-'.$noz.'" value="'.$rst['disc5'].'" class="hitung numberhit"  /></td>
    <td><input type="text" name="total['.$noz.']" id="total-'.$noz.'"  class="total numberhit"  /></td>
</tr>
';
$noz++;
$no++;
}
$tampiltabletotal = mysql_query("SELECT id_sales,tpo.alltotal AS alltotal, tpo.discper AS discper, tpo.disc AS disc, tpo.ppnper AS ppnper, tpo.ppn AS ppn, tpo.grand_total AS grandtotal FROM trans_sales_order tpo LEFT JOIN trans_lkb tl ON(tl.id_sales_order=tpo.id_sales_order) 
WHERE tl.id_lkb='$nmr_so' GROUP BY tpo.id_sales_order");
$r = mysql_fetch_array($tampiltabletotal);
if ($r['discper']==0) {
$discper="";
}else{
  $discper=$r['discper'];
}
if ($r['ppnper']==0) {
$ppnper="";
}else{
  $ppnper=$r['ppnper'];
}
echo '
  <tr id="productall">
  <td colspan="8"></td>
    <td colspan="4" style="text-align:right;" ><p><b>ToTal All SUb </b></p></td>
    <td colspan="2"  >
    <input name="id_sales" type="hidden" value="'.$r['id_sales'].'"   readonly>
    <input name="alltotal" type="text" class="hitung2 form-control numberhit" id="total" value="" readonly></td>

<input name="alldiscpersen" type="hidden" id="persendisc" value="'.$discper.'" style="width:2em;" > |
<input name="alldiscnominal" type="hidden" id="totaldisc" value="'.$r['disc'].'"  class="hitung2 form-control numberhit" >
<input name="allppnpersen" type="hidden" id="persenppn" value="'.$ppnper.'" style="width:2em;"> | (R
<input name="allppnnominal" type="hidden" id="totalppn" value="'.$r['ppn'].'"  class="hitung2 form-control numberhit" >
<input name="grandtotal" type="hidden" id="grandtotal" value=""  readonly="readonly" class="form-control numberhit">
  </tr>


';

echo'<input type="hidden" name="noz" value="'.$noz.'" id="noz" readonly="readonly" />';
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