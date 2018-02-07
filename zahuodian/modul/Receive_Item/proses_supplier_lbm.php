<?php
 include "../../config/koneksi.php";
 $text=$_GET['text'];
if (!empty($text)){
	$tampil=mysql_query("SELECT * FROM `trans_pur_order` where id_supplier =$text AND status_trans ='0' and is_void=0 ") ;

    $no=1;
    if (isset($tampil)){
    	 while ($r=mysql_fetch_array($tampil)){
			echo '
			 <tr onclick="nilaipo(\''.$r['id_pur_order'].'\')">
			<td>
			<span>'.$no.'</span></td>
			<td >'.$r['id_pur_order'].'</td></tr>';
			$no++;}}
    		echo "<p>Tidak Ada Purchase Order</p>";
 }  else {
    $tampil=mysql_query("SELECT * FROM `trans_pur_order` where status_trans ='0' and is_void=0 ") ;
    $no=1;
    if (isset($tampil)){
    	 while ($r=mysql_fetch_array($tampil)){
			echo '
			 <tr onclick="nilaipo(\''.$r['id_pur_order'].'\')">
			<td>
			<span>'.$no.'</span></td>
			<td >'.$r['id_pur_order'].'</td></tr>';
			$no++;}}
    		echo "<p>Tidak Ada Purchase Order</p>";
 }

?>
