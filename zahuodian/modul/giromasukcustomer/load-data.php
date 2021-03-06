<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
include "../../config/coneksi.php";
 include "../../lib/input.php";
if(isset($_GET["caribarang"])){



$table = 'barang';


$primaryKey = 'id_barang';

$columns = array(
	array('db' => '`u`.`id`', 'dt' => 0, 'field' => 'id' ),
	array( 'db' => '`u`.`ac`', 'dt' => 1, 'field' => 'ac' ),
	array( 'db' => '`u`.`rek`',  'dt' => 2 , 'field' => 'rek' ),
	array( 'db' => '`u`.`nama_bank`',  'dt' => 3 , 'field' => 'nama_bank' ),
	array('db'        => 'CONCAT(`u`.`id`," # ",`u`.`nama_bank`)', 'dt' => 4, 'field' => 'id', 'as' =>  'id',
	       'formatter' => function( $d ) {
	       	 $j = explode(" # ", $d);
	       return '<button class="btn-sm btn-success" onclick="addMore(\''.$j[0].'\',\''.$j[1].'\')"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></button>';
    }
),
);

$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);



require( '../../lib/scripts/ssp.customized.class.php' );
 
$joinQuery = "FROM `master_bank` AS `u`";
$extraWhere = "`u`.`is_void` = 0 "; 
//$groupBy=  " `u`.`id_barang`";
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);

} else { // ##########################query detail

$table = 'trans_lpb';

$primaryKey = 'id';

$columns = array(
	array('db' => '`u`.`tanggal`', 'dt' => 0, 'field' => 'tanggal' ),
	array('db' => '`u`.`tanggal`', 'dt' => 1, 'field' => 'tanggal' ),
	array('db' => '`u`.`bukti_bayarjual`',  'dt' => 2 , 'field' => 'bukti_bayarjual' ),
	array('db' => '`u`.`nama_bank`',  'dt' => 3 , 'field' => 'nama_bank' ),
	array('db' => '`u`.`rek`',  'dt' => 4 , 'field' => 'rek' ),
	array('db' => '`u`.`no_giro_jual`',  'dt' => 5 , 'field' => 'no_giro_jual' ),
	array('db' => '`u`.`status`',  'dt' => 6 , 'field' => 'status',
		'as' => 'status' ,
		'formatter' => function($d){
		$ex=explode('-', $d);
		if ($ex[0]=='0' and $ex[2]=='0' ) {
			return format_rupiah($ex[1]);
		}
		} ),
	array('db' => '`u`.`status`',  'dt' => 7 , 'field' => 'status',
		'as' => 'status_blm_cair' ,
		'formatter' => function($d){
		$ex=explode('-', $d);
		if ($ex[0]=='1' and $ex[2]=='0') {
			return format_rupiah($ex[1]);
		}
		} ),
	array('db' => '`u`.`giroditolak`',  'dt' => 8 , 'field' => 'giroditolak',
		'as' => 'giroditolak' ,
		'formatter' => function($d){
		$ex=explode('-', $d);
		if ($ex[0]=='1') {
			return format_rupiah($ex[1]);
		}
		} ),
);

$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);



require( '../../lib/scripts/ssp.customized.class.php' );
    $akhir = $_GET["akhir"];
	$awal = $_GET["awal"];
	$barang = $_GET["barang"];
	$joinQuery = "FROM (SELECT id_masterbank,tgl_pembayaranjual as tanggal,bukti_bayarjual,no_giro_jual,jatuh_tempo_jual,
CONCAT(status_giro_jual,'-',nominaljual,'-',giro_ditolak_jual) as status,
CONCAT(giro_ditolak_jual,'-',nominaljual,'-',status_giro_jual) as giroditolak ,nama_bank,rek  FROM `trans_bayarjual_header`
	 tbh,`master_bank` mb WHERE mb.id=tbh.id_masterbank and bukti_bayarjual like 'BGM%' and tbh.is_void='0') u";
	$extraWhere="";
if ((empty($barang))&&(empty($awal))&&(empty($akhir))) {
	$extraWhere =  null;
}elseif ((!empty($barang))&&(!empty($awal))&&(!empty($akhir))) {
	$extraWhere .=  " (tanggal BETWEEN  \"".$awal."\" AND \"".$akhir."\") and id_masterbank=$barang order by tanggal" ;
}elseif ((!empty($barang))&&(!empty($awal) )){
	$extraWhere .=  " (tanggal >= \"".$awal."\")and id_masterbank=$barang order by tanggal";
}elseif ((!empty($awal))&&(!empty($akhir))) {
	$extraWhere .=  " (tanggal BETWEEN  \"".$awal."\" AND \"".$akhir."\") order by tanggal";
}elseif (!empty($barang)) {
	$extraWhere .=  " id_masterbank=$barang order by tanggal";
}elseif (!empty($awal)) {
	$extraWhere .=  " (tanggal >= \"".$awal."\") order by tanggal";
}

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
); }
} else {
	alert("aaaa");
    echo '<script>window.location="404.html"</script>';
}

// select *,if(Mosule='Laporan Barang Masuk',qty,''),if(Mosule='Laporan Barang Keluar' ,qty,'') from
// (SELECT id_barang, tgl_lpb as tgl,'Laporan Barang Masuk' as Mosule,qty_diterima_convert As qty FROM trans_lpb tl,trans_lpb_detail tld WHERE tl.id_lpb=tld.id_lpb 
// union
// SELECT id_barang, tgl_adjustment as tgl,'Adjustment' as Mosule,plusminus_barang As qty FROM adjustment_stok
// union
// SELECT id_barang, tgl_lkb as tgl,'Laporan Barang Keluar' as Mosule,qty_diterima_convert As qty FROM trans_lkb tl,trans_lkb_detail tld WHERE tl.id_lkb=tld.id_lkb ) as a where id_barang=3 order by tgl

