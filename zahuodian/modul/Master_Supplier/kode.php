<?php
	include "../../config/koneksi.php";
	error_reporting(E_ALL & ~E_NOTICE);
	$nama=strtoupper($_POST['nama_supplier']);
	$sql="SELECT MAX(kode_supplier) jum FROM supplier WHERE kode_supplier LIKE '$nama%'";
	//$sql="SELECT COUNT(*) jum FROM customer WHERE kode_customer LIKE '$nama%'";
	$jum_kode=mysql_fetch_array(mysql_query($sql));
	$sub_kode=substr($jum_kode['jum'], 1);
	$nourut=$sub_kode+1;
	if ($nourut<10)
	  $strurut='00'.$nourut;
	else if ($nourut<100) 
	  $strurut='0'.$nourut;
	else
	  $strurut=$nourut;
	$kode=$nama.$strurut;
	echo $kode;
?> 