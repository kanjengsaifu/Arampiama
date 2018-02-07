<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
 include "../../config/coneksi.php";
 include "../../lib/input.php";
 include "../../lib/fungsi_tanggal.php";
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'trans_terima_tukang_header';

// Table's primary key
$primaryKey = 'id_trans_terima_tukang_header';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
	array('db' => 't.id_trans_terima_tukang_header', 'dt' => 0, 'field' => 'id_trans_terima_tukang_header' ),
	array( 'db' => 't.id_terima_tukang', 'dt' => 1, 'field' => 'id_terima_tukang' ),
	array( 'db' => 't.nonota_terima_tukang',  'dt' => 2 , 'field' => 'nonota_terima_tukang' ),
	array( 'db' => 's.nama_supplier', 'dt' => 3, 'field' => 'nama_supplier'),
	array( 'db' => 't.tgl_trans', 'dt' => 4, 'field' => 'tgl_trans',
			'formatter' => function($i) {
				return tgl_indo($i);
			}),
	array( 'db' => 't.grandtotal', 'dt' => 5, 'field' => 'grandtotal',
			'formatter' => function($j) {
				return "Rp ".number_format($j);
			}),
    array(
	       'db'        => 'CONCAT(t.id_trans_terima_tukang_header," # ",t.status)',
	       'dt'        => 6,
	       'field' => 'id_trans_terima_tukang_header',
	       'as' => 'id_trans_terima_tukang_header',
	       'formatter' => function( $d ) {
	       	$status= explode(" # ", $d);
	       	if ($status[1]=='1') {
	       		$out = '<a  class="btn btn-default" href="modul/tukanganpenerimaan/cetak.php?id='.$status[0].'"><span class="glyphicon glyphicon-print"></span></a>';
	       	} else {
	       		$out = '<a href="?module=tukanganpenerimaan&act=edit&id='.$status[0].' " class="btn btn-warning" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
						    <a href="modul/tukanganpenerimaan/aksi_tukanganpenerimaan.php?module=tukanganpenerimaan&act=hapus&id='.$status[0].' " class="btn btn-danger" title="Delete"><span class="glyphicon glyphicon-trash">';
	       	}
	       return $out;
    }
),
);


// SQL server connection information
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( '../../lib/scripts/ssp.customized.class.php' );
 
$joinQuery = "FROM trans_terima_tukang_header AS t JOIN supplier AS s ON t.id_supplier = s.id_supplier";
$extraWhere = "t.is_void = 0"; 
       
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
