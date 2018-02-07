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
	array('db' => 'a.id_supplier', 'dt' => 0, 'field' => 'id_supplier'),
	array( 'db' => 'a.kode_supplier', 'dt' => 1, 'field' => 'kode_supplier'),
	array( 'db' => 'a.nama_supplier', 'dt' => 2, 'field' => 'nama_supplier'),
	array( 'db' => 'a.jenis', 'dt' => 3, 'field' => 'jenis', 'formatter' => function($i) {
		if ($i == 'B') {
			$j = "Supplier";
		} else if ($i == 'A') {
			$j = "Tukang";
		} else {
			$j = "Lain-lain";
		}
		return $j;
	}),
	array( 'db' => 'a.alamat_supplier', 'dt' => 4, 'field' => 'alamat_supplier'),
	array( 'db' => 'a.region', 'dt' => 5, 'field' => 'region'),
	array( 'db' => 'a.telp1_supplier', 'dt' => 6,  'field' => 'telp1_supplier'
	 ),
	array( 'db' => 'a.telp1_sales', 'dt' => 7,  'field' => 'telp1_sales'
	 ),
	array(
	       'db'        => 'a.id_supplier',
	       'dt'        => 8,
	       'field' => 'id_supplier',
	       'formatter' => function( $d, $row ) {
	       return '
	        <a href="?module=supplier&act=editmenu&id='.$d.'" class="btn btn-sm btn-warning" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
    <a href="modul/supplier/aksi_supplier.php?module=supplier&act=hapus&id='.$d.'" class="btn btn-sm btn-danger" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>';
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
$joinQuery = "FROM (select `id_supplier`,`kode_supplier`,`nama_supplier`,`jenis`,`alamat_supplier`,`region`,`telp1_supplier`,`telp1_sales` from supplier s,region r where r.id_region=s.id_region and s.`is_void` = 0 and jenis='C')  a";     
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, null )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
