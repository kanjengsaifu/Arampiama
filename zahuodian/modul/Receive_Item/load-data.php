<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
include "../../config/coneksi.php";
 include "../../lib/input.php";
if($_GET["pencarian"]='po'){



$table = 'trans_pur_order';


$primaryKey = 'id_pur_order';

$columns = array(
	array('db' => '`u`.`id`', 'dt' => 0, 'field' => 'id' ),
	array( 'db' => '`u`.`id_pur_order`', 'dt' => 1, 'field' => 'id_pur_order' ),
	array( 'db' => '`u`.`nama_supplier`',  'dt' => 2 , 'field' => 'nama_supplier' ),
	array( 'db' => '`u`.`alamat_supplier`',  'dt' => 3 , 'field' => 'alamat_supplier' ),
	array('db'        => 'CONCAT(`u`.`id_supp`," # ",`u`.`id_pur_order`," # ",`u`.`alamat_supplier`," # ",`u`.`nama_supplier`)', 'dt' => 4, 'field' => 'id', 'as' =>  'id',
	       'formatter' => function( $d ) {
	       	 $j = explode(" # ", $d);
	       return '<button class="btn-sm btn-success" onclick="nilaipo(\''.$j[0].'\',\''.$j[1].'\',\''.$j[2].'\',\''.$j[3].'\')"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></button>';
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
 
$joinQuery = "FROM (SELECT id,id_pur_order,nama_supplier,alamat_supplier,t.id_supplier as id_supp,t.is_void,t.status_trans FROM trans_pur_order t,supplier s where t.id_supplier=s.id_supplier) AS u";
$extraWhere = "`u`.`is_void` = 0 and (`u`.`status_trans` = '0' or `u`.`status_trans` = '1') "; 
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
	array('db' => '`u`.`bukti_bayar`',  'dt' => 2 , 'field' => 'bukti_bayar' ),
	array('db' => '`u`.`nama_bank`',  'dt' => 3 , 'field' => 'nama_bank' ),
	array('db' => '`u`.`rek`',  'dt' => 4 , 'field' => 'rek' ),
	array('db' => '`u`.`no_giro`',  'dt' => 5 , 'field' => 'no_giro' ),
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
	$joinQuery = "FROM (SELECT id_masterbank,tgl_pembayaran as tanggal,bukti_bayar,no_giro,jatuh_tempo,CONCAT(status_giro,'-',nominal,'-',giro_ditolak) as status,CONCAT(giro_ditolak,'-',nominal,'-',status_giro) as giroditolak ,nama_bank,rek  FROM `trans_bayarbeli_header`
	 tbh,`master_bank` mb WHERE mb.id=tbh.id_masterbank and bukti_bayar like 'BGK%' and tbh.is_void='0') u";
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

