<?php 
 include "../../config/koneksi.php";
 include "../../lib/input.php";
$data=$_POST['data'];
  $query="select * from (
(SELECT alamat_customer as alamat,`id_invoice`,`grand_total`,'Customer' as keterangan, `nama_customer` as nama,'' as `no_expedisi`,`no_nota` FROM `trans_sales_invoice` t,`customer` c WHERE c.id_customer=t.id_customer and t.`is_void`=0 and `status_lunas`='0'  )
union all 
(SELECT alamat_supplier as alamat,`id_invoice`,`grand_total`,'Supplier' as keterangan,`nama_supplier` as nama,`no_expedisi`,`no_nota` FROM `trans_invoice` t,`supplier` s WHERE t.id_supplier=s.id_supplier and t.`is_void`= 0 and `status_lunas`='0' )) as table_data
order by case 
    when nama LIKE '$data%' then 1 
    when nama LIKE '%$data%'  then 2 
    when id_invoice LIKE '$data%'  then 3
    when id_invoice LIKE '%$data%'  then 4 
    when no_nota LIKE '$data%'  then 5
    when no_nota LIKE '%$data%'  then 6 
end desc
  ";
  $result = mysql_query($query);
  while ($r=mysql_fetch_array($result)) :
      ?>
    <tr onclick="input_nominal_dibayar('<?= $r['id_invoice']; ?>','<?= $r['nama']; ?>','<?= $r['alamat']; ?>','<?= format_jumlah($r['grand_total']); ?>')"><td><?= $r['keterangan']; ?></td><td><?= $r['nama']; ?><td><?= $r['id_invoice']; ?></td><td><?= $r['grand_total']; ?></td></td><td><?= $r['no_expedisi']; ?></td><td><?= $r['no_nota']; ?></td></tr>
    <?php  endwhile; ?>