<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
  include "../../config/coneksi.php";
  include "../../lib/input.php";
  
if(isset($_GET["customer"])){ //################################ CARI modal customer

$table = 'customer';


$primaryKey = 'id_customer';

$columns = array(
	array('db' => '`u`.`id_customer`', 'dt' => 0, 'field' => 'id_customer' ),
	array( 'db' => '`u`.`kode_customer`', 'dt' => 1, 'field' => 'kode_customer' ),
	array( 'db' => '`u`.`nama_customer`',  'dt' => 2 , 'field' => 'nama_customer' ),
	array( 'db' => '`u`.`alamat_customer`',  'dt' => 3 , 'field' => 'alamat_customer' ),
	array('db'        => 'CONCAT(`u`.`id_customer`," # ",`u`.`nama_customer`)', 'dt' => 4, 'field' => 'id_customer', 'as' =>  'id_customer',
	       'formatter' => function( $d ) {
	       	 $j = explode(" # ", $d);
	       return '<button class="btn-sm btn-success" onclick="addMore(\''.$j[0].'\',\''.$j[1].'\',\'customer\')"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></button>';
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
 
$joinQuery = "FROM `customer` AS `u`";
$extraWhere = "`u`.`is_void` = 0 "; 
//$groupBy=  " `u`.`id_barang`";
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);

} else if (isset($_GET["sales"])){ //################################ CARI modal sales

$table = 'sales';


$primaryKey = 'id_sales';

$columns = array(
	array('db' => '`u`.`id_sales`', 'dt' => 0, 'field' => 'id_sales' ),
	array( 'db' => '`u`.`nama_sales`', 'dt' => 1, 'field' => 'nama_sales' ),
	array( 'db' => '`u`.`telp1_sales`',  'dt' => 2 , 'field' => 'telp1_sales' ),
	array( 'db' => '`u`.`telp2_sales`',  'dt' => 3 , 'field' => 'telp2_sales' ),
	array('db'        => 'CONCAT(`u`.`id_sales`," # ",`u`.`nama_sales`)', 'dt' => 4, 'field' => 'id_sales', 'as' =>  'id_sales',
	       'formatter' => function( $d ) {
	       	 $j = explode(" # ", $d);
	       return '<button class="btn-sm btn-success" onclick="addMore(\''.$j[0].'\',\''.$j[1].'\',\'sales\')"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></button>';
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
 
$joinQuery = "FROM `sales` AS `u`";
$extraWhere = "`u`.`is_void` = 0 "; 
//$groupBy=  " `u`.`id_barang`";
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);

} else if (isset($_GET['startup'])) { // ##########################query detail awal

$table = 'trans_sales_invoice';

$primaryKey = 'id';

$columns = array(
	array('db' => '`ti`.`id`', 'dt' => 0, 'field' => 'id' ),
	array('db' => '`ti`.`tgl`', 'dt' => 1, 'field' => 'tgl',
		'formatter' => function($d){
		return tanggalan($d);
		}),
	array('db' => '`ti`.`id_invoice`', 'dt' => 2, 'field' => 'id_invoice' ),
	array('db' => '`ti`.`id_sales_order`', 'dt' => 3, 'field' => 'id_sales_order' ),
	array('db' => '`ti`.`alltotal`', 'dt' => 4, 'field' => 'alltotal',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`ti`.`allppnnominal`', 'dt' => 5, 'field' => 'allppnnominal',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`ti`.`alldiscnominal`', 'dt' => 6, 'field' => 'alldiscnominal',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`ti`.`grand_total`', 'dt' => 7, 'field' => 'grand_total',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`tk`.`pembayaran`', 'dt' => 8, 'field' => 'pembayaran',
		'formatter' => function($d){
		return format_rupiah($d);
		}), 
	array('db' => 'SUM(`tid`.`harga_si` - (`tid`.`hpp` * `tid`.`qty_si_convert`))', 'dt' => 9, 'field' => 'laba', 'as' => 'laba',
		'formatter' => function($d){
		return format_rupiah($d);
		}), 	
	array('db' => '`c`.`nama_customer`', 'dt' => 10, 'field' => 'nama_customer' ),
	array('db' => '`tb`.`nama_sales`', 'dt' => 11, 'field' => 'nama_sales' )
);

$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);

require( '../../lib/scripts/ssp.customized.class.php' );
	$joinQuery = "FROM (SELECT sum(tbd.nominal_alokasi_detail_jual) as pembayaran , tbd.id_customer
	as id_customer1, tbd.nota_invoice as nota_invoice1 FROM trans_bayarjual_detail tbd LEFT JOIN trans_bayarjual_header tbh 
	ON(tbh.bukti_bayarjual=tbd.bukti_bayarjual) 
	GROUP BY tbd.nota_invoice ) as tk RIGHT JOIN trans_sales_invoice ti 
	ON(tk.nota_invoice1=ti.id_invoice) LEFT JOIN customer c ON(c.id_customer=ti.id_customer) 
	LEFT JOIN (SELECT tso.id_sales_order, s.nama_sales, tso.id_sales FROM trans_sales_order tso 
	LEFT JOIN sales s ON(s.id_sales=tso.id_sales)) as tb ON(tb.id_sales_order=ti.id_sales_order) LEFT JOIN trans_sales_invoice_detail tid ON(tid.id_invoice=ti.id_invoice)";

	$extraWhere =  "`ti`.`is_void` = 0";

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
); 
} else if (isset($_GET['cus']) && isset($_GET['sal'])) { // ##########################query SEARCH

$table = 'trans_sales_invoice';

$primaryKey = 'id';

$columns = array(
	array('db' => '`ti`.`id`', 'dt' => 0, 'field' => 'id' ),
	array('db' => '`ti`.`tgl`', 'dt' => 1, 'field' => 'tgl',
		'formatter' => function($d){
		return tanggalan($d);
		}),
	array('db' => '`ti`.`id_invoice`', 'dt' => 2, 'field' => 'id_invoice' ),
	array('db' => '`ti`.`id_sales_order`', 'dt' => 3, 'field' => 'id_sales_order' ),
	array('db' => '`ti`.`alltotal`', 'dt' => 4, 'field' => 'alltotal',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`ti`.`allppnnominal`', 'dt' => 5, 'field' => 'allppnnominal',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`ti`.`alldiscnominal`', 'dt' => 6, 'field' => 'alldiscnominal',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`ti`.`grand_total`', 'dt' => 7, 'field' => 'grand_total',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`tk`.`pembayaran`', 'dt' => 8, 'field' => 'pembayaran',
		'formatter' => function($d){
		return format_rupiah($d);
		}), 
	array('db' => 'SUM(`tid`.`harga_si` - (`tid`.`hpp` * `tid`.`qty_si_convert`))', 'dt' => 9, 'field' => 'laba', 'as' => 'laba',
		'formatter' => function($d){
		return format_rupiah($d);
		}), 	
	array('db' => '`c`.`nama_customer`', 'dt' => 10, 'field' => 'nama_customer' ),
	array('db' => '`tb`.`nama_sales`', 'dt' => 11, 'field' => 'nama_sales' )
);

$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);

require( '../../lib/scripts/ssp.customized.class.php' );
	$joinQuery = "FROM (SELECT sum(tbd.nominal_alokasi_detail_jual) as pembayaran , tbd.id_customer
	as id_customer1, tbd.nota_invoice as nota_invoice1 FROM trans_bayarjual_detail tbd LEFT JOIN trans_bayarjual_header tbh 
	ON(tbh.bukti_bayarjual=tbd.bukti_bayarjual) 
	GROUP BY tbd.nota_invoice ) as tk 
	RIGHT JOIN trans_sales_invoice ti 
	ON(tk.nota_invoice1=ti.id_invoice) LEFT JOIN customer c ON(c.id_customer=ti.id_customer) 
	LEFT JOIN 
	(SELECT tso.id_sales_order, s.nama_sales, tso.id_sales FROM trans_sales_order tso  LEFT JOIN sales s ON(s.id_sales=tso.id_sales)) as tb ON(tb.id_sales_order=ti.id_sales_order) 
	LEFT JOIN trans_sales_invoice_detail tid ON(tid.id_invoice=ti.id_invoice)";

	$extraWhere =  "`ti`.`is_void` = 0 ";
if(!empty($_GET['cus'])){

		$extraWhere .= " AND `ti`.`id_customer` = ".$_GET['cus'];	
}
if(!empty($_GET['sal']) ){
	$extraWhere .=  " AND `tb`.`id_sales` = ".$_GET['sal'];

}
if(isset($_GET['akhir']) && isset($_GET['awal'])){
	$extraWhere .=" AND (ti.tgl BETWEEN  \"".$_GET['awal']."\" AND \"".$_GET['akhir']."\") order by ti.tgl";

}

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
); 
}// ################ akhir else


// ###################### ELSE JIKA SESSIN TIDAK SESUAI ################
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

