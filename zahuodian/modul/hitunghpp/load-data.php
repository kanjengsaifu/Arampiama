<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
 include "../../config/coneksi.php";
 include "../../lib/input.php";


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
$table = 'hitung_hpp_header';

// Table's primary key
$primaryKey = 'id_hitung_hpp_heder';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
	array( 'db' => 'h.id_hitung_hpp_heder', 'dt' => 0, 'field' => 'id_hitung_hpp_heder'),
	array( 'db' => 'h.no_hitung_hpp', 'dt' => 1, 'field' => 'no_hitung_hpp'),
	array( 'db' => 't.id_terima_tukang', 'dt' => 2, 'field' => 'id_terima_tukang'),
	array( 'db' => 'b.nama_barang', 'dt' => 3, 'field' => 'nama_barang'),
	array( 'db' => 'h.tgl_trans', 'dt' => 4, 'field' => 'tgl_trans'),
	array( 'db' => 'h.hpp_akhir', 'dt' => 5, 'field' => 'hpp_akhir',
			'formatter' => function ($i) {
				return format_rupiah($i);
			}),
	array( 'db'        => 'h.id_hitung_hpp_heder', 'dt'        => 6,  'field' => 'id_hitung_hpp_heder',
	       'formatter' => function( $d ) {
	       return '<a class="btn-sm btn-warning" href="?module=hitunghpp&act=detail&id='.$d.'"><span class="glyphicon glyphicon-list" aria-hidden="true"></span></a>';
    })
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
 
$joinQuery = "FROM hitung_hpp_header AS h JOIN trans_terima_tukang_header AS t ON t.id_terima_tukang = h.id_trans_terima_tukang_header JOIN barang b ON b.id_barang = h.id_barang_header ";
$extraWhere = "h.is_void = '0' "; 
//$groupBy=  " `tb`.`id_barang`";
$orderBy = "h.id_hitung_hpp_heder desc";
 
echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $orderBy )
//SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
