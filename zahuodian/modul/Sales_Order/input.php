  <?php
 include "../../config/koneksi.php";
 $filter = $_GET['kd'];
  $noz= $_GET['nox'];
$cuss= $_GET['cuss'];
echo $cuss;
 $sup= mysql_query("SELECT * FROM barang WHERE id_barang = '$filter' ");
  $data = mysql_fetch_array($sup)
   ?>
  <tr class="inputtable">
  <input type=hidden name="id_so_barang[]" value="<?php echo $data['id_barang']?>"  id="id_so_barang-<?php echo $noz; ?>" >
    <input type=hidden name="id[]" value="" id="id-<?php echo $noz; ?>" >
	<td colspan="2"><?php echo $data['kode_barang']?> <br> <?php echo $data['nama_barang']?>
   <input class="form-control" type="hidden" name="nama_barang[]" value="<?php echo $data['nama_barang']?>" id="nama_barang-<?php echo $noz; ?>"  disabled />
	     <input class=""  type="hidden" name="kode_barang[]" value="<?php echo $data['kode_barang']?>"   id="kode_barang-<?php echo $noz; ?>"  disabled />
	 </td>

   </td>
     <td>
      <select style="width:100px;" class="form-control hitung" id="jenis_satuan-<?php echo $noz; ?>" name="jenis_satuan[]">
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
          else
             echo " <option value='".$data[$val_harga].'-'.$data[$val].'-'.$data[$val_kali]."' >".$data[$val].' ('.$data[$val_kali].')'."</option>";
        }
      }
      ?>    
      </select>
    </td>

	 <td>
	       <!--<input type="text" name="harga_sat1[]" data-toggle='modal' data-target='#myModal-<?php //echo $noz; ?>' value="<?php// echo $data['harga_sat1']?>"  id="harga-<?php //echo $noz; ?>"  class="hitung" />-->
               <input type="text" name="harga_sat1[]" ondblclick="$('#myModal-<?php echo $noz; ?>').modal('show')" value="<?php echo $data['harga_sat1']?>"  id="harga-<?php echo $noz; ?>"  class="hitung numberhit form-control" title="double klik untuk mengetahu harga terakhir">
<!-- Dialog Modal -->
<div class='modal fade' id='myModal-<?php echo $noz; ?>' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
        <h4 class='modal-title' id='myModalLabel'>History Tiga Transaksi Terakhir</h4>
      </div>
      <div class='modal-body'>
       <table class='table table-hover'>
       <th>Nama Customer</th>
        <th>HPP</th>
         <th>Tanggal Transaksi</th>
       <?php
     $history3t=mysql_query("SELECT   nama_customer,(total/qty_si_convert) as hpp , `tgl_si` FROM 
`trans_sales_invoice_detail` tid,trans_sales_invoice ti,customer s WHERE ti.id_customer = '$cuss' and tid.id_invoice=ti.id_invoice and s.id_customer=ti.id_customer and id_barang='$data[id_barang]' order by tgl_si desc LIMIT 3");
       while ($r=mysql_fetch_array($history3t)) {
         echo "<tr>
         <td>$r[nama_customer]</td>
         <td>$r[hpp]</td>
         <td>$r[tgl_si]</td>
         </tr>";
       }

       ?>


</table></div>
      <div class='modal-footer'>
      
      </div>
    </div>
  </div>
</div>
	  
	 <td><input  type="text" name="jumlah[]" id="satuan-<?php echo $noz; ?>"  class="hitung form-control numberhit" /></td>
            <td><input style="width:50px;" type="text" name="disc1[]"  id="disc1_barang-<?php echo $noz; ?>"  class="hitung numberhit" /></td>
            <td><input style="width:50px;" type="text" name="disc2[]" id="disc2_barang-<?php echo $noz; ?>"  class="hitung numberhit" /></td>
            <td><input style="width:50px;" type="text" name="disc3[]" id="disc3_barang-<?php echo $noz; ?>" class="hitung numberhit" /></td>
            <td><input style="width:50px;" type="text" name="disc4[]" id="disc4_barang-<?php echo $noz; ?>" class="hitung numberhit"  /></td>
            <td><input  type="text" name="disc5[]" id="disc5_barang-<?php echo $noz; ?>" class="hitung numberhit form-control"  /></td>
            <td><input type="text" name="total[]" id="total-<?php echo $noz; ?>"  class="total numberhit" readonly="readonly" /></td>
  <td>
    <div class="btn btn-primary btn-sm" type="button" id="search" data-toggle="modal" data-target="#search-md"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></div>  </td><td> <div  class="btn btn-danger btn-sm" name="del_item" onclick="deleteRow(this)"><span class='glyphicon glyphicon-trash'></span></div>
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
