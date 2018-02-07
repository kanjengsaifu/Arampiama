<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
 include "../../config/coneksi.php";
 include "../../lib/input.php";

  if (isset($_GET['titipan'])) { // ##########################	QUERY GLOBAL

$table = 'trans_bayarbeli_header';

$primaryKey = 'id_bayarbeli';

$columns = array(
	array('db' => '`tb`.`id_bayarbeli`', 'dt' => 0, 'field' => 'id_bayarbeli'),
	array('db' => '`tb`.`bukti_bayar`', 'dt' => 1, 'field' => 'bukti_bayar'),
	array( 'db' => '`s`.`nama_supplier`', 'dt' => 2, 'field' => 'nama_supplier'),
	array( 'db' => '`tb`.`nominal`', 'dt' => 3, 'field' => 'nominal',
    		'formatter' => function($d){
	       return format_rupiah($d);
    		}),
	array( 'db' => '`tb`.`nominal_alokasi`', 'dt' => 4, 'field' => 'nominal_alokasi',
    		'formatter' => function($d){
	       return format_rupiah($d);
    		}),
	array( 'db' => '`tb`.`sisa_alokasi`', 'dt' => 5, 'field' => 'sisa_alokasi',
    		'formatter' => function($d){
	       return format_rupiah($d);
    		}),
	array( 'db' => '`a`.`kode_akunkasperkiraan`', 'dt' => 6, 'field' => 'kode_akunkasperkiraan'),
	array( 'db' => '`tb`.`ket`', 'dt' => 7, 'field' => 'ket'),
	array( 'db' => '`tb`.`status_bayar`', 'dt' => 8, 'field' => 'status_bayar'),
	array( 'db'        => '`tb`.`id_bayarbeli`', 'dt'        => 9,  'field' => 'id_bayarbeli',
	       'formatter' => function( $d ) {
	       return '<a class="btn-sm btn-warning" href="?module=titipansupplier&act=alokasi&id='.$d.'"><span class="glyphicon glyphicon-list" aria-hidden="true"></span></a>';
    })
);


$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);


require( '../../lib/scripts/ssp.customized.class.php' );
 
$joinQuery = "FROM `trans_bayarbeli_header` AS `tb` JOIN `akun_kas_perkiraan` AS `a` ON (`tb`.`id_akunkasperkiraan` = `a`.`id_akunkasperkiraan`) LEFT JOIN `supplier` AS `s` ON (`s`.`id_supplier` = `tb`.`id_supplier`) ";
$extraWhere = "`tb`.`is_void` = '0'  AND status_titipan = 'T' "; 
//$groupBy=  " `tb`.`id_barang`";
$orderBy = "`tb`.`id_bayarbeli` DESC";
 
echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere, $orderBy )
//SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere )
	);
} else if(isset($_GET['invoicesupplier'])){

$table = 'trans_invoice';

$primaryKey = 'id';

$columns = array(
	array('db' => '`ti`.`id`', 'dt' => 0, 'field' => 'id'),
	array( 'db' => '`s`.`nama_supplier`', 'dt' => 1, 'field' => 'nama_supplier'),
	array('db' => '`ti`.`id_invoice`', 'dt' => 2, 'field' => 'id_invoice' ),
	array( 'db' => '`ti`.`tgl`',  'dt' => 3, 'field' => 'tgl' ),
    	array( 'db' => '`ti`.`grand_total`', 'dt' => 4, 'field' => 'grand_total',
    		'formatter' => function($d){
	       return format_rupiah($d)."<input type='hidden' class='total' data='\"$d\"'>";
    		}),
	array(
	       'db' => '`ti`.`id_invoice`',
	       'dt'        => 5,
	       'field' => 'id_invoice',
	       'formatter' => function($d, $row) {
	       return "<a class='btn-sm btn-success' onclick='intorow(this)' data='$d' id='close'><span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a>";
    })
);

$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);


require( '../../lib/scripts/ssp.customized.class.php' );
 
$joinQuery = "FROM `trans_invoice` AS `ti` JOIN `supplier` AS `s` ON (`ti`.`id_supplier` = `s`.`id_supplier`) ";
$extraWhere = "`ti`.`is_void` = 0 AND `ti`.`status_lunas` != '1' AND `ti`.`id_supplier`= '$_GET[invoicesupplier]' "; 
//$groupBy=  " `tb`.`id_barang`";
 
echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
} else {
	echo"titik... titik...";
}
//################## END SESIIONN
} else {
    echo '<script>window.location="404.html"</script>';
}
