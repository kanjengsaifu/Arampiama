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
    array( 'db' => 'stok_tukang', 'dt' => 3, 'field' => 'stok_tukang'),
	array(
	       'db'        => '`u`.`id_barang`',
	       'dt'        => 4,
	       'field' => 'id_barang',
	       'formatter' => function( $d ) {
	       return '<a class="btn-sm btn-success" href="#" onclick="add_barang(\''.$d.'\')"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
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
 $id_supplier=$_GET['id_supplier'];
$joinQuery = "FROM (Select a.id_barang,kode_barang,nama_barang,stok_tukang from barang a,stok_tukang b where a.id_barang=b.id_barang and b.id_supplier='".$id_supplier."' and stok_tukang!='0' and a.is_void='0') as u ";

 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
