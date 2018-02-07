<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
include "../../config/coneksi.php";
 include "../../lib/input.php";
if(isset($_GET["caribarang"])){

$table = 'Customer';

$primaryKey = 'id_Customer';

$columns = array(
	array('db' => '`u`.`id_Customer`', 'dt' => 0, 'field' => 'id_Customer' ),
	array( 'db' => '`u`.`kode_Customer`', 'dt' => 1, 'field' => 'kode_Customer' ),
	array( 'db' => '`u`.`nama_Customer`',  'dt' => 2 , 'field' => 'nama_Customer' ),
	array( 'db' => '`u`.`telp1_Customer`',  'dt' => 3 , 'field' => 'telp1_Customer' ),
	array('db'        => 'CONCAT(`u`.`id_Customer`," # ",`u`.`nama_Customer`)', 'dt' => 4, 'field' => 'id', 'as' =>  'id',
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
 
$joinQuery = "FROM `Customer` AS `u`";
$extraWhere = "`u`.`is_void` = 0 "; 
//$groupBy=  " `u`.`id_barang`";
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);

} else { // ##########################query detail

$table = 'Customer';

$primaryKey = 'id_Customer';

$columns = array(
	array('db' => '`u`.`id_lap_rekap_piutang`', 'dt' => 0, 'field' => 'id_lap_rekap_piutang' ),
	array('db' => '`u`.`nota`', 'dt' => 1, 'field' => 'nota',
		'formatter' => function($d){
		return $d;
		}),
	array('db' => '`u`.`tgl_transaksi`', 'dt' => 2, 'field' => 'tgl_transaksi',
		'formatter' => function($d){
		return tanggalan($d);
		}),
	array('db' => '`u`.`saldo_awal`',  'dt' => 3 , 'field' => 'saldo_awal', 
		'formatter' => function($d){
	       return format_rupiah($d);
    		}),
	array('db' => '`u`.`Pembelian`',  'dt' => 4, 'field' => 'Pembelian', 
		'formatter' => function($d){
	       return format_rupiah($d);
    		}),
	array('db' => '`u`.`Pembayaran`',  'dt' => 5 , 'field' => 'Pembayaran' , 
		'formatter' => function($d){
	       return format_rupiah($d);
    		}),
	array('db' => '`u`.`saldo_akhir`',  'dt' => 6 , 'field' => 'saldo_akhir' , 
		'formatter' => function($d){
	       return format_rupiah($d);
    		}),
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
	$awal_bln="".date('Y')."-".date('m')."-01 00:00:00";
$joinQuery = "from(SELECT * FROM lap_rekap_piutang l) as u";
$extraWhere =  "id_Customer=$barang ";
if ((!empty($awal))&&(!empty($akhir))) {
$extraWhere .= "and date(tgl_transaksi) >= date('".$awal."') and date(tgl_transaksi) <= date('".$akhir."') ";
}
$extraWhere .="order by id_lap_rekap_piutang asc, tgl_transaksi asc";
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

