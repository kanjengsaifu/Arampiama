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
$table = 'customer';

// Table's primary key
$primaryKey = 'id_customer';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
	array('db' => 'id_customer', 'dt' => 0, 'field' => 'id_customer' ),
	array( 'db' => 'kode_customer', 'dt' => 1, 'field' => 'kode_customer' ),
	array( 'db' => 'nama_customer',  'dt' => 2 , 'field' => 'nama_customer' ),
	array( 'db' => 'alamat_customer',  'dt' => 3, 'field' => 'alamat_customer' ),
	array( 'db' => 'telp_customer', 'dt' => 4, 'field' => 'telp_customer'),
	array( 'db' => 'batas_limit',  'dt' => 5, 'field' => 'batas_limit'),
	array( 'db' => 'aging',  'dt' => 6, 'field' => 'aging' ),
	array(
	       'db'        => 'id_customer',
	       'dt'        => 7,
	       'field' => 'id_customer',
	       'formatter' => function( $d ) {
	       return '
    <a href="?module=customer&act=editmenu&id='.$d.'" class="btn btn-sm btn-warning" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>

    <a href="modul/customer/aksi_customer.php?module=customer&act=hapus&id='.$d.'" class="btn btn-sm btn-danger" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>';
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
