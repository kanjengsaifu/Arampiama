<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
include "../../config/coneksi.php";
 include "../../lib/input.php";
if(isset($_GET["caribarang"])){


$table = 'akun_kas_perkiraan';


$primaryKey = 'id_akunkasperkiraan';

$columns = array(
	array('db' => '`u`.`id_akunkasperkiraan`', 'dt' => 0, 'field' => 'id_akunkasperkiraan' ),
	array( 'db' => '`u`.`nama_akunkasperkiraan`', 'dt' => 1, 'field' => 'nama_akunkasperkiraan' ),
	array('db'        => 'CONCAT(`u`.`id_akunkasperkiraan`," # ",`u`.`nama_akunkasperkiraan`)', 'dt' => 2, 'field' => 'id', 'as' =>  'id',
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
 
$joinQuery = "FROM ( SELECT id_akunkasperkiraan,nama_akunkasperkiraan FROM akun_kas_perkiraan akp,akun_header ah
 where akp.kode_akun_header=ah.kode_akun_header and ah.is_void=0 and akp.is_void=0 and ah.nama_akun='bank') AS `u`";
//$groupBy=  " `u`.`id_barang`";
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere=null )
);

} else { // ##########################query detail

$table = 'trans_lpb';

$primaryKey = 'id';

$columns = array(
	array('db' => '`u`.`tanggal`', 'dt' => 0, 'field' => 'tanggal' ),
	array('db' => '`u`.`tanggal`', 'dt' => 1, 'field' => 'tanggal' ),
	array('db' => '`u`.`bukti_bayarjual`',  'dt' => 2 , 'field' => 'bukti_bayarjual' ),
	array('db' => '`u`.`nama_akunkasperkiraan`',  'dt' => 3 , 'field' => 'nama_akunkasperkiraan' ),
	array('db' => '`u`.`tgl_giro_cair`',  'dt' => 4 , 'field' => 'tgl_giro_cair' ),
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
	array('db' => '`u`.`status`',  'dt' => 8 , 'field' => 'status',
		'as' => 'status' ,
		'formatter' => function($d){
		$ex=explode('-', $d);
		if ($ex[0]=='1' and $ex[2]=='1') {
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
	$id_akunkasperkiraandicari = $_GET["barang"];
	$joinQuery = "FROM (SELECT akp.id_akunkasperkiraan,nama_akunkasperkiraan,tgl_giro_cair,tgl_pembayaranjual as tanggal,bukti_bayarjual,no_giro_jual
,jatuh_tempo_jual,
CONCAT(status_giro_jual,'-',nominaljual,'-',giro_ditolak_jual)as status
 FROM trans_bayarjual_header tbh left join akun_kas_perkiraan akp on (akp.id_akunkasperkiraan=tbh.id_akunkasperkiraan)
where  bukti_bayarjual like 'BGM%' and tbh.is_void='0') u";
	$extraWhere="";
if ((empty($id_akunkasperkiraandicari))&&(empty($awal))&&(empty($akhir))) {
	$extraWhere =  null;
}elseif ((!empty($id_akunkasperkiraandicari))&&(!empty($awal))&&(!empty($akhir))) {
	$extraWhere .=  " (tanggal BETWEEN  \"".$awal."\" AND \"".$akhir."\") and id_akunkasperkiraan=$id_akunkasperkiraandicari order by tanggal" ;
}elseif ((!empty($id_akunkasperkiraandicari))&&(!empty($awal) )){
	$extraWhere .=  " (tanggal >= \"".$awal."\")and id_akunkasperkiraan=$id_akunkasperkiraandicari order by tanggal";
}elseif ((!empty($awal))&&(!empty($akhir))) {
	$extraWhere .=  " (tanggal BETWEEN  \"".$awal."\" AND \"".$akhir."\") order by tanggal";
}elseif (!empty($id_akunkasperkiraandicari)) {
	$extraWhere .=  " id_akunkasperkiraan=$id_akunkasperkiraandicari order by tanggal";
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

