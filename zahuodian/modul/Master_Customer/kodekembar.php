<?php
	include "../../config/koneksi.php";
	error_reporting(E_ALL & ~E_NOTICE);
	$nama=strtoupper($_POST['nama']);
	$sql="SELECT COUNT(*) jum FROM customer WHERE kode_customer LIKE '$nama%'";
	$jum_kode=mysql_fetch_array(mysql_query($sql));
	$nourut=$jum_kode['jum']+1;
	if ($nourut<10)
	  $strurut='0000'.$nourut;
	else if ($nourut<100)
	  $strurut='000'.$nourut;
	else if ($nourut<1000)
	  $strurut='00'.$nourut;
	else if ($nourut<10000)
	  $strurut='0'.$nourut;
	else
	  $strurut=$nourut;
	$kode=$nama.$strurut;
	echo $kode;
?> 