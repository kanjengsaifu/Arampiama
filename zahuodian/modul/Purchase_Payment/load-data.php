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
$table = 'supplier';

// Table's primary key
$primaryKey = 'id_supplier';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
	array('db' => 'a.id_supplier', 'dt' => 0, 'field' => 'id_supplier'),
	array( 'db' => 'a.supplier', 'dt' => 1, 'field' => 'supplier'),
	array( 'db' => 'a.region', 'dt' => 2, 'field' => 'region'),
	array( 'db' => 'a.telp1_supplier', 'dt' => 3,  'field' => 'telp1_supplier'
	 ),
	array( 'db' => 'a.telp1_sales', 'dt' => 4,  'field' => 'telp1_sales'
	 ),
	array(
	       'db'        => 'a.tambah',
	       'dt'        => 5,
	       'field' => 'tambah',
	       'formatter' => function($d) {
	       return '<a href="?module='.$d.'" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-edit"></span> Payment</a>';
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
$joinQuery = "FROM (select `id_supplier`,concat('".$module."&act=Tambah&Id=',id_supplier) as tambah,Concat(`kode_supplier`,' - ',`nama_supplier`) as supplier,`jenis`,`alamat_supplier`,`region`,`telp1_supplier`,`telp1_sales` from supplier s,region r where r.id_region=s.id_region and s.`is_void` = 0)  a";     
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, null )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
