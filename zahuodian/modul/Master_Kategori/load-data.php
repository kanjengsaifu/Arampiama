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
$table = 'kategori';

// Table's primary key
$primaryKey = 'id_kategori';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
	array('db' => '`u`.`id_adjustment`', 'dt' => 0, 'field' => 'id_adjustment' ),
	array( 'db' => '`ud`.`nama_gudang`', 'dt' => 1, 'field' => 'nama_gudang' ),
	array( 'db' => '`ub`.`nama_barang`',  'dt' => 2 , 'field' => 'nama_barang' ),
	array( 'db' => '`u`.`tgl_adjustment`',  'dt' => 3, 'field' => 'tgl_adjustment' ),
	array( 'db' => '`u`.`plusminus_barang`', 'dt' => 4, 'field' => 'plusminus_barang'),
	array( 'db' => '`u`.`keterangan`',  'dt' => 5, 'field' => 'keterangan' ),
	array(
	       'db'        => 'id_adjustment',
	       'dt'        => 6,
	       'field' => 'id_adjustment',
	       'formatter' => function( $d ) {
	       return '
	        <a href="?module=adjustment&act=editmenu&id='.$d.' " class="btn btn-warning" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
    <a href="$aksi?module=adjustment&act=hapus&id='.$d.' " class="btn btn-danger" title="Delete"><span class="glyphicon glyphicon-trash">';
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
 
$joinQuery = "FROM `adjustment_stok` AS `u` JOIN `gudang` AS `ud` ON (`ud`.`id_gudang` = `u`.`id_gudang`) 
JOIN `barang` AS `ub` ON (`ub`.`id_barang` = `u`.`id_barang`)";
$extraWhere = "`u`.`is_void` = 0";        
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
