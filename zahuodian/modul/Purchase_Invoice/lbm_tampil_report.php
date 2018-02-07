<?php
include "koneksi.php";
$text=$_GET['text'];
 $query="SELECT * FROM trans_pur_order o, supplier s,trans_lpr t where s.id_supplier=t.id_supplier and t.id_pur_order=o.id_pur_order and status_pay='$text';";
  $tampil = mysql_query($query);
  $no = 1;
  while ($r=mysql_fetch_array($tampil)){
  echo "
            <tr>
           <td>$no</td>
            <td>$r[id_lpr]</td>
            <td>$r[id_pur_order]</td>
            <td>$r[nama_supplier]</td>
            <td>$r[grand_total]</td>
            <td>$r[tgl_update]</td>
  <td>";
  if ($text=='0'){
echo "
   <a href='?module=purchaseinvoice&act=tambah&id=$r[id_lpr]'class='btn btn-success' ><span class='glyphicon glyphicon-ok' aria-hidden='true'></span></a>
  </td></tr>";
  }
  else{
echo "   <a href='?module=purchaseinvoice&act=tambah&id=$r[id_lpr]'class='btn btn-warning' ><span class='glyphicon glyphicon-edit' aria-hidden='true'></span></a>
  </td></tr>";
  }
  
$no++;
}
?>