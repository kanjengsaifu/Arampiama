<?php
 include "../../config/koneksi.php";
 $text=$_GET['text'];
if(isset($_GET['status'])){
		$status = $_GET['status'];
		if ($status == "edit"){
	 $tampil=mysql_query("SELECT * FROM `trans_lpb` where id_supplier =$text") ;
	    $no=1;
	    if (mysql_num_rows($tampil) > 0 ){
	    	 while ($r=mysql_fetch_array($tampil)){
				echo '
				 <a> <tr class="btn btn-default" href="#" role="button" onclick="nilaipo(\''.$r['id_lpb'].'\')">
				<td>
				<span>'.$no.'</span></td>
				<td >'.$r['id_lpb'].'</td>
				<td>'.$r['no_nota_supplier'].'</td></tr></a>';
				$no++;}
			} else { echo "<tr> <td colspan='3'> <p>Tidak Ada LPB</p></td></tr>";}
	    	}

    // if ---- isset status
} else {
	if (empty($_GET['text'])){
		 echo "<tr> <td colspan='2'> <p>Tidak Ada LPB</p></td></tr>";
		} else {
    $tampil=mysql_query("SELECT * FROM `trans_lpb` where id_supplier =$text  AND status_trans ='1' ") ;
    $no=1;
    if ((mysql_num_rows($tampil)) > 0) {
    	 while ($r=mysql_fetch_array($tampil)){
			echo '
			 <tr onclick="nilaipo(\''.$r['id_lpb'].'#'.$r['no_expedisi'].'#'.$r['no_nota_supplier'].'\')">
			<td>
			<span>'.$no.'</span></td>
			<td >'.$r['id_lpb'].'</td>
			<td>'.$r['no_nota_supplier'].'</td></tr>';
			$no++;}
		} else { echo "<tr> <td colspan='3'> <p>Tidak Ada LPB</p></td></tr>";}
	}
    	}
?>
