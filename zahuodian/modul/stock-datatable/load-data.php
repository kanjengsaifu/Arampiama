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
$table = 'stok';

// Table's primary key
$primaryKey = 'id_stok';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names

$columns = array(
	array('db' => 's1.nama_barang', 'dt' => 0, 'field' => 'id_stok' ),
	array( 'db' => 's1.nama_barang', 'dt' => 1, 'field' => 'kode_barang' ),
	array( 'db' => 's2.nama_barang', 'dt' => 2, 'field' => 'nama_barang1' ),
	array( 'db' => 's3.nama_barang', 'dt' => 3, 'field' => 'nama_barang2' ),
	array( 'db' => 's4.nama_barang', 'dt' => 4, 'field' => 'nama_barang3' ),
	array( 'db' => 's5.nama_barang', 'dt' => 5, 'field' => 'nama_barang4' )
    
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
 
//$joinQuery = "FROM `stok` AS `u` JOIN `gudang` AS `ug` ON (`ug`.`id_gudang` = `u`.`id_gudang`) JOIN `barang` AS `ub` ON (`ub`.`id_barang` = `u`.`id_barang`)";
//$extraWhere = "`ug`.`status_gudang` = 0"; 
//$groupBy =  '`ug`.`id_gudang`'; 
//$order = '`ug`.`status_gudang`';




$extraWhere="where s1.id_barang=s2.id_barang and s1.id_barang=s3.id_barang and s1.id_barang=s4.id_barang and s1.id_barang=s5.id_barang"
$joinQuery = "FROM
(SELECT id_stok,nama_barang,nama_gudang,s.stok_sekarang,s.id_barang,s.id_gudang
FROM stok s,barang b, gudang g
where  s.id_barang=b.id_barang and g.id_gudang=s.id_gudang and s.id_gudang=1) s1,
(SELECT id_stok,nama_barang,nama_gudang,s.stok_sekarang,s.id_barang,s.id_gudang
FROM stok s,barang b, gudang g
where  s.id_barang=b.id_barang and g.id_gudang=s.id_gudang and s.id_gudang=2) s2,
(SELECT id_stok,nama_barang,nama_gudang,s.stok_sekarang,s.id_barang,s.id_gudang
FROM stok s,barang b, gudang g
where  s.id_barang=b.id_barang and g.id_gudang=s.id_gudang and s.id_gudang=3) s3,
(SELECT id_stok,nama_barang,nama_gudang,s.stok_sekarang,s.id_barang,s.id_gudang
FROM stok s,barang b, gudang g
where  s.id_barang=b.id_barang and g.id_gudang=s.id_gudang and s.id_gudang=4) s4,
(SELECT id_stok,nama_barang,nama_gudang,s.stok_sekarang,s.id_barang,s.id_gudang
FROM stok s,barang b, gudang g
where  s.id_barang=b.id_barang and g.id_gudang=s.id_gudang and s.id_gudang=5) s5";

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);
} else {
    echo '<script>window.location="404.html"</script>';
}
