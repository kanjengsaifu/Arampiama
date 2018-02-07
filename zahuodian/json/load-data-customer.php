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
$module=$_GET['module'];
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
	array('db' => 'a.id_customer', 'dt' => 0, 'field' => 'id_customer'),
	array( 'db' => 'a.kode_customer', 'dt' => 1, 'field' => 'kode_customer'),
	array( 'db' => 'a.kode_customer', 'dt' => 2, 'field' => 'kode_customer'),
	array( 'db' => 'a.region', 'dt' => 3, 'field' => 'region'),
	array( 'db' => 'a.telp_customer', 'dt' => 4,  'field' => 'telp_customer'
	 ),
	array( 'db' => 'a.alamat_customer', 'dt' => 5,  'field' => 'alamat_customer'
	 ),
	array(
	       'db'        => 'a.tambah',
	       'dt'        => 6,
	       'field' => 'tambah',
	       'formatter' => function($d) {
	       return '<a href="?module='.$d.'" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-edit"></span> + Tambah</a>';
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
$joinQuery = "FROM (select `id_customer`,concat('".$module."&act=Tambah&Id=',id_customer) as tambah,`kode_customer`,`nama_customer`,`alamat_customer`,`region`,`telp_customer` from customer s,region r where r.id_region=s.id_region and s.`is_void` = 0)  a";     
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, null )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
