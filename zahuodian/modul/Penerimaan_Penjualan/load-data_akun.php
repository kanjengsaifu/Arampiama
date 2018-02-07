<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
 include "../../config/coneksi.php";
 include "../../lib/input.php";



// DB table to use
$table = 'trans_invoice';

// Table's primary key
$primaryKey = 'id';

$columns = array(
	array('db' => '`a`.`id_akunkasperkiraan`', 'dt' => 0, 'field' => 'id_akunkasperkiraan'),
	array( 'db' => '`a`.`kode_akun`', 'dt' => 1, 'field' => 'kode_akun'),
	array('db' => '`a`.`nama_akunkasperkiraan`', 'dt' => 2, 'field' => 'nama_akunkasperkiraan' ),
	array(
	       'db' => '`a`.`id_akunkasperkiraan`',
	       'dt'        => 3,
	       'field' => 'id_akunkasperkiraan',
	       'formatter' => function($d, $row) {
	   return '<a class="btn-sm btn-success" href="#" onclick="add_invoice(\'akun/'.$d.'\')"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
    })
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
 
$joinQuery = "FROM (SELECT * FROM `akun_kas_perkiraan` WHERE `is_void`='0') a";
//$groupBy=  " `tb`.`id_barang`";
 
echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, null)
//SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
