
<?php
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
$table = 'Trans_sales_invoice';

// Table's primary key
$primaryKey = 'id';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
	array('db' => '`u`.`id`', 'dt' => 0, 'field' => 'id' ),
	array( 'db' => '`ub`.`nama_customer`', 'dt' => 1, 'field' => 'nama_customer' ),
	array( 'db' => '`u`.`id_invoice`',  'dt' => 2 , 'field' => 'id_invoice' ),
         array( 'db' => '`u`.`tgl`', 'dt' => 3, 'field' => 'tgl'),
	array( 'db' => '`u`.`grand_total`',  'dt' => 4, 'field' => 'grand_total',
	       'formatter' =>function( $d, $row ) {
                      return "Rp. ".number_format($d,null,null,'.');
                  }),
           array( 'db' => '`u`.`status_lunas`',  'dt' => 5, 'field' => 'pembayaran', 'as' => 'pembayaran',
           	 'formatter' =>function( $d, $row ) {
           	 	if($d==1){
           	 		$c = "Lunas";
           	 	}
           	 	else{
           	 		$c = "Belum Lunas";
           	 	}
                      return $c ;
                  }),

	array(
	       'db'        =>  'CONCAT( `u`.`id_invoice`, "\',\'" ,`ub`.`nama_customer`, "\',\'" ,`ub`.`id_customer`  )',
	       'dt'        => 6,
	       'field' => 'aksi',
	       'as' => 'aksi',
	       'formatter' => function( $d, $row ) {
	       return '<a class="btn-sm btn-success" onclick="addrjbno(\''.$d.'\')" id="addrjb"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
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
$joinQuery = "FROM `Trans_sales_invoice` AS `u` JOIN `customer` AS `ub` ON (`ub`.`id_customer` = `u`.`id_customer`) LEFT JOIN `trans_pembayaran` AS `tp` ON (`tp`.`id_invoice` = `u`.`id_invoice`)";
$extraWhere = "`u`.`is_void` = 0 and `u`.`status_retur`=0  ";
$order = "`u`.`id_invoice` DESC "; 

	echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $order)
);
