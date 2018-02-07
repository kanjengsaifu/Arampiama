<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
include "../../config/coneksi.php";
 include "../../lib/input.php";
if(isset($_GET["caribarang"])){

$table = 'barang';

$primaryKey = 'u.id_barang';

$columns = array(
	array('db' => '`u`.`id_barang`', 'dt' => 0, 'field' => 'id_barang' ),
	array( 'db' => '`u`.`kode_barang`', 'dt' => 1, 'field' => 'kode_barang' ),
	array( 'db' => '`u`.`nama_barang`',  'dt' => 2 , 'field' => 'nama_barang' ),
	array( 'db' => '`u`.`sataun1`',  'dt' => 3 , 'field' => 'satuan1' ),
	array('db'        => 'CONCAT(`u`.`id_barang`," # ",`u`.`nama_barang`)', 'dt' => 4, 'field' => 'id', 'as' =>  'id',
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
 
$joinQuery = "FROM `barang` AS `u`";
$extraWhere = "`u`.`is_void` = 0 "; 
//$groupBy=  " `u`.`id_barang`";
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);

} else { // ##########################query detail

$table = 'barang';

$primaryKey = 'id_barang';

$columns = array(
	array('db' => '`u`.`id_lap_rekap_barang`', 'dt' => 0, 'field' => 'id_lap_rekap_barang' ),
	array('db' => '`u`.`tgl_transaksi`', 'dt' => 1, 'field' => 'tgl_transaksi' ),
	array('db' => '`u`.`nota`', 'dt' => 2, 'field' => 'nota',
		'formatter' => function($d){
		return $d;
		}),
	array('db' => '`u`.`keterangan`', 'dt' => 3, 'field' => 'keterangan',
		'formatter' => function($d){
		return $d;
		}),
	array('db' => '`u`.`harga_sat1`', 'dt' => 4, 'field' => 'harga_sat1',
		'formatter' => function($d){
		return $d;
		}),
	array('db' => '`u`.`masuk`',  'dt' => 5, 'field' => 'masuk', 
		'formatter' => function($d){
	       return format_jumlah($d);
    		}),
	array('db' => '`u`.`harga_masuk`',  'dt' => 6, 'field' => 'harga_masuk', 
		'formatter' => function($d){
	       return format_jumlah($d);
    		}),
	array('db' => '`u`.`rupiah_masuk`',  'dt' => 7, 'field' => 'rupiah_masuk' , 
		'formatter' => function($d){
	       return format_jumlah($d);
    		}),	
	array('db' => '`u`.`keluar`',  'dt' => 8, 'field' => 'keluar', 
		'formatter' => function($d){
	       return format_jumlah($d);
    		}),
	array('db' => '`u`.`harga_keluar`',  'dt' => 9, 'field' => 'harga_keluar' , 
		'formatter' => function($d){
	       return format_jumlah($d);
    		}),	
  	array('db' => '`u`.`rupiah_keluar`',  'dt' => 10, 'field' => 'rupiah_keluar' , 
		'formatter' => function($d){
	       return format_jumlah($d);
    		}),  
	array('db' => '`u`.`saldo_akhir`',  'dt' => 11, 'field' => 'saldo_akhir' , 
		'formatter' => function($d){
	       return format_jumlah($d);
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
$joinQuery = "from(SELECT tgl_transaksi,b.id_barang,id_lap_rekap_barang,saldo_akhir,rupiah_keluar,harga_keluar,keluar,rupiah_masuk,harga_masuk,masuk,harga_sat1,nota,keterangan FROM lap_rekap_barang l,barang b where b.id_barang=l.id_barang) as u";
$extraWhere =  "u.id_barang=$barang ";
if ((!empty($awal))&&(!empty($akhir))) {
$extraWhere .= "and date(tgl_transaksi) >= date('".$awal."') and date(tgl_transaksi) <= date('".$akhir."') ";
}
$extraWhere .="order by id_lap_rekap_barang asc, tgl_transaksi asc";
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

