<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
$nmr_so=$_GET['text'];
$noz= $_GET['nox'];
$tampiltable=mysql_query("SELECT kali,satuan,nama_barang,b.id_barang,t.id as id,kode_barang,tld.id_sales_order,(jumlah - sum(qty_diterima)) as jumlah
FROM `trans_sales_order_detail` t,barang b,trans_lkb_detail  tld
WHERE tld.kode_barang_so=t.id  and  t.id_barang=b.id_barang and tld.id_sales_order =  '$nmr_so' group by kode_barang_so");
$temp=mysql_fetch_array($tampiltable);
echo $temp['nama_barang'];
if (empty($temp)){
  $tampiltable=mysql_query("SELECT *,(kali*jumlah)  as total_convert, jumlah as total_qty FROM `trans_sales_order_detail` t,barang b   WHERE t.id_barang=b.id_barang and id_sales_order = '$nmr_so'  ");
}else{
$tampiltable=mysql_query("SELECT kali,satuan,nama_barang,b.id_barang,t.id as id,kode_barang,tld.id_sales_order,jumlah,satuan,
  (((kali*jumlah) - sum(qty_diterima_convert))/kali) as total_qty,((kali*jumlah) - sum(qty_diterima_convert)) as total_convert
FROM `trans_sales_order_detail` t,barang b,trans_lkb_detail  tld
WHERE tld.kode_barang_so=t.id  and  t.id_barang=b.id_barang and tld.id_sales_order =  '$nmr_so' group by kode_barang_so");
} $no=1;
while ($rst = mysql_fetch_array($tampiltable)){
  if ($rst['total_convert']>0) {
    # code...

  echo '
  <!--<tr class="inputtable">-->
  <tr>
    <input type=hidden name="id_lkb[]" value="'. $rst['id'].'" id="id_lkb-'.$noz.'" >
      <td>
       '.$no.'
    </td>
    <td style="text-align:left !important">
       <input type="hidden" name="kode_barang[]" value="'.$rst['kode_barang'].'"   id="kode_barang-'.$noz.'" readonly="readonly"  />
       '.$rst['kode_barang'].' - 
        <input type="hidden" name="id_barang[]" value="'.$rst['id_barang'].'"   id="id_barang-'.$noz.'"  />

       <input type="hidden" name="nama_barang[]" value="'.$rst['nama_barang'].'" id="nama_barang-'.$noz.'"  readonly />
       '.$rst['nama_barang'].'
    </td>
   <td>
       <input type="hidden" name="jumlah_diminta[]" value="'.$rst['total_qty'].'"  id="jumlahdiminta-'.$noz.'" readonly="readonly"  class="hitung form-control" />
       '.$rst['jumlah'] .' '.$rst['satuan'].'
    </td>
  
      <td id="checkjumlah"><input type="text" value="'.number_format($rst['total_qty'],2).'"  name="selisih[]" id="selisih-'.$noz.'"  class="selisih numberhit" /><br>
            <input type="hidden" value="'.$rst['kali'].'" name="qty_convert[]" id="qty_convert-'.$noz.'"  class="selisih" />
            <input type="hidden" value="'.$rst['satuan'].'" name="qty_satuan[]" id="qty_satuan-'.$noz.'"  class="selisih" />
      </td>';
  echo '<td><select id="jenis_satuan-'.$noz.'" name="jenis_satuan[]" class=" form-control" required>';
$tampil_lbr=mysql_query("SELECT * FROM barang where is_void=0 and id_barang='".$rst['id_barang']."' ");
$data=mysql_fetch_array($tampil_lbr);
            for ($i=1; $i <= 5 ; $i++) { 
        $val= "satuan".$i;
        $val_kali= "kali".$i;
        $val_harga= "harga_sat".$i;
        if ($data[$val]!=""){
          echo $data[$val];
          if ($data[$val]==$rst['satuan']){
             echo " <option value='".$data[$val_harga].'-'.$data[$val].'-'.$data[$val_kali]."' selected>".$data[$val].' ('.$data[$val_kali].')'."</option>";
          }
   
        }
      }

     echo '</select></td><td><select id="gudang_lkb-'.$noz.'" name="gudang_lkb[]" class=" form-control" required>';
$tampil_lbr=mysql_query("SELECT * FROM gudang where is_void=0 ");
         while($w=mysql_fetch_array($tampil_lbr)){
              echo "<option value=$w[id_gudang]>$w[nama_gudang]</option>";
          }
echo '</select></td>';
  echo '
    <div class="btn btn-primary" type="button" id="search" data-toggle="modal" data-target="#search-md"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></div>
';
$no++;
$noz++;
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