<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
include "../../config/coneksi.php";
 include "../../lib/input.php";
if(isset($_GET["caribarang"])){

$table = 'barang';

$primaryKey = 'id_barang';

$columns = array(
	array('db' => '`u`.`id_barang`', 'dt' => 0, 'field' => 'id_barang' ),
	array( 'db' => '`u`.`kode_barang`', 'dt' => 1, 'field' => 'kode_barang' ),
	array( 'db' => '`u`.`nama_barang`',  'dt' => 2 , 'field' => 'nama_barang' ),
	array('db'        => 'CONCAT(`u`.`id_barang`," # ",`u`.`nama_barang`)', 'dt' => 3, 'field' => 'id', 'as' =>  'id',
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
	array('db' => '`u`.`id_barang`', 'dt' => 0, 'field' => 'id_barang' ),
	array('db' => '`u`.`nama_barang`', 'dt' => 1, 'field' => 'nama_barang' ),
	array('db' => '`u`.`satuan1`',  'dt' => 2 , 'field' => 'satuan1' ),
	array('db' => '`u`.`saldo_awal`',  'dt' => 3, 'field' => 'saldo_awal','formatter' => function($d){
	       return format_jumlah($d);
    		}),

	array('db' => '`u`.`masuk`',  'dt' => 4 , 'field' => 'masuk', 
		'formatter' => function($d){
	       return format_jumlah($d);
    		}),
	array('db' => '`u`.`keluar`',  'dt' => 5 , 'field' => 'keluar' , 
		'formatter' => function($d){
	       return format_jumlah($d);
    		}),
	array('db' => '`u`.`saldo_akhir`',  'dt' => 6 , 'field' => 'saldo_akhir' , 
		'formatter' => function($d){
	       return format_jumlah($d);
    		}),
	array('db' =>  'CONCAT(`u`.`id_barang`," # ",`u`.`cari_awal`," # ",`u`.`cari_akhir`)', 'dt' => 7, 'field' => 'detail', 'as' => 'detail',
		'formatter' => function($d){
				 $k = explode(" # ", $d);
		$detail ="<a href='?module=kartubarang&act=detail&id=$k[0]&awal=$k[1]&akhir=$k[2]' class='btn btn-sm btn-success' title='Detail' target='_blank'><span class='glyphicon glyphicon-list'></span></a>";
		return $detail;
		} )
	
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
if ((empty($barang))&&(empty($awal))&&(empty($akhir))) {
	$joinQuery = "from(SELECT '".$awal_bln."' as cari_awal,'".$akhir."' as cari_akhir ,l.id_barang,nama_barang,satuan1,saldo_awal,sum(masuk) as masuk ,sum(keluar) as keluar,
(saldo_awal+sum(masuk)-sum(keluar))as saldo_akhir
FROM lap_rekap_barang l,barang s where l.id_barang=s.id_barang
and date(tgl_transaksi) >= date('".$awal_bln."') and date(tgl_transaksi) <= date(now()) group by l.id_barang) as u";
$extraWhere = "";
}elseif ((!empty($barang))&&(!empty($awal))&&(!empty($akhir))) {
	$joinQuery = "from(SELECT  '".$awal."' as cari_awal,'".$akhir."' as cari_akhir , l.id_barang,nama_barang,satuan1,saldo_awal,sum(masuk) as masuk ,sum(keluar) as keluar,
(saldo_awal+sum(masuk)-sum(keluar))as saldo_akhir
FROM lap_rekap_barang l,barang s where l.id_barang=s.id_barang
and date(tgl_transaksi) >= date('".$awal."') and date(tgl_transaksi) <= date('".$akhir."') group by l.id_barang) as u";
	$extraWhere =  " id_barang=$barang " ;
}elseif ((!empty($awal))&&(!empty($akhir))) {
		$joinQuery = "from(SELECT  '".$awal."' as cari_awal,'".$akhir."' as cari_akhir ,l.id_barang,nama_barang,satuan1,saldo_awal,sum(masuk) as masuk ,sum(keluar) as keluar,
(saldo_awal+sum(masuk)-sum(keluar))as saldo_akhir
FROM lap_rekap_barang l,barang s where l.id_barang=s.id_barang
and date(tgl_transaksi) >= date('".$awal."') and date(tgl_transaksi) <= date('".$akhir."') group by l.id_barang) as u";
$extraWhere="";
}elseif (!empty($barang)) {
		$joinQuery = "from(SELECT  '".$awal."' as cari_awal,'".$akhir."' as cari_akhir ,l.id_barang,nama_barang,satuan1,saldo_awal,sum(masuk) as masuk ,sum(keluar) as keluar,
(saldo_awal+sum(masuk)-sum(keluar))as saldo_akhir
FROM lap_rekap_barang l,barang s where l.id_barang=s.id_barang
and date(tgl_transaksi) >= date('".$awal_bln."') and date(tgl_transaksi) <= date(now()) group by l.id_barang) as u";
	$extraWhere =  "id_barang=$barang ";
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

