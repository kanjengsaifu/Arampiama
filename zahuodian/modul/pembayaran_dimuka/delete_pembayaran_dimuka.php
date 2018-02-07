<?php 
 include "../../config/koneksi.php";
 include "../../lib/input.php";
  session_start();
 $data=$_POST['data'];
 $result=mysql_query("SELECT `no_nota_pembayaran_dimuka` as dimuka,id_invoice as invoice FROM `trans_pembayaran_dimuka` WHERE `id_pembayaran_dimuka`='$data'");
 $r=mysql_fetch_array($result);
$nota_invoice=$r['invoice'];
$nota_dimuka=$r['dimuka'];
$check ="SELECT td.bukti_bayar,(`nominal`-IFNULL(sum(td.`nominal_alokasi`),0)) as sisa FROM `trans_bayarbeli_header` t left join `trans_bayarbeli_detail` td on (td.bukti_bayar=t.bukti_bayar)WHERE `status_titipan`='T' and t.`is_void`='0' and `giro_ditolak`='0' and td.bukti_bayar_dimuka= '".$nota_dimuka."' group by t.`bukti_bayar` ";
echo $check;
		$result=mysql_query($check);
		while ($c_pembayaran=mysql_fetch_array($result)) {
		if ($c_pembayaran['sisa']<0) {
                  	  input_only_log("UPDATE `trans_bayarbeli_header` SET `status_bayar`='klop' where `bukti_bayar`='".$c_pembayaran['bukti_bayar']."'");
                        }else{
		input_only_log("UPDATE `trans_bayarbeli_header` SET `status_bayar`='gantung' where `bukti_bayar`='".$c_pembayaran['bukti_bayar']."'");
                        }
		}
 $query="UPDATE `trans_pembayaran_dimuka` SET `is_void`=1,`user_update`='$_SESSION[namauser]' ,`tgl_update`= now() where `id_pembayaran_dimuka`='$data'";
 input_only_log($query);
  $query="DELETE FROM `trans_bayarbeli_detail` where  `bukti_bayar_dimuka`='$nota_dimuka'";
 input_only_log($query);

 $check ="SELECT ((`grand_total` - ifnull(sum(nominal_alokasi),0)) ) as sisa_invoice FROM `trans_invoice` t left join trans_bayarbeli_detail tbd on (tbd.nota_invoice=t.id_invoice) WHERE  t.`is_void`= 0 and `status_lunas`='0' and id_invoice='$nota_invoice' group by id_invoice";
 echo $check;
	$result=mysql_query($check);
	$c_invoice=mysql_fetch_array($result);
		if ($c_invoice['sisa_invoice']<0) {
			input_only_log("UPDATE trans_invoice SET  status_lunas = '1'  WHERE id_invoice =  '" .$nota_invoice. "'");
		}else{
		           input_only_log("UPDATE trans_invoice SET status_lunas = '0'  WHERE id_invoice =  '" .$nota_invoice. "'");
		}


 ?>