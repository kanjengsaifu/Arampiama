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
$table = 'barang';

// Table's primary key
$primaryKey = 'id_barang';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
	array('db' => '`u`.`id_barang`', 'dt' => 0, 'field' => 'id_barang' ),
	array( 'db' => '`u`.`kode_barang`', 'dt' => 1, 'field' => 'kode_barang' ),
	array( 'db' => '`u`.`nama_barang`',  'dt' => 2 , 'field' => 'nama_barang' ),
    array( 'db' => 'sum(stok_sekarang)', 'dt' => 3, 'field' => 'stok_sekarang', 'as' => 'stok_sekarang'),
	array( 'db' => '`u`.`stok_minimum`',  'dt' => 4, 'field' => 'stok_minimum',
	       'formatter' =>function( $d, $row ) {
                      return number_format($d,null,null,'.');
                  }
             ),
	array(
	       'db'        => '`u`.`id_barang`',
	       'dt'        => 5,
	       'field' => 'id_barang',
	       'formatter' => function( $d ) {
	       return '<a class="btn-sm btn-success" href="#" onclick="addMore(\''.$d.'\')"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
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
 
$joinQuery = "FROM  (select b.id_barang,kode_barang,nama_barang,stok_sekarang,stok_minimum,b.is_void  from barang b, stok s where b.id_barang=s.id_barang ) as u ";
$extraWhere = "`u`.`is_void` = 0 "; 
$groupBy=  " `u`.`id_barang`";
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere,$groupBy )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
