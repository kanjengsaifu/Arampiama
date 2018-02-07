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
$table = 'trans_bayarbeli_header';

// Table's primary key
$primaryKey = 'id_bayarbeli';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
$columns = array(
	array('db' => '`u`.`id_bayarbeli`', 'dt' => 0, 'field' => 'id_bayarbeli' ),
	array( 'db' => '`u`.`bukti_bayar`', 'dt' => 1, 'field' => 'bukti_bayar' ),
	array( 'db' => '`u`.`nama_supplier`',  'dt' => 2 , 'field' => 'nama_supplier' ),
	array( 'db' => '`u`.`sisa`',  'dt' => 3, 'field' => 'sisa',
	       'formatter' =>function( $d, $row ) {
                      return number_format($d,null,null,'.');
                  }
             ),
	array(
	       'db'        => '`u`.`id_bayarbeli`',
	       'dt'        => 4,
	       'field' => 'id_bayarbeli',
	       'formatter' => function( $d ) {
	       return '<a class="btn-sm btn-success" href="#" onclick="addMore(\''.$d.'\')"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
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
 
$joinQuery = "FROM  (SELECT `id_bayarbeli`,nama_supplier,`id_akunkasperkiraan`,t.`bukti_bayar`,(`nominal`-IFNULL(sum(td.`nominal_alokasi`),0)) as sisa FROM `trans_bayarbeli_header` t left join `trans_bayarbeli_detail` td on (td.bukti_bayar=t.bukti_bayar),`supplier` s WHERE t.`id_supplier`=s.`id_supplier` and `status_titipan`='T' and t.`is_void`='0' and `giro_ditolak`='0' group by `bukti_bayar`  having sisa>0) as u ";
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
