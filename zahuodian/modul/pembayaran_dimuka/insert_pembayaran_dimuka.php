<?php 
 include "../../config/koneksi.php";
 include "../../lib/input.php";
  session_start();
$tgl_trans 		=$_POST['tgl_trans'];
$nota_invoice 	=$_POST['nota_invoice'];
$id_supplier 	=$_POST['id_supplier'];
$no_nota_pembayaran_dimuka	=cari_kode();
$error ="Data - Data Ini tidak dapat tersimpan Karena Kesalahan Nominal <br>";
$counter=0;
$error_counter=0;
foreach ($_POST['id_akunkasperkiraan'] as $i => $value) {
	$check ="SELECT ((`grand_total` - ifnull(sum(nominal_alokasi),0))-".$_POST['nominal-alokasi'][$i]." ) as sisa_invoice FROM `trans_invoice` t left join trans_bayarbeli_detail tbd on (tbd.nota_invoice=t.id_invoice) WHERE  t.`is_void`= 0 and `status_lunas`='0' and id_invoice='$nota_invoice ' group by id_invoice";
	$result=mysql_query($check);
	$c_invoice=mysql_fetch_array($result);
	if ($c_invoice['sisa_invoice']>=0) {
		if ($c_invoice['sisa_invoice']==0) {
			 input_only_log("UPDATE trans_invoice SET 
                               status_lunas = '1'  WHERE id_invoice =  '" .$nota_invoice. "'");
		}
		$check ="SELECT  (`nominal`-IFNULL(sum(td.`nominal_alokasi`),0)-".$_POST['nominal-alokasi'][$i].") as sisa FROM `trans_bayarbeli_header` t left join `trans_bayarbeli_detail` td on (td.bukti_bayar=t.bukti_bayar)WHERE  `status_titipan`='T' and t.`is_void`='0' and `giro_ditolak`='0' and t.bukti_bayar= '".$_POST['bukti_bayar'][$i]."'  group by t.`bukti_bayar`";
		$result=mysql_query($check);
		$c_pembayaran=mysql_fetch_array($result);
		if ($c_pembayaran['sisa']>=0) {
	 			 $query= "INSERT INTO trans_bayarbeli_detail(
                                               bukti_bayar_dimuka, bukti_bayar,id_akunkasperkiraan_detail,nota_invoice, ket,sisa_invoice, id_supplier,nominal_alokasi,user_update)
                                                VALUES (
                                                 '".$no_nota_pembayaran_dimuka."',
                                                '".$_POST['bukti_bayar'][$i]."',
                                                '71',
                                                '".$nota_invoice."',
                                                '',
                                                '".$c_invoice['sisa_invoice']."',
                                                '".$id_supplier."',
                                                '".$_POST['nominal-alokasi'][$i]."',
                                                '".$_SESSION["namauser"]."') ";	
                                                input_only_log($query);
                                                $counter=1;
                                                if ($c_pembayaran['sisa']==0) {
                                             input_only_log("UPDATE `trans_bayarbeli_header` SET `status_bayar`='klop' where `bukti_bayar`='".$_POST['bukti_bayar'][$i]."'");
                                                }
                                             }else{
                                             	$error .=" - ".$_POST['bukti_bayar'][$i]."<br>";
                                             	$error_counter=1;
                                             }
	}else{
		$error .=" - ".$_POST['bukti_bayar'][$i]."<br>";
	}
}
$error .="Silakan Check Kembali";
if ($error_counter==1) {
	echo $error;
}else{
	echo "Data Tersimpan Sempurna";
}
if ($counter==1) {
	 $query='
INSERT INTO `trans_pembayaran_dimuka`
( `no_nota_pembayaran_dimuka`, `id_invoice`,`keterangan`,`tgl_transaksi`, `user_update`) 
VALUES 
("'.$no_nota_pembayaran_dimuka.'","'.$nota_invoice.'","","'.$tgl_trans.'","'.$_SESSION["namauser"].'")';
 input_only_log($query);
}

 function cari_kode(){
    $var = explode('-', date("Y-m-d"));
    $sql_cari="SELECT max(`no_nota_pembayaran_dimuka`) as kode FROM `trans_pembayaran_dimuka` WHERE `no_nota_pembayaran_dimuka` LIKE 'TPD/$var[0]$var[1]%' ";
    $result=mysql_query($sql_cari);
    $hasil=mysql_fetch_array($result);
    $kode = explode('/',$hasil["kode"]);
    $kode_urut=100001+$kode[2];
   return 'TPD/'.implode('', $var).'/'.substr($kode_urut,1);
}
 ?>