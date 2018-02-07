<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
 include "../../config/coneksi.php";
 include "../../lib/input.php";
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
$table = 'trans_totalan_tukang';

// Table's primary key
$primaryKey = 'id';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
	array('db' => '`u`.`id`', 'dt' => 0, 'field' => 'id' ),
	array( 'db' => '`u`.`no_totalan_tukang`', 'dt' => 1, 'field' => 'no_totalan_tukang' ),
	array( 'db' => '`u`.`id_terima_tukang`',  'dt' => 2 , 'field' => 'id_terima_tukang' ),
	array( 'db' => '`u`.`nota_tukang`',  'dt' => 3 , 'field' => 'nota_tukang' ),
	array( 'db' => '`u`.`nama_supplier`',  'dt' => 4 , 'field' => 'nama_supplier' ),
	array(
	       'db'        => '`u`.`nominal_totalan`',
	       'dt'        => 5,
	       'field' => 'nominal_totalan',
	       'formatter' => function( $d ) {
	       return format_rupiah($d);
    }
),
	array( 'db' => '`u`.`tanggal`',  'dt' => 6 , 'field' => 'tanggal' ),
    	array(
	       'db'        => '`u`.`id`',
	       'dt'        => 7,
	       'field' => 'id',
	       'formatter' => function( $d ) {
	       return '<a  class="btn-sm btn-warning" href="?module=tukanganpenotaan&act=tambah&id='.$d.'"><span class="glyphicon glyphicon-list" aria-hidden="true"></span></a>';
    }
),
    	array(
	       'db'        => '`u`.`id`',
	       'dt'        => 8,
	       'field' => 'id',
	       'formatter' => function( $d ) {
	       return '<a  class="btn-sm btn-default" href="modul/tukanganpenotaan/cetak.php?id='.$d.'"><span class="glyphicon glyphicon-print"></span></a>';
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
 
$joinQuery = "FROM (SELECT a.id,a.`no_totalan_tukang`,`id_terima_tukang`,`nota_tukang`,`nama_supplier`,`nominal_totalan`,date_format(`tgl_totalan`,'%d %M %Y') as tanggal FROM `trans_totalan_tukang` a, `trans_totalan_tukang_detail` b, supplier c WHERE a.`no_totalan_tukang`=b.`no_totalan_tukang` and a.`id_supplier`=c.`id_supplier` and a.is_void='0'  group by a.`no_totalan_tukang` order by `id` desc) AS `u`  ";
 
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, null)
);
} else {
    echo '<script>window.location="404.html"</script>';
}
