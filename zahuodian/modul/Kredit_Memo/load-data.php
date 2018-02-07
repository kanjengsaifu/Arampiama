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
$table = 'trans_retur_pembelian';

// Table's primary key
$primaryKey = 'id';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
	array('db' => '`u`.`kode_rbb`', 'dt' => 0, 'field' => 'kode_rbb' ),
	array('db' => '`u`.`kode_rbb`', 'dt' => 1, 'field' => 'kode_rbb' ),
	array( 'db' => '`ud`.`id_invoice`', 'dt' => 2, 'field' => 'id_invoice' ),
	array( 'db' => '`u`.`grandtotal_sebelum_terretur`',  'dt' => 3 , 'field' => 'grandtotal_sebelum_terretur' ),
	array( 'db' => '`u`.`grandtotal_retur`',  'dt' => 4 , 'field' => 'grandtotal_retur' ),
	array( 'db' => '`ud`.`grand_total`',  'dt' => 5 , 'field' => 'grand_total' ),
	array(
	       'db'        => '`u`.`kode_rbb`',
	       'dt'        => 6,
	       'field' => 'kode_rbb',
	       'formatter' => function( $d ) {
	       return '
	       <!-- <a href="?module=kreditmemo&act=editmenu&id='.$d.' " class="btn btn-warning" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>-->
    <a href="modul/kreditmemo/aksi_kreditmemo.php?module=kreditmemo&act=hapus&id='.$d.' " class="btn btn-danger" title="Delete"><span class="glyphicon glyphicon-trash">';

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
 
$joinQuery = "FROM `trans_retur_pembelian` AS `u` JOIN `trans_invoice` AS `ud` ON (`ud`.`id_invoice` = `u`.`no_invoice_terretur`)";

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, null)
);
} else {
    echo '<script>window.location="404.html"</script>';
}
