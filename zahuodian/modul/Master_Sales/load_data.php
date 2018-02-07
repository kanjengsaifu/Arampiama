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
$table = 'sales';

// Table's primary key
$primaryKey = 'id_sales';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
	array('db' => 'id_sales', 'dt' => 0),
	array('db' => 'nama_sales', 'dt' => 1),
	array( 'db' => 'telp1_sales', 'dt' => 2 ),
	array( 'db' => 'telp2_sales', 'dt' => 3 ),
	array( 'db' => 'ket', 'dt' => 4 ),
	
	array(
	       'db'        => 'id_sales',
	       'dt'        => 5,
	       'field' => 'id_sales',
	       'formatter' => function( $d, $row ) {
	       return '
	        <a href="?module=sales&act=edit&id='.$d.'" class="btn btn-warning" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
    <a href="modul/sales/aksi_sales.php?module=sales&act=hapus&id='.$d.'" class="btn btn-danger" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>';
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

$extraWhere = "`is_void` = 0";        
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
