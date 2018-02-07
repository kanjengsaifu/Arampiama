
<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
$nmr_po=$_GET['text'];
$noz= $_GET['nox'];
$tampiltable=mysql_query("SELECT kali,satuan,nama_barang,b.id_barang,t.id as id,kode_barang,tld.id_pur_order,(jumlah - sum(qty_diterima)) as jumlah
FROM `trans_pur_order_detail` t,barang b,trans_lpb_detail  tld
WHERE tld.kode_barang_po=t.id  and  t.id_barang=b.id_barang and tld.id_pur_order =  '$nmr_po' group by kode_barang_po");
$temp=mysql_fetch_array($tampiltable);
echo $temp['nama_barang'];
if (empty($temp)){
  $tampiltable=mysql_query("SELECT *,(kali*jumlah) as total_convert, jumlah as total_qty  FROM `trans_pur_order_detail` t,barang b   WHERE t.id_barang=b.id_barang and id_pur_order = '$nmr_po'  ");
  echo "aaaa";
}else{
$tampiltable=mysql_query("SELECT kali,satuan,jumlah,nama_barang,satuan1,b.id_barang,t.id as id,kode_barang,tld.id_pur_order,
  ((kali*jumlah) - sum(qty_diterima_convert)) as total_convert,
  ((kali*jumlah) - sum(qty_diterima_convert))/kali as total_qty
FROM `trans_pur_order_detail` t,barang b,trans_lpb_detail  tld
WHERE tld.kode_barang_po=t.id  and  t.id_barang=b.id_barang and tld.id_pur_order =  '$nmr_po' group by kode_barang_po having total_convert > 0");
}
 $no=1;
while ($rst = mysql_fetch_array($tampiltable)){

  echo '
  <!--<tr class="inputtable">-->
  <tr>
    <input type=hidden name="id_lbm[]" value="'. $rst['id'].'" id="id_lbm-'.$noz.'" >
      <td>
       '.$no.'
    </td>
 <td style="text-align:left !important">
       <input type="hidden" name="kode_barang[]" value="'.$rst['kode_barang'].'"   id="kode_barang-'.$noz.'" readonly="readonly"  />
       '.$rst['kode_barang'].' - 
        <input type="hidden" name="id_barang[]" value="'.$rst['id_barang'].'"   id="id_barang-'.$noz.'"  />

       <input type="hidden" name="nama_barang[]" value="'.$rst['nama_barang'].'" id="nama_barang-'.$noz.'"  disabled />
       '.$rst['nama_barang'].'
    </td>
   <td>
       <input type="hidden" name="jumlah_diminta[]" value="'.$rst['total_qty'].'"  id="jumlahdiminta-'.$noz.'" readonly="readonly"  class="hitung" />
       '.$rst['jumlah'].' '. $rst['satuan'].'
    </td>  
     <td id="checkjumlah"><input type="text" value="'.number_format($rst['total_qty'],2).'"  name="selisih[]" id="selisih-'.$noz.'"  class="selisih form-control numberhit" /></td>
     <input type="hidden" value="'.$rst['kali'].'" name="qty_convert[]" id="qty_convert-'.$noz.'"  class="selisih" />
     <input type="hidden" value="'.$rst['satuan'].'" name="qty_satuan[]" id="qty_satuan-'.$noz.'"  class="selisih" />
    <td>';
          echo '<select id="jenis_satuan-'.$noz.'" name="jenis_satuan[]" class="form-control" required>';
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
echo '</select> </td> <td><select id="gudang_lbm-'.$noz.'" name="gudang_lbm[]" class="form-control" required>';
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