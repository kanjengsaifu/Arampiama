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
$table = 'master_barang';

// Table's primary key
$primaryKey = 'id_barang';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names 
$columns = array(
	array('db' => '`u`.`id_barang`', 'dt' => 0, 'field' => 'id_barang' ),
	array( 'db' => '`u`.`kode_supplier_barang`', 'dt' => 1, 'field' => 'kode_supplier_barang' ),
	array( 'db' => '`u`.`nama_barang`', 'dt' => 2, 'field' => 'nama_barang' ),
	array( 'db' => '`u`.`kategori`',  'dt' => 3 , 'field' => 'kategori' ),
	array( 'db' => '`u`.`merk`',  'dt' => 4, 'field' => 'merk' ),
	array( 'db' => '`u`.`akun`',  'dt' => 5, 'field' => 'akun' ),
    array( 'db' => '`u`.`harga_satuan`',  'dt' => 6, 'field' => 'harga_satuan' ),
	array( 'db' => '`u`.`stok_minimum`',  'dt' => 7, 'field' => 'stok_minimum',
	       'formatter' =>function( $d, $row ) {
                      return number_format($d,null,null,'.');
                  }
             ),
	array(
	       'db'        => 'id_barang', 'dt'        => 8, 'field' => 'id_barang',
	       'formatter' => function( $d ) {
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
 
$joinQuery = "FROM (Select akun,id_barang,kode_supplier_barang,nama_barang , kategori , merk, concat(harga_Sat1,'/',satuan1)   as `harga_satuan`,  stok_minimum from master_barang b left join master_merk m on m.id_merk = b.id_merk left join master_kategori k on k.id_kategori = b.id_kategori left join master_akun a on a.id_akun=b.id_akun and b.is_void = 0 order by nama_barang ) as u";

       
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, '')
);
} else {
    echo '<script>window.location="404.html"</script>';
}
