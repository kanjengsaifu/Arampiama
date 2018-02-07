<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
   $kode = $_GET['kd'];
  $jr = $_GET['jenis_retur'];

if(isset($kode)){
  if ($jr==1) {
   echo '
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th  rowspan="2" id="tablenumber">No</th>
      <th rowspan="2" >Nama Barang</th>
      <th rowspan="2" >Qty</th>
      <th rowspan="2" >Total Harga</th>
      <th rowspan="2" >HPP per unit</th>

         <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  colspan="5"><b>RETUR</b></th>
        </tr>
        <tr>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  >Gudang</th>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  >Jumlah</th>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  >Satuan</th>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  >Harga</th>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  >Total Harga</th>
        </tr>
        </thead> 

        <tbody id="product">';
$query="select *from Trans_sales_invoice ti,Trans_sales_invoice_detail tid,Customer s,barang b, stok st, gudang gd where ti.id_invoice=tid.id_invoice and s.id_Customer=ti.id_Customer and b.id_barang=tid.id_barang and st.id_gudang=gd.id_gudang and st.id_barang=tid.id_barang and tid.id_invoice='$kode' GROUP BY  tid.id";
echo $query;
$tampiltable=mysql_query($query);
$noz = 0;
$no=1;
$rst_jumlah = mysql_num_rows($tampiltable);
  while ($rst = mysql_fetch_array($tampiltable)){
  echo '
  <tr class="inputtable">
    <td>
     <input type="hidden" name="id[]" value="'.$rst['id'].'" id="id-'.$noz.'"  readonly />
       '.$no.'
    </td>
    <td>
     <input type="hidden" name="id_barang['.$noz.']" value="'.$rst['id_barang'].'" id="nama_barang-'.$noz.'"  readonly />
       <input type="hidden" name="nama_barang['.$noz.']" value="'.$rst['nama_barang'].'" id="nama_barang-'.$noz.'"  readonly />'.$rst['nama_barang'].'
    </td>
     <td>
       <input type="hidden" name="qty_si['.$noz.']" value="'.$rst['qty_si'].'"  id="qty_si-'.$noz.'" readonly class="hitung" />'.$rst['qty_si'].'-'.$rst['qty_si_satuan'].'
         <input type="hidden" name="qty_si_convert['.$noz.']" value="'.$rst['qty_si_convert'].'"  id="qty_si_convert-'.$noz.'" readonly class="hitung" /> 
              <input type="hidden" name="qty_si_satuan['.$noz.']" value="'.$rst['qty_si_satuan'].'"  id="qty_si_satuan-'.$noz.'" readonly class="hitung" /> 
       </td>
    <td><input type="hidden" name="total['.$noz.']" id="total-'.$noz.'"  class="total" value="'.$rst['total'].'" readonly="readonly" />'.format_rupiah($rst['total']).'</td>
    <td>
       <input type="hidden" name="harga_si['.$noz.']" value="'.($rst['total']/$rst['qty_si_convert']).'"  id="harga_si-'.$noz.'"  class="hitung" readonly />'.format_rupiah($rst['total']/$rst['qty_si_convert']).'
    </td>
</td> <td><select id="gudang_lbm-'.$noz.'" name="id_gudang[]" class="form-control" required>';
$tampil_lbr=mysql_query("SELECT * FROM gudang where is_void=0 ");
         while($w=mysql_fetch_array($tampil_lbr)){
              echo "<option value=$w[id_gudang]>$w[nama_gudang]</option>";
          }
echo '</select></td>
<td style="border: 2px dotted #ddd;"><input type="text" name="jml-rjb['.$noz.']" id="jml_rjb-'.$noz.'"  class="rjb form-control numberhit" /></td>
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
 <td style="border: 2px dotted #ddd;">
       <input type="text" name="harga-rjb['.$noz.']"  value="'.(floor($rst['total']/$rst['qty_si_convert'])).'" id="harga_rjb-'.$noz.'"  class="rjb totalrjb  form-control numberhit"  />
    </td>
<td style="border: 2px dotted #ddd;"><input type="text" class="sub_total  form-control numberhit" name="total-rjb['.$noz.']" id="total_rjb-'.$noz.'"   readonly/></td>
</tr>
';
$noz++;
$no++;
}
  echo '<input type="hidden" id="noz" name="noz"  value="'.$noz.'"   />';
  $edit = mysql_query("select * from Trans_sales_invoice ti,Trans_sales_invoice_detail tid,Customer s,barang b where ti.id_invoice=tid.id_invoice and s.id_Customer=ti.id_Customer and b.id_barang=tid.id_barang and ti.id_invoice='$kode'");
    $r    = mysql_fetch_array($edit);
echo'
        </tbody>
        <tfoot>
        <tr id="productall">
    <td colspan="4" rowspan="4"> <br><button class="btn btn-success"  type="submit"  name="save" value="Save" style="float:left;margin-left:20px;">Save </button> 
          <a class="btn btn-warning" type="button" href="media.php?module=returpembelian" style="float:left;margin-left:10px;">Batal</a>
          </td>
    <td colspan="5" style="text-align:right;" ><p><b>Total Retur </b></p></td>
    <td colspan="1"  ><input name="returtotal" type="text"  class="rjb2 form-control numberhit"  id="returtotal"  readonly></td>
  </tr>

  <tr>
     <td colspan="5" style="text-align:right;"><p> Disc (%) <input name="returdiscpersen" type="text" id="returdiscpersen" value="'.$r['returdiscpersen'].'" style="width:2em;"  class="rjb2 form-control numberhit" > | (Rp) </p></td>
    <td colspan="1" style="nowrap:nowrap;"><input name="returdiscnominal" type="text" id="returdiscnominal" value="'.$r['returdiscnominal'].'"   class="rjb2 form-control numberhit"  ></td>
  </tr>
  <tr>
    <td colspan="5" style="text-align:right;"><p> Ppn (%) <input name="returppnpersen" type="text" id="returpersenppn" value="'.$r['returppnpersen'].'" style="width:2em;" class="rjb2 form-control numberhit" > | (Rp) </p></td>
    <td colspan="1"  style="nowrap:nowrap;"><input name="returppnnominal" type="text" id="returppnnominal" value="'.$r['returppnnominal'].'"   class="rjb2 form-control numberhit"  ></td>
  </tr>
  <tr>
     <td colspan="5" style="text-align:right;"><b>Grand total retur</b></td>
    <td colspan="1"><b><input name="returgrandtotal" type="text" id="grandtotalretur"  readonly="readonly"  class="rjb2 form-control numberhit" ></b></td>
  </tr>
                </tfoot>';
}elseif ($jr==2) {
    echo '
        <thead>
  <tr style="background-color:#F5F5F5;">
      <th  rowspan="2" id="tablenumber">No</th>
      <th rowspan="2" >Nama Barang</th>
      <th rowspan="2" >Qty</th>
      <th rowspan="2" >Total Harga</th>
      <th rowspan="2" >HPP per unit</th>

         <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  colspan="4"><b>RETUR</b></th>
        </tr>
        <tr>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  >Jumlah</th>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  >Satuan</th>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  >Harga</th>
        <th style="background-color:#ddd;color:#000;border: 1px solid #fff;"  >Total Harga</th>
        </tr>
        </thead> 

        <tbody id="product">';
$query="select *from Trans_sales_invoice ti,Trans_sales_invoice_detail tid,Customer s,barang b, stok st, gudang gd where ti.id_invoice=tid.id_invoice and s.id_Customer=ti.id_Customer and b.id_barang=tid.id_barang and st.id_gudang=gd.id_gudang and st.id_barang=tid.id_barang and tid.id_invoice='$kode' GROUP BY  tid.id";
echo $query;
$tampiltable=mysql_query($query);
$noz = 0;
$no=1;
$rst_jumlah = mysql_num_rows($tampiltable);
  while ($rst = mysql_fetch_array($tampiltable)){
  echo '
  <tr class="inputtable">
    <td>
     <input type="hidden" name="id[]" value="'.$rst['id'].'" id="id-'.$noz.'"  readonly />
       '.$no.'
    </td>
    <td>
     <input type="hidden" name="id_barang['.$noz.']" value="'.$rst['id_barang'].'" id="nama_barang-'.$noz.'"  readonly />
       <input type="hidden" name="nama_barang['.$noz.']" value="'.$rst['nama_barang'].'" id="nama_barang-'.$noz.'"  readonly />'.$rst['nama_barang'].'
    </td>
     <td>
       <input type="hidden" name="qty_si['.$noz.']" value="'.$rst['qty_si'].'"  id="qty_si-'.$noz.'" readonly class="hitung" />'.$rst['qty_si'].'-'.$rst['qty_si_satuan'].'
         <input type="hidden" name="qty_si_convert['.$noz.']" value="'.$rst['qty_si_convert'].'"  id="qty_si_convert-'.$noz.'" readonly class="hitung" /> 
              <input type="hidden" name="qty_si_satuan['.$noz.']" value="'.$rst['qty_si_satuan'].'"  id="qty_si_satuan-'.$noz.'" readonly class="hitung" /> 
       </td>
    <td><input type="hidden" name="total['.$noz.']" id="total-'.$noz.'"  class="total" value="'.$rst['total'].'" readonly="readonly" />'.format_rupiah($rst['total']).'</td>
    <td>
       <input type="hidden" name="harga_si['.$noz.']" value="'.($rst['total']/$rst['qty_si_convert']).'"  id="harga_si-'.$noz.'"  class="hitung" readonly />'.format_rupiah($rst['total']/$rst['qty_si_convert']).'
    </td>

<td style="border: 2px dotted #ddd;"><input type="text" name="jml-rjb['.$noz.']" id="jml_rjb-'.$noz.'"  class="rjb form-control numberhit" /></td>
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
 <td style="border: 2px dotted #ddd;">
       <input type="text" name="harga-rjb['.$noz.']"  value="'.(floor($rst['total']/$rst['qty_si_convert'])).'" id="harga_rjb-'.$noz.'"  class="rjb totalrjb  form-control numberhit"  />
    </td>
<td style="border: 2px dotted #ddd;"><input type="text" class="sub_total  form-control numberhit" name="total-rjb['.$noz.']" id="total_rjb-'.$noz.'"   readonly/></td>
</tr>
';
$noz++;
$no++;
}
  echo '<input type="hidden" id="noz" name="noz"  value="'.$noz.'"   />';
  $edit = mysql_query("select * from Trans_sales_invoice ti,Trans_sales_invoice_detail tid,Customer s,barang b where ti.id_invoice=tid.id_invoice and s.id_Customer=ti.id_Customer and b.id_barang=tid.id_barang and ti.id_invoice='$kode'");
    $r    = mysql_fetch_array($edit);
echo'
        </tbody>
        <tfoot>
        <tr id="productall">
    <td colspan="4" rowspan="4"> <br><button class="btn btn-success"  type="submit"  name="save" value="Save" style="float:left;margin-left:20px;">Save </button> 
          <a class="btn btn-warning" type="button" href="media.php?module=returpembelian" style="float:left;margin-left:10px;">Batal</a>
          </td>
    <td colspan="4" style="text-align:right;" ><p><b>Total Retur </b></p></td>
    <td colspan="1"  ><input name="returtotal" type="text"  class="rjb2 form-control numberhit"  id="returtotal"  readonly></td>
  </tr>

  <tr>
     <td colspan="4" style="text-align:right;"><p> Disc (%) <input name="returdiscpersen" type="text" id="returdiscpersen" value="'.$r['returdiscpersen'].'" style="width:2em;"  class="rjb2 form-control numberhit" > | (Rp) </p></td>
    <td colspan="1" style="nowrap:nowrap;"><input name="returdiscnominal" type="text" id="returdiscnominal" value="'.$r['returdiscnominal'].'"   class="rjb2 form-control numberhit"  ></td>
  </tr>
  <tr>
    <td colspan="4" style="text-align:right;"><p> Ppn (%) <input name="returppnpersen" type="text" id="returpersenppn" value="'.$r['returppnpersen'].'" style="width:2em;" class="rjb2 form-control numberhit" > | (Rp) </p></td>
    <td colspan="1"  style="nowrap:nowrap;"><input name="returppnnominal" type="text" id="returppnnominal" value="'.$r['returppnnominal'].'"   class="rjb2 form-control numberhit"  ></td>
  </tr>
  <tr>
     <td colspan="4" style="text-align:right;"><b>Grand total retur</b></td>
    <td colspan="1"><b><input name="returgrandtotal" type="text" id="grandtotalretur"  readonly="readonly"  class="rjb2 form-control numberhit" ></b></td>
  </tr>
                </tfoot>';
}
}
else{
	echo 'issset depan salah';
}
?>