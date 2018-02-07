<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
 include "../config/coneksi.php";

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
	array('db' => 'id_kategori', 'dt' => 0),
	array( 'db' => 'kategori', 'dt' => 1 ),
	array(
	       'db'        => 'id_kategori',
	       'dt'        => 2,
	       'formatter' => function( $d ) {
	       return '
<button onclick="tampiledit(\''.$d.'\');"class="btn btn-sm btn-warning" title="Edit"><span class="glyphicon glyphicon-edit"></span></button>
';
    }
),
		array(
	       'db'        => 'id_kategori',
	       'dt'        => 3,
	       'formatter' => function( $d ) {
	       return '
    <a href="modul/kategori/aksi_kategori.php?module=kategori&act=hapus&id='.$d.'" class="btn btn-sm btn-danger" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>';
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

require( '../lib/scripts/ssp.customized.class.php' );

$extraWhere = "`is_void` = 0 ";        
 if ($_GET['kategori']!='') {
	$extraWhere .= "and kategori like '".$_GET['kategori']."%'";
}
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
