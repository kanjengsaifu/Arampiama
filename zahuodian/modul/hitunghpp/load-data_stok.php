<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
 include "../../config/coneksi.php";
 include "../../lib/input.php";



// DB table to use
$table = 'stok_tukang';

// Table's primary key
$primaryKey = 'id_stoktukang';

$columns = array(
	array('db' => 't.id_stoktukang', 'dt' => 0, 'field' => 'id_stoktukang'),
	array( 'db' => 's.nama_supplier', 'dt' => 1, 'field' => 'nama_supplier'),
	array('db' => 'b.kode_barang', 'dt' => 2, 'field' => 'kode_barang' ),
	array( 'db' => 'b.nama_barang',  'dt' => 3, 'field' => 'nama_barang' ),
   	array( 'db' => 't.stok_tukang', 'dt' => 4, 'field' => 'stok_tukang'),
	array(
	       'db' => 't.id_barang',
	       'dt'        => 5,
	       'field' => 'id_barang',
	       'formatter' => function($d, $row) {
	       return "<a class='btn-sm btn-success' onclick='intorow(this)' data='$d' id='close'><span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a>";
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
 
$joinQuery = "FROM stok_tukang AS t JOIN supplier AS s ON t.id_supplier = s.id_supplier JOIN barang b ON t.id_barang = b.id_barang";
//$groupBy=  " `tb`.`id_barang`";
 
echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, null)
//SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
