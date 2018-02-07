<?php
 include "../../config/koneksi.php";
 $text=$_GET['text'];
 echo $text;
    $tampil=mysql_query("SELECT * FROM `trans_lkb` where id_customer ='$text' AND status_trans ='1' ") ;
    $no=1;
    if (!empty($tampil)){
    	 while ($r=mysql_fetch_array($tampil)){
			echo '
			 <tr onclick="nilaiso(\''.$r['id_lkb'].'#'.$r['no_expedisi'].'#'.$r['no_nota_customer'].'\')">
			<td>
			<span>'.$no.'</span></td>
			<td >'.$r['id_lkb'].'</td>
			<td>'.$r['no_nota_customer'].'</td>
			</tr>';
			$no++;}}
			else{
    		echo "<tr><td colspan='3'><B>Tidak Ada Purchase Order</B></td></tr>";}
?>
