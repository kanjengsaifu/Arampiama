<?php
include "koneksi.php";
$text=$_GET['text'];
    $tampil=mysql_query("SELECT * FROM `trans_sales_order` where id_customer =$text") ;
    $no=1;
    if (isset($tampil)){
    	 while ($r=mysql_fetch_array($tampil)){
			echo '
			 <tr onclick="nilaiso(\''.$r['id_sales_order'].'\')">
			<td>
			<span>'.$no.'</span></td>
			<td >'.$r['id_sales_order'].'</td></tr>';
			$no++;}}
    		echo "Tidak Ada Purchase Order";
?>
