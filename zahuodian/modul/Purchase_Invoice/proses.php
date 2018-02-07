<?php
include "koneksi.php";
$text=$_GET['text'];
    $tampil=mysql_query("SELECT * FROM `trans_pur_order` where id_supplier =$text") ;
    $no=1;
    if (isset($tampil)){
    	 while ($r=mysql_fetch_array($tampil)){
			echo '
			 <tr onclick="nilaipo(\''.$r['id_pur_order'].'\')">
			<td>
			<span>'.$no.'</span></td>
			<td >'.$r['id_pur_order'].'</td></tr>';
			$no++;}}
    		echo "Tidak Ada Purchase Order";
?>
