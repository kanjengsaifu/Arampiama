<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
 include "../../config/coneksi.php";

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
$table = 'supplier';

// Table's primary key
$primaryKey = 'id_supplier';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
	array('db' => '`u`.`id_trans_terima_tukang_header`', 'dt' => 0, 'field' => 'id_trans_terima_tukang_header' ),
	array( 'db' => '`u`.`nama_tukang`', 'dt' => 1, 'field' => 'nama_tukang' ),
	array( 'db' => '`u`.`id_terima_tukang`',  'dt' => 2 , 'field' => 'id_terima_tukang' ),
	array( 'db' => '`u`.`nonota_terima_tukang`',  'dt' => 3 , 'field' => 'nonota_terima_tukang' ),
	array( 'db' => '`u`.`grandtotal`',  'dt' => 4 , 'field' => 'grandtotal' ),
    	array(
	       'db'        => '`u`.`id_trans_terima_tukang_header`',
	       'dt'        => 5,
	       'field' => 'id_trans_terima_tukang_header',
	       'formatter' => function( $d ) {
	       return '<a  class="btn-sm btn-success" href="?module=tukanganpenotaan&act=tambah&id='.$d.'">Proses</a>';
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
 
$joinQuery = "FROM (SELECT id_trans_terima_tukang_header,Concat(kode_supplier,' - ',nama_supplier) as nama_tukang,id_terima_tukang,nonota_terima_tukang,grandtotal,a.id_supplier FROM trans_terima_tukang_header a, supplier b WHERE a.id_supplier=b.id_supplier and status = 0 AND a.is_void = 0) AS `u`  ";
 
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, null)
);
} else {
    echo '<script>window.location="404.html"</script>';
}
