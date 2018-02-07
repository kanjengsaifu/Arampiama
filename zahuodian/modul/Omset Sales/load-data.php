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
$table = 'trans_sales_invoice';

// Table's primary key
$primaryKey = 'id';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
	array('db' => '`udi`.`nama_sales`', 'dt' => 0, 'field' => 'nama_sales' ),
	array( 'db' => '`u`.`grand_total`', 'dt' => 1, 'field' => 'grand_total' ),
	array( 'db' => '`u`.`nama_barang`',  'dt' => 2 , 'field' => 'nama_barang' ),
	array( 'db' => '`ud`.`merk`',  'dt' => 3, 'field' => 'merk' ),
         array( 'db' => 'CONCAT( `u`.`harga_sat1`, "/ (" ,`u`.`satuan1`,")" )', 'dt' => 4, 'field' => 'harga_sat1', 'as' => 'harga_sat1',
	       'formatter' =>function( $d, $row ) {
	       	list ($harga, $satuan) = split ('[/]', $d);
                      return 'Rp. '.number_format($harga,2,',','.')."/". $satuan;
                  }
                  ),
	array( 'db' => '`u`.`stok_minimum`',  'dt' => 5, 'field' => 'stok_minimum',
	       'formatter' =>function( $d, $row ) {
                      return number_format($d,null,null,'.');
                  }
             ),
	array(
	       'db'        => 'id_barang',
	       'dt'        => 6,
	       'field' => 'id_barang',
	       'formatter' => function( $d ) {
	       return '
	       <a href="?module=barang&act=editmenu&id='.$d.'" class="btn btn-warning" title="Edit"><span class="glyphicon glyphicon-edit"></span></a>
    <a href="$aksi?module=barang&act=hapus&id='.$d.'" class="btn btn-danger" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>';
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
 
$joinQuery = "FROM `barang` AS `u` JOIN `merk` AS `ud` ON (`ud`.`id_merk` = `u`.`id_merk`)";
$extraWhere = "`u`.`is_void` = 0"; 
       
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
