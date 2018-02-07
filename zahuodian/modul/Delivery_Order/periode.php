<?php
 include "../../config/koneksi.php";
$awal=$_GET['awal'];
$akhir=$_GET['akhir'];
    $tampil=mysql_query("SELECT * FROM trans_pur_order o, customer s,trans_lkb t where s.id_customer=t.id_customer and t.id_pur_order=o.id_pur_order and t.tgl_update >'$awal' and t.tgl_update<'$akhir' ") ;
  $no = 1;
  while ($r=mysql_fetch_array($tampil)){
  echo "
            <tr>
           <td>$no</td>
            <td>$r[id_lkb]</td>
            <td>$r[id_pur_order]</td>
            <td>$r[nama_customer]</td>
            <td>$r[tgl_update]</td>
  <td>
    <a href='?module=laporankeluarbarang&act=edit&id=$r[id]' class='btn btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></a>
        <a href='modul/barang/aksi_laporankeluarbarang.php?module=laporankeluarbarang&act=hapus&id=$r[id]' class='btn btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
  </td></tr>"; 
$no++;
}
?>
